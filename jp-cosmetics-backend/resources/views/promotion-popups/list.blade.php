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

    .popup-thumb {
        width: 80px;
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

<section class="w-100 bg-white rounded overflow-hidden shadow">
    <!-- Header -->
    <div class="p-2 px-4 d-flex justify-content-between align-items-center" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="text-md m-0">Promotion Popups</h3>
        @hasPermission('promotion-popup.create')
        <a href="{{ route('promotion-popup.create') }}" class="btn btn-primary btn-sm" style="background-color: hsla(227, 64%, 37%, 0.879);">
            <i class="fa-solid fa-plus"></i> Create Popup
        </a>
        @endHasPermission
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="small text-secondary">ID</th>
                    <th class="small text-secondary d-none d-sm-table-cell">Image</th>
                    <th class="small text-secondary">Title</th>
                    <th class="small text-secondary d-none d-md-table-cell">Button</th>
                    <th class="small text-secondary text-center">Live</th>
                    <th class="small text-secondary text-center d-none d-lg-table-cell">Status</th>
                    <th class="small text-secondary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popups as $popup)
                    <tr>
                        <td>{{ $popup->id }}</td>
                        <td class="d-none d-sm-table-cell">
                            @if($popup->image)
                                <img class="popup-thumb" src="{{ $popup->image }}" alt="Popup Image">
                            @else
                                <div class="popup-thumb bg-light d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $popup->title }}</div>
                 
                            @if($popup->is_live)
                                <span class="badge bg-danger live-badge mt-1">
                                    <i class="fa-solid fa-star"></i> LIVE
                                </span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($popup->button_text)
                                <span class="badge bg-primary">{{ $popup->button_text }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('promotion-popup.setLive', $popup->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <div class="form-check d-flex justify-content-center">
                                    <input type="radio" 
                                           name="live_popup_{{ $popup->id }}" 
                                           class="live-radio form-check-input" 
                                           {{ $popup->is_live ? 'checked' : '' }}
                                           onclick="if(confirm('Set this popup as LIVE?\n\nThis popup will be shown on the website.\nAny other live popup will be automatically disabled.')) { this.form.submit(); } else { event.preventDefault(); return false; }">
                                </div>
                            </form>
                        </td>
                        <td class="d-none d-sm-table-cell py-1">
                            <form action="{{ route('promotion-popup.toggleStatus', $popup->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch toggle-switch-lg flex justify-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="togglePopup{{ $popup->id }}"
                                        onchange="if(confirm('Are you sure you want to {{ $popup->status ? 'disable' : 'enable' }} this popup?')) { this.form.submit(); } else { this.checked = !this.checked; }"
                                        {{ $popup->status ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @hasPermission('promotion-popup.edit')
                                <a href="{{ route('promotion-popup.edit', $popup->id) }}" class="flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 shadow-md transition">Edit</a>
                                @endHasPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No promotion popups found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($popups, 'links'))
        <div class="px-3 pb-3">
            {{ $popups->links() }}
        </div>
    @endif
</section>

@endsection