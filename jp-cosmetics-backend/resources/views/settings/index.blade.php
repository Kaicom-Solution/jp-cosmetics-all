@extends('master')

@section('contents')
<style>
    .tox-tinymce {
        border-radius: 0 0 8px 8px !important;
    }
    .text-end{
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
</style>
<div class="rounded shadow-sm">
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Privacy Policy</h3>
    </div>

    <div class="bg-white p-4 rounded-bottom">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Privacy Policy -->
        {{-- <div class="mb-4 p-3 border rounded"> --}}
            {{-- <h2 class="mb-3"><strong>Privacy Policy</strong></h2> --}}
            <form action="{{ route('settings.update', 'privacy_policy') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    {{-- <label class="form-label">Description</label> --}}
                    <textarea name="description" rows="6" class="form-control" placeholder="Enter Privacy Policy content here...">{{ $settings['privacy_policy']->description ?? '' }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @hasPermission('settings.update')
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">Update Privacy Policy</button>
                </div>
                @endHasPermission
            </form>
        {{-- </div> --}}
    </div>


    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Cookie Policy</h3>
    </div>

    <div class="bg-white p-4 rounded-bottom">
        <form action="{{ route('settings.update', 'cookie_policy') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                {{-- <label class="form-label">Description</label> --}}
                <textarea name="description" rows="6" class="form-control" placeholder="Enter Cookie Policy content here...">{{ $settings['cookie_policy']->description ?? '' }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            @hasPermission('settings.update')
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Update Cookie Policy</button>
            </div>
            @endHasPermission
        </form>
    </div>


    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Terms & Conditions</h3>
    </div>
    <div class="bg-white p-4 rounded-bottom">
        <form action="{{ route('settings.update', 'terms_conditions') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <textarea name="description" rows="6" class="form-control" placeholder="Enter Terms & Conditions content here...">{{ $settings['terms_conditions']->description ?? '' }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            @hasPermission('settings.update')
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Update Terms & Conditions</button>
            </div>
            @endHasPermission
        </form>
    </div>

    <!-- Returns & Exchanges -->
    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Returns & Exchanges</h3>
    </div>
    <div class="bg-white p-4 rounded-bottom">
        <form action="{{ route('settings.update', 'returns_exchanges') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                {{-- <label class="form-label">Description</label> --}}
                <textarea name="description" rows="6" class="form-control" placeholder="Enter Returns & Exchanges policy here...">{{ $settings['returns_exchanges']->description ?? '' }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            @hasPermission('settings.update')
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Update Returns & Exchanges</button>
            </div>
            @endHasPermission
        </form>
    </div>

        <!-- Shipping & Delivery -->

    <div class="p-3 rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Shipping & Delivery</h3>
    </div>
    <div class="bg-white p-4 rounded-bottom">
        <form action="{{ route('settings.update', 'shipping_delivery') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                {{-- <label class="form-label">Description</label> --}}
                <textarea name="description" rows="6" class="form-control" placeholder="Enter Shipping & Delivery information here...">{{ $settings['shipping_delivery']->description ?? '' }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            @hasPermission('settings.update')
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Update Shipping & Delivery</button>
            </div>
            @endHasPermission
        </form>
    </div>
</div>

<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({
    selector: 'textarea[name="description"]',
    height: 300,
    menubar: false,
    license_key: 'gpl',
    plugins: 'link image lists code table fullscreen',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
});
</script>

@endsection