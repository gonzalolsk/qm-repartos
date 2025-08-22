<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="ordenes",
 *     description="Gestión de órdenes"
 * )
 */
class OrderController extends Controller
{

    /**
     * @OA\Post(
     *     path="/ordenes",
     *     tags={"Ordenes"},
     *     summary="Alta de orden asociada a un cliente",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id","codigo_de_orden","fecha_creacion"},
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="codigo_de_orden", type="string", example="ORD001"),
     *             @OA\Property(property="fecha_creacion", type="string", format="date", example="2025-01-15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Orden creada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Orden creada exitosamente")
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
                'client_id' => 'required|exists:clients,id', // validamos que el cliente exista
                'codigo_de_orden' => 'required|string|unique:orders,codigo_de_orden', // validamos que el codigo de orden sea único
                'fecha_creacion' => 'required|date'
            ]);

            $orden = Order::create($validatedData);
            $orden->load('client');

            return response()->json([
                'success' => true,
                'data' => $orden,
                'message' => 'Orden creada exitosamente'
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
     * @OA\Patch(
     *     path="/ordenes/{orderId}/asignar-reparto",
     *     tags={"Ordenes"},
     *     summary="Asignar una orden a un reparto",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         description="ID de la orden",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"distribution_id"},
     *             @OA\Property(property="distribution_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden asignada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Orden asignada al reparto exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación o orden ya asignada"
     *     )
     * )
     */
    public function assignToDistribution(Request $request, $orderId): JsonResponse // Asignar una orden a un reparto
    {
        try {
            $validatedData = $request->validate([
                'distribution_id' => 'required|exists:distributions,id'
            ]);

            $orden = Order::find($orderId);

            // validamos que la orden exista
            if (!$orden) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            // validamos que la orden no tenga un reparto asignado
            if ($orden->distribution_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La orden ya está asignada a un reparto'
                ], 422);
            }

            // actualizamos la orden para asignarle el reparto
            $orden->update(['distribution_id' => $validatedData['distribution_id']]);
            // cargamos el cliente y el vehiculo del reparto
            $orden->load(['client', 'distribution.vehicle']); 

            return response()->json([
                'success' => true,
                'data' => $orden,
                'message' => 'Orden asignada al reparto exitosamente'
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


