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

    th a:hover {
        color: #031a33;
        text-decoration: underline;
    }

    .hover-effect:hover .badge {
        background-color: rgb(29, 37, 43);
        cursor: pointer;
        text-decoration: underline;
    }

    .toggle-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .toggle-switch-lg .form-check-input:checked {
        background-color: #28a745;
        /* green for active */
        border-color: #28a745;
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

<style>
    .cat-thumb{width:60px;height:60px;object-fit:cover;border-radius:8px}
</style>

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Categories</h3>
        @hasPermission('category.create')
        <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Category
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">SL</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Image</th>
                    <th class="small text-secondary">Name</th>
                    <th class="small text-secondary d-none d-md-table-cell">Slug</th>
                    <th class="small text-secondary d-none d-md-table-cell">Parent</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Sequence</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Products</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Popular</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td class="d-none d-sm-table-cell">
                            <img class="cat-thumb" src="{{ $cat->image }}" alt="Category">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $cat->name }}</div>
                            @if($cat->description)
                                <div class="text-muted small">{{ Str::limit($cat->description, 60) }}</div>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">{{ $cat->slug }}</td>
                        <td class="d-none d-md-table-cell">{{ $cat->parent?->name ?? '—' }}</td>
                        <td class="text-center d-none d-lg-table-cell">{{ $cat->sequence }}</td>
                        <td class="text-center d-none d-lg-table-cell">{{ $cat->products_count ?? 0 }}</td>
                        <td class="d-none d-sm-table-cell">
                            @if($cat->is_popular)
                                <span class="badge bg-success pl-4 pr-4 pt-2 pb-2">Yes</span>
                            @else
                                <span class="badge bg-secondary pl-4 pr-4 pt-2 pb-2">No</span>
                            @endif  
                        </td>
                        <td class="d-none d-sm-table-cell py-1 ">
                            <form action="{{ route('category.toggleStatus', $cat->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="toggleExam{{ $cat->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $cat->status ? 'disable' : 'enable' }} this category?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $cat->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('categories.edit')
                                <a href="{{ route('category.edit', $cat->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($categories, 'links'))
        <div class="px-3 pb-3">
            {{ $categories->links() }}
        </div>
    @endif
</section>

@endsection
