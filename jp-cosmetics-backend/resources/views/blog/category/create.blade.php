@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create Blog Category</h3>
    </div>

    <!-- Form Start -->
    <form action="{{ route('blog.category.store') }}" method="POST" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input required type="text" name="name" id="name" class="form-control" placeholder="Enter Category Name" value="{{ old('name') }}" />
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug (Optional) -->
                    <div class="col-md-6">
                        <label class="form-label">Slug (optional)</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="auto-generated" value="{{ old('slug') }}" />
                        <small class="text-muted">Leave empty to auto-generate from name</small>
                        @error('slug')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Short description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                @hasPermission('blog.category.store')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('blog.category.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
    <!-- Form End -->
</div>

<!-- JS: Slugify -->
<script>
    // Slugify from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const slugify = str => str
        .toString()
        .trim()
        .toLowerCase()
        .replace(/[\s\_]+/g, '-')
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^\-+|\-+$/g, '');

    nameInput.addEventListener('input', () => {
        if (!slugInput.value || slugInput.value === '' || slugInput.dataset.touched !== '1') {
            slugInput.value = slugify(nameInput.value);
        }
    });

    // If user edits slug manually, stop auto-sync
    slugInput.addEventListener('input', () => {
        slugInput.dataset.touched = '1';
    });
</script>

@endsection