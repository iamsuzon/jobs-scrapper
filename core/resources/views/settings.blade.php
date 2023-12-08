@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row mx-auto justify-content-center">
            <div class="col-md-4">
                <x-messages/>

                <div class="card">
                    <div class="card-body text-center p-5 d-flex gap-3">
                        <a href="{{route('settings.database.update')}}" class="scrap-btn btn btn-primary">Upgrade Database</a>
                        <a href="{{route('settings.cache.clear')}}" class="scrap-btn btn btn-success">Clear Cache</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
