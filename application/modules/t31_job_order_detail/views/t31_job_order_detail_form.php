
<div class="col-12">
    <form action="<?php echo $action; ?>" method="post" class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $button ?></h3>
        </div>
        <div class="card-body col-6">
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Job Order </label>
                <div class="col">
                    <input type="text" class="form-control" name="job_order" id="job_order" placeholder="Job Order" value="<?= $job_order ?>" />
                    <small class="form-hint"><?= form_error('job_order') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Armada </label>
                <div class="col">
                    <input type="text" class="form-control" name="armada" id="armada" placeholder="Armada" value="<?= $armada ?>" />
                    <small class="form-hint"><?= form_error('armada') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nomor Container </label>
                <div class="col">
                    <input type="text" class="form-control" name="nomor_container" id="nomor_container" placeholder="Nomor Container" value="<?= $nomor_container ?>" />
                    <small class="form-hint"><?= form_error('nomor_container') ?></small>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $id ?>" />
        </div>
        <div class="card-footer text-start">
            <button type="submit" class="btn btn-primary"><?= $button ?></button>
            <a href="<?= site_url('t31_job_order_detail') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>