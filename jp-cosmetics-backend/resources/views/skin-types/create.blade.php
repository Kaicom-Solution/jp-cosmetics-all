@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create Skin Type</h3>
    </div>

    <form action="{{ route('skin-type.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <!-- Logo Upload -->
            <div class="col-md-3 text-center">
                <p class="text-muted">Recommendation Image Size: 645×335 px</p>
                <div class="position-relative border rounded bg-light d-flex align-items-center justify-content-center h-75">
                    <img id="imagePreview" src="{{ asset('imagePH.png') }}" alt="Logo Preview"
                         class="w-100 h-100 object-fit-cover rounded"/>
                    <button type="button" id="removeImage"
                            class="btn btn-sm btn-light bg-white border position-absolute top-0 end-0 rounded-circle">×</button>
                </div>
                <input type="file" accept="image/*" id="fileInput" name="logo" class="d-none"/>
                <label for="fileInput" class="btn btn-dark mt-3 w-100">Choose Logo</label>
                @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-9 mt-10">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-4">
                        <label class="form-label">Skin Type Name</label>
                        <input required type="text" name="name" id="name" class="form-control"
                               placeholder="Enter Skin Type Name" value="{{ old('name') }}"/>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Description -->
                    <div class="col-md-8">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control"
                                  placeholder="Short description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @hasPermission('skin-types.store')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById("fileInput").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById("imagePreview").src = e.target.result;
        reader.readAsDataURL(file);
    });

    document.getElementById("removeImage").addEventListener("click", function() {
        document.getElementById("imagePreview").src = "{{ asset('imagePH.png') }}";
        document.getElementById("fileInput").value = "";
    });
</script>

@endsection