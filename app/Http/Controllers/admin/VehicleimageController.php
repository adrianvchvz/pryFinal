<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vehicleimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleimageController extends Controller
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
        try {
            $vehicle = Vehicle::findOrFail($id);
            $images = Vehicleimage::where('vehicle_id', $id)->get();
            return view('admin.vehicles.show', compact('vehicle', 'images'));
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error' . $th->getMessage()], 500);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $image = Vehicleimage::findOrFail($id);
            if ($image->image && Storage::exists(str_replace('/storage/', 'public/', $image->image))) {
                Storage::delete(str_replace('/storage/', 'public/', $image->image));
            }
            $image->delete();

            return response()->json(['message' => 'Imagen eliminada correctamente', 200]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }

    public function setProfile($image_id)
    {
        try {
            $image = Vehicleimage::findOrFail($image_id);
            // Ponemos todas las imágenes del vehículo como no principal
            Vehicleimage::where('vehicle_id', $image->vehicle_id)->update(['profile' => 0]);
            // Ponemos la seleccionada como principal
            $image->profile = 1;
            $image->save();
            return response()->json(['message' => 'Imagen actualizada correctamente', 200]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la actualización' . $th->getMessage()], 500);
        }
    }
}
