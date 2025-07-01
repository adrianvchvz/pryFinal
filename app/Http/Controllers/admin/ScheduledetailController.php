<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Scheduledetail;
use App\Models\Shift;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduledetailController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $detalle = Scheduledetail::with('zone', 'vehicle', 'shift')->findOrFail($id);
        $fecha = $detalle->scheduleday->date;
        $scheduledayId = $detalle->scheduleday_id;

        // Ayudantes asignados actualmente
        $ayudantesAsignados = DB::table('scheduledetailoccupants')
            ->where('scheduledetail_id', $detalle->id)
            ->pluck('employee_id')
            ->toArray();

        // IDs de empleados con vacaciones en esa fecha
        $empleadosConVacaciones = DB::table('vacations')
            ->where('start_date', '<=', $fecha)
            ->where('end_date', '>=', $fecha)
            ->pluck('employee_id');

        // Empleados ya asignados como conductores ese día (excepto este registro)
        $conductoresOcupados = DB::table('scheduledetails')
            ->where('scheduleday_id', $scheduledayId)
            ->where('id', '!=', $detalle->id)
            ->pluck('conductor_id');

        // Ayudantes ya asignados ese día en otras zonas
        $ayudantesOcupados = DB::table('scheduledetailoccupants')
            ->join('scheduledetails', 'scheduledetailoccupants.scheduledetail_id', '=', 'scheduledetails.id')
            ->where('scheduledetails.scheduleday_id', $scheduledayId)
            ->where('scheduledetails.id', '!=', $detalle->id)
            ->pluck('employee_id');

        // Conductores disponibles + el actual
        $conductores = Employee::whereHas('type', fn($q) => $q->where('name', 'CONDUCTOR'))
            ->whereNotIn('id', $empleadosConVacaciones)
            ->where(function ($query) use ($conductoresOcupados, $detalle) {
                $query->whereNotIn('id', $conductoresOcupados)
                    ->orWhere('id', $detalle->conductor_id);
            })
            ->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        // Ayudantes disponibles + los ya asignados en este detalle
        $ayudantes = Employee::whereHas('type', fn($q) => $q->where('name', 'AYUDANTE'))
            ->whereNotIn('id', $empleadosConVacaciones)
            ->where(function ($query) use ($ayudantesOcupados, $ayudantesAsignados) {
                $query->whereNotIn('id', $ayudantesOcupados)
                    ->orWhereIn('id', $ayudantesAsignados);
            })
            ->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        $shifts = Shift::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');

        return view('admin.scheduledetails.edit', compact(
            'detalle',
            'conductores',
            'ayudantes',
            'ayudantesAsignados',
            'shifts',
            'vehicles'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'conductor_id' => 'required|exists:employees,id',
            'ayudantes_ids' => 'required|array|min:1',
            'vehicle_id' => 'required|exists:vehicles,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $detalle = Scheduledetail::findOrFail($id);
        $detalle->conductor_id = $request->conductor_id;
        $detalle->vehicle_id = $request->vehicle_id;
        $detalle->shift_id = $request->shift_id;
        $detalle->status = 'COMPLETO';
        $detalle->save();

        // Ayudantes
        DB::table('scheduledetailoccupants')->where('scheduledetail_id', $detalle->id)->delete();
        foreach ($request->ayudantes_ids as $aid) {
            DB::table('scheduledetailoccupants')->insert([
                'scheduledetail_id' => $detalle->id,
                'employee_id' => $aid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Asignación actualizada correctamente.'], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $scheduledetail = Scheduledetail::find($id);
            $scheduledetail->delete();
            return response()->json(['message' => 'Asignación eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación: ' . $th->getMessage()], 500);
        }
    }
}
