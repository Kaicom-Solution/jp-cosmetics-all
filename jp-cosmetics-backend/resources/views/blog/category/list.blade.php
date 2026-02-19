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

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Blog Categories</h3>
        @hasPermission('blog.category.create')
        <a href="{{ route('blog.category.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Category
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary">Name</th>
                    <th class="small text-secondary d-none d-md-table-cell">Slug</th>
                    {{-- <th class="small text-secondary d-none d-md-table-cell">Description</th> --}}
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Blogs</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $category->name }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $category->slug }}</td>
                        {{-- <td class="d-none d-md-table-cell">
                            @if($category->description)
                                <div class="text-muted small">{{ Str::limit($category->description, 60) }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td> --}}
                        <td class="text-center d-none d-lg-table-cell">{{ $category->blogs_count ?? 0 }}</td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('blog.category.toggleStatus', $category->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="toggleCategory{{ $category->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $category->status ? 'disable' : 'enable' }} this category?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $category->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('blog.category.edit')
                                <a href="{{ route('blog.category.edit', $category->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No blog categories found.</td>
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