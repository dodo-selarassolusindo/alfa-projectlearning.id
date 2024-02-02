
<div class="col-12">
    <form action="<?php echo $action; ?>" method="post" class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $button ?></h3>
        </div>
        <div class="card-body col-6">
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nama </label>
                <div class="col">
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?= $nama ?>" />
                    <small class="form-hint"><?= form_error('nama') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Akun </label>
                <div class="col">
                    <input type="text" class="form-control" name="akun" id="akun" placeholder="Akun" value="<?= $akun ?>" />
                    <small class="form-hint"><?= form_error('akun') ?></small>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $id ?>" />
        </div>
        <div class="card-footer text-start">
            <button type="submit" class="btn btn-primary"><?= $button ?></button>
            <a href="<?= site_url('t06_cost') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>