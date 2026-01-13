<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Result;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ResultController extends Controller
{
    /**
     * Muestra el formulario (modal o vista) para subir PDF.
     */
    public function uploadForm(Result $resultado)
    {
        $resultado->load('orden.paciente');
        return view('gestion.resultados.upload', compact('resultado'));
    }

    /**
     * Sube el PDF, lo guarda y dispara la notificación a n8n.
     */
    public function uploadPdf(Request $request, Result $resultado)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:5120', // Máx 5MB
        ]);

        try {
            // 1. Eliminar PDF antiguo si existe
            if ($resultado->pdf_path) {
                Storage::disk('public')->delete($resultado->pdf_path);
            }

            // 2. Guardar nuevo PDF
            $path = $request->file('pdf')->store('resultados', 'public');

            // 3. Generar URL pública absoluta (IMPORTANTE para n8n)
            // Asegúrate que APP_URL en .env sea correcto (ej: https://tusitio.com)
            $urlPdf = asset('storage/' . $path);

            // 4. Actualizar BD
            $resultado->update([
                'pdf_path'       => $path,
                'url_pdf'        => $urlPdf, // Guardamos la URL generada
                'result_date'    => Carbon::now(),
                'validated_date' => Carbon::now(),
                'status'         => 'validado',
            ]);

            // 5. Enviar a n8n
            $envioExitoso = $this->enviarPayloadAN8n($resultado);

            $mensaje = $envioExitoso 
                ? 'PDF subido y enviado a n8n correctamente.' 
                : 'PDF subido, pero n8n no respondió (se guardó notificación de error).';

            if ($request->ajax()) {
                return response()->json([
                    'success'   => true,
                    'message'   => $mensaje,
                    'resultado' => $resultado->fresh(),
                ]);
            }

            return redirect()->route('ordenes.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir PDF: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    /**
     * Reenvío manual desde el botón "Enviar WhatsApp/Email".
     */
    public function sendToN8n($id)
    {
        $resultado = Result::with('orden.paciente')->findOrFail($id);

        if (!$resultado->pdf_path) {
            return response()->json(['success' => false, 'message' => 'No hay PDF cargado para enviar.'], 422);
        }

        $exito = $this->enviarPayloadAN8n($resultado);

        return response()->json([
            'success' => $exito,
            'message' => $exito ? 'Enviado a n8n correctamente.' : 'Error al enviar a n8n.'
        ]);
    }

    /**
     * MÉTODO PRIVADO: Centraliza la lógica de llamar a n8n.
     */
    private function enviarPayloadAN8n(Result $resultado)
    {
        $resultado->load('orden.paciente');
        $paciente = $resultado->orden->paciente;
        
        // URL del Webhook de n8n definida en .env
        $webhookUrl = config('services.n8n.resultados_webhook'); 

        if (!$webhookUrl) {
            $this->registrarNotificacion($resultado->id, 'error', 'Webhook n8n no configurado en .env');
            return false;
        }

        // Datos que enviaremos a n8n
        $payload = [
            'resultado_id' => $resultado->id,
            'orden_id'     => $resultado->orden->id,
            'paciente'     => [
                'nombre'   => $paciente->name,
                'apellido' => $paciente->last_name,
                'email'    => $paciente->email,
                'telefono' => $paciente->phone, // Ej: 59170000000
            ],
            'pdf_url'      => $resultado->url_pdf, // URL pública
            'estado'       => $resultado->status,
        ];

        try {
            // Hacemos la petición POST a n8n
            $response = Http::post($webhookUrl, $payload);

            if ($response->successful()) {
                $resultado->update(['status' => 'enviado']);
                $this->registrarNotificacion($resultado->id, 'enviado', null);
                return true;
            } else {
                $this->registrarNotificacion($resultado->id, 'error', 'n8n error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            $this->registrarNotificacion($resultado->id, 'error', 'Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function registrarNotificacion($resultadoId, $status, $error)
    {
        Notification::create([
            'result_id'     => $resultadoId,
            'channel'       => 'n8n',
            'send_date'     => Carbon::now(),
            'status'        => $status,
            'message_error' => $error,
        ]);
    }
    public function index(Request $request)
    { // Iniciamos consulta con relaciones necesarias
        $query = Result::with(['orden.paciente']);

        // ----------------------------------------------------
        // 1. BÚSQUEDA INTELIGENTE (Nombre, Apellido o CI)
        // ----------------------------------------------------
        if ($request->filled('search')) {
            $search = trim($request->search); // Limpiamos espacios extra

            // Buscamos dentro de la relación 'orden.paciente'
            $query->whereHas('orden.paciente', function ($q) use ($search) {
                $q->where(function($subQ) use ($search) {
                    // Búsqueda por Nombre (insensible a mayúsculas)
                    $subQ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                        // Búsqueda por Apellido
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($search) . '%'])
                        // Búsqueda por CI (Exacta o parcial)
                        ->orWhere('ci', 'LIKE', "%{$search}%")
                        // Búsqueda por Nombre Completo (Concatenado)
                        ->orWhereRaw("LOWER(CONCAT(name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%']);
                });
            });
        }

        // ----------------------------------------------------
        // 2. FILTRO POR FECHAS
        // ----------------------------------------------------
        if ($request->filled('start_date')) {
            $query->whereDate('result_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('result_date', '<=', $request->end_date);
        }

        // ----------------------------------------------------
        // 3. FILTRO POR ESTADO
        // ----------------------------------------------------
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Resultados paginados (10 por página)
        $resultados = $query->orderBy('id', 'desc')->paginate(10);

        return view('gestion.resultados.index', compact('resultados'));
    }
     public function downloadPdf($id)
    {
        $resultado = Result::findOrFail($id);

        if (!$resultado->pdf_path || !file_exists(public_path('storage/' . $resultado->pdf_path))) {
            return back()->with('error', 'El archivo PDF no se encuentra disponible.');
        }

        return response()->download(public_path('storage/' . $resultado->pdf_path));
    }
    


     public function updateStatus(Request $request, $id)
    {
        // 1. Validar el estado recibido
        $request->validate([
            'status' => 'required|in:pendiente,validado,entregado'
        ]);

        try {
            // 2. Buscar el Resultado
            $resultado = Result::findOrFail($id);

            // 3. Actualizar Estado
            $resultado->status = $request->status;

            // 4. Si el estado es "validado" o superior, registramos al usuario actual
            if ($request->status == 'validado' || $request->status == 'entregado') {
                // Guardamos el ID del usuario logueado (porque tu migración pide unsignedBigInteger)
                $resultado->validated_by = auth()->id(); 
                
                // Si aún no tenía fecha de validación, la ponemos ahora
                if (!$resultado->validated_date) {
                    $resultado->validated_date = now();
                }
            }

            $resultado->save();

            return back()->with('success', 'Estado actualizado a: ' . ucfirst($request->status));

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar estado: ' . $e->getMessage());
        }
    }



}
