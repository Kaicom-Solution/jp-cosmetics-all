@extends('master')

@section('contents')

<style>
    .statusbtn-active { background-color: hsla(215, 94%, 42%, 0.879); cursor: pointer; }
    .statusbtn-disabled { background-color: hsla(358, 92%, 33%, 0.879); cursor: pointer; }
    th a:hover { color: #031a33; text-decoration: underline; }
    .hover-effect:hover .badge { background-color: rgb(29, 37, 43); cursor: pointer; text-decoration: underline; }

    .badge-pill { border-radius: 999px; padding: .35rem .6rem; font-size: .75rem; }
    .badge-gray { background:#eef2f7; color:#334155; }
    .badge-green { background:#dcfce7; color:#166534; }
    .badge-blue  { background:#dbeafe; color:#1e40af; }
    .badge-yellow{ background:#fef9c3; color:#854d0e; }
    .badge-red   { background:#fee2e2; color:#991b1b; }
    .badge-purple{ background:#f3e8ff; color:#6b21a8; }
    .badge-slate { background:#e2e8f0; color:#0f172a; }
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

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="py-3 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Orders</h3>
    </div>

    <!-- Optional: quick filters -->
    <div class="px-4 pt-3 pb-4">
        <form method="GET" action="" class="row g-2">
            <div class="col-12 col-md-3">
                <input type="text" name="search_value" value="{{ request('search_value') }}" class="form-control" placeholder="Search order # / customer">
            </div>
            <div class="col-6 col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">Payment Status</option>
                    @foreach(['pending','processing','cancel','failed','success','refunded'] as $ps)
                        <option value="{{ $ps }}" {{ request('payment_status')===$ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Order Status</option>
                    @foreach(['pending','confirm','dispatched','delivered','cancelled','returned','success'] as $st)
                        <option value="{{ $st }}" {{ request('status')===$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="payment_method" class="form-select">
                    <option value="">Method</option>
                    @foreach(['COD','online'] as $pm)
                        <option value="{{ $pm }}" {{ request('payment_method')===$pm ? 'selected' : '' }}>{{ $pm }}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-4 col-md-2">
                    <button class="btn btn-outline-secondary w-100">Filter</button>
                </div>
                <div class="col-3 col-md-1">
                    <a href="{{ route('order.list') }}" class="btn btn-outline-danger w-100">Reset</a>
                </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">SL</th>
                    <th class="small text-secondary">Order #</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Customer</th>
                    <th class="small text-secondary text-end">Subtotal</th>
                    <th class="small text-secondary text-end d-none d-xl-table-cell">Delivery</th>
                    <th class="small text-secondary text-end d-none d-xl-table-cell">Discount</th>
                    <th class="small text-secondary text-end">Payable</th>
                    <th class="small text-secondary d-none d-xl-table-cell">Payment</th>
                    <th class="small text-secondary">Status</th>
                    <th class="small text-secondary d-none d-lg-table-cell">Date</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                            <td>{{ $o->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $o->order_number }}</div>
                            <div class="text-muted small d-lg-none">
                                {{ $o->customer->name ?? '' }} 
                            </div>
                        </td>
                        <td class="d-none d-lg-table-cell">
                            {{ $o->customer->name ?? '' }}
                            <div class="text-muted small">{{ $o->customer->email ?? '' }}</div>
                        </td>

                        <td class="text-end">{{ number_format($o->sub_total_amount, 2) }}</td>
                        <td class="text-end d-none d-xl-table-cell">{{ number_format($o->delivery_charge, 2) }}</td>
                        <td class="text-end d-none d-xl-table-cell">{{ number_format($o->discount_amount ?? 0, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($o->payable_total ?? 0, 2) }}</td>

                        <td class="d-none d-xl-table-cell">
                            @php
                                $pm = $o->payment_method;
                                $ps = $o->payment_status;
                                $pmBadge = $pm === 'COD' ? 'badge-yellow' : 'badge-blue';
                                $psBadge = match($ps) {
                                    'success'   => 'badge-green',
                                    'processing'=> 'badge-blue',
                                    'pending'   => 'badge-slate',
                                    'refunded'  => 'badge-purple',
                                    'cancel', 'failed' => 'badge-red',
                                    default     => 'badge-gray'
                                };
                            @endphp
                            <span class="badge badge-pill {{ $pmBadge }}">{{ $pm }}</span>
                            <span class="badge badge-pill {{ $psBadge }}">{{ ucfirst($ps) }}</span>
                        </td>

                        <td>
                            @php
                                $st = $o->status;
                                $stBadge = match($st) {
                                    'success'   => 'badge-green',
                                    'delivered' => 'badge-green',
                                    'confirm'   => 'badge-blue',
                                    'dispatched'=> 'badge-yellow',
                                    'pending'   => 'badge-slate',
                                    'returned'  => 'badge-purple',
                                    'cancelled' => 'badge-red',
                                    default     => 'badge-gray'
                                };
                            @endphp
                            <span class="badge badge-pill {{ $stBadge }}">{{ ucfirst($st) }}</span>
                        </td>

                        <td class="d-none d-lg-table-cell">
                            <div class="text-nowrap">{{ $o->created_at?->format('Y-m-d') }}</div>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('orders.show')
                                <a href="{{ route('order.show', $o->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-green-500 text-white hover:bg-green-600 shadow-md transition">View</a>
                                @endHasPermission

                                @hasPermission('orders.edit')
                                <a href="{{ route('order.edit', $o->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($orders, 'links'))
        <div class="px-3 pb-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</section>

@endsection
