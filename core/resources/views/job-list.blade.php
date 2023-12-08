@extends('layouts.app')

@section('styles')
    <style>
        .button_wrapper .active{
            background: #00111a;
            border-color: #00111a;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    @php
        $jobTypes = 'all';
        if (isset($type))
        {
            $jobTypes = $type;
        }
    @endphp

    <div class="container my-5">
        <div class="row mx-auto justify-content-center">
            <div class="col-lg-12">
                <div class="button_wrapper d-flex justify-content-end gap-2 my-4">
                    <a href="{{route('jobs.all')}}" class="btn btn-primary {{$jobTypes == 'all' ? 'active' : ''}}">All Jobs</a>
                    <a href="{{route('jobs.all.type', 'applied')}}" class="btn btn-success {{$jobTypes == 'applied' ? 'active' : ''}}">Applied Jobs</a>
                    <a href="{{route('jobs.all.type', 'unapplied')}}" class="btn btn-warning {{$jobTypes == 'unapplied' ? 'active' : ''}}">Unapplied Jobs</a>
                    <a href="{{route('jobs.all.type', 'hidden')}}" class="btn btn-danger {{$jobTypes == 'hidden' ? 'active' : ''}}">Hidden Jobs</a>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="search_wrapper mb-4">
                    <input class="form-control search_input" type="text" placeholder="Search...">
                </div>
                <div class="card">
                    <div class="card-header text-capitalize">{{$jobTypes}} Job List ({{$jobs->total()}})</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="dropdown d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="all-checkbox">
                                        </div>
                                        <a class="text-dark btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLinkCheckbox" data-bs-toggle="dropdown" aria-expanded="false"></a>

                                        <ul class="dropdown-menu status-dropdown" aria-labelledby="dropdownMenuLinkCheckbox">
                                            <li><a class="status-checkbox-btn dropdown-item text-success" href="#0" data-value="applied">Applied</a></li>
                                            <li><a class="status-checkbox-btn dropdown-item text-warning" href="#0" data-value="unapplied">Not Applied</a></li>
                                            <li><a class="status-checkbox-btn dropdown-item text-danger" href="#0" data-value="hidden">Hide</a></li>
                                        </ul>
                                    </div>
                                </th>
                                <th>SL.</th>
                                <th>Job Ref</th>
                                <th>Job Title</th>
                                <th>Job URL</th>
                                <th>Job Status</th>
                                <th>Action</th>
                                <th>Job Posted Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = $jobs->currentPage() == 1 ? 1 : $jobs->perPage() + 1;
                            @endphp

                            @foreach($jobs as $job)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input job-checkbox" type="checkbox" value="{{$job->id}}">
                                        </div>
                                    </td>
                                    <td>{{$i++}}</td>
                                    <td>{{$job->ref}}</td>
                                    <td>{{$job->title}}</td>
                                    <td class="text-center">
                                        @php
                                            $id = explode('-', $job->ref)[1] ?? '0';
                                            $url = 'https://jobsireland.ie/en-US/job-Details?id='.$id;
                                        @endphp
                                        <a href="{{$url}}" class="btn btn-primary btn-sm" target="_blank">Open</a>
                                    </td>
                                    <td>
                                        <span
                                            class="text-capitalize {{$job->status == 'unapplied' ? 'text-warning' : ($job->status == 'applied' ? 'text-success' : 'text-danger')}}">{{$job->status}}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-info text-light btn-sm text-capitalize dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{$job->status}}
                                            </a>

                                            <ul class="dropdown-menu status-dropdown" aria-labelledby="dropdownMenuLink">
                                                <li><a class="status-btn dropdown-item text-success" href="#0" data-id="{{$job->id}}" data-value="applied">Applied</a></li>
                                                <li><a class="status-btn dropdown-item text-warning" href="#0" data-id="{{$job->id}}" data-value="unapplied">Not Applied</a></li>
                                                <li><a class="status-btn dropdown-item text-danger" href="#0" data-id="{{$job->id}}" data-value="hidden">Hide</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $jobs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelector('#all-checkbox').addEventListener('click', function () {
            let checkboxes = document.querySelectorAll('.job-checkbox');

            if (this.checked)
            {
                checkboxes.forEach(function (item) {
                    item.checked = true;
                })
            } else {
                checkboxes.forEach(function (item) {
                    item.checked = false;
                })
            }
        });

        document.querySelector('.search_input').addEventListener('keyup', function () {
            let search_query = this.value;

            axios.post(`{{route('jobs.search')}}`, {
                data: search_query
            }).then(function (response) {
                console.log(response)
                    if (response.data.status && response.data.total > 0)
                    {
                        let markup = '';
                        response.data.jobs.forEach(function (item) {
                            markup += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.ref}</td>
                                    <td>${item.title}</td>
                                    <td class="text-center">
                                        <a href="${item.url}" class="btn btn-primary btn-sm" target="_blank">Open</a>
                                    </td>
                                    <td>
                                        <span class="text-capitalize ${item.status === 'unapplied' ? 'text-warning' : (item.status === 'applied' ? 'text-success' : 'text-danger')}">${item.status}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-info text-light btn-sm text-capitalize dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                Status
                                            </a>

                                            <ul class="dropdown-menu status-dropdown" aria-labelledby="dropdownMenuLink">
                                                <li><a class="status-btn dropdown-item text-success" href="#0" data-id="${item.id}" data-value="applied">Applied</a></li>
                                                <li><a class="status-btn dropdown-item text-warning" href="#0" data-id="${item.id}" data-value="unapplied">Not Applied</a></li>
                                                <li><a class="status-btn dropdown-item text-danger" href="#0" data-id="${item.id}" data-value="hidden">Hide</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            `;
                        });

                        document.querySelector('tbody').innerHTML = markup;
                    } else {
                        document.querySelector('tbody').innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center">No Data Found</td>
                            </tr>
                        `;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        });


        document.querySelectorAll('.status-dropdown .status-btn').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                let id = this.getAttribute('data-id');
                let status = this.getAttribute('data-value');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to change the status of this job!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        changeStatus({id, status});
                    }
                })
            })
        })

        document.querySelectorAll('.status-dropdown .status-checkbox-btn').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                let status = this.getAttribute('data-value');
                let id = [];
                document.querySelectorAll('.job-checkbox').forEach(function (item) {
                    if (item.checked)
                    {
                        id.push(item.value);
                    }
                });

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to change the status of this job!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        changeStatus({id, status});
                    }
                })
            })
        })

        const changeStatus = (data) => {
            axios.post(`{{route('jobs.status.change')}}`, {
                id: data.id,
                status: data.status
            }).then(function (response) {
                console.log(response)
                if (response.data.status)
                {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.data.message,
                    });
                }
            }).catch(function (error) {
                    console.log(error);
            });
        }
    </script>
@endsection
