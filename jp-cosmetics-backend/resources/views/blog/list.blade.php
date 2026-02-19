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

    .blog-thumb {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
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
        <h3 class="text-md m-0">Blogs</h3>
        @hasPermission('blog.create')
        <a href="{{ route('blog.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Blog
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Image</th>
                    <th class="small text-secondary">Title</th>
                    <th class="small text-secondary d-none d-md-table-cell">Category</th>
                    <th class="small text-secondary d-none d-md-table-cell">Author</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Featured</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td class="d-none d-sm-table-cell">
                            @if($blog->image)
                                <img class="blog-thumb" src="{{ $blog->image }}" alt="Blog Image">
                            @else
                                <div class="blog-thumb bg-light d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $blog->title }}</div>
                           
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-info">{{ $blog->category?->name ?? 'N/A' }}</span>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $blog->author ?? '—' }}</td>
                        <td class="d-none d-sm-table-cell">
                            <form action="{{ route('blog.toggleFeatured', $blog->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @if($blog->is_featured)
                                    <button type="submit" class="badge bg-success border-0" style="cursor: pointer;" onclick="return confirm('Remove from featured?')">
                                        <i class="fa-solid fa-star"></i> Yes
                                    </button>
                                @else
                                    <button type="submit" class="badge bg-secondary border-0" style="cursor: pointer;" onclick="return confirm('Mark as featured?')">
                                        <i class="fa-regular fa-star"></i> No
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('blog.toggleStatus', $blog->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="toggleBlog{{ $blog->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $blog->status ? 'disable' : 'enable' }} this blog?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $blog->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('blog.edit')
                                <a href="{{ route('blog.edit', $blog->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No blogs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($blogs, 'links'))
        <div class="px-3 pb-3">
            {{ $blogs->links() }}
        </div>
    @endif
</section>

@endsection