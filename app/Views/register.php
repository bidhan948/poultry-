<?= $this->extend("layout/master") ?>


<?= $this->section("content") ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Register New User</h3>
    </div>
    <div class="card-body">

        <?php if (isset($validation)) : ?>
            <div class="alert alert-warning">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo base_url()?>/registerPost" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <input type="text" name="name" placeholder="Name" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <input type="text" name="username" placeholder="Username" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control">
                    </div>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Signup</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>