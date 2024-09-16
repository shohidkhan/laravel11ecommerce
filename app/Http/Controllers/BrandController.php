<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class BrandController extends Controller {
    //

    public function index() {
        $brands = Brand::orderBy('id', 'desc')->with('user')->paginate(5);
        return view('admin.brand.index', compact('brands'));
        // return $brands;
    }

    public function create() {
        return view('admin.brand.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user_id = Auth::user()->id;

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->user_id = $user_id;
        $brand->slug = Str::slug($request->slug);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->getClientOriginalExtension();
            $fileName = Carbon::now()->timestamp . '.' . $file_extension;
            $this->generateBrandThumbnailImage($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->save();
        return redirect()->route('brand.index')->with('status', 'Brand created successfully');
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user_id = Auth::user()->id;
        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->user_id = $user_id;
        $brand->slug = Str::slug($request->slug);
        if ($request->hasFile('image')) {
            if (File::exists('uploads/brands/' . $brand->image)) {
                File::delete('uploads/brands/' . $brand->image);
            }
            $image = $request->file('image');
            $file_extension = $image->getClientOriginalExtension();
            $fileName = Carbon::now()->timestamp . '.' . $file_extension;
            $this->generateBrandThumbnailImage($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->update();
        return redirect()->route('brand.index')->with('status', 'Brand updated successfully');
    }

    public function generateBrandThumbnailImage($image, $imageName) {
        $destinationPath = public_path('/uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function destroy($id) {
        $brand = Brand::findOrFail($id);
        if (File::exists('uploads/brands/' . $brand->image)) {
            File::delete('uploads/brands/' . $brand->image);
        }
        $brand->delete();
        return redirect()->route('brand.index')->with('status', 'Brand deleted successfully');
    }
}
