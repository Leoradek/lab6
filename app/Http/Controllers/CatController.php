<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CatController extends Controller
{
    // GET /cats - CON SOPORTE PARA LIMIT
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit');
        $sql = 'SELECT * FROM gatos';
        
        if ($limit && is_numeric($limit)) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        
        $gatos = DB::select($sql);
        return response()->json($gatos);
    }

    // GET /cats/{id} - CON ERROR 404
    public function show($id): JsonResponse
    {
        $gato = DB::select('SELECT * FROM gatos WHERE id = ?', [$id]);
        
        if (empty($gato)) {
            // FORZAR respuesta JSON
            return response()->json([
                'error' => 'Gato no encontrado',
                'message' => 'El gato con id ' . $id . ' no existe'
            ], 404);
        }
        
        return response()->json($gato[0]);
    }

    // POST /cats - CREAR GATO
    public function store(Request $request): JsonResponse
    {
        $nombre = $request->input('nombre');
        DB::insert('INSERT INTO gatos (nombre) VALUES (?)', [$nombre]);
        
        $id = DB::getPdo()->lastInsertId();
        $gato = DB::select('SELECT * FROM gatos WHERE id = ?', [$id]);
        
        return response()->json($gato[0], 201);
    }

    // PUT /cats/{id} - ACTUALIZAR CON ERROR 404
    public function update(Request $request, $id): JsonResponse
    {
        // Verificar si existe
        $gato = DB::select('SELECT * FROM gatos WHERE id = ?', [$id]);
        
        if (empty($gato)) {
            // FORZAR respuesta JSON
            return response()->json([
                'error' => 'Gato no encontrado',
                'message' => 'El gato con id ' . $id . ' no existe'
            ], 404);
        }
        
        // Actualizar
        $nombre = $request->input('nombre');
        DB::update('UPDATE gatos SET nombre = ? WHERE id = ?', [$nombre, $id]);
        
        // Devolver gato actualizado
        $gatoActualizado = DB::select('SELECT * FROM gatos WHERE id = ?', [$id]);
        return response()->json($gatoActualizado[0]);
    }

    // DELETE /cats/{id} - ELIMINAR CON ERROR 404
    public function destroy($id): JsonResponse
    {
        // Verificar si existe
        $gato = DB::select('SELECT * FROM gatos WHERE id = ?', [$id]);
        
        if (empty($gato)) {
            // FORZAR respuesta JSON
            return response()->json([
                'error' => 'Gato no encontrado',
                'message' => 'El gato con id ' . $id . ' no existe'
            ], 404);
        }
        
        // Eliminar
        DB::delete('DELETE FROM gatos WHERE id = ?', [$id]);
        
        return response()->json([
            'message' => 'Gato eliminado correctamente'
        ]);
    }
}