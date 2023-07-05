<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mannat Themes">
    <meta name="keyword" content="">

    <title>Card Based Garbage Collection System</title>

    <!-- Theme icon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/slidebars.min.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="sticky-header">
    <section style="background-color: rgb(3, 104, 104)">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="wrapper-page">
                        <div class="account-pages">
                            <div class="account-box">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="card-title text-center">
                                            <img src="logo.png" width="100" height="100" alt=""
                                                class="">
                                            <h5 class="mt-3"><strong>
                                                Card Based Garbage Collection System

                                                </strong>
                                            </h5>
                                            <h5 class="mt-3"><strong>
                                               | Welcome Back! |
                                                </strong>
                                            </h5>
                                        </div>
                                        <form class="form mt-5 contact-form" method="post"
                                            action="{{ route('validate') }}">
                                            @csrf
                                            <div class="form-group ">
                                                <div class="col-sm-12">
                                                    <input class="form-control form-control-line" name="email"
                                                        type="text" placeholder="Username" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-12">
                                                    <input class="form-control form-control-line" name="password"
                                                        type="password" placeholder="Password" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12 mt-4">
                                                    <button class="btn btn-block"
                                                        style="background-color: rgb(3, 104, 104); color: white;"
                                                        type="submit">Log In</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-migrate.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/slidebars.min.js"></script>


    <!--app js-->
    <script src="assets/js/jquery.app.js"></script>
</body>

</html>
