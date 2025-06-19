<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Zone;
use App\Models\Zonecoord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $zones = Zone::all();

        if ($request->ajax()) {

            return DataTables::of($zones)
                ->addColumn('coords', function ($zone) {
                    return '<a href="' . route('admin.zones.show', $zone->id) . '" class="btn btn-primary btn-sm">
                    <i class="fas fa-map-marker-alt"></i></a>';
                })
                ->addColumn('map', function ($zone) {
                    return '<button class="btn btn-success btn-sm btnMap" id=' . $zone->id . '>
                    <i class="fas fa-map-marked-alt"></i></button>';
                })
                ->addColumn('edit', function ($zone) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $zone->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($zone) {
                    return '<form action="' . route('admin.zones.destroy', $zone->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['coords', 'map', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.zones.index', compact('zones'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::pluck('name', 'id');
        return view('admin.zones.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "unique:zones"
            ]);
            Zone::create($request->all());
            return response()->json(['message' => 'Zona registrada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $zone = Zone::find($id);

        $coords = Zonecoord::where('zone_id', $id);

        if ($request->ajax()) {
            return DataTables::of($coords)
                ->addColumn('delete', function ($coord) {
                    return '<form action="' . route('admin.zonecoords.destroy', $coord->id) . '" method="POST" 
                            class="frmDelete d-inline">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                            </button></form>';
                })
                ->rawColumns(['delete'])
                ->make(true);
        } else {
            return view('admin.zones.show', compact('zone'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $zone = Zone::find($id);
        $districts = District::pluck('name', 'id');
        return view('admin.zones.edit', compact('zone', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                "name" => "unique:zones,name," . $id
            ]);
            $zone = Zone::find($id);
            $zone->update($request->all());
            return response()->json(['message' => 'Zona actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $zone = Zone::find($id);
            $zone->delete();
            return response()->json(['message' => 'Zona eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación: ' . $th->getMessage()], 500);
        }
    }
}
