@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create Notice</h3>
    </div>

    <!-- Form Start -->
    <form action="{{ route('notice.store') }}" method="POST" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input required type="text" name="title" class="form-control" placeholder="Enter Notice Title" value="{{ old('title') }}" />
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Enter notice description...">{{ old('description') }}</textarea>
                        <small class="text-muted">This will be shown in the header slider</small>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="row g-3 mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> 
                            <strong>Note:</strong> Notice will be created as Active. You can set it as Live from the list page.
                        </div>
                    </div>
                </div> --}}

                <!-- Hidden field to set status = 1 always -->
                <input type="hidden" name="status" value="1">

                @hasPermission('notice.store')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('notice.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
    <!-- Form End -->
</div>

<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({
    selector: 'textarea[name="description"]',
    height: 300,
    menubar: false,
    license_key: 'gpl',
    plugins: 'link image lists code table fullscreen',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
});
</script>

@endsection