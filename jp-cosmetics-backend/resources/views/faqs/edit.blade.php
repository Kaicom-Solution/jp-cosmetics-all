@extends('master')

@section('contents')

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Edit FAQ</h3>
    </div>

    <!-- Form -->
    <form action="{{ route('faq.update', $faq->id) }}" method="POST" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <div class="row g-3">
                    <!-- Question -->
                    <div class="col-md-12">
                        <label class="form-label">Question <span class="text-danger">*</span></label>
                        <input required type="text" name="question" class="form-control" placeholder="Enter FAQ Question" value="{{ old('question', $faq->question) }}" />
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Answer -->
                    <div class="col-md-12">
                        <label class="form-label">Answer <span class="text-danger">*</span></label>
                        <textarea required name="answer" rows="6" class="form-control" placeholder="Enter FAQ Answer...">{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="col-md-12">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" min="0" class="form-control" placeholder="e.g. 1" value="{{ old('order', $faq->order) }}" />
                        <small class="text-muted">FAQs are displayed in ascending order</small>
                        @error('order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Status -->
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $faq->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        <small class="text-muted">You can also toggle status from the list page</small>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @hasPermission('faq.update')
                <div class="row g-3 mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('faq.list') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                    </div>
                </div>
                @endHasPermission
            </div>
        </div>
    </form>
</section>

@endsection