
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $judul_form ?></h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr><td>Nama</td><td><?php echo $nama; ?></td></tr>
                <tr><td>Akun</td><td><?php echo $akun; ?></td></tr>
            </table>
        </div>
        <div class="card-footer text-start">
            <a href="<?= site_url('t06_cost') ?>" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
