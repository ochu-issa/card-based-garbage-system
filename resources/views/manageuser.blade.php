@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Manage users</h4>
        </div>
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
        <div class="data-table">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="card m-b-30">

                        <div class="card-body table-responsive">

                            <h5 class="header-title">List users</h5>

                            <button type="button" class="btn btn-primary bg-modal-gred-1 btn-animation"
                                data-animation="flipInX" data-toggle="modal" data-target="#exampleModal22"><i
                                    class="fa fa-plus"></i> Register new user</button> <br><br>

                            <div class="table-odd">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/no</th>
                                            <th>Full Name</th>
                                            <th>Gender</th>
                                            <th>Address</th>
                                            <th>Phone Number</th>
                                            <th>Card Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($residents as $resident)
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$resident->f_name}} {{$resident->l_name}}</td>
                                                <td>{{$resident->gender}}</td>
                                                <td>{{$resident->address}}</td>
                                                <td>{{$resident->phone_number}}</td>
                                                <td>{{$cards->where('id', $resident->card_id)->first()->card_number}}</td>
                                                <td>
                                                    <button class="btn btn-secondary btn-sm"><span class="fa fa-edit"></span></button>
                                                    <button class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></button>
                                                </td>
                                            </tr>
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

    <!--user registration modal-->
    <div class="modal fade" id="exampleModal22" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-modal-gred-1">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('registerresident')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col col-md-6">
                                <label for="">First Name</label>
                                <input type="text" placeholder="Enter first name..." name="f_name" class="form-control"
                                    id="" required>
                            </div>
                            <div class="col col-md-6">
                                <label for="">Last Name</label>
                                <input type="text" placeholder="Enter last name..." name="l_name" class="form-control"
                                    id="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-6">
                                <label for="">Gender</label>
                                <select name="gender" id="" class="form-control" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col col-md-6">
                                <label for="">Address</label>
                                <input type="text" placeholder="Enter Address (House number)..." name="address"
                                    class="form-control" id="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-6">
                                <label for="">Phone Number</label>
                                <input type="text" placeholder="Enter Phone number`..." name="phone_number"
                                    class="form-control" id="" required>
                            </div>
                            <div class="col col-md-6">
                                <label for="Select Card Number">Select Card Number</label>
                                <select name="cardnumber" class="form-control" id="" required>
                                    <option value="" selected disabled>--</option>

                                    @foreach ($cards as $card)
                                        @if ($card->card_status == 'unsigned')
                                            <option value="{{ $card->card_number }}">{{ $card->card_number }}</option>
                                        @endif
                                    @endforeach

                                </select>
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
    {{-- end of Modal --}}
@endsection

