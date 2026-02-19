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
        background: linear-gradient(135deg, #1a6fad 0%, #0d4e82 100%);
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
    .summary-box {
        border-radius: 10px;
        padding: 14px 20px;
        min-width: 150px;
        flex: 1 1 150px;
    }
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-pending    { background:#fff3cd; color:#856404; }
    .status-confirm    { background:#cff4fc; color:#055160; }
    .status-dispatched { background:#d1ecf1; color:#0c5460; }
    .status-delivered  { background:#d4edda; color:#155724; }
    .status-cancelled  { background:#f8d7da; color:#721c24; }
    .status-returned   { background:#e2e3e5; color:#383d41; }
    .status-success    { background:#d4edda; color:#155724; }
    .payment-success   { background:#d4edda; color:#155724; }
    .payment-pending   { background:#fff3cd; color:#856404; }
    .payment-failed    { background:#f8d7da; color:#721c24; }
    .payment-processing{ background:#cff4fc; color:#055160; }
    .payment-refunded  { background:#e2e3e5; color:#383d41; }
</style>

<!-- DateRangePicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="analytics-card mb-4">
    <div class="section-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bar-chart-line-fill fs-5"></i>
            <h3 class="m-0 fs-6 fw-semibold">Total Sales Report</h3>
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

    <!-- Summary Cards -->
    <div class="px-4 py-3 border-bottom bg-light" id="summaryArea">
        <div class="d-flex flex-wrap gap-3">
            <div class="summary-box bg-white border text-center">
                <div class="text-muted small mb-1">Total Orders</div>
                <div class="fw-bold fs-5 text-dark" id="s-orders">—</div>
            </div>
            <div class="summary-box" style="background:#eef4ff; border:1px solid #c5d8ff;">
                <div class="text-muted small mb-1">Sub Total</div>
                <div class="fw-bold fs-5 text-primary" id="s-subtotal">—</div>
            </div>
            <div class="summary-box" style="background:#fff8e1; border:1px solid #ffe082;">
                <div class="text-muted small mb-1">Delivery Charges</div>
                <div class="fw-bold fs-5 text-warning" id="s-delivery">—</div>
            </div>
            <div class="summary-box" style="background:#fce4ec; border:1px solid #f48fb1;">
                <div class="text-muted small mb-1">Total Discount</div>
                <div class="fw-bold fs-5 text-danger" id="s-discount">—</div>
            </div>
            <div class="summary-box" style="background:#e8f5e9; border:1px solid #a5d6a7;">
                <div class="text-muted small mb-1">Net Payable</div>
                <div class="fw-bold fs-5 text-success" id="s-payable">—</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive p-3">
        <table id="totalSalesTable" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th class="small">#</th>
                    <th class="small">Date</th>
                    <th class="small">Order No.</th>
                    <th class="small">Customer</th>
                    <th class="small">Phone</th>
                    <th class="small text-end">Sub Total (৳)</th>
                    <th class="small text-end">Delivery (৳)</th>
                    <th class="small text-end">Discount (৳)</th>
                    <th class="small text-end">Payable (৳)</th>
                    <th class="small">Payment</th>
                    <th class="small">Status</th>
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
        return '৳ ' + parseFloat(v || 0).toLocaleString('en-BD', {minimumFractionDigits: 2});
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
            '{{ route("analytics.total-sales.export") }}'
            + '?start_date=' + startDate.format('YYYY-MM-DD')
            + '&end_date='   + endDate.format('YYYY-MM-DD')
        );
    }
    updateExportLink();

    const table = $('#totalSalesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:  '{{ route("analytics.total-sales.data") }}',
            data: function (d) {
                d.start_date = startDate.format('YYYY-MM-DD');
                d.end_date   = endDate.format('YYYY-MM-DD');
            }
        },
        columns: [
            { data: null, render: (d, t, r, meta) => meta.row + meta.settings._iDisplayStart + 1, orderable: false },
            { data: 'created_at', render: d => moment(d).format('DD MMM YY') },
            { data: 'order_number' },
            { data: 'customer_name', defaultContent: '—' },
            { data: 'phone', defaultContent: '—' },
            { data: 'sub_total_amount',  render: v => formatMoney(v), className: 'text-end' },
            { data: 'delivery_charge',   render: v => formatMoney(v), className: 'text-end' },
            { data: null, render: r => formatMoney((r.discount_amount||0) + (r.discount_from_coupon||0)), className: 'text-end' },
            { data: 'payable_total',     render: v => `<strong>${formatMoney(v)}</strong>`, className: 'text-end' },
            { data: 'payment_status', render: s => `<span class="badge-status payment-${s}">${s}</span>` },
            { data: 'status', render: s => `<span class="badge-status status-${s}">${s}</span>` },
        ],
        order: [[1, 'desc']],
        pageLength: 15,
        language: {
            processing: '<div class="d-flex justify-content-center py-3"><div class="spinner-border text-primary" role="status"></div></div>',
            emptyTable: '<div class="text-center text-muted py-4"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No sales data for selected range.</div>'
        },
        drawCallback: function(settings) {
            const s = settings.json && settings.json.summary;
            if (!s) return;
            $('#s-orders').text(parseInt(s.total_orders || 0).toLocaleString());
            $('#s-subtotal').text(formatMoney(s.total_subtotal));
            $('#s-delivery').text(formatMoney(s.total_delivery));
            $('#s-discount').text(formatMoney(s.total_discount));
            $('#s-payable').text(formatMoney(s.total_payable));
        }
    });

});
</script>
@endpush