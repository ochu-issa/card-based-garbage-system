@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Testing Area</h4>
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
            <div class="col col-md-4"></div>
            <div class="col col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="header-title text-black">Testing Api</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('api')}}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col col-md-12">
                                    <label for="card number">Card Number</label>
                                    <input type="text" name="card_number" class="form-control"
                                        placeholder="Enter card number her ..." id="" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col col-md-3"></div>
                                <div class="col col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block"><span class="fa fa-money"></span> Deposit fund
                                    </button>
                                </div>
                                <div class="col col-md-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col col-md-4"></div>
        </div>
    </div>
    <!--end container-->
@endsection
