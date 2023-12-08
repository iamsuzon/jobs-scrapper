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
                        <form method="POST" action="{{route('keywords')}}">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="keyword_group_name">Keyword Group Name</label>
                                <input class="form-control mt-2" id="keyword_group_name" type="text" name="keyword_group_name" value="{{old('keyword_group_name')}}">
                            </div>

                            <div x-data="{fields: [{id: 1, value: ''}], itemValue: []}">
                                <label for="keywords" class="form-label">Add Keywords</label>
                                <template x-for="(field, index) in fields" :key="index">
                                    <div class="form-group d-flex justify-content-between gap-2 mb-3">
                                        <input class="form-control" id="keywords" type="text" name="keywords[]" x-model="itemValue[index]">
                                        <a href="#" class="btn btn-success" @click.prevent="fields.push({id: index + 1, value: itemValue[index]})">+</a>
                                        <a href="#" class="btn btn-danger" @click.prevent="fields.pop()">-</a>
                                    </div>
                                </template>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row mx-auto justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Keyword List ({{$keywords->total()}})</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Keyword Name</th>
                                <th>Keywords</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($keywords as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->identifier}}</td>
                                    <td class="text-capitalize">
                                        @foreach(json_decode($item->keywords, true) ?? [] as $keyword)
                                            <span class="badge bg-primary" style="font-size: 15px">{{$keyword}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{route('keywords.status', $item->id.rand(9,999999))}}" class="btn {{$item->status ? 'btn-success' : 'btn-danger'}} btn-sm">
                                            {{$item->status ? 'Activated' : 'Inactivated'}}
                                        </a>
                                    </td>
                                    <td>
                                        <a class="edit-btn btn btn-primary btn-sm" href="{{route('keywords.edit', $item->id.rand(9,999999))}}">Edit</a>
                                        <a class="delete-btn btn btn-danger btn-sm" href="{{route('keywords.delete', $item->id.rand(9,999999))}}">Remove</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $keywords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelector('.delete-btn').addEventListener('click', function (e) {
            e.preventDefault();
            let url = this.getAttribute('href');
            deleteItem(url);
        });

        function deleteItem(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                confirmButtonColor: "#DD6B55",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
@endsection
