<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Result;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResultadoPdfMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
     public function uploadForm(Result $resultado)
    {
        $resultado->load('orden.paciente');

        return view('gestion.resultados.upload', compact('resultado'));
    }
     public function uploadPdf(Request $request, Result $resultado)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        try {
            // Guardar PDF
            if ($resultado->pdf_path) {
                Storage::disk('public')->delete($resultado->pdf_path);
            }

            $path = $request->file('pdf')->store('resultados', 'public');

            $resultado->update([
                'pdf_path'       => $path,
                'result_date'    => Carbon::now(),
                'validated_date' => Carbon::now(),
                'status'         => 'validado',
            ]);

            // Llamar a n8n (ejemplo)
            $orden    = $resultado->orden()->with('paciente')->first();
            $paciente = $orden->paciente;

            $webhookUrl = config('services.n8n.resultados_webhook'); 
            // config/services.php -> 'n8n' => ['resultados_webhook' => 'https://tu-servidor/webhook/resultados']

            $payload = [
                'result_id'   => $resultado->id,
                'paciente'    => [
                    'nombre'  => $paciente->name,
                    'apellido'=> $paciente->last_name,
                    'email'   => $paciente->email,
                    'telefono'=> $paciente->phone,
                ],
                'orden_id'    => $orden->id,
                'pdf_url'     => $resultado->url_pdf,
            ];

            $estadoNotificacion = 'pendiente';
            $mensajeError       = null;

            try {
                if ($webhookUrl) {
                    $response = Http::post($webhookUrl, $payload);

                    if ($response->successful()) {
                        $estadoNotificacion = 'enviada';
                    } else {
                        $estadoNotificacion = 'error';
                        $mensajeError       = $response->body();
                    }
                } else {
                    $estadoNotificacion = 'no_configurado';
                }
            } catch (\Exception $e) {
                $estadoNotificacion = 'error';
                $mensajeError       = $e->getMessage();
            }

            // Registrar notificación
            Notification::create([
                'result_id'    => $resultado->id,
                'channel'      => 'n8n',
                'send_date'    => Carbon::now(),
                'status'       => $estadoNotificacion,
                'message_error'=> $mensajeError,
            ]);

            if ($estadoNotificacion === 'enviada') {
                $resultado->update(['status' => 'enviado']);
            }

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $resultado = Result::with(['orden.paciente', 'notificaciones'])->findOrFail($id);

        return view('gestion.resultados.show', compact('resultado'));
    }
    // public function sendToN8n($id)
    // {
    //     $resultado = Result::with('orden.paciente')->findOrFail($id);
    //     $paciente  = $resultado->orden->paciente;

    //     $webhookUrl = config('services.n8n.results_webhook');

    //     if (!$webhookUrl) {
    //         return back()->withErrors('Webhook de n8n no configurado (N8N_RESULTS_WEBHOOK)');
    //     }

    //     // Datos que se mandan a n8n
    //     $payload = [
    //         'result_id'     => $resultado->id,
    //         'order_id'      => $resultado->order_id,
    //         'paciente'      => [
    //             'id'        => $paciente->id,
    //             'nombre'    => $paciente->name,
    //             'apellido'  => $paciente->last_name,
    //             'email'     => $paciente->email,
    //             'telefono'  => $paciente->phone,
    //         ],
    //         'pdf_url'       => $resultado->url_pdf,   // accessor que ya tienes
    //         'estado'        => $resultado->status,
    //         'enviado_desde' => 'laravel-laboratorio',
    //     ];

    //     try {
    //         $response = Http::post($webhookUrl, $payload);

    //         $ok = $response->successful();

    //         Notification::create([
    //             'result_id'    => $resultado->id,
    //             'channel'      => 'email_whatsapp', // o lo que uses
    //             'send_date'    => Carbon::now(),
    //             'status'       => $ok ? 'enviado' : 'error',
    //             'message_error'=> $ok ? null : $response->body(),
    //         ]);

    //         if ($ok) {
    //             // Opcional: marcar resultado como "enviado"
    //             $resultado->update(['status' => 'enviado']);

    //             return back()->with('success', 'Resultados enviados a n8n correctamente.');
    //         } else {
    //             return back()->withErrors('Error al enviar a n8n: '.$response->body());
    //         }

    //     } catch (\Exception $e) {
    //         Notification::create([
    //             'result_id'    => $resultado->id,
    //             'channel'      => 'email_whatsapp',
    //             'send_date'    => Carbon::now(),
    //             'status'       => 'error',
    //             'message_error'=> $e->getMessage(),
    //         ]);

    //         return back()->withErrors('Excepción al llamar a n8n: '.$e->getMessage());
    //     }
    // }
    /**
     * Show the form for editing the specified resource.
     */


    public function sendToN8n($id)
{
    $resultado = Result::with('orden.paciente')->findOrFail($id); // correcto: $id es Result
    $paciente  = $resultado->orden->paciente;

    $webhookUrl = config('services.n8n.results_webhook');

    if (!$webhookUrl) {
        return response()->json([
            'success' => false,
            'message' => 'Webhook de n8n no configurado.'
        ]);
    }

    $payload = [
        'result_id'     => $resultado->id,
        'order_id'      => $resultado->order_id,
        'paciente'      => [
            'id'       => $paciente->id,
            'nombre'   => $paciente->name,
            'apellido' => $paciente->last_name,
            'email'    => $paciente->email,
            'telefono' => $paciente->phone,
        ],
        'pdf_url'       => $resultado->url_pdf,
        'estado'        => $resultado->status,
        'enviado_desde' => 'laravel-laboratorio',
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
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
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

            if(count(Mail::failures()) > 0){
                return response()->json(['success'=>false, 'message'=>'No se pudo enviar el correo'], 500);
            }

            // Enviar WhatsApp vía n8n
            $client = new \GuzzleHttp\Client();
            $webhookUrl = 'https://tuservidor-n8n.com/webhook/resultado';
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
}
