<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Brandmodel;
use App\Models\Color;
use App\Models\Vehicle;
use App\Models\Vehicleimage;
use App\Models\Vehicletype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicles = DB::select("CALL sp_vehicles(1)");

        if ($request->ajax()) {
            return DataTables::of($vehicles)
                ->addColumn('image', function ($vehicle) {
                    return '<img src="' . ($vehicle->image == '' ? asset('storage/brand_logo/no_image.png') : asset($vehicle->image)) . '" alt="" width="80px" height="50px">';
                })
                ->addColumn('images', function ($vehicle) {
                    return '<button class="btn btn-primary btn-sm btnImages" id="' . $vehicle->id . '">
                    <i class="fas fa-images""></i></button>';
                })
                ->addColumn('edit', function ($vehicle) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $vehicle->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($vehicle) {
                    return '<form action="' . route('admin.vehicles.destroy', $vehicle->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['image', 'images', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.vehicles.index', compact('vehicles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::pluck('name', 'id');
        $models = Brandmodel::pluck('name', 'id');
        $types = Vehicletype::pluck('name', 'id');
        $colors = Color::pluck('name', 'id');

        return view('admin.vehicles.create', compact('brands', 'models', 'types', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:vehicles,name'],
            'plate' => ['required', 'regex:/^[A-Z0-9]{6}$|^[A-Z0-9]{2}-[A-Z0-9]{4}$|^[A-Z0-9]{3}-[A-Z0-9]{3}$/i', 'unique:vehicles,plate'],
            'year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            'code' => ['required', 'unique:vehicles,code'],
        ], [
            'name.required' => 'El nombre del vehículo es obligatorio.',
            'name.unique' => 'Este nombre ya está registrado.',
            'plate.regex' => 'El formato de la placa no es válido (ej: XXXXXX, XX-XXXX o XXX-XXX).',
            'plate.required' => 'El campo placa es obligatorio.',
            'plate.unique' => 'Esta placa ya está registrada.',
            'code.unique' => 'Este código ya está en uso.',
            'year.required' => 'El campo año es obligatorio.',
            'year.digits' => 'El año debe tener 4 dígitos.',
            'year.min' => 'El año debe ser mayor o igual a 1900.',
            'year.max' => 'El año no puede ser mayor al actual.',
        ]);


        try {
            $vehicle = Vehicle::create($request->except('image'));

            if ($request->hasFile('image')) {
                $image = $request->file("image")->store("public/vehicle_images/" . $vehicle->id);
                $urlImage = Storage::url($image);

                Vehicleimage::create([
                    "image" => $urlImage,
                    "profile" => 1,
                    "vehicle_id" => $vehicle->id
                ]);
            }

            return response()->json(['message' => 'Vehículo registrado correctamente'], 200);
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
        $vehicle = Vehicle::find($id);

        $brands = Brand::pluck('name', 'id');
        $models = Brandmodel::pluck('name', 'id');
        $types = Vehicletype::pluck('name', 'id');
        $colors = Color::pluck('name', 'id');

        return view('admin.vehicles.edit', compact('vehicle', 'brands', 'models', 'types', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'unique:vehicles,name,' . $id],
            'plate' => ['required', 'regex:/^[A-Z0-9]{6}$|^[A-Z0-9]{2}-[A-Z0-9]{4}$|^[A-Z0-9]{3}-[A-Z0-9]{3}$/i', 'unique:vehicles,plate'],
            'year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            'code' => ['required', 'unique:vehicles,code'],
        ], [
            'name.required' => 'El nombre del vehículo es obligatorio.',
            'name.unique' => 'Este nombre ya está registrado.',
            'plate.regex' => 'El formato de la placa no es válido (ej: XXXXXX, XX-XXXX o XXX-XXX).',
            'plate.required' => 'El campo placa es obligatorio.',
            'plate.unique' => 'Esta placa ya está registrada.',
            'code.unique' => 'Este código ya está en uso.',
            'year.required' => 'El campo año es obligatorio.',
            'year.digits' => 'El año debe tener 4 dígitos.',
            'year.min' => 'El año debe ser mayor o igual a 1900.',
            'year.max' => 'El año no puede ser mayor al actual.',
        ]);


        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->update($request->except("image"));

            if ($request->hasFile('image')) {
                $image = $request->file("image")->store("public/vehicle_images/" . $vehicle->id);
                $urlImage = Storage::url($image);

                DB::table('vehicleimages')->where('vehicle_id', $id)->update(['profile' => 0]);

                Vehicleimage::create([
                    "image" => $urlImage,
                    "profile" => 1,
                    "vehicle_id" => $vehicle->id
                ]);
            }

            return response()->json(['message' => 'Vehículo actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            // Eliminar imágenes asociadas
            $images = Vehicleimage::where('vehicle_id', $id)->get();
            foreach ($images as $img) {
                // Borrar archivo físico si existe
                if ($img->image && Storage::exists(str_replace('/storage/', 'public/', $img->image))) {
                    Storage::delete(str_replace('/storage/', 'public/', $img->image));
                }
                $img->delete();
            }

            // Eliminar carpeta entera si quieres (opcional)
            Storage::deleteDirectory("public/vehicle_images/" . $id);

            // Eliminar vehículo
            $vehicle->delete();

            return response()->json(['message' => 'Vehículo eliminado correctamente', 200]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
