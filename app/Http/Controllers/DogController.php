<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;

class DogController extends Controller
{
    // GET /dogs?limit=N
    public function index(Request $request)
    {
        $limit = $request->query('limit');

        if ($limit && is_numeric($limit) && $limit > 0) {
            $dogs = Dog::limit($limit)->get();
        } else {
            $dogs = Dog::all();
        }

        return response()->json($dogs);
    }

    // POST /dogs
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:50']);
        $dog = Dog::create($request->all());
        return response()->json($dog, 201);
    }

    // GET /dogs/{id} 
    public function show(string $id)
    {
        $dog = Dog::find($id);
        
        if (!$dog) {
            return response()->json([
                'error' => 'Perro no encontrado',
                'message' => 'El perro con id ' . $id . ' no existe'
            ], 404);
        }
        
        return response()->json($dog);
    }

    // PUT /dogs/{id} 
    public function update(Request $request, string $id)
    {
        $dog = Dog::find($id);
        
        if (!$dog) {
            return response()->json([
                'error' => 'Perro no encontrado',
                'message' => 'El perro con id ' . $id . ' no existe'
            ], 404);
        }
        
        $request->validate(['nombre' => 'required|string|max:50']);
        $dog->update($request->all());

        return response()->json($dog);
    }

    // DELETE /dogs/{id} 
    public function destroy(string $id)
    {
        $dog = Dog::find($id);
        
        if (!$dog) {
            return response()->json([
                'error' => 'Perro no encontrado',
                'message' => 'El perro con id ' . $id . ' no existe'
            ], 404);
        }
        
        $dog->delete();

        return response()->json(['message' => 'Perro eliminado correctamente']);
    }
}