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
        background: linear-gradient(135deg, #7752a0 0%, #5a3d7a 100%);
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
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
        text-align: center !important;
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
    .payment-refunded  { background:#e2e3e5; color:#383d41; }
    .payment-cod       { background:#e8d5f7; color:#5a1fa8; }
    .payment-online    { background:#d0e9ff; color:#0a4f99; }
</style>

<!-- DateRangePicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="analytics-card mb-4">
    <div class="section-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill fs-5"></i>
            <h3 class="m-0 fs-6 fw-semibold">Customer Order Report</h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Date Range Picker -->
            <div class="daterange-wrap">
                <i class="bi bi-calendar3"></i>
                <input type="text" id="daterange" name="daterange" readonly />
            </div>
            <!-- CSV Export -->
            <a href="#" id="exportBtn"
               class="btn btn-sm btn-light fw-semibold d-flex align-items-center gap-1"
               style="border-radius:8px; font-size:13px;">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary strip -->
    <div class="px-4 py-2 border-bottom bg-slate-50 d-flex flex-wrap gap-4" id="summaryStrip" style="display:none!important">
    </div>

    <!-- Table -->
    <div class="table-responsive p-3">
        <table id="customerOrderTable" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr class="text-center">
                    <th class="small">#</th>
                    <th class="small">Date</th>
                    <th class="small">Customer</th>
                    <th class="small">Phone</th>
                    <th class="small">Order No.</th>
                    <th class="small">Order Status</th>
                    <th class="small">Payment</th>
                    <th class="small">Method</th>
                    <th class="small text-end">Payable</th>
                </tr>
            </thead>
            <tbody class="text-center"></tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
<!-- Moment + DateRangePicker -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {

    // ── Date Range Picker ──────────────────────────────
    let startDate = moment().startOf('month');
    let endDate   = moment().endOf('month');

    function formatDisplay(start, end) {
        return start.format('DD MMM YYYY') + ' – ' + end.format('DD MMM YYYY');
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
    }, function(start, end) {
        startDate = start;
        endDate   = end;
        $('#daterange').val(formatDisplay(start, end));
        table.ajax.reload();
        updateExportLink();
    });

    $('#daterange').val(formatDisplay(startDate, endDate));

    // ── Export Link ────────────────────────────────────
    function updateExportLink() {
        const url = '{{ route("analytics.customer-orders.export") }}'
            + '?start_date=' + startDate.format('YYYY-MM-DD')
            + '&end_date='   + endDate.format('YYYY-MM-DD');
        $('#exportBtn').attr('href', url);
    }
    updateExportLink();

    // ── DataTable ──────────────────────────────────────
    const table = $('#customerOrderTable').DataTable({
        processing:  true,
        serverSide:  true,
        ajax: {
            url:  '{{ route("analytics.customer-orders.data") }}',
            data: function (d) {
                d.start_date = startDate.format('YYYY-MM-DD');
                d.end_date   = endDate.format('YYYY-MM-DD');
            }
        },
        columns: [
            { data: null, render: (d, t, r, meta) => meta.row + meta.settings._iDisplayStart + 1, orderable: false },
            { data: 'created_at', render: d => moment(d).format('DD MMM YY') },
            { data: 'customer_name', render: (d, t, r) =>
                `<div class='fw-semibold'>${d ?? '—'}</div>` },
            { data: 'customer_phone', defaultContent: '—' },
            { data: 'order_number' },
            { data: 'status', render: s => `<span class="badge-status status-${s}">${s}</span>` },
            { data: 'payment_status', render: s => `<span class="badge-status payment-${s}">${s}</span>` },
            { data: 'payment_method', render: m => `<span class="badge-status payment-${m.toLowerCase()}">${m}</span>` },
            { data: 'payable_total', render: v => `<span class='fw-semibold'>৳ ${parseFloat(v).toLocaleString('en-BD', {minimumFractionDigits:2})}</span>` },
        ],
        order: [[1, 'desc']],
        pageLength: 15,
        language: {
            processing: '<div class="d-flex justify-content-center py-3"><div class="spinner-border text-primary" role="status"></div></div>',
            emptyTable: '<div class="text-center text-muted py-4"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No orders found for selected range.</div>'
        },
        drawCallback: function(settings) {
            const json = settings.json;
            if (json && json.summary) buildSummary(json.summary);
        }
    });

    function buildSummary(s) { /* customer report has no server-side summary */ }

});
</script>
@endpush