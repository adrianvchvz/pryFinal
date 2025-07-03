<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Contracttype;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contracts = Contract::select(
            'contracts.id',
            'contracts.start_date',
            'contracts.end_date',
            'contracts.status',
            'contracts.description',
            'contracts.employee_id',
            'contracts.contract_type_id',
            'contracts.created_at',
            'contracts.updated_at',
            'ct.name as contract_type_name',
            'e.names as employee_names',
            'e.lastnames as employee_lastnames'
        )
            ->leftJoin('contracttypes as ct', 'contracts.contract_type_id', '=', 'ct.id')
            ->leftJoin('employees as e', 'contracts.employee_id', '=', 'e.id')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($contracts)
                ->addColumn('employee_name', function ($contract) {
                    return $contract->employee_lastnames . ' ' . $contract->employee_names ;
                })
                ->addColumn('type_name', function ($contract) {
                    return $contract->contract_type_name; // <-- Este es el nombre del tipo de contrato
                })
                ->addColumn('delete', function ($contract) {
                    return '<form action="' . route('admin.contracts.destroy', $contract->id) . '" method="POST"
                        class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i
                         class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['employee_name', 'type_name', 'delete'])
                ->make(true);
        } else {
            return view('admin.contracts.index', compact('contracts'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $typec = Contracttype::pluck('name', 'id');
        return view('admin.contracts.create', compact('typec'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_type_id' => 'required|exists:contracttypes,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
            // Valida fecha de fin solo si es eventual (lo puedes hacer con reglas condicionales si prefieres)
        ]);

        $employeeId = $request->employee_id;
        $contractType = $request->contract_type_id;

        // Validación para NOMBRADO o PERMANENTE (no se pueden repetir)
        if (in_array($contractType, [1, 2])) {
            $exists = Contract::where('employee_id', $employeeId)
                ->whereIn('contract_type_id', [1, 2])
                ->exists();
            if ($exists) {
                return response()->json(['message' => 'Empleado ya tiene un contrato establecido.'], 400);
            }
        }

        // Validación para EVENTUAL (deben pasar 2 meses)
        if ($contractType == 3) {
            $lastEventual = Contract::where('employee_id', $employeeId)
                ->where('contract_type_id', 3)
                ->orderByDesc('end_date')
                ->first();

            if ($lastEventual && $lastEventual->end_date) {
                $diff = \Carbon\Carbon::parse($lastEventual->end_date)->diffInMonths(now());
                if ($diff < 2) {
                    return response()->json(['message' => 'Debe esperar 2 meses para registrar un nuevo contrato eventual.'], 400);
                }
            }
        }

        try {
            // Registro del contrato
            Contract::create([
                'employee_id' => $employeeId,
                'contract_type_id' => $contractType,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status ? 1 : 0,
                'description' => $request->description,
            ]);

            return response()->json(['message' => 'Contrato registrado correctamente'], 200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $contract->delete();
            return response()->json(['message' => 'Contrato eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}