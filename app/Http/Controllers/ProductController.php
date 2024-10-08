<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller {
    //

    public function index() {
        $products = Product::orderBy('id', 'desc')->with('category', 'brand')->paginate(5);

        return view('admin.product.index', compact('products'));
    }

    public function create() {

        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function store(Request $request) {

        // dd($request->all());
        $request->validate([
            "category_id" => "required",
            "brand_id" => "required",
            "name" => "required",
            "slug" => "required|unique:products,slug",
            "short_description" => "required",
            "description" => "required",
            "regular_price" => "required",
            "sale_price" => "required",
            "SKU" => "required",
            "quantity" => "required",
            "stock_status" => "required",
            "featured" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "status" => "required",
        ]);

        $user_id = Auth::user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_original_name = $image->getClientOriginalName();
            $time = Carbon::now()->timestamp;
            $file_name = "{$time}-{$file_original_name}";
            $img_url = "uploads/products/thumbnail/{$file_name}";
            $image_upload = $image->move(public_path('uploads/products/thumbnail'), $file_name);
        }

        //multiple images
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if ($request->hasFile('images')) {
            $allowedExtensions = ['jpg', 'png', 'jpeg', 'gif'];
            $images = $request->file('images');

            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $checkExtension = in_array($extension, $allowedExtensions);
                if ($checkExtension) {
                    $gfile_original_name = $image->getClientOriginalName();
                    $gtime = Carbon::now()->timestamp;
                    $gfile_name = "{$gtime}-{$counter}-{$gfile_original_name}";
                    $gimg_url = "uploads/products/{$gfile_name}";
                    $gallery_arr[] = $gimg_url;
                    $image_upload = $image->move(public_path('uploads/products'), $gfile_name);
                    $counter++;
                }
            }

            $gallery_images = implode(",", $gallery_arr);
        }

        Product::create([
            "user_id" => $user_id,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "slug" => $request->slug,
            "name" => $request->name,
            "short_description" => $request->short_description,
            "description" => $request->description,
            "regular_price" => $request->regular_price,
            "sale_price" => $request->sale_price,
            "SKU" => $request->SKU,
            "quantity" => $request->quantity,
            "stock_status" => $request->stock_status,
            "featured" => $request->featured,
            "image" => $request->hasFile('image') ? $img_url : null,
            "images" => $request->hasFile('images') ? $gallery_images : null,
            "status" => $request->status,
        ]);

        return redirect()->route('product.index')->with('status', 'Product has added successfully');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.product.edit', compact('categories', 'brands', 'product'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            "category_id" => "required",
            "brand_id" => "required",
            "name" => "required",
            "slug" => "required|unique:products,slug," . $id,
            "short_description" => "required|max:500",
            "description" => "required",
            "regular_price" => "required",
            "sale_price" => "required",
            "SKU" => "required",
            "quantity" => "required",
            "stock_status" => "required",
            "featured" => "required",
            "image" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "status" => "required",
        ]);

        $user_id = Auth::user()->id;
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                unlink(public_path($product->image));
            }
            $image = $request->file('image');
            $file_original_name = $image->getClientOriginalName();
            $time = Carbon::now()->timestamp;
            $file_name = "{$time}-{$file_original_name}";
            $img_url = "uploads/products/thumbnail/{$file_name}";
            $image_upload = $image->move(public_path('uploads/products/thumbnail/'), $file_name);
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                $images = explode(",", $product->images);
                foreach ($images as $image) {
                    if ($image) {
                        unlink(public_path($image));
                    }
                }
            }
            $allowedExtensions = ['jpg', 'png', 'jpeg', 'gif'];
            $gimages = $request->file('images');
            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            foreach ($gimages as $gimg) {
                $extension = $gimg->getClientOriginalExtension();
                $checkExtension = in_array($extension, $allowedExtensions);

                if ($checkExtension) {
                    $goriginal_name = $gimg->getClientOriginalName();
                    $gtime = Carbon::now()->timestamp;
                    $gfile_name = "{$gtime}-{$counter}-{$goriginal_name}";
                    $gimg_url = "uploads/products/{$gfile_name}";
                    $gallery_arr[] = $gimg_url;
                    $image_upload = $gimg->move(public_path('uploads/products'), $gfile_name);
                    $counter++;
                }
            }

            $gallery_images = implode(",", $gallery_arr);
        }

        Product::where('id', $id)->update([
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "slug" => $request->slug,
            "name" => $request->name,
            "short_description" => $request->short_description,
            "description" => $request->description,
            "regular_price" => $request->regular_price,
            "sale_price" => $request->sale_price,
            "SKU" => $request->SKU,
            "quantity" => $request->quantity,
            "stock_status" => $request->stock_status,
            "featured" => $request->featured,
            "image" => $request->hasFile('image') ? $img_url : $product->image,
            "images" => $request->hasFile('images') ? $gallery_images : $product->images,
            "status" => $request->status,
        ]);

        return redirect()->route('product.index')->with('status', 'Product has updated successfully');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        if ($product->image) {
            unlink(public_path($product->image));
        }

        if ($product->images) {
            $gimages = explode(",", $product->images);
            foreach ($gimages as $gimg) {
                if ($gimg) {
                    unlink(public_path($gimg));
                }
            }
        }

        $product->delete();
        return redirect()->route('product.index')->with('status', 'Product has deleted successfully');
    }

    public function productStatusChange($id) {
        $product = Product::findOrFail($id);
        if ($product->status == 1) {
            $product->status = 0;
        } else {
            $product->status = 1;
        }

        $product->save();
        return redirect()->route('product.index')->with('status', 'Product status has changed successfully');
    }
}
