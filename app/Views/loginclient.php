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
                            <div class="col-lg-6"> 
                                <img src="<?php echo base_url("Assets/img/image1.jpg")?>" width="500px">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                        <form class="" action="<?= base_url('loginClient') ?>" method="post">
                                            <div class="form-group">                                                     
                                                <label for="phone">Numero de telephone</label>
                                                <input type="text" class="form-control form-control-user" placeholder="Enter phone Address..." name="phone" id="phone">
                                            </div>
                                            <div class="form-group"> 
                                                <center><button type="submit" class="btn btn-success">Submit</button></center>

                                            </div>
 
                                             
                                        </form>
                                        <form class="" action="<?= base_url('register') ?>" method="get">
                                            <div class="text-center">
                                                <button type="submit" class="btn" style="color:blue">Create an account</button>
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