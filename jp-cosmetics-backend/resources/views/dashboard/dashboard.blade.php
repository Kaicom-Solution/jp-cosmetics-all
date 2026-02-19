@extends('master')

@section('contents')

{{-- DateRangePicker CSS --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .dash-header {
        background: linear-gradient(135deg, #7752a0 0%, #4a3060 100%);
        border-radius: 10px 10px 0 0;
        padding: 14px 20px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ── KPI Cards ── */
    .kpi-card {
        border-radius: 14px;
        padding: 20px 22px;
        border: 1px solid #e9ecef;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform .15s, box-shadow .15s;
        height: 100%;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.09);
    }
    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
        color: #1a1a2e;
    }
    .kpi-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        margin-bottom: 4px;
    }
    .kpi-sub {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }

    /* Section divider label */
    .section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9ca3af;
        margin: 20px 0 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    /* DateRange Picker input */
    .drp-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .drp-wrap .bi-calendar3 {
        position: absolute;
        left: 10px;
        color: rgba(255,255,255,0.7);
        font-size: 14px;
        pointer-events: none;
    }
    #dashDaterange {
        padding: 6px 12px 6px 30px;
        cursor: pointer;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        min-width: 220px;
        height: 34px;
        font-size: 13px;
        color: #fff;
        /* backdrop-filter: blur(4px); */
    }
    #dashDaterange::placeholder { color: rgba(255,255,255,0.6); }

    /* Chart card */
    .chart-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
</style>

