@extends('layout/app')
@section('content')
    <div class="container-fluid">
        <div class="page-head">
            <h4 class="mt-2 mb-2">Dashboard</h4>
        </div>
        @if (session('success'))
            <script>
                $(document).ready(function() {
                    $.Notification.autoHideNotify(
                        'success',
                        'top right',
                        'Welcome',
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
                        'Ooops',
                        '{{ session('error') }}'
                    )
                });
            </script>
        @endif
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-3 col-sm-3">
                        <div class="widget-box bg-white m-b-30">
                            <div class="row d-flex align-items-center">
                                <div class="col-4">
                                    <div class="text-center"><i class="ti ti-eye"></i></div>
                                </div>
                                <div class="col-4">
                                    <div class="dynamicbar">Loading..</div>
                                </div>
                                <div class="col-4">
                                    <h2 class="m-0 counter">145</h2>
                                    <p>Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <div class="widget-box bg-white m-b-30">
                            <div class="row d-flex align-items-center text-center">
                                <div class="col-4">
                                    <div class="text-center"><i class="ti ti-user"></i></div>
                                </div>
                                <div class="col-4">
                                    <div class="inlinesparkline">Loading..</div>
                                </div>
                                <div class="col-4">
                                    <h2 class="m-0 counter">946</h2>
                                    <p>Total Cards</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <div class="widget-box bg-white m-b-30">
                            <div class="row d-flex align-items-center">
                                <div class="col-4">
                                    <div class="text-center"><i class="ti ti-money"></i></div>
                                </div>
                                <div class="col-4">
                                    <div class="dynamicbar">Loading..</div>
                                </div>
                                <div class="col-4">
                                    <h2 class="m-0 counter">548</h2>
                                    <p>Total Request</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <div class="widget-box bg-white m-b-30">
                            <div class="row d-flex align-items-center">
                                <div class="col-4">
                                    <div class="text-center"><i class="ti ti-wallet"></i></div>
                                </div>
                                <div class="col-4">
                                    <div class="inlinesparkline">Loading..</div>
                                </div>
                                <div class="col-4">
                                    <h2 class="m-0 counter">999</h2>
                                    <p>Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
    <!--end container-->
@endsection
