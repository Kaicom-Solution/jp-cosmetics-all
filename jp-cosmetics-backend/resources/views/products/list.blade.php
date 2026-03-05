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
    .prod-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
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
    <div class="py-2.5 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2"
        style="background-color: rgb(119, 82, 125); color:#ffffff">

        <h3 class="text-md m-0">Products</h3>

        <div class="d-flex align-items-center gap-2 flex-nowrap">
            <form method="GET" action="{{ route('product.list') }}" class="d-flex align-items-center gap-2 mb-0">
                <div class="input-group input-group-sm" style="min-width:260px">
                    <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search by name or category...">
                    <button type="submit" class="btn btn-light fw-semibold" style="color:rgb(69, 85, 203)">
                        <i class="fa-solid fa-filter me-1"></i> Apply
                    </button>
                </div>

                @if(request('search'))
                    <a href="{{ route('product.list') }}" class="btn btn-sm btn-outline-danger">
                        Reset
                    </a>
                @endif
            </form>

            @hasPermission('product.create')
                <a href="{{ route('product.create') }}" 
                    class="btn btn-primary btn-sm ms-2"
                    style="background-color: hsla(227, 64%, 37%, 0.879); white-space:nowrap;">
                    <i class="fa-solid fa-plus"></i> Create Product
                </a>
            @endHasPermission
        </div>
    </div>


    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">SL</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Image</th>
                    <th class="small text-secondary">Name</th>
                    <th class="small text-secondary d-none d-md-table-cell">Category</th>
                    <th class="small text-secondary d-none d-md-table-cell">Brand</th>
                    <th class="small text-secondary d-none d-md-table-cell">Product Type</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td class="d-none d-sm-table-cell">
                            <img class="prod-thumb" src="{{ $prod->primary_image }}" alt="Product">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $prod->name }}</div>
                            @if($prod->description)
                                <div class="text-muted small">{{ Str::limit($prod->description, 60) }}</div>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">{{ $prod->category->name ?? '—' }}</td>
                        <td class="d-none d-md-table-cell">{{ $prod->brand->name ?? '—' }}</td>
                        <td class="d-none d-md-table-cell">{{ $prod->product_type }}</td>
                        <td class="d-none d-md-table-cell">{{ $prod->status }}</td>
                    
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('product.view', $prod->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-green-500 text-white hover:bg-green-600 shadow-md transition">View</a>
                                @hasPermission('products.edit')
                                <a href="{{ route('product.edit', $prod->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                                
                                {{-- Duplicate Button --}}
                                <form action="{{ route('product.duplicate', $prod->id) }}" method="POST" 
                                    onsubmit="return confirm('Duplicate this product?')">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-yellow-500 text-white hover:bg-yellow-600 shadow-md transition">
                                        Duplicate
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($products, 'links'))
        <div class="px-3 pb-3">
            {{ $products->links() }}
        </div>
    @endif
</section>

@endsection
