<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Result;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResultadoPdfMail;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ResultController extends Controller
{
    /**
     * Mostrar formulario para subir PDF
     */
    public function uploadForm(Result $resultado)
    {
        $resultado->load('orden.paciente');
        
        return view('gestion.resultados.upload', compact('resultado', ));
    }

    /**
     * Subir PDF y enviar datos a n8n
     */
    public function uploadPdf(Request $request, Result $resultado)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        try {
            // Eliminar PDF antiguo si existe
            if ($resultado->pdf_path) {
                Storage::disk('public')->delete($resultado->pdf_path);
            }

            // Guardar nuevo PDF
            $path = $request->file('pdf')->store('resultados', 'public');

            $resultado->update([
                'pdf_path'       => $path,
                'result_date'    => Carbon::now(),
                'validated_date' => Carbon::now(),
                'status'         => 'validado',
            ]);

            // Preparar payload para n8n
            $orden    = $resultado->orden()->with('paciente')->first();
            $paciente = $orden->paciente;

            $payload = [
                'resultado_id' => $resultado->id,
                'orden_id'     => $orden->id,
                'paciente'     => [
                    'nombre'   => $paciente->name,
                    'apellido' => $paciente->last_name,
                    'email'    => $paciente->email,
                    'telefono' => $paciente->phone,
                ],
                'pdf_url' => $resultado->url_pdf,
                'estado'  => $resultado->status,
            ];

            // Enviar a n8n
            $webhookUrl = config('services.n8n.resultados_webhook');
            $estadoNotificacion = 'pendiente';
            $mensajeError = null;

            try {
                if ($webhookUrl) {
                    $response = Http::post($webhookUrl, $payload);

                    if ($response->successful()) {
                        $estadoNotificacion = 'enviada';
                        $resultado->update(['status' => 'enviado']);
                    } else {
                        $estadoNotificacion = 'error';
                        $mensajeError = $response->body();
                    }
                } else {
                    $estadoNotificacion = 'no_configurado';
                }
            } catch (\Exception $e) {
                $estadoNotificacion = 'error';
                $mensajeError = $e->getMessage();
            }

            // Registrar notificación
            Notification::create([
                'result_id'    => $resultado->id,
                'channel'      => 'n8n',
                'send_date'    => Carbon::now(),
                'status'       => $estadoNotificacion,
                'message_error'=> $mensajeError,
            ]);

            // Retornar respuesta
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'PDF subido y notificación enviada (o en proceso).',
                    'resultado' => $resultado->fresh(),
                ]);
            }

            return redirect()->route('ordenes.index')
                ->with('success', 'PDF subido y notificación enviada (o en proceso).');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir PDF.',
                    'error'   => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al subir PDF: '.$e->getMessage());
        }
    }

    /**
     * Mostrar resultado
     */
    public function show($id)
    {
        $resultado = Result::with(['orden.paciente', 'notificaciones'])->findOrFail($id);
        return view('gestion.resultados.show', compact('resultado'));
    }

    /**
     * Enviar resultado a n8n, correo y WhatsApp
     */
    public function sendToN8n($id)
    {
        $resultado = Result::with('orden.paciente')->findOrFail($id);
        $paciente  = $resultado->orden->paciente;

        $webhookUrl = config('services.n8n.resultados_webhook');

        if (!$webhookUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook de n8n no configurado.'
            ]);
        }

        $payload = [
            'result_id' => $resultado->id,
            'orden_id'  => $resultado->orden->id,
            'paciente'  => [
                'id'       => $paciente->id,
                'nombre'   => $paciente->name,
                'apellido' => $paciente->last_name,
                'email'    => $paciente->email,
                'telefono' => $paciente->phone,
            ],
            'pdf_url' => $resultado->url_pdf,
            'estado'  => $resultado->status,
        ];

        try {
            $response = Http::post($webhookUrl, $payload);

            Notification::create([
                'result_id'    => $resultado->id,
                'channel'      => 'email_whatsapp',
                'send_date'    => Carbon::now(),
                'status'       => $response->successful() ? 'enviado' : 'error',
                'message_error'=> $response->successful() ? null : $response->body(),
            ]);

            return response()->json([
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Enviado correctamente a correo y WhatsApp' : 'Error al enviar'
            ]);
        } catch (\Exception $e) {
            Notification::create([
                'result_id'    => $resultado->id,
                'channel'      => 'email_whatsapp',
                'send_date'    => Carbon::now(),
                'status'       => 'error',
                'message_error'=> $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Excepción al enviar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar PDF por correo y WhatsApp (opcional)
     */
    public function sendPdf($resultadoId)
    {
        $resultado = Result::findOrFail($resultadoId);
        $orden     = $resultado->orden;

        if(!$resultado->pdf_path) {
            return response()->json(['success' => false, 'message' => 'No hay PDF disponible'], 422);
        }

        try {
            // Enviar correo
            Mail::to($orden->paciente->email)->send(new ResultadoPdfMail($orden, $resultado->pdf_path));

            // Enviar WhatsApp vía n8n
            $client = new \GuzzleHttp\Client();
            $webhookUrl = config('services.n8n.resultados_webhook'); 
            $client->post($webhookUrl, [
                'json' => [
                    'telefono' => $orden->paciente->telefono,
                    'mensaje'  => "Hola {$orden->paciente->name}, su resultado ya fue enviado a su correo."
                ]
            ]);

            return response()->json(['success'=>true, 'message'=>'Correo enviado y WhatsApp notificado correctamente.']);

        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Error: '.$e->getMessage()], 500);
        }
    }
    public function enviarAN8n(Result $resultado)
    {
        $resultado->load('orden.paciente');
        $paciente = $resultado->orden->paciente;

        $webhookUrl = config('services.n8n.resultados_webhook');
        $token      = config('services.n8n.sanctum_token');

        if (!$webhookUrl || !$token) {
            return response()->json(['success' => false, 'message' => 'Webhook o token no configurado.'], 500);
        }

        $payload = [
            'result_id' => $resultado->id,
            'orden_id'  => $resultado->orden_id,
            'paciente'  => [
                'id'       => $paciente->id,
                'nombre'   => $paciente->name,
                'apellido' => $paciente->last_name,
                'email'    => $paciente->email,
                'telefono' => $paciente->phone,
            ],
            'pdf_url'   => $resultado->url_pdf,
            'estado'    => $resultado->status,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $token"
            ])->post($webhookUrl, $payload);

            Notification::create([
                'result_id'    => $resultado->id,
                'channel'      => 'email_whatsapp',
                'send_date'    => now(),
                'status'       => $response->successful() ? 'enviado' : 'error',
                'message_error'=> $response->successful() ? null : $response->body(),
            ]);

            return response()->json([
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Enviado a n8n correctamente' : 'Error al enviar a n8n',
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
