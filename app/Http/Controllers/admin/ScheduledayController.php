<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Scheduleday;
use App\Models\Scheduledetail;
use App\Models\Shift;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ScheduledayController extends Controller
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
    public function show(Request $request, string $id)
    {
        $scheduleday = Scheduleday::find($id);

        // Traer detalles del día con datos necesarios
        $scheduledetails = DB::table('scheduledetails')
            ->join('zones', 'scheduledetails.zone_id', '=', 'zones.id')
            ->join('vehicles', 'scheduledetails.vehicle_id', '=', 'vehicles.id')
            ->join('shifts', 'scheduledetails.shift_id', '=', 'shifts.id')
            ->leftJoin('employees as conductores', 'scheduledetails.conductor_id', '=', 'conductores.id')
            ->where('scheduleday_id', $id)
            ->select(
                'scheduledetails.id',
                'zones.name as zone_name',
                'vehicles.name as vehicle_name',
                'shifts.name as shift_name',
                'scheduledetails.status',
                DB::raw("COALESCE(CONCAT(conductores.names, ' ', conductores.lastnames), 'Sin asignar') as conductor_fullname")
            )
            ->get();

        // Si es AJAX, armar tabla con ayudantes
        if ($request->ajax()) {
            return DataTables::of($scheduledetails)
                ->addColumn('ayudantes', function ($detail) {
                    // Traer ayudantes desde tabla intermedia
                    $ayudantes = DB::table('scheduledetailoccupants')
                        ->join('employees', 'employees.id', '=', 'scheduledetailoccupants.employee_id')
                        ->where('scheduledetail_id', $detail->id)
                        ->select(DB::raw("CONCAT(employees.names, ' ', employees.lastnames) as fullname"))
                        ->pluck('fullname')
                        ->toArray();

                    return implode(', ', $ayudantes);
                })
                ->addColumn('edit', function ($detail) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $detail->id . '">
                        <i class="fas fa-pen"></i></button>';
                })
                ->addColumn('delete', function ($detail) {
                    return '<form action="' . route('admin.scheduledetails.destroy', $detail->id) . '" method="POST" 
                        class="frmDelete d-inline">' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                        </button></form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.scheduledays.show', compact('scheduleday'));
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
        $request->validate([
            'schedule_id'    => 'required|exists:schedules,id',
            'fechas'         => 'required|array|min:1',
            'conductor_id'   => 'required|exists:employees,id',
            'ayudantes_ids'  => 'required|array|min:1',
        ]);

        try {
            $scheduleId = $request->schedule_id;
            $fechasSeleccionadas = $request->fechas;

            // Validar vacaciones del conductor
            foreach ($fechasSeleccionadas as $fecha) {
                if ($this->empleadoTieneVacaciones($request->conductor_id, $fecha)) {
                    return response()->json(['message' => 'El conductor tiene vacaciones en la fecha ' . $fecha], 422);
                }

                foreach ($request->ayudantes_ids as $aid) {
                    if ($this->empleadoTieneVacaciones($aid, $fecha)) {
                        return response()->json(['message' => 'Un ayudante tiene vacaciones en la fecha ' . $fecha], 422);
                    }
                }
            }

            foreach ($fechasSeleccionadas as $fecha) {
                $scheduleday = Scheduleday::where('schedule_id', $scheduleId)
                    ->where('date', $fecha)
                    ->first();

                if (!$scheduleday) continue;

                $detallesIncompletos = Scheduledetail::where('scheduleday_id', $scheduleday->id)
                    ->where('status', 'INCOMPLETO')
                    ->get();

                foreach ($detallesIncompletos as $detalle) {
                    // Actualizar conductor
                    $detalle->conductor_id = $request->conductor_id;
                    $detalle->status = 'COMPLETO';
                    $detalle->save();

                    // Eliminar ayudantes anteriores por si acaso
                    DB::table('scheduledetailoccupants')->where('scheduledetail_id', $detalle->id)->delete();

                    // Registrar nuevos ayudantes
                    foreach ($request->ayudantes_ids as $aid) {
                        DB::table('scheduledetailoccupants')->insert([
                            'scheduledetail_id' => $detalle->id,
                            'employee_id'       => $aid,
                            'created_at'        => now(),
                            'updated_at'        => now()
                        ]);
                    }
                }

                // Verificar si ya todos los detalles están completos
                $totalDetalles = Scheduledetail::where('scheduleday_id', $scheduleday->id)->count();
                $completos = Scheduledetail::where('scheduleday_id', $scheduleday->id)
                    ->where('status', 'COMPLETO')
                    ->count();

                $scheduleday->status = ($totalDetalles === $completos) ? 'COMPLETO' : 'INCOMPLETO';
                $scheduleday->save();
            }

            return response()->json(['message' => 'Reemplazos asignados correctamente.'], 200);
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
            $scheduleday = Scheduleday::find($id);
            $scheduleday->delete();
            return response()->json(['message' => 'Día eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación: ' . $th->getMessage()], 500);
        }
    }

    public function editDays($id)
    {
        $schedule = Schedule::findOrFail($id);

        $zonasIncompletas = Scheduledetail::join('scheduledays', 'scheduledetails.scheduleday_id', '=', 'scheduledays.id')
            ->join('zones', 'scheduledetails.zone_id', '=', 'zones.id')
            ->join('vehicles', 'scheduledetails.vehicle_id', '=', 'vehicles.id')
            ->join('shifts', 'scheduledetails.shift_id', '=', 'shifts.id')
            ->leftJoin('employees as c', 'scheduledetails.conductor_id', '=', 'c.id')
            ->where('scheduledays.schedule_id', $schedule->id)
            ->where('scheduledetails.status', 'INCOMPLETO')
            ->select(
                'scheduledetails.zone_id',
                'zones.name as zone_name',
                'scheduledetails.vehicle_id',
                'vehicles.name as vehicle_name',
                'scheduledetails.shift_id',
                'shifts.name as shift_name',
                DB::raw("COALESCE(CONCAT(c.names, ' ', c.lastnames), '') as conductor_name"),
                'scheduledetails.conductor_id'
            )
            ->distinct()
            ->get();

        // 1. Obtener días incompletos
        $incompleteDays = Scheduleday::where('schedule_id', $schedule->id)
            ->where('status', 'INCOMPLETO')
            ->get();

        // 2. Agrupar detalles incompletos por zona
        $zonas = [];
        foreach ($incompleteDays as $day) {
            $detalles = Scheduledetail::where('scheduleday_id', $day->id)
                ->where('status', 'INCOMPLETO')
                ->get();

            foreach ($detalles as $detalle) {
                $zonas[$detalle->zone_id]['zone_id'] = $detalle->zone_id;
                $zonas[$detalle->zone_id]['zone_name'] = $detalle->zone->name;
                $zonas[$detalle->zone_id]['dates'][] = $day->date;
                $zonas[$detalle->zone_id]['shift_id'] = $detalle->shift_id;
                $zonas[$detalle->zone_id]['vehicle_id'] = $detalle->vehicle_id;

                // Preasignaciones
                $zonas[$detalle->zone_id]['conductor_id'] ??= $detalle->conductor_id;

                $zonas[$detalle->zone_id]['ayudantes_ids'] ??= DB::table('scheduledetailoccupants')
                    ->where('scheduledetail_id', $detalle->id)
                    ->pluck('employee_id')
                    ->toArray();
            }
        }

        // 4. Filtrar empleados disponibles globalmente
        $fechasTodas = collect($zonas)->flatMap(fn($z) => $z['dates'])->unique();

        // Empleados con vacaciones en esas fechas
        $empleadosConVacaciones = DB::table('vacations')
            ->where(function ($query) use ($fechasTodas) {
                foreach ($fechasTodas as $fecha) {
                    $query->orWhere(function ($q) use ($fecha) {
                        $q->where('start_date', '<=', $fecha)
                            ->where('end_date', '>=', $fecha);
                    });
                }
            })
            ->pluck('employee_id');

        // Empleados asignados ya en otras zonas
        $empleadosAsignados = DB::table('scheduledetails')
            ->join('scheduledays', 'scheduledetails.scheduleday_id', '=', 'scheduledays.id')
            ->whereIn('scheduledays.date', $fechasTodas)
            ->pluck('conductor_id')
            ->merge(
                DB::table('scheduledetailoccupants')
                    ->join('scheduledetails', 'scheduledetailoccupants.scheduledetail_id', '=', 'scheduledetails.id')
                    ->join('scheduledays', 'scheduledetails.scheduleday_id', '=', 'scheduledays.id')
                    ->whereIn('scheduledays.date', $fechasTodas)
                    ->pluck('employee_id')
            )
            ->unique();

        $empleadosAsignados = $empleadosAsignados->filter()->unique()->values();

        // Recuperar los empleados ya asignados en zonas incompletas (aunque estén filtrados)
        $idsConductoresYaAsignados = collect($zonas)->pluck('conductor_id')
            ->filter(fn($id) => !is_null($id))  // evita nulls
            ->unique()
            ->values();

        $idsAyudantesYaAsignados = collect($zonas)->pluck('ayudantes_ids')->flatten()->filter()->unique();

        // Final: Conductores disponibles + ya asignados
        $conductores = Employee::whereHas('type', fn($q) => $q->where('name', 'CONDUCTOR'))
            ->whereNotIn('id', $empleadosConVacaciones)
            ->where(function ($query) use ($empleadosAsignados, $idsConductoresYaAsignados) {
                $query->whereNotIn('id', $empleadosAsignados)
                    ->orWhereIn('id', $idsConductoresYaAsignados);
            })
            ->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');

        // Ayudantes disponibles + ya asignados
        $ayudantes = Employee::whereHas('type', fn($q) => $q->where('name', 'AYUDANTE'))
            ->whereNotIn('id', $empleadosConVacaciones)
            ->where(function ($query) use ($empleadosAsignados, $idsAyudantesYaAsignados) {
                $query->whereNotIn('id', $empleadosAsignados)
                    ->orWhereIn('id', $idsAyudantesYaAsignados);
            })
            ->pluck(DB::raw("CONCAT(names, ' ', lastnames)"), 'id');


        $shifts = Shift::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $zonasDisponibles = Zone::pluck('name', 'id');

        $zonasAyudantes = [];
        foreach ($zonas as $zona) {
            $zonaId = $zona['zone_id'] ?? null;
            if ($zonaId) {
                $zonasAyudantes[$zonaId] = $zona['ayudantes_ids'] ?? [];
            }
        }

        return view('admin.scheduledays.edit', compact(
            'schedule',
            'conductores',
            'ayudantes',
            'incompleteDays',
            'zonasIncompletas',
            'shifts',
            'vehicles',
            'zonasAyudantes'
        ));
    }

    private function empleadoTieneVacaciones($empleadoId, $fecha)
    {
        return DB::table('vacations')
            ->where('employee_id', $empleadoId)
            ->where('start_date', '<=', $fecha)
            ->where('end_date', '>=', $fecha)
            ->exists();
    }

    public function iniciarRecorrido($id)
    {
        try {
            $scheduleday = Scheduleday::with('details.shift')->findOrFail($id);

            // 1. Validar que todas las zonas estén COMPLETAS
            if ($scheduleday->details()->where('status', 'INCOMPLETO')->exists()) {
                return response()->json(['message' => 'No se puede iniciar. Hay zonas incompletas.'], 422);
            }

            // 2. Validar asistencia
            foreach ($scheduleday->details as $detalle) {
                $fecha = $scheduleday->date;

                // Validar conductor
                if (!DB::table('attendances')->where('employee_id', $detalle->conductor_id)
                    ->whereDate('date', $fecha)->exists()) {
                    return response()->json(['message' => 'Conductor sin asistencia en ' . $detalle->zone->name], 422);
                }

                // Validar ayudantes
                $ayudantes = DB::table('scheduledetailoccupants')
                    ->where('scheduledetail_id', $detalle->id)
                    ->pluck('employee_id');

                foreach ($ayudantes as $aid) {
                    if (!DB::table('attendances')->where('employee_id', $aid)
                        ->whereDate('date', $fecha)->exists()) {
                        return response()->json(['message' => 'Un ayudante no tiene asistencia en ' . $detalle->zone->name], 422);
                    }
                }
            }

            // 3. Todo OK: Actualizar estado de recorrido
            $scheduleday->details()->update(['trip_status' => 'INICIADO']);

            return response()->json(['message' => 'Recorrido iniciado correctamente.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error: ' . $th->getMessage()], 500);
        }
    }
}
