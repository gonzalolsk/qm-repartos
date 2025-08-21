<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Repartos",
 *     description="Gestión de repartos"
 * )
 */
class DistributionController extends Controller
{

    /**
     * @OA\Post(
     *     path="/repartos",
     *     tags={"Repartos"},
     *     summary="Alta de un reparto con vehículo asignado",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codigo_de_reparto","fecha_entrega","vehicle_id"},
     *             @OA\Property(property="codigo_de_reparto", type="string", example="REP001"),
     *             @OA\Property(property="fecha_entrega", type="string", format="date", example="2025-01-15"),
     *             @OA\Property(property="estado", type="string", enum={"pendiente","en_progreso","completado","cancelado"}, example="pendiente"),
     *             @OA\Property(property="vehicle_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reparto creado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Reparto creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'codigo_de_reparto' => 'required|string|unique:distributions,codigo_de_reparto',
                'fecha_entrega' => 'required|date',
                'estado' => 'sometimes|in:pendiente,en_progreso,completado,cancelado',
                'vehicle_id' => 'required|exists:vehicles,id' //  Validamos que el vehículo exista
            ]);

            $reparto = Distribution::create($validatedData);
            $reparto->load('vehicle');

            return response()->json([
                'success' => true,
                'data' => $reparto,
                'message' => 'Reparto creado exitosamente'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/repartos/por-fecha",
     *     tags={"Repartos"},
     *     summary="Listar los repartos de un día, mostrando las órdenes y los clientes asociados.",
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         required=true,
     *         description="Fecha de entrega (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-01-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Repartos encontrados",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="fecha_consulta", type="string", example="2025-01-15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function getRepartosByDate(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'fecha' => 'required|date'
            ]);

            $fecha = Carbon::parse($validatedData['fecha'])->format('Y-m-d');

            $repartos = Distribution::with([
                'vehicle',
                'orders.client'
            ])
            ->whereDate('fecha_entrega', $fecha)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $repartos,
                'fecha_consulta' => $fecha
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
