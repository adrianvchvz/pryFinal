<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Scheduleday;
use App\Models\Scheduleoccupant;
use App\Models\Shift;
use App\Models\Vacation;
use App\Models\Vehicle;
use App\Models\Zone;
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
        $schedules = Schedule::select(
            'schedules.id',
            'zones.name as zone_name',
            'vehicles.name as vehicle_name',
            'shifts.name as shift_name',
            'schedules.start_date',
            'schedules.end_date',
            'schedules.status'
        )->join('zones', 'schedules.zone_id', '=', 'zones.id')
            ->join('vehicles', 'schedules.vehicle_id', '=', 'vehicles.id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')->get();

        if ($request->ajax()) {
            return DataTables::of($schedules)
                ->addColumn('status_text', function ($schedule) {
                    switch ($schedule->status) {
                        case 0:
                            return 'Cancelado';
                        case 1:
                            return 'Pendiente';
                        case 2:
                            return 'Iniciado';
                        case 3:
                            return 'Finalizado';
                        default:
                            return 'Desconocido';
                    }
                })
                ->addColumn('show', function ($schedule) {
                    return '<button class="btn btn-primary btn-sm btnShow" id="' . $schedule->id . '">
                    <i class="fas fa-eye"></i></button>';
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
        $zones = Zone::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $shifts = Shift::pluck('name', 'id');

        // Solo empleados que tengan al menos un contrato
        $empleados_contrato = DB::table('contracts')
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        // Conductores contratados
        $conductores = DB::table('employees')
            ->whereIn('id', $empleados_contrato)
            ->where('type_id', 1)
            ->selectRaw("id, CONCAT(names, ' ', lastnames) as fullname")
            ->pluck('fullname', 'id');

        // Ayudantes contratados
        $ayudantes = DB::table('employees')
            ->whereIn('id', $empleados_contrato)
            ->where('type_id', 2)
            ->selectRaw("id, CONCAT(names, ' ', lastnames) as fullname")
            ->pluck('fullname', 'id');

        return view('admin.schedules.create', compact('zones', 'vehicles', 'shifts'))
            ->with('conductores', [])
            ->with('ayudantes', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'zona_id' => 'required|exists:zones,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'shift_id' => 'required|exists:shifts,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'days' => 'required|array|min:1',
                'conductor_id' => 'required|exists:employees,id',
                'ayudantes_ids' => 'required|array|min:1',
                'ayudantes_ids.*' => 'exists:employees,id'
            ], [
                'ayudantes_ids.required' => 'Debe seleccionar al menos un ayudante.',
                'ayudantes_ids.min' => 'Debe seleccionar al menos un ayudante.',
                'ayudantes_ids.*.exists' => 'El ayudante seleccionado no es válido.'
            ]);

            $conductor_id = $request->conductor_id;
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $vacaciones_conductor = Vacation::where('employee_id', $conductor_id)
                ->where(function ($q) use ($start_date, $end_date) {
                    $q->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date);
                })
                ->exists();

            if ($vacaciones_conductor) {
                return response()->json(['message' => 'El conductor seleccionado tiene vacaciones en esas fechas. No se puede programar.'], 400);
            }

            foreach ($request->ayudantes_ids as $ayudante_id) {
                $vacaciones_ayudante = Vacation::where('employee_id', $ayudante_id)
                    ->where(function ($q) use ($start_date, $end_date) {
                        $q->where('start_date', '<=', $end_date)
                            ->where('end_date', '>=', $start_date);
                    })
                    ->exists();

                if ($vacaciones_ayudante) {
                    // Puedes mostrar cuál ayudante tiene el conflicto
                    $empleado = Employee::find($ayudante_id);
                    $nombre = $empleado ? $empleado->names . ' ' . $empleado->lastnames : 'Ayudante';
                    return response()->json(['message' => "El ayudante $nombre tiene vacaciones en esas fechas. No se puede programar."], 400);
                }
            }

            $schedule = Schedule::create([
                'zone_id' => $request->zona_id,
                'vehicle_id' => $request->vehicle_id,
                'shift_id' => $request->shift_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 1
            ]);

            foreach ($request->days as $day) {
                Scheduleday::create([
                    'schedule_id' => $schedule->id,
                    'day_of_week' => $day
                ]);
            }

            Scheduleoccupant::create([
                'schedule_id' => $schedule->id,
                'employee_id' => $request->conductor_id,
                'employee_type_id' => 1,
                'status' => 1
            ]);

            foreach ($request->ayudantes_ids as $ayudante_id) {
                Scheduleoccupant::create([
                    'schedule_id' => $schedule->id,
                    'employee_id' => $ayudante_id,
                    'employee_type_id' => 2,
                    'status' => 1
                ]);
            }
            return response()->json(['message' => 'Programación registrada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);
        $zones = Zone::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $shifts = Shift::pluck('name', 'id');

        // Días abreviados
        $diasCorto = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
        $diasProgramados = Scheduleday::where('schedule_id', $schedule->id)->pluck('day_of_week')->toArray();
        $diasString = implode(', ', array_map(fn($n) => $diasCorto[$n], $diasProgramados));

        // Conductor
        $conductor = Scheduleoccupant::where('schedule_id', $schedule->id)->where('employee_type_id', 1)->first();
        $conductorNombre = '';
        if ($conductor) {
            $emp = Employee::find($conductor->employee_id);
            if ($emp) $conductorNombre = "{$emp->names} {$emp->lastnames}";
        }

        // Ayudantes
        $ayudantes = Scheduleoccupant::where('schedule_id', $schedule->id)->where('employee_type_id', 2)->pluck('employee_id')->toArray();
        $ayudantesNombres = [];
        if (count($ayudantes)) {
            $ayudantesArr = Employee::whereIn('id', $ayudantes)->get();
            foreach ($ayudantesArr as $a) {
                $ayudantesNombres[] = "{$a->names} {$a->lastnames}";
            }
        }

        return view('admin.schedules.show', compact(
            'schedule',
            'zones',
            'vehicles',
            'shifts',
            'diasString',
            'conductorNombre',
            'ayudantesNombres'
        ));
    }



    /**
     * Show the form for editing the specified resource.
     */
    // ScheduleController.php

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $zones = Zone::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $shifts = Shift::pluck('name', 'id');

        $empleados_contrato = DB::table('contracts')
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        $scheduledays = Scheduleday::where('schedule_id', $id)->pluck('day_of_week')->toArray();
        $occupants = Scheduleoccupant::where('schedule_id', $id)->get();
        $conductor_id = optional($occupants->where('employee_type_id', 1)->first())->employee_id;
        $ayudantes_ids = $occupants->where('employee_type_id', 2)->pluck('employee_id')->toArray();

        // Para edición: muestra los empleados asignados aunque estén en vacaciones
        // Si quieres filtrar por fechas, puedes usar el mismo rango que la programación
        $start = $schedule->start_date;
        $end = $schedule->end_date;

        // Conductores disponibles + el asignado (aunque esté de vacaciones)
        $conductores = DB::table('employees')
            ->whereIn('id', $empleados_contrato)
            ->where('type_id', 1)
            ->where(function ($query) use ($start, $end, $conductor_id) {
                $query->whereNotIn('id', function ($sub) use ($start, $end) {
                    $sub->select('employee_id')
                        ->from('vacations')
                        ->where(function ($q) use ($start, $end) {
                            $q->where('start_date', '<=', $end)
                                ->where('end_date', '>=', $start);
                        });
                })
                    ->orWhere('id', $conductor_id); // Siempre incluir el asignado
            })
            ->selectRaw("id, CONCAT(names, ' ', lastnames) as fullname")
            ->pluck('fullname', 'id');

        // Ayudantes disponibles + los asignados
        $ayudantes = DB::table('employees')
            ->whereIn('id', $empleados_contrato)
            ->where('type_id', 2)
            ->where(function ($query) use ($start, $end, $ayudantes_ids) {
                $query->whereNotIn('id', function ($sub) use ($start, $end) {
                    $sub->select('employee_id')
                        ->from('vacations')
                        ->where(function ($q) use ($start, $end) {
                            $q->where('start_date', '<=', $end)
                                ->where('end_date', '>=', $start);
                        });
                });
                if ($ayudantes_ids) {
                    $query->orWhereIn('id', $ayudantes_ids);
                }
            })
            ->selectRaw("id, CONCAT(names, ' ', lastnames) as fullname")
            ->pluck('fullname', 'id');

        return view('admin.schedules.edit', compact(
            'schedule',
            'zones',
            'vehicles',
            'shifts',
            'conductores',
            'ayudantes',
            'scheduledays',
            'conductor_id',
            'ayudantes_ids'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'zona_id' => 'required|exists:zones,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'shift_id' => 'required|exists:shifts,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'days' => 'required|array|min:1',
                'conductor_id' => 'required|exists:employees,id',
                'ayudantes_ids' => 'required|array|min:1',
                'ayudantes_ids.*' => 'exists:employees,id'
            ], [
                'ayudantes_ids.required' => 'Debe seleccionar al menos un ayudante.',
                'ayudantes_ids.min' => 'Debe seleccionar al menos un ayudante.',
                'ayudantes_ids.*.exists' => 'El ayudante seleccionado no es válido.'
            ]);

            $schedule = Schedule::findOrFail($id);
            $schedule->zone_id = $request->zona_id;
            $schedule->vehicle_id = $request->vehicle_id;
            $schedule->shift_id = $request->shift_id;
            $schedule->start_date = $request->start_date;
            $schedule->end_date = $request->end_date;
            $schedule->save();

            // Actualiza días (borras y vuelves a insertar)
            Scheduleday::where('schedule_id', $id)->delete();
            foreach ($request->days as $day) {
                Scheduleday::create([
                    'schedule_id' => $schedule->id,
                    'day_of_week' => $day
                ]);
            }

            // Actualiza ocupantes (borras y vuelves a insertar)
            Scheduleoccupant::where('schedule_id', $id)->delete();
            Scheduleoccupant::create([
                'schedule_id' => $schedule->id,
                'employee_id' => $request->conductor_id,
                'employee_type_id' => 1,
                'status' => 1
            ]);
            foreach ($request->ayudantes_ids as $ayudante_id) {
                Scheduleoccupant::create([
                    'schedule_id' => $schedule->id,
                    'employee_id' => $ayudante_id,
                    'employee_type_id' => 2,
                    'status' => 1
                ]);
            }
            return response()->json(['message' => 'Programación actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error al actualizar: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Borrar relacionados
            Scheduleday::where('schedule_id', $id)->delete();
            Scheduleoccupant::where('schedule_id', $id)->delete();

            // Borra programación
            Schedule::destroy($id);

            return response()->json(['message' => 'Programación eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al eliminar: ' . $th->getMessage()], 500);
        }
    }
}
