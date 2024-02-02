
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $judul_form ?></h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr><td>Job Order</td><td><?php echo $job_order; ?></td></tr>
                <tr><td>Armada</td><td><?php echo $armada; ?></td></tr>
                <tr><td>Nomor Container</td><td><?php echo $nomor_container; ?></td></tr>
            </table>
        </div>
        <div class="card-footer text-start">
            <a href="<?= site_url('t31_job_order_detail') ?>" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
