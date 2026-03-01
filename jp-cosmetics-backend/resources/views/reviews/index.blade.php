@extends('master')

@section('contents')

<style>
    .statusbtn-active {
        background-color: hsla(215, 94%, 42%, 0.879);
        cursor: pointer;
    }

    .statusbtn-disabled {
        background-color: hsla(358, 92%, 33%, 0.879);
        cursor: pointer;
    }

    .toggle-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .toggle-switch-lg .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .star-rating {
        color: #f59e0b;
        font-size: 1rem;
        letter-spacing: 2px;
    }

    .star-empty {
        color: #d1d5db;
    }
</style>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Reviews</h3>
        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark">
                Total: {{ $reviews->total() }}
            </span>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">#</th>
                    <th class="small text-secondary">Customer</th>
                    <th class="small text-secondary">Product</th>
                    <th class="small text-secondary">Order</th>
                    <th class="small text-secondary text-center">Rating</th>
                    <th class="small text-secondary d-none d-md-table-cell">Review</th>
                    <th class="small text-secondary text-center">Approved</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>

                        {{-- Customer --}}
                        <td>
                            <div class="fw-semibold">{{ $review->order->customer->name ?? '—' }}</div>
                            <div class="text-muted small">{{ $review->order->customer->phone ?? '' }}</div>
                        </td>

                        {{-- Product --}}
                        <td>
                            <div class="fw-semibold">{{ $review->product->name ?? '—' }}</div>
                        </td>

                        {{-- Order --}}
                        <td>
                            <span class="badge bg-secondary">{{ $review->order->order_number ?? '—' }}</span>
                        </td>

                        {{-- Rating --}}
                        <td class="text-center">
                            @if($review->rating)
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ★
                                        @else
                                            <span class="star-empty">★</span>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted small">{{ $review->rating }}/5</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Review Text --}}
                        <td class="d-none d-md-table-cell">
                            @if($review->review)
                                <div class="text-muted small">{{ Str::limit($review->review, 80) }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Approve Toggle --}}
                        <td class="text-center py-1">
                            <form action="{{ route('reviews.toggleApprove', $review->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg d-flex justify-content-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        onchange="if(confirm('Are you sure you want to {{ $review->is_approved_review ? 'disapprove' : 'approve' }} this review?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $review->is_approved_review ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>

                        {{-- Action --}}
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('reviews.destroy')
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 shadow-md transition">
                                        Delete
                                    </button>
                                </form>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($reviews, 'links'))
        <div class="px-3 pb-3">
            {{ $reviews->links() }}
        </div>
    @endif
</section>

@endsection