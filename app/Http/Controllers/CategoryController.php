<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    //

    public function index() {
        $categories = Category::orderBy('id', 'desc')->with('user')->paginate(5);
        return view('admin.category.index', compact('categories'));
    }

    public function create() {
        return view("admin.category.create");
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user_id = Auth::user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_original_name = $image->getClientOriginalName();
            $time = Carbon::now()->timestamp;
            $file_name = "{$time}-{$file_original_name}";
            $img_url = "uploads/categories/{$file_name}";
            $image_upload = $image->move(public_path('uploads/categories'), $file_name);
        }

        Category::create([
            'name' => $request->name,
            'user_id' => $user_id,
            'slug' => Str::slug($request->slug),
            'image' => $request->hasFile('image') ? $img_url : null,
        ]);

        return redirect()->route('category.index')->with('status', 'Category created successfully');
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user_id = Auth::user()->id;
        $category = Category::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($category->image) {
                unlink(public_path($category->image));
            }

            $image = $request->file('image');
            $file_original_name = $image->getClientOriginalName();
            $time = Carbon::now()->timestamp;
            $file_name = "{$time}-{$file_original_name}";
            $img_url = "uploads/categories/{$file_name}";
            $image_upload = $image->move(public_path('uploads/categories'), $file_name);
        }

        Category::where('id', $id)->update([
            'name' => $request->name,
            'user_id' => $user_id,
            'slug' => Str::slug($request->slug),
            'image' => $request->hasFile('image') ? $img_url : $category->image,
        ]);

        return redirect()->route('category.index')->with('status', 'Category updated successfully');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        if ($category->image) {
            unlink(public_path($category->image));
        }
        $category->delete();
        return redirect()->route('category.index')->with('status', 'Category deleted successfully');
    }
}
