<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Zoneassignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ZoneassignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $zoneassignments = Zoneassignment::select(
            'zoneassignments.id',
            'zones.name as zone_name',
            'vehicles.name as vehicle_name',
            'shifts.name as shift_name',
            'employees.names as conductor_names',
            'employees.lastnames as conductor_lastnames'
        )
            ->join('zones', 'zoneassignments.zone_id', '=', 'zones.id')
            ->join('vehicles', 'zoneassignments.vehicle_id', '=', 'vehicles.id')
            ->join('shifts', 'zoneassignments.shift_id', '=', 'shifts.id')
            ->join('employees', 'zoneassignments.conductor_id', '=', 'employees.id')
            ->get();


        if ($request->ajax()) {
            return DataTables::of($zoneassignments)
                ->addColumn('empleados', function ($za) {
                    $ayudantes = DB::table('zoneassignmenthelpers')
                        ->join('employees', 'employees.id', '=', 'zoneassignmenthelpers.employee_id')
                        ->where('assignment_id', $za->id)
                        ->select(DB::raw("CONCAT(employees.names, ' ', employees.lastnames) as fullname"))
                        ->pluck('fullname')
                        ->toArray();

                    return '<strong>Conductor:</strong> ' . $za->conductor_names . ' ' . $za->conductor_lastnames .
                        '<br><strong>Ayudantes:</strong> ' . implode(', ', $ayudantes);
                })
                ->addColumn('edit', function ($za) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $za->id . '"><i class="fas fa-pen"></i></button>';
                })
                ->addColumn('delete', function ($za) {
                    return '<form action="' . route('admin.zoneassignments.destroy', $za->id) . '" method="POST" class="frmDelete">'
                        . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['empleados', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.zoneassignments.index', compact('zoneassignments'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $shifts = Shift::pluck('name', 'id');

        // Conductores (type_id = 1)
        $conductores = Employee::whereHas('type', function ($query) {
            $query->where('name', 'CONDUCTOR');
        })->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        // Ayudantes (type_id = 2)
        $ayudantes = Employee::whereHas('type', function ($query) {
            $query->where('name', 'AYUDANTE');
        })->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        return view('admin.zoneassignments.create', compact('zones', 'vehicles', 'shifts', 'conductores', 'ayudantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'zone_id' => 'required',
                'vehicle_id' => 'required',
                'shift_id' => 'required',
                'conductor_id' => 'required',
                'ayudantes_ids' => 'required|array|min:1'
            ]);

            // 1. Crear asignación principal
            $zoneassignment = Zoneassignment::create([
                'zone_id' => $request->zone_id,
                'vehicle_id' => $request->vehicle_id,
                'shift_id' => $request->shift_id,
                'conductor_id' => $request->conductor_id,
            ]);

            // 2. Registrar ayudantes en tabla intermedia
            foreach ($request->ayudantes_ids as $ayudante_id) {
                DB::table('zoneassignmenthelpers')->insert([
                    'assignment_id' => $zoneassignment->id,
                    'employee_id' => $ayudante_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['message' => 'Asignación registrada correctamente'], 200);
        } catch (\Throwable $th) {
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
        $assignment = Zoneassignment::findOrFail($id);

        $zones = Zone::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $shifts = Shift::pluck('name', 'id');

        $conductores = Employee::whereHas('type', function ($query) {
            $query->where('name', 'CONDUCTOR');
        })->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        $ayudantes = Employee::whereHas('type', function ($query) {
            $query->where('name', 'AYUDANTE');
        })->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        $conductor_id = $assignment->conductor_id;

        $ayudantes_ids = DB::table('zoneassignmenthelpers')
            ->where('assignment_id', $id)
            ->pluck('employee_id')
            ->toArray();

        return view('admin.zoneassignments.edit', compact(
            'assignment',
            'zones',
            'vehicles',
            'shifts',
            'conductores',
            'ayudantes',
            'conductor_id',
            'ayudantes_ids'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'zone_id' => 'required',
                'vehicle_id' => 'required',
                'shift_id' => 'required',
                'conductor_id' => 'required',
                'ayudantes_ids' => 'required|array|min:1'
            ]);

            $assignment = Zoneassignment::findOrFail($id);

            // Actualiza la asignación principal
            $assignment->update([
                'zone_id' => $request->zone_id,
                'vehicle_id' => $request->vehicle_id,
                'shift_id' => $request->shift_id,
                'conductor_id' => $request->conductor_id,
            ]);

            // Elimina ayudantes anteriores
            DB::table('zoneassignmenthelpers')->where('assignment_id', $id)->delete();

            // Inserta nuevos ayudantes
            foreach ($request->ayudantes_ids as $ayudante_id) {
                DB::table('zoneassignmenthelpers')->insert([
                    'assignment_id' => $assignment->id,
                    'employee_id' => $ayudante_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['message' => 'Asignación actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al actualizar: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $assignment = Zoneassignment::find($id);
            $assignment->delete();
            return response()->json(['message' => 'Asignación eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
