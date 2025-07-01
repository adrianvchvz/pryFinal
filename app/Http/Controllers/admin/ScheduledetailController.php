<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Scheduleday;
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
        $turnoActualId = $detalle->shift_id;

        // Ayudantes ya asignados a otras zonas en el mismo turno ese día
        $ayudantesOcupados = DB::table('scheduledetailoccupants')
            ->join('scheduledetails', 'scheduledetailoccupants.scheduledetail_id', '=', 'scheduledetails.id')
            ->where('scheduledetails.scheduleday_id', $scheduledayId)
            ->where('scheduledetails.id', '!=', $detalle->id)
            ->where('scheduledetails.shift_id', $turnoActualId)
            ->pluck('employee_id');

        // Conductores ya asignados a otras zonas en el mismo turno ese día
        $conductoresOcupados = DB::table('scheduledetails')
            ->where('scheduleday_id', $scheduledayId)
            ->where('id', '!=', $detalle->id)
            ->where('shift_id', $turnoActualId)
            ->pluck('conductor_id');

        // Empleados con vacaciones en esa fecha
        $empleadosConVacaciones = DB::table('vacations')
            ->where('start_date', '<=', $fecha)
            ->where('end_date', '>=', $fecha)
            ->pluck('employee_id');

        // Ayudantes ya asignados a este detalle
        $ayudantesAsignados = DB::table('scheduledetailoccupants')
            ->where('scheduledetail_id', $detalle->id)
            ->pluck('employee_id')
            ->toArray();

        // Conductores disponibles + el actual
        $conductores = Employee::whereHas('type', fn($q) => $q->where('name', 'CONDUCTOR'))
            ->whereNotIn('id', $empleadosConVacaciones)
            ->where(function ($query) use ($conductoresOcupados, $detalle) {
                $query->whereNotIn('id', $conductoresOcupados)
                    ->orWhere('id', $detalle->conductor_id);
            })
            ->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        // Ayudantes disponibles + los que ya están asignados a este detalle
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
            'conductor_id'   => 'required|exists:employees,id',
            'ayudantes_ids'  => 'required|array|min:1',
            'shift_id'       => 'required|exists:shifts,id',
            'vehicle_id'     => 'required|exists:vehicles,id',
        ]);

        try {
            $detalle = Scheduledetail::findOrFail($id);
            $scheduleday = $detalle->scheduleday;

            $fecha = $scheduleday->date;
            $turnoId = $request->shift_id;

            // Verificar si el conductor ya está asignado en otro detalle con el mismo turno ese día
            $conductorOcupado = Scheduledetail::whereHas('scheduleday', function ($q) use ($fecha) {
                $q->where('date', $fecha);
            })
                ->where('shift_id', $turnoId)
                ->where('conductor_id', $request->conductor_id)
                ->where('id', '!=', $detalle->id)
                ->exists();

            if ($conductorOcupado) {
                return response()->json(['message' => 'El conductor ya está asignado en otra zona con el mismo turno.'], 422);
            }

            // Verificar ayudantes
            foreach ($request->ayudantes_ids as $aid) {
                $ayudanteOcupado = DB::table('scheduledetailoccupants')
                    ->join('scheduledetails', 'scheduledetailoccupants.scheduledetail_id', '=', 'scheduledetails.id')
                    ->join('scheduledays', 'scheduledetails.scheduleday_id', '=', 'scheduledays.id')
                    ->where('scheduledays.date', $fecha)
                    ->where('scheduledetails.shift_id', $turnoId)
                    ->where('scheduledetailoccupants.employee_id', $aid)
                    ->where('scheduledetailoccupants.scheduledetail_id', '!=', $detalle->id)
                    ->exists();

                if ($ayudanteOcupado) {
                    return response()->json(['message' => 'Uno de los ayudantes ya está asignado en otra zona con el mismo turno.'], 422);
                }
            }

            // Actualizar asignación
            $detalle->update([
                'shift_id'     => $request->shift_id,
                'vehicle_id'   => $request->vehicle_id,
                'conductor_id' => $request->conductor_id,
                'status'       => 'COMPLETO',
            ]);

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
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error al actualizar: ' . $th->getMessage()], 500);
        }
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
