@extends('layouts.admin')
@section('title', 'Add Product')
@push('styles')
    <style>
        .g-images{
            display: flex;
        }
    </style>
@endpush
@section('content')
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('product.index') }}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit product</div>
                </li>
            </ul>
        </div>
        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
            action="{{ route('product.update', $product->id) }}">
            @csrf
            @method('PUT')
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name"
                        name="name" tabindex="0" value="{{ old('name', $product->name) }}" aria-required="true" >
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('name')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug"
                        name="slug" tabindex="0" value="{{ old('slug', $product->slug) }}" aria-required="true" >
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('slug')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id">
                                <option value="">Choose category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id  }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                                
                            </select>
                            @error('category_id')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>
                    
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id">
                                <option value="">Choose Brand</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id  }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name  }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>
                    
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span
                            class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description"
                        placeholder="Short Description" tabindex="0" aria-required="true"
                        >{{ old('short_description', $product->short_description) }}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('short_description')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror        
                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                    </div>
                    <textarea class="mb-10" name="description" placeholder="Description"
                        tabindex="0" aria-required="true" >{{ old('description', $product->description) }}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('description')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror 
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" >
                            <img src="{{ asset($product->image) }}"
                                class="effect8" alt="{{ $product->name }}">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror 

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        
                        @if($product->images)
                        @foreach (explode(',', $product->images) as $img)
                        
                            <div class="item gitems">
                                <img src="{{ asset($img) }}" alt="">
                             </div>
                         
                         @endforeach
                        @endif
                                                                    
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*"
                                    multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images')
                    <span class="text-danger text-center h6">{{ $message }}</span>
                @enderror 
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price"
                            name="regular_price" tabindex="0" value="{{ old('regular_price', $product->regular_price) }}" aria-required="true"
                            >
                            @error('regular_price')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror
                    </fieldset>
                     
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price"
                            name="sale_price" tabindex="0" value="{{ old('sale_price', $product->sale_price) }}" aria-required="true"
                            >
                            @error('sale_price')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                    </fieldset>
                   
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU"
                            tabindex="0" value="{{ old('SKU', $product->SKU) }}" aria-required="true" >
                            @error('SKU')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                    </fieldset>
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity"
                            name="quantity" tabindex="0" value="{{ old('quantity', $product->quantity) }}" aria-required="true"
                            >
                            @error('quantity')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                    </fieldset>
                    
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock" {{ old('stock_status', $product->stock_status) == 'instock' ? 'selected' : '' }}>InStock</option>
                                <option value="outofstock" {{ old('stock_status', $product->stock_status) == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            @error('stock_status')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                        </div>
                    </fieldset>
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select class="" name="featured">
                                <option value="0" {{ old('featured', $product->featured) == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('featured', $product->featured) == '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('featured')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                        </div>
                    </fieldset>
                    
                    <fieldset class="name">
                        <div class="body-title mb-10">Status</div>
                        <div class="select mb-10">
                            <select class="" name="status">
                                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>InActive</option>
                                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Active</option>
                            </select>
                            @error('status')
                                <span class="text-danger text-center h6">{{ $message }}</span>
                            @enderror 
                        </div>
                    </fieldset>
                    
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Update product</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('#myFile').on('change', function(){
                
                const photoInp=$('#myFile');
                const [file]=this.files;
               
                if(file){
                    $("#imgpreview img").attr("src",URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
            $('#gFile').on('change', function(){
                
                const photoInp=$('#gFile');
                const gPhotos=this.files;
                if(gPhotos){
                    $('.gitems').remove();
                    $.each(gPhotos, function(key, val){
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" alt="galleryImgages"></div>`)
                })
                }
                
                
            });

            $("input[name='name']").on('change',function(){
                $("input[name='slug']").val(StringToSlug($(this).val()));
                
            });
        })

        function StringToSlug(string){
            return string.toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,"-");
        }
    </script>
@endpush