<?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>
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
    
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('uploadpaiement');?>">
        <h4>IMPORT PAIEMENT</h4>
        <div class="form-group">
            <label for="formGroupExampleInput">Select Files</label>
            <input type="file" name="file" class="form-control" multiple>
            </div> 

            <div class="form-group">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
<?= $this->endSection() ?>
