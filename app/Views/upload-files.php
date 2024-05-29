<!DOCTYPE html>
<html>
<head>
  <title>Codeigniter 4 Multiple Files Upload Example</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 
</head>
<body>
 <div class="container" style="margin-top: 30px;">
   <h3>Codeigniter 4 Multiple Files Upload</h3>
    <br>
     
    <?php if (session('error')) : ?>
        <div class="alert alert-danger alert-dismissible">
            <?= session('error') ?>
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    <?php endif ?>

    <?php if (session('success')) : ?>
        <div class="alert alert-success alert-dismissible">
            <?= session('success') ?>
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    <?php endif ?>
 
    <div class="row">
      <div class="col-md-9">

        <form method="post" enctype="multipart/form-data" action="<?php echo base_url('getFiles');?>">
 
          <div class="form-group">
            <label for="formGroupExampleInput">Select Files</label>
            <input type="file" name="file1" class="form-control">
          </div> 
          <div class="form-group">
            <label for="formGroupExampleInput">Select Files</label>
            <input type="file" name="file2" class="form-control">
          </div> 
 
          <div class="form-group">
           <button type="submit" class="btn btn-success">Submit</button>
          </div>
          
        </form>
        <form method="post" enctype="multipart/form-data" action="<?php echo base_url('uploadpaiement');?>">
            <h1>IMPORT PAIEMENT</h1>
          <div class="form-group">
            <label for="formGroupExampleInput">Select Files</label>
            <input type="file" name="file" class="form-control">
          </div> 
 
          <div class="form-group">
           <button type="submit" class="btn btn-success">Submit</button>
          </div>
          
        </form>
      </div>
    </div>
</div>
</body>
</html>