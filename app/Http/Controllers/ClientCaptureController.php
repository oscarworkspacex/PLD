<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClientValidationRequest;
use App\Http\Requests\UpdatePrestamoStatusRequest;
use App\Http\Requests\UploadClientAnexoRequest;
use App\Http\Requests\StoreClientCaptureRequest;
use App\Http\Requests\UpdateClientCaptureRequest;
use App\Models\ClientCapture;
use App\Models\ClientCaptureAnexo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientCaptureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $clients = ClientCapture::query()
            ->select(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'datos_identificacion'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(function (ClientCapture $client) {
                $datosIdentificacion = is_array($client->datos_identificacion)
                    ? $client->datos_identificacion
                    : [];

                $fallbackNombre = trim(implode(' ', array_filter([
                    $datosIdentificacion['primer_nombre'] ?? null,
                    $datosIdentificacion['segundo_nombre'] ?? null,
                    $datosIdentificacion['apellido_paterno'] ?? null,
                    $datosIdentificacion['apellido_materno'] ?? null,
                ])));

                $fullName = trim(implode(' ', array_filter([
                    $client->nombre ?: $fallbackNombre,
                    $client->apellido_paterno,
                    $client->apellido_materno,
                ])));

                return [
                    'id' => $client->id,
                    'nombre' => $fullName !== '' ? $fullName : 'Sin nombre',
                ];
            })
            ->values();

        return response()->json([
            'data' => $clients,
        ]);
    }

    /**
     * Return a lightweight list for "Seleccionar clientes".
     */
    public function selector(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $clients = ClientCapture::query()
            ->select([
                'id',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'rfc',
                'tipo_solicitud',
                'datos_identificacion',
                'anexo_path',
                'anexo_nombre',
                'cliente_validado',
            ])
            ->withCount('anexos')
            ->with('latestAnexo')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%")
                        ->orWhere('rfc', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->limit(500)
            ->get()
            ->map(function (ClientCapture $client) {
                $datosIdentificacion = is_array($client->datos_identificacion)
                    ? $client->datos_identificacion
                    : [];

                $fallbackNombre = trim(implode(' ', array_filter([
                    $datosIdentificacion['primer_nombre'] ?? null,
                    $datosIdentificacion['segundo_nombre'] ?? null,
                    $datosIdentificacion['apellido_paterno'] ?? null,
                    $datosIdentificacion['apellido_materno'] ?? null,
                ])));

                $nombreCompleto = trim(implode(' ', array_filter([
                    $client->nombre ?: $fallbackNombre,
                    $client->apellido_paterno,
                    $client->apellido_materno,
                ])));

                $tipoSolicitud = (string) ($client->tipo_solicitud ?? '');
                $tipoPersona = str_contains($tipoSolicitud, 'moral')
                    ? 'Persona moral'
                    : 'Persona fisica';

                $anexoDisponible = ($client->anexos_count ?? 0) > 0 || ! empty($client->anexo_path);
                $anexoNombre = $client->latestAnexo?->nombre_original ?: $client->anexo_nombre;
                $anexoTotal = max((int) ($client->anexos_count ?? 0), ! empty($client->anexo_path) ? 1 : 0);

                return [
                    'numero_cliente' => $client->id,
                    'cliente' => $nombreCompleto !== '' ? $nombreCompleto : 'Sin nombre',
                    'rfc' => $client->rfc ?: ($datosIdentificacion['rfc_homoclave'] ?? 'N/A'),
                    'tipo_persona' => $tipoPersona,
                    'anexo_disponible' => $anexoDisponible,
                    'anexo_nombre' => $anexoNombre,
                    'anexo_total' => $anexoTotal,
                    'cliente_validado' => $client->cliente_validado,
                ];
            })
            ->values();

        return response()->json([
            'data' => $clients,
        ]);
    }

    /**
     * Return loans list for "Prestamo" section.
     */
    public function prestamos(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $prestamos = ClientCapture::query()
            ->select([
                'id',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'datos_identificacion',
                'tipo_solicitud',
                'monto_solicitado',
                'estatus_prestamo',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('id', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->limit(500)
            ->get()
            ->map(function (ClientCapture $client) {
                $datosIdentificacion = is_array($client->datos_identificacion)
                    ? $client->datos_identificacion
                    : [];

                $fallbackNombre = trim(implode(' ', array_filter([
                    $datosIdentificacion['primer_nombre'] ?? null,
                    $datosIdentificacion['segundo_nombre'] ?? null,
                    $datosIdentificacion['apellido_paterno'] ?? null,
                    $datosIdentificacion['apellido_materno'] ?? null,
                ])));

                $nombreCompleto = trim(implode(' ', array_filter([
                    $client->nombre ?: $fallbackNombre,
                    $client->apellido_paterno,
                    $client->apellido_materno,
                ])));

                return [
                    'numero_prestamo' => $client->id,
                    'nombre_cliente' => $nombreCompleto !== '' ? $nombreCompleto : 'Sin nombre',
                    'tipo_solicitud' => $client->tipo_solicitud,
                    'monto_solicitado' => $client->monto_solicitado,
                    'estatus_prestamo' => $client->estatus_prestamo ?: 'PENDIENTE',
                ];
            })
            ->values();

        return response()->json([
            'data' => $prestamos,
        ]);
    }

    /**
     * Update one loan status (ACEPTADO / RECHAZADO).
     */
    public function updatePrestamoStatus(UpdatePrestamoStatusRequest $request, ClientCapture $clientCapture): JsonResponse
    {
        $clientCapture->update([
            'estatus_prestamo' => $request->validated()['estatus_prestamo'],
        ]);

        return response()->json([
            'message' => 'Estatus de préstamo actualizado correctamente.',
            'data' => [
                'numero_prestamo' => $clientCapture->id,
                'estatus_prestamo' => $clientCapture->estatus_prestamo,
            ],
        ]);
    }

    /**
     * Upload an anexo file for one client.
     */
    public function uploadAnexo(UploadClientAnexoRequest $request, ClientCapture $clientCapture): JsonResponse
    {
        Storage::disk('local')->makeDirectory("clientes_anexos/{$clientCapture->id}");

        $anexoFile = $request->file('anexo');
        $storedPath = $anexoFile->store("clientes_anexos/{$clientCapture->id}", 'local');

        $clientCapture->anexos()->create([
            'path' => $storedPath,
            'nombre_original' => $anexoFile->getClientOriginalName(),
            'mime_type' => $anexoFile->getClientMimeType(),
            'size_bytes' => $anexoFile->getSize(),
        ]);

        // Se mantiene por compatibilidad con el esquema previo de un solo anexo.
        $clientCapture->update([
            'anexo_path' => $storedPath,
            'anexo_nombre' => $anexoFile->getClientOriginalName(),
        ]);

        return response()->json([
            'message' => 'Anexo cargado correctamente.',
        ]);
    }

    /**
     * Download an uploaded anexo.
     */
    public function downloadAnexo(ClientCapture $clientCapture): StreamedResponse
    {
        $latestAnexo = $clientCapture->anexos()->latest('id')->first();

        if ($latestAnexo && Storage::disk('local')->exists($latestAnexo->path)) {
            return Storage::disk('local')->download(
                $latestAnexo->path,
                $latestAnexo->nombre_original
            );
        }

        if ($clientCapture->anexo_path && Storage::disk('local')->exists($clientCapture->anexo_path)) {
            return Storage::disk('local')->download(
                $clientCapture->anexo_path,
                $clientCapture->anexo_nombre ?: basename($clientCapture->anexo_path)
            );
        }

        abort(404, 'Anexo no encontrado.');
    }

    /**
     * List all uploaded anexos for one client.
     */
    public function listAnexos(ClientCapture $clientCapture): JsonResponse
    {
        $anexos = $clientCapture->anexos()
            ->orderByDesc('id')
            ->get(['id', 'nombre_original', 'mime_type', 'size_bytes', 'created_at'])
            ->map(function (ClientCaptureAnexo $anexo) use ($clientCapture) {
                return [
                    'id' => $anexo->id,
                    'nombre' => $anexo->nombre_original,
                    'mime_type' => $anexo->mime_type,
                    'size_bytes' => $anexo->size_bytes,
                    'fecha_carga' => optional($anexo->created_at)?->format('Y-m-d H:i:s'),
                    'download_url' => route('admin.client-capture.anexos.download', [
                        'clientCapture' => $clientCapture->id,
                        'anexo' => $anexo->id,
                    ]),
                ];
            })
            ->values();

        if ($anexos->isEmpty() && $clientCapture->anexo_path && Storage::disk('local')->exists($clientCapture->anexo_path)) {
            $anexos = collect([
                [
                    'id' => null,
                    'nombre' => $clientCapture->anexo_nombre ?: basename($clientCapture->anexo_path),
                    'mime_type' => null,
                    'size_bytes' => null,
                    'fecha_carga' => null,
                    'download_url' => route('admin.client-capture.anexo.download', [
                        'clientCapture' => $clientCapture->id,
                    ]),
                ],
            ]);
        }

        return response()->json([
            'data' => $anexos,
        ]);
    }

    /**
     * Download one specific anexo from a client.
     */
    public function downloadAnexoItem(ClientCapture $clientCapture, int $anexo): StreamedResponse
    {
        $anexoItem = $clientCapture->anexos()->whereKey($anexo)->first();

        if (! $anexoItem || ! Storage::disk('local')->exists($anexoItem->path)) {
            abort(404, 'Anexo no encontrado.');
        }

        return Storage::disk('local')->download(
            $anexoItem->path,
            $anexoItem->nombre_original
        );
    }

    /**
     * Update client validation status (SI / NO).
     */
    public function updateValidation(UpdateClientValidationRequest $request, ClientCapture $clientCapture): JsonResponse
    {
        $clientCapture->update([
            'cliente_validado' => $request->validated()['cliente_validado'],
        ]);

        return response()->json([
            'message' => 'Validacion actualizada correctamente.',
        ]);
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
    public function store(StoreClientCaptureRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $payload = $this->buildClientCapturePayload($validated);

        // Evita errores si la BD aún no tiene alguna columna nueva.
        $allowedColumns = Schema::getColumnListing('client_captures');
        $safePayload = Arr::only($payload, $allowedColumns);

        $clientCapture = ClientCapture::create($safePayload);

        return response()->json([
            'message' => 'Formulario guardado correctamente.',
            'data' => $clientCapture,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientCapture $clientCapture)
    {
        $garantias = $clientCapture->garantias;

        if (is_string($garantias) && $garantias !== '') {
            $decoded = json_decode($garantias, true);
            $garantias = is_array($decoded) ? $decoded : [];
        }

        return response()->json([
            'data' => [
                'id' => $clientCapture->id,
                'tipo_solicitud' => $clientCapture->tipo_solicitud,
                'medio_contacto' => [
                    'medio_contacto' => $clientCapture->medio_contacto,
                    'promotor' => $clientCapture->promotor,
                    'puesto_contacto' => $clientCapture->puesto_contacto,
                    'telefono_contacto' => $clientCapture->telefono_contacto,
                ],
                'datos_identificacion' => is_array($clientCapture->datos_identificacion) ? $clientCapture->datos_identificacion : [],
                'datos_laborales' => is_array($clientCapture->datos_laborales) ? $clientCapture->datos_laborales : [],
                'solicitud_operacion' => is_array($clientCapture->solicitud_operacion) ? $clientCapture->solicitud_operacion : [],
                'datos_contacto' => is_array($clientCapture->datos_contacto) ? $clientCapture->datos_contacto : [],
                'garantias' => is_array($garantias) ? $garantias : [],
                'pld' => is_array($clientCapture->pld) ? $clientCapture->pld : [],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientCapture $clientCapture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientCaptureRequest $request, ClientCapture $clientCapture): JsonResponse
    {
        $validated = $request->validated();
        $payload = $this->buildClientCapturePayload($validated);

        $allowedColumns = Schema::getColumnListing('client_captures');
        $safePayload = Arr::only($payload, $allowedColumns);

        $clientCapture->update($safePayload);

        return response()->json([
            'message' => 'Cliente actualizado correctamente.',
            'data' => $clientCapture->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientCapture $clientCapture): JsonResponse
    {
        $clientCapture->delete();

        return response()->json([
            'message' => 'Cliente eliminado correctamente.',
        ]);
    }

    /**
     * Build a safe payload for create/update.
     */
    private function buildClientCapturePayload(array $validated): array
    {
        $medioContacto = $validated['medio_contacto'] ?? [];
        $datosIdentificacion = $validated['datos_identificacion'] ?? [];
        $datosLaborales = $validated['datos_laborales'] ?? [];
        $solicitudOperacion = $validated['solicitud_operacion'] ?? [];
        $datosContacto = $validated['datos_contacto'] ?? [];
        $garantias = $validated['garantias'] ?? [];
        $pld = $validated['pld'] ?? [];

        $nombreCompleto = trim(sprintf(
            '%s %s',
            $datosIdentificacion['primer_nombre'] ?? '',
            $datosIdentificacion['segundo_nombre'] ?? ''
        ));

        return [
            'tipo_solicitud' => $validated['tipo_solicitud'] ?? null,

            // Medio de contacto
            'medio_contacto' => $medioContacto['medio_contacto'] ?? null,
            'promotor' => $medioContacto['promotor'] ?? null,
            'puesto_contacto' => $medioContacto['puesto_contacto'] ?? null,
            'telefono_contacto' => $medioContacto['telefono_contacto'] ?? null,

            // Datos de identificación
            'nombre' => $nombreCompleto !== '' ? $nombreCompleto : null,
            'apellido_paterno' => $datosIdentificacion['apellido_paterno'] ?? null,
            'apellido_materno' => $datosIdentificacion['apellido_materno'] ?? null,
            'curp' => $datosIdentificacion['curp'] ?? null,
            'rfc' => $datosIdentificacion['rfc_homoclave'] ?? null,
            'fecha_nacimiento' => $datosIdentificacion['fecha_nacimiento'] ?? null,
            'lugar_nacimiento' => $datosIdentificacion['entidad_nacimiento'] ?? null,
            'nacionalidad' => $pld['nacionalidad'] ?? null,
            'estado_civil' => $datosIdentificacion['estado_civil'] ?? null,
            'regimen_matrimonial' => null,

            // Datos laborales
            'ocupacion' => $pld['ocupacion'] ?? $datosLaborales['situacion_laboral'] ?? null,
            'empresa' => $datosLaborales['nombre_empresa'] ?? null,
            'puesto' => $datosLaborales['puesto'] ?? null,
            'antiguedad' => $datosIdentificacion['tiempo_vivir'] ?? null,
            'telefono_empresa' => $datosLaborales['telefono_laboral'] ?? null,

            // Solicitud de operación
            'ingresos_brutos' => $solicitudOperacion['ingresos_brutos'] ?? null,
            'ingresos_netos' => $solicitudOperacion['ingresos_netos'] ?? null,
            'otros_ingresos' => $solicitudOperacion['otros_ingresos'] ?? null,
            'gastos_fijos' => $solicitudOperacion['gastos_fijos'] ?? null,
            'deudas_actuales' => $solicitudOperacion['deudas_actuales'] ?? null,
            'producto_financiero' => $solicitudOperacion['producto_financiero'] ?? null,
            'forma_pago' => $solicitudOperacion['forma_pago'] ?? null,
            'tasa_anual' => $solicitudOperacion['tasa_anual'] ?? null,
            'comision' => $solicitudOperacion['comision'] ?? null,
            'plazo_solicitado' => $solicitudOperacion['plazo_solicitado'] ?? null,
            'monto_solicitado' => $solicitudOperacion['monto_solicitado'] ?? null,
            'fecha_disposicion' => $solicitudOperacion['fecha_disposicion'] ?? null,
            'fecha_primera_cuota' => $solicitudOperacion['fecha_primera_cuota'] ?? null,

            // Datos de contacto
            'calle' => $datosIdentificacion['calle'] ?? null,
            'numero_exterior' => $datosIdentificacion['numero_exterior'] ?? null,
            'numero_interior' => null,
            'colonia' => $datosIdentificacion['colonia'] ?? null,
            'codigo_postal' => $datosIdentificacion['codigo_postal'] ?? null,
            'municipio' => $datosIdentificacion['municipio'] ?? null,
            'estado' => $datosIdentificacion['estado'] ?? null,
            'telefono_casa' => $datosContacto['telefono_casa'] ?? null,
            'telefono_celular' => $datosContacto['telefono_celular'] ?? null,
            'email' => $datosContacto['email'] ?? null,

            // Payload completo por sección
            'datos_identificacion' => $datosIdentificacion,
            'datos_laborales' => $datosLaborales,
            'solicitud_operacion' => $solicitudOperacion,
            'datos_contacto' => $datosContacto,
            'pld' => $pld,

            // Secciones sin columnas dedicadas
            'garantias' => ! empty($garantias) ? json_encode($garantias, JSON_UNESCAPED_UNICODE) : null,
            'pld_datos' => ! empty($pld) ? json_encode($pld, JSON_UNESCAPED_UNICODE) : null,
        ];
    }
}
