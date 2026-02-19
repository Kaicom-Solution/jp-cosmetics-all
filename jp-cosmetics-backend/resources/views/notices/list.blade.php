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

    .toggle-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .toggle-switch-lg .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .live-radio {
        width: 1.5rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .live-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
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

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Notices</h3>
        @hasPermission('notice.create')
        <a href="{{ route('notice.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Notice
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary">Title</th>
                    <th class="small text-secondary d-none d-md-table-cell">Description</th>
                    <th class="small text-secondary text-center">Live</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notices as $notice)
                    <tr>
                        <td>{{ $notice->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $notice->title }}</div>
                            @if($notice->is_live)
                                <span class="badge bg-danger live-badge mt-1">
                                    <i class="fa-solid fa-broadcast-tower"></i> LIVE
                                </span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($notice->description)
                                <div class="text-muted small">{{ Str::limit($notice->description, 80) }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('notice.setLive', $notice->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <div class="form-check d-flex justify-content-center">
                                    <input type="radio" 
                                           name="live_notice_{{ $notice->id }}" 
                                           class="live-radio form-check-input" 
                                           {{ $notice->is_live ? 'checked' : '' }}
                                           onclick="if(confirm('🔴 Set this notice as LIVE?\n\nThis will be shown in the header slider.\nAny other live notice will be automatically disabled.')) { this.form.submit(); } else { event.preventDefault(); return false; }">
                                </div>
                            </form>
                        </td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('notice.toggleStatus', $notice->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="toggleNotice{{ $notice->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $notice->status ? 'disable' : 'enable' }} this notice?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $notice->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('notice.edit')
                                <a href="{{ route('notice.edit', $notice->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No notices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($notices, 'links'))
        <div class="px-3 pb-3">
            {{ $notices->links() }}
        </div>
    @endif
</section>

@endsection