
@extends('master')

@section('contents')

<style>
    .analytics-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .section-header {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        border-radius: 10px 10px 0 0;
        padding: 14px 20px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .daterange-wrap { position: relative; }
    .daterange-wrap .bi-calendar3 { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6c757d; }
    #daterange {
        padding-left: 32px;
        cursor: pointer;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 8px;
        min-width: 230px;
        height: 36px;
        font-size: 13px;
    }
    .attr-badge {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .product-name-cell { font-weight: 600; color: #1a202c; }
    .qty-pill {
        background: #fff3e0;
        color: #e65100;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="analytics-card mb-4">
    <div class="section-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-box-seam-fill fs-5"></i>
            <h3 class="m-0 fs-6 fw-semibold">Total Sales by Products</h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="daterange-wrap">
                <i class="bi bi-calendar3"></i>
                <input type="text" id="daterange" name="daterange" readonly />
            </div>
            <a href="#" id="exportBtn"
               class="btn btn-sm btn-light fw-semibold d-flex align-items-center gap-1"
               style="border-radius:8px; font-size:13px;">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive p-3">
        <table id="productSalesTable" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th class="small">#</th>
                    <th class="small">Product Name</th>
                    <th class="small">Attribute</th>
                    <th class="small">Variant</th>
                    <th class="small text-end">Qty Sold</th>
                    <th class="small text-end">Unit Price</th>
                    <th class="small text-end">Sub Total</th>
                    <th class="small text-end">Discount</th>
                    <th class="small text-end">Net Payable</th>
                    <th class="small text-center">Orders</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {

    let startDate = moment().startOf('month');
    let endDate   = moment().endOf('month');

    function formatDisplay(s, e) { return s.format('DD MMM YYYY') + ' – ' + e.format('DD MMM YYYY'); }
    function formatMoney(v) {
        return '৳ ' + parseFloat(v || 0).toLocaleString('en-BD', { minimumFractionDigits: 2 });
    }

    $('#daterange').daterangepicker({
        startDate: startDate,
        endDate:   endDate,
        opens:     'left',
        ranges: {
            'Today':      [moment(), moment()],
            'This Week':  [moment().startOf('week'),  moment().endOf('week')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
            'This Year':  [moment().startOf('year'),  moment().endOf('year')],
        },
        locale: { format: 'DD MMM YYYY', applyLabel: 'Apply', cancelLabel: 'Cancel' }
    }, function(s, e) {
        startDate = s; endDate = e;
        $('#daterange').val(formatDisplay(s, e));
        table.ajax.reload();
        updateExportLink();
    });

    $('#daterange').val(formatDisplay(startDate, endDate));

    function updateExportLink() {
        $('#exportBtn').attr('href',
            '{{ route("analytics.product-sales.export") }}'
            + '?start_date=' + startDate.format('YYYY-MM-DD')
            + '&end_date='   + endDate.format('YYYY-MM-DD')
        );
    }
    updateExportLink();

    const table = $('#productSalesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:  '{{ route("analytics.product-sales.data") }}',
            data: function (d) {
                d.start_date = startDate.format('YYYY-MM-DD');
                d.end_date   = endDate.format('YYYY-MM-DD');
            }
        },
        columns: [
            { data: null, render: (d, t, r, meta) => meta.row + meta.settings._iDisplayStart + 1, orderable: false },
            { data: 'product_name', render: v => `<span class="product-name-cell">${v}</span>` },
            { data: 'attribute_name',  render: v => `<span class="attr-badge">${v}</span>` },
            { data: 'attribute_value', render: v => `<span class="attr-badge" style="background:#e3f2fd;color:#1565c0;">${v}</span>` },
            { data: 'total_qty', render: v => `<span class="qty-pill">${parseInt(v).toLocaleString()}</span>`, className: 'text-end' },
            { data: 'avg_unit_price', render: v => formatMoney(v), className: 'text-end' },
            { data: 'total_sub_total', render: v => formatMoney(v), className: 'text-end' },
            { data: 'total_discount',  render: v => formatMoney(v), className: 'text-end' },
            { data: 'total_payable',   render: v => `<strong>${formatMoney(v)}</strong>`, className: 'text-end' },
            { data: 'total_orders', render: v => `<span class="badge bg-secondary">${v}</span>`, className: 'text-center' },
        ],
        order: [[4, 'desc']],
        pageLength: 15,
        language: {
            processing: '<div class="d-flex justify-content-center py-3"><div class="spinner-border text-success" role="status"></div></div>',
            emptyTable: '<div class="text-center text-muted py-4"><i class="bi bi-box fs-2 d-block mb-2"></i>No product sales data for selected range.</div>'
        }
    });

});
</script>
@endpush