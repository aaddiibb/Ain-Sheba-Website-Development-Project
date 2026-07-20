@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Mobile sidebar toggle --}}
    <div class="d-md-none p-2 border-bottom">
        <button class="btn btn-sm btn-outline-secondary w-100" type="button"
                data-bs-toggle="collapse" data-bs-target="#citizenSidebarMobile">
            <i class="bi bi-list me-1"></i>Menu
        </button>
    </div>
    <div class="collapse d-md-none" id="citizenSidebarMobile">
        @include('citizen._sidebar', ['user' => auth()->user()])
    </div>

    <div class="row">
        <div class="col-md-3 p-0 d-none d-md-block">
            @include('citizen._sidebar', ['user' => auth()->user()])
        </div>
        <div class="col-md-9 p-4">
            @yield('citizen-content')
        </div>
    </div>
</div>
@endsection
