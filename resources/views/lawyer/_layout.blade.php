@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-0">
            @include('lawyer._sidebar', ['user' => auth()->user()])
        </div>
        <div class="col-md-9 p-4">
            @yield('lawyer-content')
        </div>
    </div>
</div>
@endsection
