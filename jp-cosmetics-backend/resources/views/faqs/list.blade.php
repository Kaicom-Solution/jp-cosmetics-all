@extends('master')

@section('contents')

<style>
    .toggle-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .toggle-switch-lg .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .order-badge {
        background: #6c757d;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
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
        <h3 class="text-md m-0">FAQs</h3>
        @hasPermission('faq.create')
        <a href="{{ route('faq.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create FAQ
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary">Question</th>
                    <th class="small text-secondary d-none d-md-table-cell">Answer</th>
                    <th class="small text-secondary text-center">Order</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td>{{ $faq->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $faq->question }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <div class="text-muted small">{{ Str::limit($faq->answer, 100) }}</div>
                        </td>
                        <td class="text-center">
                            <span class="order-badge">{{ $faq->order }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('faq.toggleStatus', $faq->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="toggleFaq{{ $faq->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $faq->status ? 'disable' : 'enable' }} this FAQ?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $faq->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('faq.edit')
                                <a href="{{ route('faq.edit', $faq->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No FAQs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($faqs, 'links'))
        <div class="px-3 pb-3">
            {{ $faqs->links() }}
        </div>
    @endif
</section>

@endsection