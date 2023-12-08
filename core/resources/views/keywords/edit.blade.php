@extends('layouts.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <x-messages/>
                <div class="card">
                    <div class="card-header"> Keywords </div>

                    <div class="card-body">
                        <form method="POST" action="{{route('keywords.edit', $keyword->id.rand(9,9999))}}">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="keyword_group_name">Keyword Group Name</label>
                                <input class="form-control" id="keyword_group_name" type="text" name="keyword_group_name">
                            </div>

                            @php
                                $keywordArr = [];
                                foreach(json_decode($keyword->keywords) as $item) {
                                    $keywordArr[] = $item;
                                }
                            @endphp

                            <label for="keywords" class="form-label">Add Keywords</label>
                            @foreach($keywordArr ?? [] as $keyword)
                                <div class="keyword-group form-group d-flex justify-content-between gap-2 mb-3">
                                    <input class="form-control keywords" id="keywords" type="text" name="keywords[]" value="{{$keyword}}">
                                    <a href="javascript:void(0)" class="btn-plus btn btn-success">+</a>
                                    <a href="javascript:void(0)" class="btn-subtract btn btn-danger">-</a>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let plus = document.querySelectorAll('.btn-plus');

        plus.forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                let keyGroup = document.querySelectorAll('.keyword-group');
                const keywordGroupsExceptLast = Array.from(keyGroup).slice(0, -1);

                console.log(keywordGroupsExceptLast);
                keywordGroupsExceptLast.forEach(element => {
                    let markup = element.cloneNode(true);
                    markup.querySelector('.keywords').value = '';
                    document.querySelector('.keyword-group').after(markup);
                });
            })
        })
    </script>
@endsection
