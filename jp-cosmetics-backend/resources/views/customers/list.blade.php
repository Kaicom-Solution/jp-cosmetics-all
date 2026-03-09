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

    .customer-thumb {
        width: 60px;
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

<style>
    .customer-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
</style>

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="py-3 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Customers</h3>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">SL</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Image</th>
                    <th class="small text-secondary">Name</th>
                    <th class="small text-secondary d-none d-md-table-cell">Email</th>
                    <th class="small text-secondary d-none d-md-table-cell">Phone</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customers->firstItem() + $loop->index }}</td>
                        <td class="d-none d-sm-table-cell">
                            <img class="customer-thumb" src="{{ $customer->image}}" alt="Customer">
                        </td>
                        <td class="d-none d-md-table-cell">{{ $customer->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $customer->email }}</td>
                        <td class="d-none d-md-table-cell">{{ $customer->phone }}</td>
                        <td class="d-none d-md-table-cell">{{ $customer->status }}</td>
                    
                        <td>
                            <div class="d-inline-flex align-items-center gap-2">
                        
                                {{-- View Orders --}}
                                <a href="{{ route('customer.orders', $customer->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2.5 rounded-lg text-xs font-medium bg-sky-500 text-white hover:bg-sky-600 shadow-md transition"
                                   title="View Orders">
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="sr-only">View Orders</span>
                                </a>
                        
                                {{-- Edit --}}
                                @hasPermission('customers.edit')
                                <a href="{{ route('customer.edit', $customer->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2.5 rounded-lg text-xs font-medium bg-green-500 text-white hover:bg-green-600 shadow-md transition"
                                   title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="sr-only">Edit</span>
                                </a>
                                @endHasPermission
                        
                                {{-- Delete --}}
                                <form action="{{ route('customer.destroy', $customer->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-2.5 rounded-lg text-xs font-medium bg-red-500 text-white hover:bg-red-600 shadow-md transition"
                                            title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                        <span class="sr-only">Delete</span>
                                    </button>
                                </form>
                        
                            </div>
                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($customers, 'links'))
        <div class="px-3 pb-3">
            {{ $customers->links() }}
        </div>
    @endif
</section>

@endsection
