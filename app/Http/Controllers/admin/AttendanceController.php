<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.attendances.index');
    }

    public function filter(Request $request)
    {
        $query = Attendance::select(
            'attendances.id',
            'e.names as employee_names',
            'e.lastnames as employee_lastnames',
            'attendances.date',
            'attendances.time_in',
            'attendances.status'
        )
            ->join('employees as e', 'attendances.employee_id', '=', 'e.id');

        if ($request->dni) {
            $dni = $request->dni;
            $query->where(function ($q) use ($dni) {
                $q->where('e.dni', 'like', "%$dni%")
                    ->orWhere('e.names', 'like', "%$dni%")
                    ->orWhere('e.lastnames', 'like', "%$dni%");
            });
        }
        if ($request->fecha_inicio) {
            $query->where('attendances.date', '>=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $query->where('attendances.date', '<=', $request->fecha_fin);
        }

        return datatables()->of($query)
            ->addColumn('employee_fullname', function ($row) {
                return $row->employee_names . ' ' . $row->employee_lastnames;
            })
            ->editColumn('status', function ($row) {
                return $row->status ? 'Presente' : 'Faltó';
            })
            ->make(true);
    }

    public function showMarkForm()
    {
        return view('admin.attendances.mark');
    }

    public function mark(Request $request)
    {
        $request->validate([
            'dni' => 'required',
            'password' => 'required',
        ]);

        // Buscar empleado
        $employee = Employee::where('dni', $request->dni)->first();

        if (!$employee || $employee->password !== $request->password) {
            return back()->withErrors(['dni' => 'DNI o contraseña incorrectos'])->withInput();
        }

        // Verificar si ya marcó asistencia hoy
        $today = date('Y-m-d');
        $alreadyMarked = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->exists();

        if ($alreadyMarked) {
            return back()->with('status', 'Ya registraste tu asistencia hoy.');
        }

        // Registrar asistencia
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'time_in' => now()->format('H:i:s'),
            'status' => 1,
        ]);

        return back()->with('status', '¡Asistencia registrada correctamente!');
    }
}
