<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $colors = Color::all();

        if ($request->ajax()) {
            return DataTables::of($colors)
                ->addColumn('edit', function ($brand) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $brand->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($brand) {
                    return '<form action="' . route('admin.colors.destroy', $brand->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.colors.index', compact('colors'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'unique:colors',
            ]);
            Color::create($request->all());
            return response()->json(['message' => 'Color registrado correctamente'], 200);
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
        $color = Color::find($id);
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $color = Color::find($id);

            $request->validate([
                'name' => 'unique:colors,name,' . $id,
            ]);

            $color->update($request->all());

            return response()->json(['message' => 'Color actualizado correctamente'], 200);
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
            $color = Color::find($id);
            $color->delete();
            return response()->json(['message' => 'Color eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
