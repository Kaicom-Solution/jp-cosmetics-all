@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create Promotion Popup</h3>
    </div>

    <!-- Form Start -->
    <form action="{{ route('promotion-popup.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <!-- Image Upload -->
            <div class="col-md-3 text-center">
                <p class="text-muted bg-green-300">Recommendation Image Size: 800*600 px</p>
                <div class="position-relative border rounded bg-light d-flex align-items-center justify-content-center h-75">
                    <img id="imagePreview" src="{{ asset('imagePH.png') }}" alt="Display Image" class="w-100 h-100 object-fit-cover rounded"/>
                    <button type="button" id="removeImage" class="btn btn-sm btn-light bg-white border position-absolute top-0 end-0 rounded-circle">×</button>
                </div>
                <input type="file" accept="image/*" id="fileInput" name="image" class="d-none" />
                <label for="fileInput" class="btn btn-dark mt-3 w-100">Choose Image</label>
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-9 mt-10">
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input required type="text" name="title" class="form-control" placeholder="Enter Popup Title" value="{{ old('title') }}" />
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Enter popup description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Button Text -->
                    <div class="col-md-6">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-control" placeholder="e.g. Shop Now" value="{{ old('button_text') }}" />
                        @error('button_text')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Button URL -->
                    <div class="col-md-6">
                        <label class="form-label">Button URL</label>
                        <input type="text" name="button_url" class="form-control" placeholder="https://example.com" value="{{ old('button_url') }}" />
                        @error('button_url')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <!-- Hidden field to set status = 1 always -->
                <input type="hidden" name="status" value="1">

                @hasPermission('promotion-popup.store')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('promotion-popup.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
    <!-- Form End -->
</div>

<!-- JS: Image preview + remove -->
<script>
    // Image preview
    document.getElementById("fileInput").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById("imagePreview").src = e.target.result;
        reader.readAsDataURL(file);
    });

    // Remove image
    document.getElementById("removeImage").addEventListener("click", function() {
        document.getElementById("imagePreview").src = "{{ asset('imagePH.png') }}";
        document.getElementById("fileInput").value = "";
    });
</script>

@endsection