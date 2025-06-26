<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employeetype;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $emptypes = Employeetype::all();

        if ($request->ajax()) {
            return DataTables::of($emptypes)
                ->addColumn('edit', function ($emptype) {
                    $protegidos = ['CONDUCTOR', 'AYUDANTE'];
                    if (in_array(strtoupper($emptype->name), $protegidos)) {
                        // Puedes dejar vacío, poner texto, o un ícono de candado
                        return '<span class="text-muted"></span>';
                    }
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $emptype->id . '">
                <i class="fas fa-pen"></i></button>';
                })
                ->addColumn('delete', function ($emptype) {
                    $protegidos = ['CONDUCTOR', 'AYUDANTE'];
                    if (in_array(strtoupper($emptype->name), $protegidos)) {
                        // Puedes dejar vacío, poner texto, o un ícono de candado
                        return '<span class="text-muted"></span>';
                    }
                    return '<form action="' . route('admin.emptypes.destroy', $emptype->id) . '" method="POST"
                        class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i
                         class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.emptypes.index', compact('emptypes'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.emptypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'unique:employeetypes',
            ], [
                'name.unique' => 'Ya existe un tipo de empleado con ese nombre.',
            ]);
            Employeetype::create($request->all());
            return response()->json(['message' => 'Tipo de empleado registrado correctamente'], 200);
        } catch (\Throwable $th) {
            // Si es un error de validación, Laravel lanza automáticamente el mensaje definido arriba
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $emptype = Employeetype::find($id);
        return view('admin.emptypes.edit', compact('emptype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $emptype = Employeetype::find($id);

            $request->validate([
                'name' => 'unique:employeetypes,name,' . $id,
            ]);

            $emptype->update($request->all());
            return response()->json(['message' => 'Tipo de empleado actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $emptype = Employeetype::find($id);

            // Protege tipos predefinidos por nombre (mayúsculas por seguridad)
            $protegidos = ['CONDUCTOR', 'AYUDANTE'];
            if ($emptype && in_array(strtoupper($emptype->name), $protegidos)) {
                return response()->json([
                    'message' => 'Este tipo de empleado es predefinido y no puede eliminarse.'
                ], 403);
            }

            $emptype->delete();
            return response()->json(['message' => 'Tipo de empleado eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
