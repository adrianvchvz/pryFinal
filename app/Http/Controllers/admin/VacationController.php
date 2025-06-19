<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $vacations = Vacation::select(
            'vacations.id',
            'vacations.year',
            'vacations.start_date',
            'vacations.end_date',
            'vacations.days',
            'vacations.pending_days',
            'vacations.description',
            'e.names as employee_names',
            'e.lastnames as employee_lastnames'
        )
            ->leftJoin('employees as e', 'vacations.employee_id', '=', 'e.id')
            ->get();

        if ($request->ajax()) {

            return DataTables::of($vacations)
                ->addColumn('employee_name', function ($v) {
                    return $v->employee_names . ' ' . $v->employee_lastnames;
                })
                ->addColumn('edit', function ($employee) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $employee->id . '">
                <i class="fas fa-pen"></i></button>';
                })
                ->addColumn('delete', function ($contract) {
                    return '<form action="' . route('admin.vacations.destroy', $contract->id) . '" method="POST"
                        class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i
                         class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['employee_name', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.vacations.index', compact('vacations'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vacations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $year = (int) $request->year;

        // Suma los días ya registrados en el año
        $totalDias = Vacation::where('employee_id', $request->employee_id)
            ->where('year', $year)
            ->sum('days');

        $diasPendientes = 30 - $totalDias;

        if ($diasPendientes <= 0) {
            return response()->json([
                'success' => false,
                'message' => "El empleado ya tiene sus 30 días de vacaciones registrados para el año $year."
            ], 400);
        }

        // Valida los campos (igual que tienes)
        $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'year'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'days'          => "required|integer|min:1|max:$diasPendientes", // <- máximo solo los días pendientes
            'pending_days'  => 'required|integer|min:0|max:30',
            'description'   => 'nullable|string',
        ], [
            'days.max'         => "Solo puede registrar hasta $diasPendientes días de vacaciones.",
            // ... tus otros mensajes ...
        ]);

        // Fechas dentro del año
        $startDate = date('Y', strtotime($request->start_date));
        $endDate = date('Y', strtotime($request->end_date));
        if ($startDate != $year || $endDate != $year) {
            return response()->json([
                'success' => false,
                'message' => "Las fechas de inicio y fin deben estar dentro del año seleccionado ($year)."
            ], 400);
        }

        // Validar que no haya solapamiento de fechas con vacaciones ya registradas en ese año
        $overlap = Vacation::where('employee_id', $request->employee_id)
            ->where('year', $year)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    // Caso 1: Nueva fecha inicio está entre un rango existente
                    $q->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->start_date);
                })
                    ->orWhere(function ($q) use ($request) {
                        // Caso 2: Nueva fecha fin está entre un rango existente
                        $q->where('start_date', '<=', $request->end_date)
                            ->where('end_date', '>=', $request->end_date);
                    })
                    ->orWhere(function ($q) use ($request) {
                        // Caso 3: El nuevo rango engloba completamente un rango existente
                        $q->where('start_date', '>=', $request->start_date)
                            ->where('end_date', '<=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'El rango de fechas seleccionado se cruza con otro periodo de vacaciones ya registrado para este año.'
            ], 400);
        }

        // REGISTRO NORMAL
        try {
            Vacation::create([
                'employee_id'   => $request->employee_id,
                'year'          => $year,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'days'          => $request->days,
                'pending_days'  => $diasPendientes - $request->days,
                'description'   => $request->description,
            ]);

            return response()->json(['message' => 'Vacaciones registradas correctamente'], 200);
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
    public function edit($id)
    {
        $vacation = Vacation::findOrFail($id);
        $employee = Employee::find($vacation->employee_id);

        return view('admin.vacations.edit', [
            'vacation' => $vacation,
            'employee' => $employee,
            'editMode' => true,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $year = (int) $request->year;
        $vacation = Vacation::findOrFail($id);

        // Suma días excluyendo el registro actual
        $totalDias = Vacation::where('employee_id', $request->employee_id)
            ->where('year', $year)
            ->where('id', '!=', $id)
            ->sum('days');

        $diasPendientes = 30 - $totalDias;

        if ($diasPendientes <= 0) {
            return response()->json([
                'success' => false,
                'message' => "El empleado ya tiene sus 30 días de vacaciones registrados para el año $year."
            ], 400);
        }

        // Valida los campos (igual que tienes)
        $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'year'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'days'          => "required|integer|min:1|max:$diasPendientes",
            'pending_days'  => 'required|integer|min:0|max:30',
            'description'   => 'nullable|string',
        ], [
            'days.max'         => "Solo puede registrar hasta $diasPendientes días de vacaciones.",
        ]);

        $startDate = date('Y', strtotime($request->start_date));
        $endDate = date('Y', strtotime($request->end_date));
        if ($startDate != $year || $endDate != $year) {
            return response()->json([
                'success' => false,
                'message' => "Las fechas deben estar dentro del año seleccionado ($year)."
            ], 400);
        }

        // Validar solapamiento excluyendo el registro actual
        $overlap = Vacation::where('employee_id', $request->employee_id)
            ->where('year', $year)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->start_date);
                })
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->end_date)
                            ->where('end_date', '>=', $request->end_date);
                    })
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '>=', $request->start_date)
                            ->where('end_date', '<=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'El rango de fechas seleccionado se cruza con otro periodo de vacaciones ya registrado para este año.'
            ], 400);
        }

        try {
            $vacation->update([
                'year'          => $year,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'days'          => $request->days,
                'pending_days'  => $diasPendientes - $request->days,
                'description'   => $request->description,
            ]);
            return response()->json(['message' => 'Vacaciones actualizadas correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $vacation = Vacation::findOrFail($id);
            $vacation->delete();

            return response()->json(['message' => 'Vacaciones eliminadas correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro: ' . $th->getMessage()], 500);
        }
    }


    public function search(Request $request)
    {
        $dni = $request->dni;
        $year = $request->year;

        // Validar datos mínimos
        if (!$dni || !$year) {
            return response()->json(['exists' => false, 'message' => 'Ingrese DNI y año.']);
        }

        $employee = Employee::where('dni', $dni)->first();

        if (!$employee) {
            return response()->json(['exists' => false, 'message' => 'Empleado no encontrado.']);
        }

        $exists = Vacation::where('employee_id', $employee->id)
            ->where('year', $year)
            ->exists();

        if ($exists) {
            return response()->json([
                'exists' => true,
                'message' => 'El empleado ya tiene vacaciones registradas para el año ' . $year . '.'
            ]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function check(Request $request)
    {
        $dni = $request->dni;
        $year = (int) $request->year;

        $employee = Employee::where('dni', $dni)->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado.'
            ]);
        }

        $totalDias = Vacation::where('employee_id', $employee->id)
            ->where('year', $year)
            ->sum('days');

        $diasPendientes = 30 - $totalDias;

        if ($totalDias >= 30) {
            return response()->json([
                'success' => false,
                'employee' => [
                    'full_name' => $employee->names . ' ' . $employee->lastnames,
                    'id' => $employee->id
                ],
                'message' => "El empleado ya tiene sus 30 días de vacaciones registrados para el año $year.",
                'dias_registrados' => $totalDias,
                'dias_pendientes' => 0
            ]);
        }

        return response()->json([
            'success' => true,
            'employee' => [
                'full_name' => $employee->names . ' ' . $employee->lastnames,
                'id' => $employee->id
            ],
            'dias_registrados' => $totalDias,
            'dias_pendientes' => $diasPendientes
        ]);
    }
}
