<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Clientes",
 *     description="Gestión de clientes"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Post(
     *     path="/clientes",
     *     tags={"Clientes"},
     *     summary="Alta de cliente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codigo","razon_social","email","direccion","latitud","longitud"},
     *             @OA\Property(property="codigo", type="string", example="CLI001"),
     *             @OA\Property(property="razon_social", type="string", example="Empresa ABC"),
     *             @OA\Property(property="email", type="string", format="email", example="cliente@empresa.com"),
     *             @OA\Property(property="direccion", type="string", example="Av. Principal 123"),
     *             @OA\Property(property="latitud", type="number", example=-34.6037),
     *             @OA\Property(property="longitud", type="number", example=-58.3816)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente creado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Cliente creado exitosamente")
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
                'codigo' => 'required|string|unique:clients,codigo',
                'razon_social' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email', // validamos que el email sea único
                'direccion' => 'required|string',
                'latitud' => 'required|numeric|between:-90,90',
                'longitud' => 'required|numeric|between:-180,180'
            ]);

            $cliente = Client::create($validatedData);

            return response()->json([
                'success' => true,
                'data' => $cliente,
                'message' => 'Cliente creado exitosamente'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

}
