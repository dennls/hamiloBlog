@extends('layouts.app')

@section('content')
    <div class="content-header" bis_skin_checked="1">
        <div class="container-fluid" bis_skin_checked="1">
            <div class="row mb-2" bis_skin_checked="1">
                <div class="col-sm-6" bis_skin_checked="1">
                    <h1 class="m-0">Bienvenido</h1>
                </div>
                <div class="col-sm-6" bis_skin_checked="1">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        {{-- <li class="breadcrumb-item active">Starter Page</li> --}}
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content" bis_skin_checked="1">
        <div class="container-fluid" bis_skin_checked="1">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('You are logged in!') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
