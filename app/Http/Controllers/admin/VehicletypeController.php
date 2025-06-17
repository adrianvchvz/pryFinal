<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicletype;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VehicletypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehtypes = Vehicletype::all();

        if ($request->ajax()) {
            return DataTables::of($vehtypes)
                ->addColumn('edit', function ($vehtype) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $vehtype->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($vehtype) {
                    return '<form action="' . route('admin.vehtypes.destroy', $vehtype->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.vehtypes.index', compact('vehtypes'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehtypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'unique:vehicletypes',
            ]);
            Vehicletype::create($request->all());
            return response()->json(['message' => 'Tipo de vehículo registrado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json([['message' => 'Hubo un error en el registro' . $th->getMessage()], 500]);
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
        $vehtype = Vehicletype::find($id);
        return view('admin.vehtypes.edit', compact('vehtype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $vehtype = Vehicletype::find($id);

            $request->validate([
                'name' => 'unique:vehicletypes,name,' . $id,
            ]);

            $vehtype->update($request->all());
            return response()->json(['message' => 'Tipo de vehículo actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json([['message' => 'Hubo un error en la actualización' . $th->getMessage()], 500]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $vehtype = Vehicletype::find($id);
            $vehtype->delete();
            return response()->json(['message' => 'Tipo de vehículo eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json([['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500]);
        }
    }
}
