@extends('master')

@section('contents')

@php
    $fmt = fn($n) => number_format((float)$n, 2);
    $order = $order ?? null;
@endphp

<style>
    .badge.rounded-pill { border-radius: 999px; padding: .35rem .6rem; font-size: .75rem; }
    .badge-gray  { background:#eef2f7; color:#334155; }
    .badge-green { background:#dcfce7; color:#166534; }
    .badge-blue  { background:#dbeafe; color:#1e40af; }
    .badge-yellow{ background:#fef9c3; color:#854d0e; }
    .badge-red   { background:#fee2e2; color:#991b1b; }
    .badge-purple{ background:#f3e8ff; color:#6b21a8; }
    .badge-slate { background:#e2e8f0; color:#0f172a; }

    .cardish { background:#fff; border-radius:10px; box-shadow:0 1px 8px rgba(16,24,40,.06); }
    .meta-row { display:flex; gap:8px; align-items:center; }
    .meta-row .label_div { min-width:140px; color:#475569; }
    .prod-thumb { width:56px; height:56px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb; }
    .section-hd { font-weight:600; font-size:14px; letter-spacing:.02em; color:#334155; text-transform:uppercase; }
    .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
</style>

<!-- Print-only header (included only in print via cloning) -->
<div id="printHeader" class="print-only-header">
    <p style="margin:0; font-size:25px; font-weight:bold; color: rgb(119, 82, 125);">
        {{ config('app.name', 'My Company Name') }}
    </p>
    <p style="margin:0; font-size:20px">Order #{{ $order?->order_number ?? '—' }}</p>
    <hr style="margin-top:8px; margin-bottom:16px;">
</div>

<div class="rounded shadow-sm">
    <div class="p-3 rounded-top d-flex justify-content-between align-items-center"
         style="background-color: rgb(119, 82, 125); color:#ffffff">
        <div>
            <h3 class="fs-5 m-0 d-flex align-items-center gap-2">
                <span>Order #{{ $order?->order_number ?? '—' }}</span>
                <small class="fw-normal" style="color:rgb(196, 196, 196)">
                    ({{ $order?->created_at?->format('Y-m-d, H:i') ?? '—' }})
                </small>
            </h3>
        </div>
        <div class="d-flex gap-2 align-items-center">
            @php
                $pmBadge = ($order?->payment_method === 'COD') ? 'badge-yellow' : 'badge-blue';
                $psBadge = match($order?->payment_status) {
                    'success'    => 'badge-green',
                    'processing' => 'badge-blue',
                    'pending'    => 'badge-slate',
                    'refunded'   => 'badge-purple',
                    'cancel', 'failed' => 'badge-red',
                    default      => 'badge-gray'
                };
                $stBadge = match($order?->status) {
                    'success'    => 'badge-green',
                    'delivered'  => 'badge-green',
                    'confirm'    => 'badge-blue',
                    'dispatched' => 'badge-yellow',
                    'pending'    => 'badge-slate',
                    'returned'   => 'badge-purple',
                    'cancelled'  => 'badge-red',
                    default      => 'badge-gray'
                };
            @endphp
            <span class="badge rounded-pill {{ $pmBadge }}">{{ $order?->payment_method ?? '—' }}</span>
            <span class="badge rounded-pill {{ $psBadge }}">{{ ucfirst($order?->payment_status ?? '—') }}</span>
            <span class="badge rounded-pill {{ $stBadge }}">{{ ucfirst($order?->status ?? '—') }}</span>

            <div class="d-none d-md-block">
                <a href="{{ route('order.print', $order->id) }}"
                    target="_blank"
                    class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-print"></i> Print
                </a>

                {{-- <button id="printBtn"
                    class="btn btn-sm btn-outline-light"
                    onclick="printSections(['printHeader','shippingSection','productsSection'])"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Print invoice"
                    disabled>
            <i class="fa-solid fa-print"></i> Print
            </button> --}}
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-bottom">
        {{-- Top meta: Customer + Shipping + Payment --}}
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="cardish p-3 h-100">
                    <div class="section-hd mb-2">Customer</div>
                    <div class="meta-row">
                        <div class="label_div">Name</div>
                        <div class="fw-semibold">
                            {{ trim(($order?->customer?->name )) ??  '—' }}
                        </div>
                    </div>
                    <div class="meta-row"><div class="label_div">Email</div><div>{{ $order?->customer?->email ?? '—' }}</div></div>
                    <div class="meta-row"><div class="label_div">Phone</div><div>{{ $order?->customer?->phone ?? '—' }}</div></div>
                    <div class="meta-row">
                        <div class="label_div">Status</div>
                        <div><span class="badge rounded-pill badge-slate">{{ ucfirst($order?->customer?->status ?? 'active') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4" id="shippingSection">
                <div class="cardish p-3 h-100">
                    <div class="section-hd mb-2">Shipping / Receiver</div>
                    <div class="meta-row">
                        <div class="label_div">Receiver</div>
                        <div>
                            {{ $order?->receiver_name
                                ?? trim(($order?->customer?->name ?? ''))
                                ?: '—' }}
                        </div>
                    </div>
                    <div class="meta-row">
                        <div class="label_div">Phone</div>
                        <div>{{ $order?->receiver_phone ?? ($order?->customer?->phone ?? '—') }}</div>
                    </div>
                    <div class="meta-row">
                        <div class="label_div">Address</div>
                        <div>
                            @if($order?->address)
                                <div class="fw-semibold">{{ $order->address->title }}</div>
                                <div>{{ $order->address->address }}</div>
                                <div class="text-muted">
                                    {{ $order->address->area ? $order->address->area.', ' : '' }}{{ $order->address->city }}
                                </div>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    @if($order?->order_note)
                        <div class="meta-row mt-2">
                            <div class="label_div">Order Note</div>
                            <div>{{ $order->order_note }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="cardish p-3 h-100">
                    <div class="section-hd mb-2">Payment</div>
                    <div class="meta-row"><div class="label_div">Method</div><div><span class="badge rounded-pill {{ $pmBadge }}">{{ $order?->payment_method ?? '—' }}</span></div></div>
                    <div class="meta-row"><div class="label_div">Status</div><div><span class="badge rounded-pill {{ $psBadge }}">{{ ucfirst($order?->payment_status ?? '—') }}</span></div></div>
                    <div class="meta-row"><div class="label_div">Channel</div><div>{{ $order?->payment_channel ?? '—' }}</div></div>
                    <div class="meta-row"><div class="label_div">TXN ID</div><div class="mono">{{ $order?->transaction_id ?? '—' }}</div></div>
                    @if($order?->remarks)
                        <div class="meta-row mt-2">
                            <div class="label_div">Remarks</div>
                            <div>{{ $order->remarks }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items table --}}
        <div class="mt-4 cardish p-3" id="productsSection">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="section-hd">Products</div>
                <div class="text-muted small">
                    {{ $order?->details?->count() ?? 0 }} item(s)
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="d-none d-md-table-cell">Attribute</th>
                            <th class="text-end text-nowrap">Qty</th>
                            <th class="text-end text-nowrap">Unit</th>
                            <th class="text-end d-none d-md-table-cell d-print-table-cell text-nowrap">Row Subtotal</th>
                            <th class="text-end d-none d-md-table-cell d-print-table-cell text-nowrap">Row Discount</th>
                            <th class="text-end text-nowrap">Row Payable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order?->details as $details)
                            @php
                                $product  = $details->product ?? null;
                                $product_attribute = $details->productAttribute ?? $details->product_attribute ?? null;
                                $img = $product?->primary_image;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $img }}" class="prod-thumb" alt="product" loading="lazy" referrerpolicy="no-referrer">
                                        <div>
                                            <div class="fw-semibold">{{ $product?->name ?? '—' }}</div>
                                            <div class="text-muted small">#{{ $product?->id ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if($product_attribute)
                                        <div class="small"><span class="text-muted">Name:</span> {{ $product_attribute->attribute_name }}</div>
                                        <div class="small"><span class="text-muted">Value:</span> {{ $product_attribute->attribute_value }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">{{ (int)($details->quantity ?? 0) }}</td>
                                <td class="text-end text-nowrap">{{ $fmt($details->unit_price ?? 0) }}</td>
                                <td class="text-end d-none d-md-table-cell d-print-table-cell text-nowrap">{{ $fmt($details->sub_total ?? 0) }}</td>
                                <td class="text-end d-none d-md-table-cell d-print-table-cell text-nowrap">{{ $fmt($details->discount_amount ?? 0) }}</td>
                                <td class="text-end fw-semibold text-nowrap">{{ $fmt($details->payable ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No items found for this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="row justify-content-end mt-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="cardish p-3">
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Subtotal</div>
                            <div class="fw-semibold">{{ $fmt($order?->sub_total_amount ?? 0) }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Delivery</div>
                            <div class="fw-semibold">{{ $fmt($order?->delivery_charge ?? 0) }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Discount</div>
                            <div class="fw-semibold text-danger">
                                - {{ $fmt( max(0, ($order?->discount_amount ?? 0) - ($order?->discount_from_coupon ?? 0)) ) }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Discount from coupon</div>
                            <div class="fw-semibold text-danger">- {{ $fmt($order?->discount_from_coupon ?? 0) }}</div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <div class="fw-semibold">Total Payable</div>
                            <div class="fw-bold fs-5">{{ $fmt($order?->payable_total ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity timeline (optional) --}}
        @if(!empty($order?->activities) && $order->activities->count())
        <div class="mt-4 cardish p-3">
            <div class="section-hd mb-2">Activity Timeline</div>
            <ul class="list-unstyled m-0">
                @foreach(($order->activities ?? collect())->sortBy('created_at') as $a)
                    <li class="mb-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge rounded-pill badge-slate">{{ ucfirst($a->from_status ?? '—') }}</span>
                                <i class="fa-solid fa-arrow-right mx-1"></i>
                                <span class="badge rounded-pill badge-blue">{{ ucfirst($a->to_status ?? '—') }}</span>
                                @if(!empty($a->remarks))
                                    <span class="ms-2 text-muted">{{ $a->remarks }}</span>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $a->created_at?->format('Y-m-d H:i') ?? '—' }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Actions --}}
        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('order.list') }}" class="btn btn-outline-secondary">
                ← Back to Orders
            </a>
            @hasPermission('orders.edit')
            <a href="{{ route('order.edit', $order->id) }}" class="btn btn-primary">
                Edit Order
            </a>
            @endHasPermission
        </div>
    </div>
</div>

{{-- PRINT CSS + HELPERS --}}
<style>
    .print-only-header { display: none; }
  
    @media print {
      @page { size: A4; margin: 12mm; }
      html, body { height: auto !important; margin: 0 !important; background: #fff !important; }
  
      body > *:not(#printOnly) { display: none !important; }
      #printOnly { display: block !important; }
  
      .cardish, .table, table, tr, td, th, img { page-break-inside: avoid; break-inside: avoid; }
      .cardish { box-shadow: none !important; }
      .print-only-header {
              display: block !important;
              text-align: center;
              margin-bottom: 20px;
          }
      * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      #printBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        }
    }
  </style>
  
  <script>
  
  function printSections(sectionIds) {
  
    const printContainer = document.createElement('div');
    printContainer.id = 'printOnly'; // important: print CSS keeps only this visible
  
    // 2) Clone the requested sections into the print container
    sectionIds.forEach(id => {
      const el = document.getElementById(id);
      if (el) printContainer.appendChild(el.cloneNode(true));
    });
  
    if (!printContainer.children.length) return;
  
    document.body.appendChild(printContainer);
  
    // 3) In the cloned content, force images to load eagerly and wait for them
    const imgs = Array.from(printContainer.querySelectorAll('img'));
    imgs.forEach(img => {
      img.setAttribute('loading', 'eager');
      img.setAttribute('decoding', 'sync');
      img.removeAttribute('referrerpolicy');
    });
  
    const waitForImg = (img) => {
      if (img.complete && img.naturalWidth > 0) return Promise.resolve();
  
      if (img.decode) {
        return img.decode().catch(() => {});
      }
      return new Promise(resolve => {
        const done = () => resolve();
        img.addEventListener('load', done, { once: true });
        img.addEventListener('error', done, { once: true });
      });
    };
  
    Promise.allSettled(imgs.map(waitForImg)).finally(() => {
  
      requestAnimationFrame(() => {
        window.print();
        setTimeout(() => printContainer.remove(), 0);
      });
    });
  }
  </script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tooltip = new bootstrap.Tooltip(document.getElementById('printBtn'));

        window.addEventListener('load', () => {
            const btn = document.getElementById('printBtn');
            btn.disabled = false;
            btn.setAttribute('title', 'Print Invoice');
            tooltip.dispose();
            new bootstrap.Tooltip(btn);
        });
    });
    </script>
  

@endsection
