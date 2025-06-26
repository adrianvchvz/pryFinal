<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shifts = Shift::all();

        if ($request->ajax()) {
            return DataTables::of($shifts)
                ->addColumn('edit', function ($shift) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $shift->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($shift) {
                    return '<form action="' . route('admin.shifts.destroy', $shift->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.shifts.index', compact('shifts'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'unique:shifts',
            ]);
            Shift::create($request->all());
            return response()->json(['message' => 'Turno registrado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro' . $th->getMessage()], 500);
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
        $shift = Shift::find($id);
        return view('admin.shifts.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $shift = Shift::find($id);

            $request->validate([
                'name' => 'unique:shifts,name,' . $id,
            ]);

            $shift->update($request->all());

            return response()->json(['message' => 'Turno actualizado correctamente'], 200);
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
            $shift = Shift::find($id);
            $shift->delete();
            return response()->json(['message' => 'Turno eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
