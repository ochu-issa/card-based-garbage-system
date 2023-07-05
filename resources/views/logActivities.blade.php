@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Log Entries</h4>
        </div>

        <div class="data-table">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="card m-b-30">

                        <div class="card-body table-responsive">

                            <h5 class="header-title">Log Entries</h5>
                            <div class="table-odd">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Log Description</th>
                                            <th>Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $index => $log)
                                            <tr>
                                                <td>{{ $index+1 }}</td>
                                                <td>{{ $log->logname }}</td>
                                                <td>{{ $log->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

    </div>
    <!--end container-->


@endsection
