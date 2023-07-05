@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Generate Report</h4>
        </div>
        <div class="row">
            <div class="col col-md-2"></div>
            <div class="col col-md-8">
                <div class="card">
                    <form action="{{ route('report') }}" method="post">
                        @csrf
                        <div class="card-header bg-primary">

                            <h5 class="mb-0 header-title  text-white">Generate Report</h5>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-md-4">

                                    <select name="when" class="form-control" id="" required>
                                        <option value="" selected disabled>-When-</option>
                                        <option value="This Week">This Week</option>
                                        <option value="This Month">This Month</option>
                                        <option value="Last Month">Last Months</option>
                                        <option value="Last Two Months">Last Two Months</option>
                                        <option value="Last Three Months">Last Three Months</option>
                                    </select>
                                </div>
                                <div class="col col-md-4">
                                    <select name="filter" class="form-control" id="" required>
                                        <option value="" selected disabled>-Filter-</option>
                                        <option value="All">All</option>
                                        <option value="Good Collector">Good Collector</option>
                                        <option value="Bad Collector">Bad Collector</option>
                                    </select>
                                </div>

                                <div class="col col-md-4">
                                    <div>
                                        <button type="submit" class="btn btn-block btn-success float-right"><span
                                                class="fa fa-download"></span>
                                            Generate Report</button>
                                    </div>

                                </div>

                            </div>
                    </form>
                    <hr style="border-top: 1px solid #ccc;">
                    <p style="font-style: italic; opacity: 0.5;">
                        <span class="fa fa-clock-o"></span> Last report was generated today
                    </p>
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
                    <div class="card-header bg-primary">
                        <h5 class="header-title text-white">List of cards</h5>
                    </div>
                    <div class="card-body table-responsive">

                        <div class="table-odd">
                            <table id="datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/no</th>
                                        <th>Card Number</th>
                                        <th>Resident Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Scanned times</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($scannedCard as $index => $card)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $card->card_number }}</td>
                                            <td>{{ $card->resident_full_name }}</td>
                                            <td>{{ $card->address }}</td>
                                            <td>{{ $card->phone_number }}</td>
                                            <td>{{ $card->scanned_times }}</td>
                                            <td>{{ $card->created_at }}</td>
                                            @if ($card->scanned_times < 3)
                                                <td><span class="badge badge-warning">Bad Collector</span></td>
                                                @else
                                                <td><span class="badge badge-success">Good Collector</span></td>
                                            @endif
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
                    <div class="row">
                        <div class="col col-md-12">
                            <label for="">Card Number</label>
                            <input type="text" placeholder="Enter card number here..." name="cardnumber"
                                class="form-control" id="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Register</button>
                </div>
            </div>
        </div>
    </div>
@endsection
