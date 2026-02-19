@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create FAQ</h3>
    </div>

    <!-- Form Start -->
    <form action="{{ route('faq.store') }}" method="POST" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Question -->
                    <div class="col-md-12">
                        <label class="form-label">Question <span class="text-danger">*</span></label>
                        <input required type="text" name="question" class="form-control" placeholder="Enter FAQ Question" value="{{ old('question') }}" />
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Answer -->
                    <div class="col-md-12">
                        <label class="form-label">Answer <span class="text-danger">*</span></label>
                        <textarea required name="answer" rows="6" class="form-control" placeholder="Enter FAQ Answer...">{{ old('answer') }}</textarea>
                        @error('answer')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="col-md-12">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" min="0" class="form-control" placeholder="e.g. 1 (auto-calculated if empty)" value="{{ old('order') }}" />
                        <small class="text-muted">Leave empty to auto-assign the next order number</small>
                        @error('order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Hidden field to set status = 1 always -->
                <input type="hidden" name="status" value="1">

                @hasPermission('faq.store')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('faq.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
    <!-- Form End -->
</div>

@endsection