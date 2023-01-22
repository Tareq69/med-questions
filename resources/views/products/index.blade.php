@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="#" method="post" class="card-header" id="filterForm">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" id="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">

                    <select name="variant" placeholder="variant" id="variant" class="form-control">
                        <option value="" selected>Select a variant</option>
                        @foreach ($variants->groupBy('title') as $title => $variantGroup)
                            <optgroup label="{{ $title }}">
                                @foreach ($variantGroup as $variant)
                                    <option value="{{ $variant->variant }}">{{ $variant->variant }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" id="price_from" aria-label="First name" placeholder="From"
                            class="form-control">
                        <input type="text" name="price_to" id="price_to" aria-label="Last name" placeholder="To"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" id="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th width="350px">Description</th>
                            <th>Variant</th>

                            <th width="150px">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>

                                <td>{{ $product->title }}<br> Created at :
                                    {{ date('jS F h:i A, Y', strtotime($product->created_at)) }}</td>
                                <td>{{ substr($product->description, 0, 200) }}</td>
                                <td>
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                        <dt class="col-sm-3 pb-0">
                                            {{ $product->variant_one }} /
                                            {{ $product->variant_two }} /
                                            {{ $product->variant_three }}
                                        </dt>
                                        <dd class="col-sm-9">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 pb-0">Price : {{ $product->price }}</dt>
                                                <dd class="col-sm-8 pb-0">InStock : {{ $product->stock }}</dd>
                                            </dl>
                                        </dd>
                                    </dl>
                                    <!-- <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button> -->
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <p>No products to show</p>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <div class="pagination d-block hide-pagination">
                        {{ $products->links() }}
                        <div class="pagination-summary ">
                            Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} products,
                            page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                         </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @push('page_js')
        <script src="{{ asset('js/productFilter.js') }}"></script>
    @endpush
