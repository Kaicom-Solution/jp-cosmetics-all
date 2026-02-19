@extends('master')

@section('contents')

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Edit Notice</h3>
    </div>

    <!-- Form -->
    <form action="{{ route('notice.update', $notice->id) }}" method="POST" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input required type="text" name="title" class="form-control" placeholder="Enter Notice Title" value="{{ old('title', $notice->title) }}" />
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Enter notice description...">{{ old('description', $notice->description) }}</textarea>
                        <small class="text-muted">This will be shown in the header slider</small>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Status -->
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $notice->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        <small class="text-muted">Set notice as live from the list page using radio button</small>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @hasPermission('notice.update')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('notice.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
</section>

@endsection