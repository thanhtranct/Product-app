@extends('layout')

@section('title', 'Products List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Products List</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Product
    </a>
</div>

@if($products->isEmpty())
    <div class="alert alert-info text-center">
        <p class="mb-0">No products found. <a href="{{ route('products.create') }}">Create your first product</a></p>
    </div>
@else
    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm hover-card">
                <!-- Product Image -->
                <div class="position-relative">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                             style="height: 250px;">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                </svg>
                                <p class="mt-2 mb-0">No Image</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Product ID Badge -->
                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark">#{{ $product->id }}</span>
                </div>

                <!-- Product Info -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate" title="{{ $product->name }}">
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                            {{ $product->name }}
                        </a>
                    </h5>
                    
                    <!-- Price -->
                    <h4 class="text-success mb-2">
                        ${{ number_format($product->price, 2) }}
                    </h4>
                    
                    <!-- Description Preview -->
                    @if($product->description)
                        <p class="card-text text-muted small mb-3" style="height: 40px; overflow: hidden;">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                    @else
                        <p class="card-text text-muted small mb-3 fst-italic" style="height: 40px;">
                            No description available
                        </p>
                    @endif
                    
                    <!-- Created Date -->
                    <p class="card-text mb-3">
                        <small class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                                <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg>
                            {{ $product->created_at->format('M d, Y') }}
                        </small>
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="mt-auto">
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-sm btn-warning w-50">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger w-100 delete-button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-action="{{ route('products.destroy', $product) }}"
                                        data-name="{{ $product->name }}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endif
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalBody">Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .hover-card:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    if (!deleteModal) return;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var action = button.getAttribute('data-action');
        var name = button.getAttribute('data-name');
        var form = document.getElementById('deleteForm');
        if (form) form.action = action;
        var body = document.getElementById('deleteModalBody');
        if (body) body.textContent = 'Are you sure you want to delete "' + name + '"? This action cannot be undone.';
    });
});
</script>
@endpush