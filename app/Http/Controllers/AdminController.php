<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);
        return response()->json($admin, 201);
    }

    public function show(string $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin no encontrado'], 404);
        }

        return response()->json($admin);
    }

    public function update(Request $request, string $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'email' => 'email|unique:admin,email,' . $id,
            'password' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);
        return response()->json($admin);
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $admin = Admin::find($id);
            if (!$admin) {
                DB::rollBack();
                return response()->json(['message' => 'Admin no encontrado'], 404);
            }
            
            $admin->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Admin eliminado correctamente',
                'success' => true
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return response()->json([
                    'message' => 'No se puede eliminar el admin porque tiene citas asociadas. Elimina primero las citas.'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Error al eliminar el admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