<section class="w-100 bg-white rounded overflow-hidden shadow">

    {{-- Header with DateRangePicker --}}
    <div class="dash-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-grid-1x2-fill fs-5"></i>
            <h3 class="m-0 fs-6 fw-semibold">Dashboard</h3>
        </div>
        <div class="drp-wrap">
            <i class="bi bi-calendar3"></i>
            <input type="text" id="dashDaterange" readonly placeholder="Select date range" />
        </div>
    </div>

    <div class="p-4">

        {{-- ── Row 1: Date-filtered KPIs ── --}}
        <div class="section-label">Filtered by date range</div>

        <div class="row g-3">

            {{-- Total Orders --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Total Orders</div>
                            <div class="kpi-value" id="kpi-totalOrders">{{ $totalOrders }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#ede9fe;">
                            <i class="bi bi-bag-check" style="color:#7c3aed;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">Within selected range</div>
                </div>
            </div>

            {{-- Pending Orders --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Pending Orders</div>
                            <div class="kpi-value text-warning" id="kpi-pendingOrders">{{ $pendingOrders }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#fef3c7;">
                            <i class="bi bi-hourglass-split" style="color:#d97706;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub text-warning" style="font-size:11px;">Needs attention</div>
                </div>
            </div>

            {{-- Total Sales --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Total Sales</div>
                            <div class="kpi-value text-success" id="kpi-totalSales">
                                ৳ {{ number_format($totalSales, 0) }}
                            </div>
                        </div>
                        <div class="kpi-icon" style="background:#dcfce7;">
                            <i class="bi bi-currency-dollar" style="color:#16a34a;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">Confirmed & paid orders</div>
                </div>
            </div>

            {{-- Delivered Orders --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Delivered</div>
                            <div class="kpi-value text-primary" id="kpi-deliveredOrders">{{ $deliveredOrders }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#dbeafe;">
                            <i class="bi bi-truck" style="color:#2563eb;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">Successfully delivered</div>
                </div>
            </div>

        </div>

        {{-- ── Row 2: Static KPIs ── --}}
        <div class="section-label">Overall statistics</div>

        <div class="row g-3">

            {{-- Total Customers --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Customers</div>
                            <div class="kpi-value" style="color:#0891b2;">{{ $totalCustomers }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#cffafe;">
                            <i class="bi bi-people-fill" style="color:#0891b2;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <a href="{{ route('customer.list') }}" class="text-decoration-none small" style="color:#0891b2;">View customers →</a>
                    </div>
                </div>
            </div>

            {{-- Active Products --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Active Products</div>
                            <div class="kpi-value" style="color:#0284c7;">{{ $activeProducts }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#e0f2fe;">
                            <i class="bi bi-box-seam-fill" style="color:#0284c7;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <a href="{{ route('product.list') }}" class="text-decoration-none small" style="color:#0284c7;">View products →</a>
                    </div>
                </div>
            </div>

            {{-- Active Coupons --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Active Coupons</div>
                            <div class="kpi-value" style="color:#be185d;">{{ $activeCoupons }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#fce7f3;">
                            <i class="bi bi-ticket-perforated-fill" style="color:#be185d;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <a href="{{ route('coupon.list') }}" class="text-decoration-none small" style="color:#be185d;">View coupons →</a>
                    </div>
                </div>
            </div>

            {{-- Low Stock --}}
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-label">Low Stock (≤ {{ $lowStockThreshold }})</div>
                            <div class="kpi-value text-danger">{{ $lowStockCount }}</div>
                        </div>
                        <div class="kpi-icon" style="background:#fee2e2;">
                            <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;"></i>
                        </div>
                    </div>
                    <div class="kpi-sub text-danger" style="font-size:11px;">
                        Out of stock: {{ $outOfStockCount }}
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Chart ── --}}
        <div class="section-label">Sales & Orders trend</div>

        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-semibold text-dark">Sales & Orders</div>
                    <small class="text-muted" id="chartRangeLabel">
                        {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
                    </small>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <span class="d-flex align-items-center gap-1" style="font-size:12px;">
                        <span style="width:12px;height:3px;background:#7c3aed;display:inline-block;border-radius:2px;"></span>
                        Sales (৳)
                    </span>
                    <span class="d-flex align-items-center gap-1" style="font-size:12px;">
                        <span style="width:12px;height:3px;background:#f59e0b;display:inline-block;border-radius:2px;"></span>
                        Orders
                    </span>
                </div>
            </div>
            <div style="height: 280px; position:relative;">
                <canvas id="salesChart"></canvas>
                <div id="chartLoader" class="position-absolute top-50 start-50 translate-middle d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function () {

    // ── Initial data passed safely from PHP ───────────
    const initialData = {
        labels:    @json($chartLabels),
        sales:     @json($salesByDay),
        orders:    @json($ordersByDay),
        startDate: '{{ $startDate->format('Y-m-d') }}',
        endDate:   '{{ $endDate->format('Y-m-d') }}',
        kpi: {
            totalOrders:    {{ $totalOrders }},
            pendingOrders:  {{ $pendingOrders }},
            totalSales:     {{ $totalSales }},
            deliveredOrders:{{ $deliveredOrders }},
        }
    };

    // ── Moment dates (parsed from YYYY-MM-DD string) ──
    let startDate = moment(initialData.startDate, 'YYYY-MM-DD');
    let endDate   = moment(initialData.endDate,   'YYYY-MM-DD');

    // ── Build Chart ───────────────────────────────────
    const ctx   = document.getElementById('salesChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: initialData.labels,
            datasets: [
                {
                    label: 'Sales (৳)',
                    data: initialData.sales,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: initialData.orders,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.07)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.yAxisID === 'y'
                                ? ' ৳ ' + parseFloat(ctx.raw).toLocaleString('en-BD', {minimumFractionDigits: 2})
                                : ' ' + ctx.raw + ' orders';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, maxRotation: 45 }
                },
                y: {
                    position: 'left',
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { size: 11 },
                        callback: function(v) {
                            return '৳' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v);
                        }
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11 }, stepSize: 1 }
                }
            }
        }
    });

    // ── DateRangePicker ───────────────────────────────
    function formatDisplay(s, e) {
        return s.format('DD MMM YYYY') + ' \u2013 ' + e.format('DD MMM YYYY');
    }

    $('#dashDaterange').daterangepicker({
        startDate: startDate,
        endDate:   endDate,
        opens:     'left',
        autoUpdateInput: true,
        ranges: {
            'Today':      [moment(), moment()],
            'This Week':  [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
            'This Month': [moment().startOf('month'),   moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year':  [moment().startOf('year'),    moment().endOf('year')],
        },
        locale: {
            format:        'DD MMM YYYY',
            applyLabel:    'Apply',
            cancelLabel:   'Cancel',
            customRangeLabel: 'Custom Range',
        }
    }, function (s, e) {
        startDate = s;
        endDate   = e;
        $('#dashDaterange').val(formatDisplay(s, e));
        reloadDashboard(s, e);
    });

    // Set initial display value
    $('#dashDaterange').val(formatDisplay(startDate, endDate));

    // ── AJAX reload via dedicated JSON endpoint ────────
    function reloadDashboard(s, e) {
        $('#chartLoader').removeClass('d-none');

        $.ajax({
            url: '{{ route("dashboard.data") }}',
            method: 'GET',
            data: {
                start_date: s.format('YYYY-MM-DD'),
                end_date:   e.format('YYYY-MM-DD'),
            },
            success: function (res) {
                // KPIs
                animateVal('#kpi-totalOrders',     res.kpi.totalOrders);
                animateVal('#kpi-pendingOrders',   res.kpi.pendingOrders);
                animateVal('#kpi-deliveredOrders', res.kpi.deliveredOrders);
                $('#kpi-totalSales').text('\u09F3 ' + parseInt(res.kpi.totalSales).toLocaleString());

                // Range label
                $('#chartRangeLabel').text(s.format('DD MMM YYYY') + ' \u2013 ' + e.format('DD MMM YYYY'));

                // Chart
                chart.data.labels           = res.chart.labels;
                chart.data.datasets[0].data = res.chart.sales;
                chart.data.datasets[1].data = res.chart.orders;
                chart.update();

                $('#chartLoader').addClass('d-none');
            },
            error: function () {
                $('#chartLoader').addClass('d-none');
            }
        });
    }

    function animateVal(selector, newVal) {
        $(selector).fadeOut(80, function () {
            $(this).text(parseInt(newVal).toLocaleString()).fadeIn(120);
        });
    }

});
</script>
@endpush