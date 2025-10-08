<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios activos ordenados alfabéticamente.
     */
    public function index(): JsonResponse
    {
        try {
            $usuarios = User::where('estado', 'activo')
                ->orderBy('nombres')
                ->orderBy('apellidos')
                ->get();

            return response()->json([
                'success' => true,
                'data' => UserResource::collection($usuarios),
                'message' => 'Usuarios obtenidos correctamente.',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los usuarios.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $usuario = User::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => new UserResource($usuario),
                'message' => 'Usuario creado exitosamente.',
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mostrar un usuario específico.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $usuario = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new UserResource($usuario),
                'message' => 'Usuario obtenido correctamente.',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Actualizar un usuario existente.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => new UserResource($usuario),
                'message' => 'Usuario actualizado exitosamente.',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar un usuario (soft delete: cambia el estado a inactivo).
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->update(['estado' => 'inactivo']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente.',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
