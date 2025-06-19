<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Routezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $routes = Route::all();

        if ($request->ajax()) {
            return DataTables::of($routes)
                ->editColumn('status', function ($route) {
                    return $route->status ? 'Activo' : 'Inactivo';
                })
                ->addColumn('gps', function ($route) {
                    return '<a href="' . route('admin.routes.show', $route->id) . '" class="btn btn-primary btn-sm">
                        <i class="fas fa-map-marked-alt"></i>
                    </a>';
                })
                ->addColumn('edit', function ($route) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $route->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($route) {
                    return '<form action="' . route('admin.routes.destroy', $route->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['edit', 'delete', 'gps'])
                ->make(true);
        } else {
            return view('admin.routes.index', compact('routes'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:routes',
            'latitude_start' => 'required|numeric',
            'longitude_start' => 'required|numeric',
            'latitude_end' => 'required|numeric',
            'longitude_end' => 'required|numeric',
            'status' => 'nullable|boolean',
        ], [
            'name.max' => 'El nombre de la ruta no debe superar los 255 caracteres.',
            'name.unique' => 'Ya existe una ruta con ese nombre.',
        ]);

        try {
            Route::create($request->all());
            return response()->json(['message' => 'Ruta registrada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $route = Route::find($id);

        $routezones = DB::select("
        SELECT r.id, r.name, r.latitude_start, r.longitude_start, r.latitude_end, r.longitude_end 
        FROM routes r 
        INNER JOIN routezones r2 ON r.id = r2.route_id 
        where r2.route_id=?
        ", [$id]);
        
        $zonesMap = DB::table('zones')
            ->leftJoin('zonecoords', 'zones.id', '=', 'zonecoords.zone_id')
            ->whereIn('zones.id', function ($query) use ($id) {
                $query->select('zone_id')
                    ->from('routezones')
                    ->where('route_id', $id);
            })
            ->select('zones.name as zone', 'zonecoords.latitude', 'zonecoords.longitude')
            ->get();

        $groupedZones = $zonesMap->groupBy('zone');

        $perimeter = $groupedZones->map(function ($zone) {
            $coords = $zone->map(function ($item) {
                return [
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                ];
            })->toArray();

            return [
                'name' => $zone[0]->zone,
                'coords' => $coords,
            ];
        })->values();

        return view('admin.routes.show', compact('route', 'routezones', 'perimeter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $route = Route::find($id);
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|unique:routes,name," . $id,
        ]);
        try {
            $route = Route::find($id);
            $route->update($request->all());
            return response()->json(['message' => 'Ruta actualizada correctamente'], 200);
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
            $route = Route::find($id);
            $route->delete();
            return response()->json(['message' => 'Ruta eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación: ' . $th->getMessage()], 500);
        }
    }
}
