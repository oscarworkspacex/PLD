<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlertaAnonimaRequest;
use App\Models\AlertaAnonima;
use App\Models\ClientCapture;
use Illuminate\Http\JsonResponse;

class AlertaAnonimaController extends Controller
{
    private function serializeReporte(AlertaAnonima $reporte): array
    {
        return [
            'id' => $reporte->id,
            'destinatario' => (string) ($reporte->destinatario ?? ''),
            'asunto' => (string) ($reporte->asunto ?? ''),
            'empleado_reportado' => (string) ($reporte->empleado_reportado ?? ''),
            'motivo' => (string) ($reporte->motivo ?? ''),
            'detalle' => (string) ($reporte->detalle ?? ''),
            'denuncia' => (string) ($reporte->denuncia ?? ''),
            'is_leida' => (bool) ($reporte->is_leida ?? false),
            'leida_at' => optional($reporte->leida_at)?->format('Y-m-d H:i:s') ?? '',
            'created_at' => optional($reporte->created_at)?->format('Y-m-d H:i:s') ?? '',
        ];
    }

    public function index(): JsonResponse
    {
        $reportes = AlertaAnonima::query()
            ->orderByDesc('id')
            ->limit(500)
            ->get()
            ->map(fn (AlertaAnonima $reporte) => $this->serializeReporte($reporte))
            ->values();

        return response()->json([
            'data' => $reportes,
        ]);
    }

    public function store(StoreAlertaAnonimaRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $reporte = AlertaAnonima::create([
            'destinatario' => $validated['destinatario'] ?? null,
            'asunto' => $validated['asunto'] ?? null,
            'empleado_reportado' => $validated['empleado_reportado'] ?? null,
            'motivo' => $validated['motivo'] ?? null,
            'detalle' => $validated['detalle'] ?? null,
            'denuncia' => $validated['denuncia'],
        ]);

        return response()->json([
            'message' => 'Reporte anónimo guardado correctamente.',
            'data' => $this->serializeReporte($reporte),
        ], 201);
    }

