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

{{-- Flash Messages --}}
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
  .brand-thumb { width:60px; height:60px; object-fit:cover; border-radius:8px; }
</style>

<section class="w-100 bg-white rounded overflow-hidden shadow">
  <!-- Header -->
  <div class="p-2 px-4 d-flex flex-wrap gap-2 justify-content-between align-items-center"
       style="background-color: rgb(119, 82, 125); color:#ffffff">
    <h3 class="text-md m-0">Brands</h3>

    @hasPermission('brands.create')
    <a href="{{ route('brand.create') }}" class="btn btn-primary btn-sm"
       style="background-color: hsla(227, 64%, 37%, 0.879);">
      <i class="fa-solid fa-plus"></i> Create Brand
    </a>
    @endHasPermission
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th class="small text-secondary">SL</th>
          <th class="small text-secondary d-none d-sm-table-cell">Logo</th>
          <th class="small text-secondary">Name</th>
          <th class="small text-secondary d-none d-md-table-cell">Slug</th>
          <th class="small text-secondary d-none d-lg-table-cell">Description</th>
          <th class="small text-secondary d-none d-lg-table-cell">Status</th>
          <th class="small text-secondary">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($brands as $brand)
          <tr>
            {{-- <td>{{ $brand->id }}</td> --}}
            <td>
              {{ $brands->firstItem() + $loop->index }}
          </td>
            <td class="d-none d-sm-table-cell">
              <img class="brand-thumb"
                   src="{{ $brand->logo }}"
                   alt="Brand Logo">
            </td>
            <td class="fw-semibold">{{ $brand->name }}</td>
            <td class="d-none d-md-table-cell">{{ $brand->slug }}</td>
            <td class="d-none d-lg-table-cell text-muted">
              {{ \Illuminate\Support\Str::limit($brand->description, 80) }}
            </td>
            <td class="d-none d-sm-table-cell py-1 ">
                <form action="{{ route('brand.toggleStatus', $brand->id) }}" method="POST">
                    @csrf
                    <div class="form-check form-switch toggle-switch-lg flex">
                        <input class="form-check-input"
                            type="checkbox"
                            id="toggleExam{{ $brand->id }}"
                            onchange="if(confirm('Are you sure you want to {{ $brand->status ? 'disable' : 'enable' }} this brand?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                            {{ $brand->status ? 'checked' : '' }}>
                    </div>
                </form>
            </td>
            <td>
              <div class="d-flex gap-2">
                @hasPermission('brands.edit')
                <a href="{{ route('brand.edit', $brand->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                @endHasPermission

                @hasPermission('brands.destroy')
                <form action="{{ route('brand.destroy', $brand->id) }}" method="POST"
                    onsubmit="return confirm('Delete this brand?');">
                  @csrf
                  @method('DELETE')
                  <button class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-red-500 text-white hover:bg-red-600 shadow-md transition" type="submit">Delete</button>
              </form>
                @endHasPermission
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No brands found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if(method_exists($brands, 'links'))
    <div class="px-3 pb-3">
      {{ $brands->withQueryString()->links() }}
    </div>
  @endif
</section>

@endsection
