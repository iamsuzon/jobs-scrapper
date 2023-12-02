@if (session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif

@foreach($errors->all() as $error)
    <div class="alert alert-danger">
        {{$error}}
    </div>
@endforeach
