<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mannat Themes">
    <meta name="keyword" content="">

    <title>Card Based Garbage Collection System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add click event listener to navigation links
            $('.side-navigation li').click(function() {
                // Remove "active" class from previously active navigation item
                $('.side-navigation li.active').removeClass('active');
                // Add "active" class to clicked navigation item
                $(this).addClass('active');
                // Prevent default behavior of link
                return true;
            });
        });
    </script>
    <!--animation-->
    <link href="assets/css/animate.css" rel="stylesheet">

    <!-- Theme icon -->
    <link rel="shortcut icon" href="logo.png">

    <link href="assets/plugins/morris-chart/morris.css" rel="stylesheet">
    <!-- Theme Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/slidebars.min.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Responsive and DataTables -->
    <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Sweet Alert css -->
    {{-- <link href="assets/plugins/sweet-alert/sweetalert2.min.css" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <!--notifications-->
    <link href="assets/plugins/notifications/notification.css" rel="stylesheet" />
</head>

<body class="sticky-header">
    <section>
        <div>
            @include('layout/sidebar')
            <div class="body-content">
                @include('layout/header')
                @yield('content')
                @include('layout/footer')
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script>
        $('#position').on('click', () => {
            swal({
                position: 'top-end',
                type: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-migrate.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/slidebars.min.js"></script>

    <!--plugins js-->
    <script src="assets/plugins/counter/jquery.counterup.min.js"></script>
    <script src="assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/plugins/sparkline-chart/jquery.sparkline.min.js"></script>
    <script src="assets/pages/jquery.sparkline.init.js"></script>

    <script src="assets/plugins/chart-js/Chart.bundle.js"></script>
    <script src="assets/plugins/morris-chart/raphael-min.js"></script>
    <script src="assets/plugins/morris-chart/morris.js"></script>
    <script src="assets/pages/dashboard-init.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.sweet-alert.init.js') }}"></script>

    <!--Notifications-->
    <script src="assets/plugins/notifications/notify.min.js"></script>
    <script src="assets/plugins/notifications/notify-metro.js"></script>
    <script src="assets/plugins/notifications/notifications.js"></script>

    <!--app js-->
    <script src="assets/js/jquery.app.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('.counter').counterUp({
                delay: 100,
                time: 1200
            });
        });
    </script>

    <!-- Responsive and datatable js -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!--app js-->
    <script src="assets/js/jquery.app.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable(),
                $('#datatable2').DataTable();
        });
    </script>
</body>

</html>
