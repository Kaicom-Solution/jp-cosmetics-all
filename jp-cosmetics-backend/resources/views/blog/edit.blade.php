@extends('master')

@section('contents')

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Edit Blog</h3>
    </div>

    <!-- Form -->
    <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <!-- Image -->
            <div class="col-md-3 text-center">
                <p class="text-muted bg-green-300">Recommendation Image Size: 1200*630 px</p>
                <div class="position-relative border rounded bg-light d-flex align-items-center justify-content-center h-75">
                    <img id="imagePreview"
                         src="{{ $blog->image ?? asset('imagePH.png') }}"
                         alt="Blog Image" class="w-100 h-100 object-fit-cover rounded" />
                    <button type="button" id="removeImage" class="btn btn-sm btn-light bg-white border position-absolute top-0 end-0 rounded-circle">×</button>
                </div>
                <input type="file" accept="image/*" id="fileInput" name="image" class="d-none" />
                <label for="fileInput" class="btn btn-dark mt-3 w-100">Choose Image</label>
                @error('image') 
                    <div class="text-danger">{{ $message }}</div> 
                @enderror
            </div>

            <!-- Fields -->
            <div class="col-md-9 mt-10">
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input required type="text" name="title" id="title" class="form-control" placeholder="Enter Blog Title" value="{{ old('title', $blog->title) }}" />
                        @error('title') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select required name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)old('category_id', $blog->category_id) === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Slug -->
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="auto-generated" value="{{ old('slug', $blog->slug) }}" />
                        <small class="text-muted">Leave empty to auto-generate from title</small>
                        @error('slug') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- Author -->
                    <div class="col-md-6">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" placeholder="Author Name" value="{{ old('author', $blog->author) }}" />
                        @error('author') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Is Featured -->
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $blog->is_featured) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Blog
                            </label>
                        </div>
                        @error('is_featured') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- Status -->
                    {{-- <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $blog->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        @error('status') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div> --}}
                </div>

                <div class="row g-3 mt-3">
                    <!-- Short Description -->
                    <div class="col-md-6">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" rows="2" class="form-control" placeholder="Brief description for listing...">{{ old('short_description', $blog->short_description) }}</textarea>
                        @error('short_description') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="col-md-6">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea required name="content" rows="8" class="form-control" placeholder="Full blog content...">{{ old('content', $blog->content) }}</textarea>
                        @error('content') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                

                @hasPermission('blog.update')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('blog.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
</section>

<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({
    selector: 'textarea[name="short_description"], textarea[name="content"]',
    height: 300,
    menubar: false,
    license_key: 'gpl',
    plugins: 'link image lists code table fullscreen',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
});
</script>

<!-- JS: Image preview/remove and slugify -->
<script>
    // Image preview
    document.getElementById("fileInput").addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => document.getElementById("imagePreview").src = ev.target.result;
        reader.readAsDataURL(file);
    });

    // Remove image
    document.getElementById("removeImage").addEventListener("click", function() {
        document.getElementById("imagePreview").src = "{{ asset('imagePH.png') }}";
        document.getElementById("fileInput").value = "";
    });

    // Slugify from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const slugify = str => str
        .toString()
        .trim()
        .toLowerCase()
        .replace(/[\s\_]+/g, '-')
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^\-+|\-+$/g, '');

    titleInput.addEventListener('input', () => {
        if (!slugInput.value || slugInput.value === '' || slugInput.dataset.touched !== '1') {
            slugInput.value = slugify(titleInput.value);
        }
    });

    // If user edits slug manually, stop auto-sync
    slugInput.addEventListener('input', () => {
        slugInput.dataset.touched = '1';
    });
</script>

@endsection