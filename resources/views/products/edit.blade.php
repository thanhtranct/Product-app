@extends('layout')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Product: {{ $product->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $product->name) }}"
                               placeholder="Enter product name">
                        @error('name')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter product description (optional)">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($product->image_path)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-thumbnail"
                                 id="currentImage"
                                 style="max-width: 300px;">
                        </div>
                    </div>
                    @endif

                    <!-- Image Upload (New) -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload New Image (Optional)</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">
                            Leave empty to keep current image. Accepted formats: JPEG, PNG, WEBP. Max size: 2MB
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Image Preview -->
                    <div class="mb-3" id="newImagePreview" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <div>
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" id="removePreview">
                                Remove New Image
                            </button>
                        </div>
                    </div>

                    <!-- External API Data (Read-only) -->
                    @if($product->external_api_data)
                    <div class="mb-3">
                        <label class="form-label">External API Data</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $product->external_api_data }}" 
                               readonly
                               disabled>
                        <div class="form-text">This data was fetched from external API when product was created.</div>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                Update Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const newImagePreview = document.getElementById('newImagePreview');
    const currentImage = document.getElementById('currentImage');
    const removePreviewBtn = document.getElementById('removePreview');

    // Image preview when file selected
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                newImagePreview.style.display = 'block';
                if (currentImage) {
                    currentImage.style.opacity = '0.5';
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove preview
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener('click', function() {
            imageInput.value = '';
            newImagePreview.style.display = 'none';
            if (currentImage) {
                currentImage.style.opacity = '1';
            }
        });
    }
</script>
@endpush