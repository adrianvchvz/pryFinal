<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Employeetype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = Employee::select(
            'employees.id',
            'employees.dni',
            'employees.lastnames',
            'employees.names',
            'employees.birthdate',
            'employees.license',
            'employees.address',
            'employees.email',
            'employees.phone',
            'employees.photo',
            'employees.password',
            'employees.status',
            'employees.created_at',
            'employees.updated_at',
            'et.name as type_name'
        )
            ->leftJoin('employeetypes as et', 'employees.type_id', '=', 'et.id')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addColumn('photo', function ($employee) {
                    return '<img src="' . ($employee->photo == '' ? asset('storage/brand_logo/no_image.png') : asset($employee->photo)) . '" alt="" width="80px" height="50px">';
                })
                ->addColumn('edit', function ($employee) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $employee->id . '">
                <i class="fas fa-pen"></i></button>';
                })
                ->addColumn('delete', function ($employee) {
                    return '<form action="' . route('admin.employees.destroy', $employee->id) . '" method="POST"
                        class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i
                         class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['photo', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.employees.index', compact('employees'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Employeetype::pluck('name', 'id');
        return view('admin.employees.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $photo = "";
            $request->validate([
                'name' => 'unique:brands',
            ]);

            $employee = Employee::create([
                'dni'        => $request->dni,
                'lastnames'  => $request->lastnames,
                'names'      => $request->names,
                'birthdate'  => $request->birthdate,
                'license'    => $request->license,
                'address'    => $request->address,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'photo'      => $photo,
                'password'   => $request->password,
                'status'     => $request->status,
                'type_id'    => $request->type_id
            ]);

            if ($request->hasFile('photo')) {
                $image = $request->file('photo')->store("public/employee_images/" . $employee->id);
                $photo = Storage::url($image);
                $employee->update(['photo' => $photo]);
            }

            return response()->json(['message' => 'Empleado registrado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en el registro' . $th->getMessage()], 500);
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
        $employee = Employee::find($id);
        $types = Employeetype::pluck('name', 'id');
        return view('admin.employees.edit', compact('employee', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::find($id);

            $request->validate([
                'name' => 'unique:employees,name,' . $id,
            ]);

            $data = [
                'dni'        => $request->dni,
                'lastnames'  => $request->lastnames,
                'names'      => $request->names,
                'birthdate'  => $request->birthdate,
                'license'    => $request->license,
                'address'    => $request->address,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'status'     => $request->status,
                'type_id'    => $request->type_id,
            ];

            // Si subió foto nueva
            if ($request->hasFile('photo')) {
                // Borra la foto anterior si existe
                if ($employee->photo && Storage::exists(str_replace('/storage/', 'public/', $employee->photo))) {
                    Storage::delete(str_replace('/storage/', 'public/', $employee->photo));
                }

                // Sube la nueva imagen
                $image = $request->file('photo')->store("public/employee_images/" . $employee->id);
                $photo = Storage::url($image);
                $data['photo'] = $photo;
            }

            // Solo actualiza la contraseña si escribió algo
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $employee->update($data);

            return response()->json(['message' => 'Empleado actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::find($id);

            // Eliminar foto física si existe y no es la predeterminada
            if ($employee->photo && Storage::exists(str_replace('/storage/', 'public/', $employee->photo))) {
                Storage::delete(str_replace('/storage/', 'public/', $employee->photo));
            }

            // Eliminar la carpeta completa del empleado
            Storage::deleteDirectory("public/employee_images/" . $id);

            $employee->delete();
            return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $dni = $request->dni;
        //$contractTypeId = $request->contract_type_id;

        $employee = Employee::where('dni', $dni)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'El empleado no existe.']);
        }

        // Si tiene contrato estable, bloquea TODO (Nombrado o Permanente)
        $hasStable = Contract::where('employee_id', $employee->id)
            ->whereIn('contract_type_id', [1, 2])
            ->exists();

        if ($hasStable) {
            return response()->json([
                'success' => false,
                'message' => 'El empleado ya tiene un contrato vigente.'
            ]);
        }

        // Chequear si tiene EVENTUAL y si han pasado 2 meses desde la fecha de fin
        $lastEventual = Contract::where('employee_id', $employee->id)
            ->where('contract_type_id', 3)
            ->orderByDesc('end_date')
            ->first();

        if ($lastEventual && $lastEventual->end_date) {
            $fin = Carbon::parse($lastEventual->end_date);
            $nuevaFecha = $fin->copy()->addMonths(2);

            if ($nuevaFecha->isFuture()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El empleado tiene un contrato vigente hasta ' . $fin->format('Y-m-d') .
                        '. Podrá registrar un nuevo contrato a partir del ' . $nuevaFecha->format('Y-m-d') . '.'
                ]);
            }
        }

        // Si pasa todas las validaciones, muestra info del empleado
        return response()->json([
            'success' => true,
            'employee' => [
                'id' => $employee->id,
                'full_name' => $employee->names . ' ' . $employee->lastnames,
            ]
        ]);
    }

    public function searchVacation(Request $request)
    {
        $dni = $request->dni;
        $employee = Employee::where('dni', $dni)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Empleado no encontrado.']);
        }

        // Solo permitir si tiene contrato nombrado o permanente vigente (sin fecha fin o end_date en el futuro)
        $now = now();
        $hasValidContract = Contract::where('employee_id', $employee->id)
            ->whereIn('contract_type_id', [1, 2])
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->exists();

        if (!$hasValidContract) {
            return response()->json([
                'success' => false,
                'message' => 'Solo personal nombrado o permanente puede registrar vacaciones.'
            ]);
        }

        return response()->json([
            'success' => true,
            'employee' => [
                'id' => $employee->id,
                'full_name' => $employee->names . ' ' . $employee->lastnames,
            ]
        ]);
    }
}
