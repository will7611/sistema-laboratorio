<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * Procesa la subida del PDF (Desarrollo)
     */
    public function uploadPdf(Request $request, Result $resultado)
    {
        // Validar hasta 200MB (204800 KB)
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:204800',
        ]);

        try {
            // 1. RESPALDO: Guardar archivo en el disco local 'public'
            if ($resultado->pdf_path) {
                Storage::disk('public')->delete($resultado->pdf_path);
            }

            $path = $request->file('pdf')->store('resultados', 'public');
            $urlPdf = asset('storage/' . $path);

            // 2. ACTUALIZAR BASE DE DATOS
            $resultado->update([
                'pdf_path'       => $path,
                'url_pdf'        => $urlPdf,
                'result_date'    => Carbon::now(),
                'status'         => 'validado',
            ]);

            // 3. COMUNICACIÓN CON N8N (Con timeout para no congelar)
            $enviado = $this->enviarAN8n($resultado);

            return response()->json([
                'success' => true,
                'message' => $enviado 
                    ? 'Archivo respaldado y enviado a n8n.' 
                    : 'Archivo guardado localmente, pero n8n no respondió a tiempo.',
                'url' => $urlPdf
            ]);

        } catch (\Exception $e) {
            Log::error("Error en subida local: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reenvío manual (Botón en la tabla)
     */
    public function sendToN8n($id)
    {
        $resultado = Result::findOrFail($id);
        
        if (!$resultado->pdf_path) {
            return response()->json(['success' => false, 'message' => 'No hay PDF cargado.'], 422);
        }

        $exito = $this->enviarAN8n($resultado);

        return response()->json([
            'success' => $exito,
            'message' => $exito ? 'Enviado correctamente.' : 'Error al conectar con n8n.'
        ]);
    }

    /**
     * Lógica central de envío a n8n
     */
    private function enviarAN8n(Result $resultado)
    {
        $webhookUrl = config('services.n8n.resultados_webhook'); 
        if (!$webhookUrl) return false;
// Obtenemos los datos del paciente desde la relación
        $paciente = $resultado->orden->paciente;
        try {
            // Timeout de 10 segundos para pruebas locales
            $response = Http::timeout(10)->post($webhookUrl, [
                'test_mode'    => true,
                'resultado_id' => $resultado->id,
                'paciente'     => $resultado->orden->paciente->name . ' ' . $resultado->orden->paciente->last_name,
                'pdf_url'      => $resultado->url_pdf,
                         'email'        => $paciente->email,      // <--- AHORA SÍ SE ENVÍA EL EMAIL
                'telefono'     => $paciente->phone,      // <--- AÑADIMOS TELÉFONO PARA WHATSAPP
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