    public function markAsRead(AlertaAnonima $alertaAnonima): JsonResponse
    {
        if (! $alertaAnonima->is_leida) {
            $alertaAnonima->update([
                'is_leida' => true,
                'leida_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Reporte marcado como leído.',
            'data' => $this->serializeReporte($alertaAnonima->fresh()),
        ]);
    }

    public function destroy(AlertaAnonima $alertaAnonima): JsonResponse
    {
        $alertaAnonima->delete();

        return response()->json([
            'message' => 'Reporte eliminado correctamente.',
        ]);
    }

    public function downloadXml()
    {
        $clientId = (int) request('client_id');

        if ($clientId <= 0) {
            return response()->json([
                'message' => 'Debe seleccionar un cliente.',
            ], 422);
        }

        $client = ClientCapture::query()->find($clientId);

        if (! $client) {
            return response()->json([
                'message' => 'Cliente no encontrado.',
            ], 404);
        }

        $nombreTitular = trim(implode(' ', array_filter([
            (string) ($client->nombre ?? ''),
            (string) ($client->apellido_paterno ?? ''),
            (string) ($client->apellido_materno ?? ''),
        ])));
        $domicilio = trim(implode(' ', array_filter([
            (string) ($client->calle ?? ''),
            (string) ($client->numero_exterior ?? ''),
            (string) ($client->numero_interior ?? ''),
        ])));
        $esPersonaMoral = str_contains(strtolower((string) ($client->tipo_solicitud ?? '')), 'moral');
        $telefono = (string) (
            $client->telefono_casa
            ?? $client->telefono_celular
            ?? $client->telefono_contacto
            ?? $client->telefono_empresa
            ?? ''
        );
        $periodo = optional($client->created_at)?->format('Ym') ?? now()->format('Ym');
        $fechaOperacion = (string) ($client->fecha_disposicion ?? optional($client->created_at)?->format('Y-m-d') ?? '');
        $fechaDeteccion = (string) (optional($client->created_at)?->format('Y-m-d') ?? '');

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><reporte_operaciones_relevantes></reporte_operaciones_relevantes>');

        $columnas = [
            'TIPO DE REPORTE',
            'PERIODO DEL REPORTE',
            'FOLIO',
            'RGANO SUPERVISOR',
            'CLAVE DEL SUJETO OBLIGADO',
            'LOCALIDAD',
            'CODIGO POSTAL DE LA SUCURSAL',
            'TIPO DE OPERACION',
            'INSTRUMENTO MONETARIO',
            'NUMERO DE CUENTA, CONTRATO U OPERACION',
            'MONTO',
            'MONEDA',
            'FECHA DE LA OPERACION',
            'FECHA DE DETECCION DE LA OPERACION',
            'NACIONALIDAD',
            'TIPO DE PERSONA',
            'RAZON SOCIAL O DENOMINACION',
            'NOMBRE',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'RFC',
            'CURP',
            'FECHA DE NACIMIENTO O CONSTITUCION',
            'DOMICILIO',
            'COLONIA',
            'CIUDAD O POBLACION',
            'TELEFONO OFICINA/PARTICULAR',
            'ACTIVIDAD ECONOMICA',
            'CONSECUTIVO DE CUENTAS Y/O PERSONAS RELACIONADAS',
            'NUMERO DE CUENTA, CONTRATO, OPERACION, PLAZA O NUMERO DE SEGURIDAD SOCIAL',
            'CLAVE DEL SUJETO OBLIGADO',
            'NOMBRE DEL TITULAR DE LA CUENTA O DE LA PERSONA RELACIONADA',
        ];

        $encabezado = $xml->addChild('encabezado');
        foreach ($columnas as $index => $nombreColumna) {
            $columna = $encabezado->addChild('columna', $nombreColumna);
            $columna->addAttribute('numero', (string) ($index + 1));
        }

        $registro = $xml->addChild('registro');
        $registro->addChild('tipo_de_reporte', 'OR');
        $registro->addChild('periodo_del_reporte', $periodo);
        $registro->addChild('folio', 'OR-'.str_pad((string) $client->id, 8, '0', STR_PAD_LEFT));
        $registro->addChild('rgano_supervisor', 'CNBV');
        $registro->addChild('clave_del_sujeto_obligado', '');
        $registro->addChild('localidad', (string) ($client->municipio ?? $client->estado ?? ''));
        $registro->addChild('codigo_postal_de_la_sucursal', (string) ($client->codigo_postal ?? ''));
        $registro->addChild('tipo_de_operacion', (string) ($client->producto_financiero ?? ''));
        $registro->addChild('instrumento_monetario', (string) ($client->forma_pago ?? ''));
        $registro->addChild('numero_de_cuenta_contrato_u_operacion', '');
        $registro->addChild('monto', (string) ($client->monto_solicitado ?? ''));
        $registro->addChild('moneda', '');
        $registro->addChild('fecha_de_la_operacion', $fechaOperacion);
        $registro->addChild('fecha_de_deteccion_de_la_operacion', $fechaDeteccion);
        $registro->addChild('nacionalidad', (string) ($client->nacionalidad ?? ''));
        $registro->addChild('tipo_de_persona', $esPersonaMoral ? 'MORAL' : 'FISICA');
        $registro->addChild('razon_social_o_denominacion', $esPersonaMoral ? $nombreTitular : '');
        $registro->addChild('nombre', $esPersonaMoral ? '' : (string) ($client->nombre ?? ''));
        $registro->addChild('apellido_paterno', $esPersonaMoral ? '' : (string) ($client->apellido_paterno ?? ''));
        $registro->addChild('apellido_materno', $esPersonaMoral ? '' : (string) ($client->apellido_materno ?? ''));
        $registro->addChild('rfc', (string) ($client->rfc ?? ''));
        $registro->addChild('curp', (string) ($client->curp ?? ''));
        $registro->addChild('fecha_de_nacimiento_o_constitucion', (string) ($client->fecha_nacimiento ?? ''));
        $registro->addChild('domicilio', $domicilio);
        $registro->addChild('colonia', (string) ($client->colonia ?? ''));
        $registro->addChild('ciudad_o_poblacion', (string) ($client->municipio ?? ''));
        $registro->addChild('telefono_oficina_particular', $telefono);
        $registro->addChild('actividad_economica', (string) ($client->ocupacion ?? ''));
        $registro->addChild('consecutivo_de_cuentas_yo_personas_relacionadas', '');
        $registro->addChild('numero_de_cuenta_contrato_operacion_plaza_o_numero_de_seguridad_social', '');
        $registro->addChild('clave_del_sujeto_obligado_segunda', '');
        $registro->addChild('nombre_del_titular_de_la_cuenta_o_de_la_persona_relacionada', $nombreTitular);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return response($dom->saveXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="tabla_operaciones_relevantes.xml"');
    }

    public function downloadXmlRules()
    {
        $clientId = (int) request('client_id');

        if ($clientId <= 0) {
            return response()->json([
                'message' => 'Debe seleccionar un cliente.',
            ], 422);
        }

        $client = ClientCapture::query()->find($clientId);

        if (! $client) {
            return response()->json([
                'message' => 'Cliente no encontrado.',
            ], 404);
        }

        $datosIdentificacion = is_array($client->datos_identificacion) ? $client->datos_identificacion : [];
        $solicitudOperacion = is_array($client->solicitud_operacion) ? $client->solicitud_operacion : [];
        $pld = is_array($client->pld) ? $client->pld : [];

        $esPersonaMoral = str_contains(strtolower((string) ($client->tipo_solicitud ?? '')), 'moral');
        $periodoYyyymmdd = optional($client->created_at)?->format('Ymd') ?? now()->format('Ymd');
        $folio = str_pad((string) $client->id, 6, '0', STR_PAD_LEFT);
        $organoSupervisor = '000001';
        $clienteNumero = (string) $client->id;
        $clienteNumero2 = str_pad(substr($clienteNumero, -2), 2, '0', STR_PAD_LEFT);

        $primerNombre = (string) ($datosIdentificacion['primer_nombre'] ?? '');
        $segundoNombre = (string) ($datosIdentificacion['segundo_nombre'] ?? '');
        $nombreBase = trim((string) ($client->nombre ?: trim($primerNombre.' '.$segundoNombre)));
        $apellidoPaternoBase = (string) ($client->apellido_paterno ?? ($datosIdentificacion['apellido_paterno'] ?? ''));
        $apellidoMaternoBase = (string) ($client->apellido_materno ?? ($datosIdentificacion['apellido_materno'] ?? ''));

        $nombre = $this->toAlnum($nombreBase !== '' ? $nombreBase : 'NOMBRE', 60);
        $apellidoPaterno = $this->toAlnum($apellidoPaternoBase !== '' ? $apellidoPaternoBase : 'XXXX', 60);
        $apellidoMaterno = $this->toAlnum($apellidoMaternoBase !== '' ? $apellidoMaternoBase : 'XXXX', 30);

        $nombreCompleto = trim($this->toAlnum(trim($nombre.' '.$apellidoPaterno.' '.$apellidoMaterno), 125));
        $razonSocial = $esPersonaMoral ? $nombreCompleto : '';

        $domicilio = $this->toAlnum(trim(implode(' ', array_filter([
            (string) ($client->calle ?? ($datosIdentificacion['calle'] ?? '')),
            (string) ($client->numero_exterior ?? ($datosIdentificacion['numero_exterior'] ?? '')),
            (string) ($client->numero_interior ?? ''),
        ]))), 60);
        $domicilio = $domicilio !== '' ? $domicilio : 'SIN DOMICILIO';

        $colonia = $this->toAlnum((string) ($client->colonia ?? ($datosIdentificacion['colonia'] ?? '')), 30);
        $colonia = $colonia !== '' ? $colonia : 'SIN COLONIA';

        $ciudad = $this->toAlnum((string) ($client->municipio ?? ($datosIdentificacion['municipio'] ?? $client->estado ?? '')), 8);
        $ciudad = $ciudad !== '' ? $ciudad : 'CIUDAD';

        $telefono = $this->toAlnum((string) (
            $client->telefono_casa
            ?? $client->telefono_celular
            ?? $client->telefono_contacto
            ?? $client->telefono_empresa
            ?? $datosIdentificacion['telefono'] ?? ''
        ), 40);
        $telefono = $telefono !== '' ? $telefono : '0000000000';

        $montoBase = (string) ($client->monto_solicitado ?? ($solicitudOperacion['monto_solicitado'] ?? '0'));
        $monto = $this->toMonto17($montoBase);

        $nacionalidadRaw = strtoupper((string) ($client->nacionalidad ?? ($pld['nacionalidad'] ?? '1')));
        $nacionalidad = $nacionalidadRaw === 'MEXICANA' || $nacionalidadRaw === 'MX' || $nacionalidadRaw === '1' ? '1' : '2';
        $tipoPersona = $esPersonaMoral ? '2' : '1';

        $fechaNacimientoRaw = preg_replace('/[^0-9]/', '', (string) ($client->fecha_nacimiento ?? ($datosIdentificacion['fecha_nacimiento'] ?? '')));
        $fechaNacimiento = strlen($fechaNacimientoRaw) === 8 ? $fechaNacimientoRaw : '19900101';

        $rfc = strtoupper($this->toAlnum((string) ($client->rfc ?? ($datosIdentificacion['rfc_homoclave'] ?? '')), 13));
        $rfc = $rfc !== '' ? $rfc : 'XAXX010101000';
        $curp = strtoupper($this->toAlnum((string) ($client->curp ?? ($datosIdentificacion['curp'] ?? '')), 18));
        $curp = $curp !== '' ? $curp : 'XEXX010101HDFXXX09';

        $codigoPostal = $this->toNumeric((string) ($client->codigo_postal ?? ($datosIdentificacion['codigo_postal'] ?? '')), 5);
        $codigoPostal = $codigoPostal !== '' ? $codigoPostal : '00000';

        $actividadEconomica = $this->toAlnum((string) ($client->ocupacion ?? ($pld['ocupacion'] ?? 'OTROS')), 7);
        $descripcionOperacion = $this->toAlnum((string) ($client->producto_financiero ?? 'OPERACION CREDITO CLIENTE '.$clienteNumero), 4000);
        $descripcionOperacion = $descripcionOperacion !== '' ? $descripcionOperacion : 'OPERACION CLIENTE '.$clienteNumero;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><reporte_operaciones_relevantes_reglas></reporte_operaciones_relevantes_reglas>');
        $registro = $xml->addChild('registro');

        // 1-4: datos reales del cliente
        $registro->addChild('tipo_de_reporte', '1');
        $registro->addChild('periodo_del_reporte', $periodoYyyymmdd);
        $registro->addChild('folio', $folio);
        $registro->addChild('rgano_supervisor', $organoSupervisor);

        // 5-35: llenado siguiendo formato/longitud solicitada
        $registro->addChild('clave_o_numero_de_registro_del_sujeto_obligado', $this->toAlnum(str_pad($clienteNumero, 8, '0', STR_PAD_LEFT), 8));
        $registro->addChild('localidad', $this->toAlnum($ciudad, 8));
        $registro->addChild('codigo_postal_de_la_sucursal', $codigoPostal);
        $registro->addChild('tipo_de_operacion', '01');
        $registro->addChild('instrumento_monetario', '01');
        $registro->addChild('numero_de_cuenta_contrato_u_operacion', $this->toAlnum(str_pad($clienteNumero, 16, '0', STR_PAD_LEFT), 16));
        $registro->addChild('monto', $monto);
        $registro->addChild('moneda', 'MXN');
        $registro->addChild('fecha_de_la_operacion', $periodoYyyymmdd);
        $registro->addChild('fecha_de_deteccion_de_la_operacion', optional($client->updated_at)?->format('Ymd') ?? $periodoYyyymmdd);
        $registro->addChild('nacionalidad', $nacionalidad);
        $registro->addChild('tipo_de_persona', $tipoPersona);
        $registro->addChild('razon_social_o_denominacion', $razonSocial);
        $registro->addChild('nombre', $esPersonaMoral ? '' : $nombre);
        $registro->addChild('apellido_paterno', $esPersonaMoral ? '' : $apellidoPaterno);
        $registro->addChild('apellido_materno', $esPersonaMoral ? '' : $apellidoMaterno);
        $registro->addChild('rfc', $rfc);
        $registro->addChild('curp', $curp);
        $registro->addChild('fecha_de_nacimiento_o_constitucion', $fechaNacimiento);
        $registro->addChild('domicilio', $domicilio);
        $registro->addChild('colonia', $colonia);
        $registro->addChild('ciudad_o_poblacion', $this->toAlnum($ciudad, 8));
        $registro->addChild('telefono', $telefono);
        $registro->addChild('actividad_economica', $actividadEconomica);

        // VRC29R1 y relacionados: usar el número del cliente.
        $registro->addChild('consecutivo_de_cuentas_yo_personas_relacionadas', $clienteNumero2);
        $registro->addChild('numero_de_cuenta_contrato_operacion_plaza_o_numero_de_seguridad_social', $this->toAlnum(str_pad($clienteNumero, 16, '0', STR_PAD_LEFT), 16));
        $registro->addChild('clave_del_sujeto_obligado', $this->toAlnum(str_pad($clienteNumero, 7, '0', STR_PAD_LEFT), 7));
        $registro->addChild('nombre_del_titular_de_la_cuenta_o_de_la_persona_relacionada', $this->toAlnum($nombreCompleto.' '.$clienteNumero, 60));
        $registro->addChild('apellido_paterno_relacionada', $this->toAlnum($apellidoPaterno.' '.$clienteNumero, 60));
        $registro->addChild('apellido_materno_relacionada', $this->toAlnum($apellidoMaterno.' '.$clienteNumero, 30));
        $registro->addChild('descripcion_de_la_operacion', $this->toAlnum($descripcionOperacion.' '.$clienteNumero, 4000));

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return response($dom->saveXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="tabla_operaciones_relevantes_reglas.xml"');
    }

    private function toAlnum(string $value, int $maxLength): string
    {
        $value = strtoupper(trim($value));
        $value = preg_replace('/[^A-Z0-9 ]/', '', $value) ?? '';

        return mb_substr($value, 0, $maxLength);
    }

    private function toNumeric(string $value, int $maxLength): string
    {
        $value = preg_replace('/[^0-9]/', '', $value) ?? '';
        if ($value === '') {
            return '';
        }

        return substr($value, 0, $maxLength);
    }

    private function toMonto17(string $value): string
    {
        $normalized = preg_replace('/[^0-9.]/', '', $value) ?? '0';
        $number = (float) $normalized;

        return number_format($number, 2, '.', '');
    }
}
