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
    .skin-type-thumb { width:60px; height:60px; object-fit:cover; border-radius:8px; }
</style>

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex flex-wrap gap-2 justify-content-between align-items-center"
         style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Skin Types</h3>

        @hasPermission('skin-types.create')
        <a href="{{ route('skin-type.create') }}" class="btn btn-primary btn-sm"
           style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Skin Type
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Logo</th>
                    <th class="small text-secondary">Name</th>
                    <th class="small text-secondary d-none d-md-table-cell">Slug</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Description</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($skinTypes as $skinType)
                    <tr>
                        <td>{{ $skinType->id }}</td>
                        <td class="d-none d-sm-table-cell">
                            <img class="skin-type-thumb"
                                 src="{{ $skinType->logo ?? asset('imagePH.png') }}"
                                 alt="Skin Type Logo">
                        </td>
                        <td class="fw-semibold">{{ $skinType->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $skinType->slug }}</td>
                        <td class="d-none d-lg-table-cell text-muted">
                            {{ \Illuminate\Support\Str::limit($skinType->description, 80) }}
                        </td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('skin-type.toggleStatus', $skinType->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           onchange="if(confirm('Are you sure you want to {{ $skinType->status ? 'disable' : 'enable' }} this skin type?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                           {{ $skinType->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('skin-types.edit')
                                <a href="{{ route('skin-type.edit', $skinType->id) }}"
                                   class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">
                                   Edit
                                </a>
                                @endHasPermission

                                @hasPermission('skin-types.destroy')
                                <form action="{{ route('skin-type.destroy', $skinType->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this skin type?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No skin types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($skinTypes, 'links'))
        <div class="px-3 pb-3">
            {{ $skinTypes->withQueryString()->links() }}
        </div>
    @endif
</section>

@endsection