@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Deposit Fund</h4>
        </div>
        <div class="row">
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
            <div class="col col-md-2"></div>
            <div class="col col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="header-title text-black">Deposit Fund</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('depositfund')}}" method="post">
                            @csrf
                        <div class="row">
                            <div class="col col-md-6">
                                <label for="card number">Card Number</label>
                                <input type="text" name="card_number" class="form-control"
                                    placeholder="Enter card number her ..." id="" required>
                            </div>
                            <div class="col col-md-6">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" min="1" max="1000000000000000000"
                                    class="form-control" placeholder="Enter amount to deposit ..."" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-md-3"></div>
                            <div class="col col-md-6">
                                <button class="btn btn-primary btn-block"><span class="fa fa-money"></span> Deposit fund </button>
                            </div>
                            <div class="col col-md-3"></div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col col-md-2"></div>
        </div>
        <br>
        <div class="data-table">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="card m-b-30">
                        <div class="card-header">
                            <button class="btn btn-primary bg-modal-gred-1 btn-animation float-right"
                            data-animation="flipInX" data-toggle="modal" data-target="#payreport"><span class="fa fa-download"></span> Click here to generate payments report</button>
                            <h5 class="header-title text-black">List of fund desposited</h5>
                        </div>
                        <div class="card-body table-responsive">

                            <div class="table-odd">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/no</th>
                                            <th>Card Number</th>
                                            <th>Amount (Tsh)</th>
                                            <th>Date & Time</th>
                                            <th>Reciept</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($deposits as $deposit)
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$deposit->card_number}}</td>
                                                <td>{{number_format($deposit->amount)}}</td>
                                                <td>{{$deposit->updated_at}}</td>
                                                <td><a href="{{route('download', $deposit->id)}}" class="btn btn-success"><span class="fa fa-download"></span> Reciept</a></td>
                                            </tr>
                                            @php
                                                $no++;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                        <tr>
                                            <th colspan="2" style="text-align: right">TOTAL</th>
                                            <th colspan="" style="background: #2753f2; text-align:center; color: white">{{number_format($total)}}/=</th>
                                            <th colspan="2"></th>
                                        </tr>

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

    <div class="modal fade" id="payreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-modal-gred-1">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate Payment report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('payment-report')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col col-md-12">
                            <label for="">When</label>
                            <select name="when" class="form-control" id="" required>
                                <option value="" selected disabled>--</option>
                                <option value="This Week">This Week</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month">Last Months</option>
                                <option value="Last Two Months">Last Two Months</option>
                                <option value="Last Three Months">Last Three Months</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Generate</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
