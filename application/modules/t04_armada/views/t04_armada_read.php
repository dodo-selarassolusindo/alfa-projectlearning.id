
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $judul_form ?></h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr><td>Merk</td><td><?php echo $merk; ?></td></tr>
                <tr><td>Tipe</td><td><?php echo $tipe; ?></td></tr>
                <tr><td>Tahun Pembuatan</td><td><?php echo $tahun_pembuatan; ?></td></tr>
                <tr><td>Harga Beli</td><td><?php echo $harga_beli; ?></td></tr>
                <tr><td>Nomor Polisi</td><td><?php echo $nomor_polisi; ?></td></tr>
                <tr><td>Nomor Rangka</td><td><?php echo $nomor_rangka; ?></td></tr>
                <tr><td>Nomor Mesin</td><td><?php echo $nomor_mesin; ?></td></tr>
                <tr><td>Tanggal Pembelian</td><td><?php echo $tanggal_pembelian; ?></td></tr>
                <tr><td>Tanggal Jatuh Tempo Pajak</td><td><?php echo $tanggal_jatuh_tempo_pajak; ?></td></tr>
                <tr><td>Tanggal Jatuh Tempo Kir</td><td><?php echo $tanggal_jatuh_tempo_kir; ?></td></tr>
            </table>
        </div>
        <div class="card-footer text-start">
            <a href="<?= site_url('t04_armada') ?>" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
