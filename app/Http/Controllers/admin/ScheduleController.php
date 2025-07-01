<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Scheduleday;
use App\Models\Scheduledetail;
use App\Models\Zoneassignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schedules = Schedule::all();

        if ($request->ajax()) {
            return DataTables::of($schedules)
                ->addColumn('show', function ($schedule) {
                    return '<a href="' . route('admin.schedules.show', $schedule->id) . '" class="btn btn-primary btn-sm btnShow">
                    <i class="fas fa-calendar"></i></a>';
                })
                ->addColumn('edit', function ($schedule) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $schedule->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($schedule) {
                    return '<form action="' . route('admin.schedules.destroy', $schedule->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['show', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.schedules.index', compact('schedules'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:schedules',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $overlap = Schedule::where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->exists();

            if ($overlap) {
                return response()->json(['message' => 'Ya existe una programación entre este rango de fechas.'], 422);
            }

            $schedule = Schedule::create($request->only(['name', 'start_date', 'end_date']));
            $zoneassignments = Zoneassignment::all();

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $scheduleday = $schedule->days()->create([
                    'date' => $date->toDateString(),
                    'status' => 'COMPLETO', // por defecto
                ]);

                foreach ($zoneassignments as $za) {
                    $incompleto = false;
                    $conductorId = $za->conductor_id;

                    // Validar vacaciones del conductor
                    $tieneVacacionesConductor = $this->empleadoTieneVacaciones($conductorId, $date);
                    if ($tieneVacacionesConductor) {
                        $conductorId = null;
                        $incompleto = true;
                    }

                    // Crear detalle
                    $detail = Scheduledetail::create([
                        'scheduleday_id' => $scheduleday->id,
                        'zone_id' => $za->zone_id,
                        'vehicle_id' => $za->vehicle_id,
                        'shift_id' => $za->shift_id,
                        'conductor_id' => $conductorId,
                        'status' => 'COMPLETO', // por defecto
                    ]);

                    // Validar ayudantes
                    $ayudantes = DB::table('zoneassignmenthelpers')
                        ->where('assignment_id', $za->id)
                        ->pluck('employee_id');

                    $ocupantesAsignados = 0;

                    foreach ($ayudantes as $aid) {
                        $tieneVacaciones = $this->empleadoTieneVacaciones($aid, $date);
                        if (!$tieneVacaciones) {
                            DB::table('scheduledetailoccupants')->insert([
                                'scheduledetail_id' => $detail->id,
                                'employee_id' => $aid,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            $ocupantesAsignados++;
                        }
                    }

                    // Si no hay conductor o no se asignó al menos un ayudante, marcar INCOMPLETO
                    if (is_null($conductorId) || $ocupantesAsignados === 0) {
                        $detail->status = 'INCOMPLETO';
                        $detail->save();
                        $incompleto = true;
                    }

                    if ($incompleto) {
                        $scheduleday->status = 'INCOMPLETO';
                        $scheduleday->save();
                    }
                }
            }

            return response()->json(['message' => 'Programación registrada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $schedule = Schedule::find($id);

        $scheduledays = Scheduleday::with('schedule')->where('schedule_id', $id)->get();

        if ($request->ajax()) {
            return DataTables::of($scheduledays)
                ->addColumn('name', function ($day) {
                    return $day->schedule->name ?? '---';
                })
                ->addColumn('status', function ($day) {
                    return $day->status ?? '---';
                })
                ->addColumn('show', function ($schedule) {
                    return '<a href="' . route('admin.scheduledays.show', $schedule->id) . '" class="btn btn-primary btn-sm btnShow">
                    <i class="fas fa-eye"></i></a>';
                })
                ->addColumn('delete', function ($schedule) {
                    return '<form action="' . route('admin.scheduledays.destroy', $schedule->id) . '" method="POST" 
                            class="frmDelete d-inline">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                            </button></form>';
                })
                ->rawColumns(['name', 'show', 'delete'])
                ->make(true);
        } else {
            return view('admin.schedules.show', compact('schedule'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // ScheduleController.php

    public function edit($id)
    {
        $schedule = Schedule::find($id);
        return view('admin.schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:schedules,name,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'name.unique' => 'El nombre ya está en uso.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o mayor que la de inicio.',
        ]);

        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Validar solapamiento con otras programaciones
            $overlap = Schedule::where('id', '!=', $id)
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->exists();

            if ($overlap) {
                return response()->json(['message' => 'Ya existe una programación entre este rango de fechas.'], 422);
            }

            // 1. Actualizar programación
            $schedule = Schedule::findOrFail($id);
            $schedule->update([
                'name' => $request->name,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // 2. Recalcular días
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            // Fechas nuevas en el rango
            $fechasNuevas = collect();
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $fechasNuevas->push($date->toDateString());
            }

            // Fechas existentes
            $fechasExistentes = $schedule->days->pluck('date')->map(fn($d) => Carbon::parse($d)->toDateString());

            // A. Crear días nuevos que no existan
            $fechasParaAgregar = $fechasNuevas->diff($fechasExistentes);
            foreach ($fechasParaAgregar as $fecha) {
                $schedule->days()->create(['date' => $fecha]);
            }

            // B. Eliminar días que están fuera del nuevo rango
            $fechasParaEliminar = $fechasExistentes->diff($fechasNuevas);
            Scheduleday::where('schedule_id', $schedule->id)
                ->whereIn('date', $fechasParaEliminar)
                ->delete();

            return response()->json(['message' => 'Programación actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::find($id);
            $schedule->delete();
            return response()->json(['message' => 'Programación eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }

    // Método de apoyo
    private function empleadoTieneVacaciones($employeeId, $fecha)
    {
        // Obtener contrato
        $contrato = DB::table('contracts')
            ->where('employee_id', $employeeId)
            ->orderByDesc('start_date')
            ->first();

        // Si no tiene contrato o si es eventual, no se valida vacaciones
        if (!$contrato || $contrato->end_date != null) {
            return false;
        }

        // Validar vacaciones
        $tieneVacaciones = DB::table('vacations')
            ->where('employee_id', $employeeId)
            ->where('start_date', '<=', $fecha)
            ->where('end_date', '>=', $fecha)
            ->exists();

        return $tieneVacaciones;
    }
}
