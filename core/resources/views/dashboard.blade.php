@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <style>
        @keyframes rotateAnimation {
            /* Rotate from 0 degrees to 360 degrees */
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .spinning-icon {
            /* Apply the rotation animation */
            animation: rotateAnimation 1s infinite linear;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row mx-auto justify-content-center">
            <x-messages/>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <a href="{{route('scrap.it')}}" class="scrap-btn btn btn-success">Scrap Jobs <i class="d-none spinning-icon las la-circle-notch"></i></a>

                        @if($searching > 0)
                            <div class="search-wrapper my-4">
                                <h3 class="search-counter">{{$searching}}</h3>
                                <h5 class="text-capitalize">jobs left to search</h5>
                            </div>
                        @endif

                        <div class="{{$searching > 0 ? 'd-none' : ''}} times my-5">
                            <p>
                                <small>{{$crawled?->created_at->format('h:m A | d-M-Y')}}</small>
                            </p>
                            <p>
                                <small>{{$crawled?->created_at->diffForHumans(['parts' => 4])}}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let search_amount = `{{$searching}}`;

        let jobInterval = null;
        if (search_amount > 0) {
            jobInterval = setInterval(function () {
                searchLeft();
            }, 10000);
        }

        function searchLeft() {
            axios.get(`{{route('jobs.all.searching.left')}}`).then(response => {
                if (response.data.status === 'success') {
                    document.querySelector('.spinning-icon').classList.remove('d-none');
                    document.querySelector('.search-counter').innerText = response.data.searching;

                    if (response.data.searching === 0) {
                        document.querySelector('.spinning-icon').classList.add('d-none');
                        document.querySelector('.search-wrapper').remove();
                        document.querySelector('.times').classList.remove('d-none');
                        clearInterval(jobInterval);
                    }
                }
            });
        }
    </script>
@endsection
