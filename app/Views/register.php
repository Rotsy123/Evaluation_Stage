<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title> 

    <!-- Custom fonts for this template-->
    <link href=<?php echo base_url("Assets/vendor/fontawesome-free/css/all.min.css")?> rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href=<?php echo base_url("Assets/css/sb-admin-2.min.css")?> rel="stylesheet">

</head>

<body>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row"> 
                        <div class="col-lg-6 d-none d-lg-block">
                            <img src="<?php echo base_url("Assets/img/image1.jpg")?>" class="img-fluid">
                        </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome!</h1>
                                    </div>
                                        <form class="" action="<?= base_url('register') ?>" method="post">
                                            <div class="form-group">                                                     
                                                <label for="name">Name</label>
                                                <input type="name" class="form-control form-control-user" placeholder="Enter name ..." name="name" id="email">
                                            </div>
                                            <div class="form-group">                                                     
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email" id="email">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password"  class="form-control form-control-user" placeholder="Enter Password" name="password" id="password">
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirm">Confirm Password </label>
                                                <input type="password"  class="form-control form-control-user" placeholder="Confirm Password" name="password_confirm" id="password_confirm">
                                            </div>
                                            <div class="text-center"> 
                                                <center><button type="submit" class="btn btn-success">Submit</button></center>

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

    <!-- Bootstrap core JavaScript-->
    <script src=<?php echo base_url("Assets/vendor/jquery/jquery.min.js")?>></script>
    <script src=<?php echo base_url("Assets/vendor/bootstrap/js/bootstrap.bundle.min.js")?>></script>

    <!-- Core plugin JavaScript-->
    <script src=<?php echo base_url("Assets/vendor/jquery-easing/jquery.easing.min.js")?>></script>

    <!-- Custom scripts for all pages-->
    <script src=<?php echo base_url("Assets/js/sb-admin-2.min.js")?>></script>

</body>

</html>