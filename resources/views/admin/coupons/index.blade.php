@extends('layouts.admin')
@section('title', 'Coupons')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Coupons</h3>
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
                    <div class="text-tiny">Coupons</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('coupon.create') }}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if(Session::has('status'))
                    <div class="alert alert-success lead">
                        <strong>{{ Session::get('status') }}</strong>
                    </div>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Cart Value</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ( $coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td class="pname">
                                  
                                    {{ $coupon->code }}
                                </td>
                                <td>
                                    @if($coupon->type=='fixed')
                                    {{ $coupon->type }}
                                    @else
                                    {{ $coupon->type }} %
                                    @endif
                                </td>
                                <td>{{ $coupon->value}}</td>
                                <td>{{ $coupon->cart_value }}</td>
                                <td>
                                    {{-- @if($coupon->expiry_date >= Carbon\Carbon::now())
                                    <span  class=" badge rounded-pill bg-success">Active</span>
                                    @else
                                    <span  class="badge rounded-pill bg-danger">Expired</span>
                                    @endif --}}
                                    @if($coupon->expiry_date >= Carbon\Carbon::now()->format('Y-m-d'))
                                    <span  class=" badge  bg-success">
                                    {{ \Carbon\Carbon::parse($coupon->expiry_date)->diffInDays(\carbon\Carbon::now()->format('Y-m-d'))  }} days left </span>
                                    @else
                                    <span  class=" badge  bg-danger">
                                        Expired {{ \Carbon\Carbon::parse($coupon->expiry_date)->diffInDays(\carbon\Carbon::now()->format('Y-m-d'))  }} days ago </span>
                                    @endif

                                </td>
                                <td>
                                    @if($coupon->status == 1)
                                    <a href="" class=" badge rounded-pill bg-success">Active</a>
                                @else
                                    <a href="" class="badge rounded-pill bg-danger">Inactive</a>
                                @endif
                                </td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{ route('coupon.edit', $coupon->id) }}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{ route('coupon.destroy', $coupon->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No data aviable</td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $coupons->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('.delete').on('click', function(e){
                e.preventDefault();
                var form=$(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    type: "warning",
                    buttons: ['No', 'Yes'],
                    confirmButtonColor: '#DC3545', 
                    showLoaderOnConfirm: true,
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush