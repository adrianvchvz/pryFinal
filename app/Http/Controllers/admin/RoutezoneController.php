<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Routezone;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoutezoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($route_id)
    {
        $route = Route::find($route_id);
        $zones = Zone::all()->pluck('name', 'id');

        return view('admin.routezones.create', compact('route', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'route_id' => 'required|exists:routes,id',
                'zone_id' => 'required|exists:zones,id',
            ]);

            Routezone::create([
                'route_id' => $validated['route_id'],
                'zone_id' => $validated['zone_id'],
            ]);

            return response()->json(['message' => 'Zona agregada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $routezones = DB::select("
            SELECT rz.id AS routezone_id, z.name AS zone_name
            FROM routes r 
            INNER JOIN routezones rz ON r.id = rz.route_id 
            INNER JOIN zones z ON z.id = rz.zone_id 
            WHERE r.id = ?
        ", [$id]);

        if ($request->ajax()) {
            return DataTables::of($routezones)
                ->addColumn('delete', function ($routezone) {
                    return '<form action="' . route('admin.routezones.destroy', $routezone->routezone_id) . '" method="POST" 
                            class="frmDelete d-inline">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                            </button></form>';
                })
                ->rawColumns(['delete'])
                ->make(true);
        } else {
            return view('admin.routezones.show', compact('routezones'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $routezone = Routezone::find($id);

            if (!$routezone) {
                return response()->json(['message' => 'La ruta de la zona no fue encontrada'], 404);
            }
            $routezone->delete();
            return response()->json(['message' => 'Zona eliminada correctamente de la ruta'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación: ' . $th->getMessage()], 500);
        }
    }
}
