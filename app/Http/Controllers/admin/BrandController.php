<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::all();

        if ($request->ajax()) {
            return DataTables::of($brands)
                ->addColumn('logo', function ($brand) {
                    return '<img src="' . ($brand->logo == '' ? asset('storage/brand_logo/no_image.png') : asset($brand->logo)) . '" alt="" width="80px" height="50px">';
                })
                ->addColumn('edit', function ($brand) {
                    return '<button class="btn btn-success btn-sm btnEditar" id="' . $brand->id . '">
                    <i class="fas fa-pen""></i></button>';
                })
                ->addColumn('delete', function ($brand) {
                    return '<form action="' . route('admin.brands.destroy', $brand->id) . '" method="POST"
                            class="frmDelete">' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm"><i
                             class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['logo', 'edit', 'delete'])
                ->make(true);
        } else {
            return view('admin.brands.index', compact('brands'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $logo = "";
            $request->validate([
                'name' => 'unique:brands',
            ]);
            if ($request->logo != "") {
                $image = $request->file('logo')->store('public/brand_logo');
                $logo = Storage::url($image);
            }
            //Brand::create($request->all());
            Brand::create([
                'logo' => $logo,
                'name' => $request->name,
                'description' => $request->description
            ]);
            //return redirect()->route('admin.brands.index')->with('success', 'Marca registrada correctamente');
            return response()->json(['message' => 'Marca registrada correctamente'], 200);
        } catch (\Throwable $th) {
            //return redirect()->route('admin.brands.index')->with('error', 'Hubo un error en el registro');
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
        $brand = Brand::find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $logo = "";

            $brand = Brand::find($id);

            $request->validate([
                'name' => 'unique:brands,name,' . $id,
            ]);

            if ($request->logo != null) {
                $image = $request->file('logo')->store('public/brand_logo');
                $logo = Storage::url($image);

                $brand->update([
                    'logo' => $logo,
                    'name' => $request->name,
                    'description' => $request->description
                ]);
            } else {
                $brand->update([
                    'name' => $request->name,
                    'description' => $request->description
                ]);
            }

            //$brand->update($request->all());
            return response()->json(['message' => 'Marca actualizada correctamente'], 200);
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
            $brand = Brand::find($id);
            $brand->delete();
            return response()->json(['message' => 'Marca eliminada correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error en la eliminación' . $th->getMessage()], 500);
        }
    }
}
