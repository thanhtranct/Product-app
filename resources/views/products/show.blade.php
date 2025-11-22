@extends('layout')

@section('title', 'Product Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                ← Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Product Details</h4>
                <div>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Product
                    </a>
                    <button type="button" 
                            class="btn btn-danger btn-sm ms-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModalShow" 
                            data-action="{{ route('products.destroy', $product) }}"
                            data-name="{{ $product->name }}">
                        <i class="bi bi-trash"></i> Delete Product
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Image Column -->
                    <div class="col-md-5">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded shadow-sm">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                 style="height: 400px;">
                                <div class="text-center">
                                    <i class="bi bi-image" style="font-size: 4rem;"></i>
                                    <p class="mt-2">No Image Available</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Details Column -->
                    <div class="col-md-7">
                        <!-- Product Name -->
                        <h2 class="mb-3">{{ $product->name }}</h2>

                        <!-- Price -->
                        <div class="mb-4">
                            <h3 class="text-success mb-0">
                                ${{ number_format($product->price, 2) }}
                            </h3>
                        </div>

                        <!-- Product Info Table -->
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Product ID</th>
                                    <td>#{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td class="text-success fw-bold">${{ number_format($product->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>
                                        @if($product->description)
                                            {{ $product->description }}
                                        @else
                                            <span class="text-muted">No description provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>External API Data</th>
                                    <td>
                                        @if($product->external_api_data)
                                            <span class="badge bg-info text-dark">{{ $product->external_api_data }}</span>
                                        @else
                                            <span class="text-muted">No data available</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>
                                        {{ $product->created_at->format('F d, Y h:i A') }}
                                        <small class="text-muted">({{ $product->created_at->diffForHumans() }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>
                                        {{ $product->updated_at->format('F d, Y h:i A') }}
                                        <small class="text-muted">({{ $product->updated_at->diffForHumans() }})</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                Edit This Product
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Product Status</h5>
                        <span class="badge bg-success fs-6">Active</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Days Since Created</h5>
                        <h3 class="text-primary">{{ $product->created_at->diffInDays(now()) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Last Updated</h5>
                        <p class="mb-0">{{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal for Show -->
<div class="modal fade" id="deleteModalShow" tabindex="-1" aria-labelledby="deleteModalShowLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteFormShow" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalShowLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalShowBody">Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('deleteModalShow');
        if (!modal) return;
        modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var action = button.getAttribute('data-action');
                var name = button.getAttribute('data-name');
                var form = document.getElementById('deleteFormShow');
                if (form) form.action = action;
                var body = document.getElementById('deleteModalShowBody');
                if (body) body.textContent = 'Are you sure you want to delete "' + name + '"? This action cannot be undone.';
        });
});
</script>
@endpush

@endsection