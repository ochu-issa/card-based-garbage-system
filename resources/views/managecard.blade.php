@extends('layout/app')
@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <script>
                $(document).ready(function() {
                    $.Notification.autoHideNotify(
                        'success',
                        'top right',
                        'Hurray',
                        '{{ session('success') }}'
                    )
                });
            </script>
        @elseif (session('error'))
            <script>
                $(document).ready(function() {
                    $.Notification.autoHideNotify(
                        'error',
                        'top right',
                        'Opps',
                        '{{ session('error') }}'
                    )
                });
            </script>
        @endif
        <div class="page-head">
            <h4 class="mt-2 mb-2">Manage cards</h4>
        </div>
        <div class="data-table">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="card m-b-30">

                        <div class="card-body table-responsive">

                            <h5 class="header-title">List cards</h5>

                            <button type="button" class="btn btn-primary bg-modal-gred-1 btn-animation"
                                data-animation="flipInX" data-toggle="modal" data-target="#registercard"><i
                                    class="fa fa-plus"></i> Register new card</button>

                            <button type="button" class="btn btn-success bg-modal-gred-1 btn-animation"
                                data-animation="flipInX" data-toggle="modal" data-target="#importcsv"><i
                                    class="fa fa-upload"></i> Import CSV</button>

                            <a href="{{ asset('download/sample.csv') }}"
                                class="btn btn-warning bg-modal-gred-1 btn-animation" data-animation="flipInX"><i
                                    class="fa fa-download"></i> Download sample CSV</a>
                            <br><br>

                            <div class="table-odd">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/no</th>
                                            <th>Card Number</th>
                                            <th>Balance (Tsh)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($cards as $card)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $card->card_number }}</td>
                                                <td>{{ $card->balance }}</td>
                                                <td>
                                                    @if ($card->card_status == 'assigned')
                                                        <span class="badge badge-success">assigned</span>
                                                    @elseif ($card->card_status == 'unsigned')
                                                        <span class="badge badge-danger">unsigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($card->status == 1)
                                                        <button type="button" class="btn btn-success btn-sm btn-animation"
                                                            data-animation="pulse" data-toggle="modal"
                                                            data-target="#block-modal-{{ $card->id }}"><span
                                                                class="fa fa-check"></span></button>
                                                    @else
                                                        <button type="button" class="btn btn-danger btn-sm btn-animation"
                                                            data-animation="pulse" data-toggle="modal"
                                                            data-target="#un-block-modal-{{ $card->id }}"><span
                                                                class="fa fa-times"></span></button>
                                                    @endif
                                                    <button class="btn btn-danger btn-sm" id="delete-btn"
                                                        value="{{ $card->id }}"><span
                                                            class="fa fa-trash"></span></button>
                                                </td>
                                            </tr>
                                            <!-- Block Modal -->
                                            <div class="modal fade" id="block-modal-{{ $card->id }}" role="document"
                                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog " role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle-1">Block the
                                                                card
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to block this card?</p>

                                                        </div>
                                                        <form action="{{ route('blockcard') }}" method="post">
                                                            @csrf
                                                            <div class="modal-footer">
                                                                <input type="hidden" value="{{ $card->id }}"
                                                                    name="card_id">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Yes! Block
                                                                    it</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end of modal --}}

                                            <!-- un-Block Modal -->
                                            <div class="modal fade" id="un-block-modal-{{ $card->id }}" role="document"
                                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog " role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle-1">Un-Block the
                                                                card
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to un-block this card?</p>

                                                        </div>
                                                        <form action="{{ route('un-blockcard') }}" method="post">
                                                            @csrf
                                                            <div class="modal-footer">
                                                                <input type="hidden" value="{{ $card->id }}"
                                                                    name="card_id">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Yes! un-block
                                                                    it</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end of modal --}}
                                            @php
                                                $no++;
                                            @endphp
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
    {{-- register card ,modal --}}
    <div class="modal fade" id="registercard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-modal-gred-1">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('registercard') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col col-md-12">
                                <label for="">Card Number</label>
                                <input type="text" placeholder="Enter card number here..." name="card_number"
                                    class="form-control" id="" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal --}}

    {{-- import csv modal --}}
    <div class="modal fade" id="importcsv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-modal-gred-1">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import CSV file</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('uploadcsv') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col col-md-12">
                                <label for="">CSV file</label>
                                <input type="file" name="excel_file" class="form-control-file"
                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                    required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '#delete-btn', function() {
                const card_id = $(this).val();
                console.log(card_id);

                $.ajax({
                    url: "http://localhost:8000/delete-card/" + card_id,
                    type: "GET",
                    dataType: 'jsonp',
                    headers: {
                        'Access-Control-Allow-Origin': '*',
                    },
                    success: function(response) {
                        console.log(response.card);
                    }
                });
            });
        });
    </script>
@endsection
