
<div class="col-12">
    <form action="<?php echo $action; ?>" method="post" class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $button ?></h3>
        </div>
        <div class="card-body col-6">
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Merk </label>
                <div class="col">
                    <input type="text" class="form-control" name="merk" id="merk" placeholder="Merk" value="<?= $merk ?>" />
                    <small class="form-hint"><?= form_error('merk') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Tipe </label>
                <div class="col">
                    <input type="text" class="form-control" name="tipe" id="tipe" placeholder="Tipe" value="<?= $tipe ?>" />
                    <small class="form-hint"><?= form_error('tipe') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Tahun Pembuatan </label>
                <div class="col">
                    <input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" placeholder="Tahun Pembuatan" value="<?= $tahun_pembuatan ?>" />
                    <small class="form-hint"><?= form_error('tahun_pembuatan') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Harga Beli </label>
                <div class="col">
                    <input type="text" class="form-control" name="harga_beli" id="harga_beli" placeholder="Harga Beli" value="<?= $harga_beli ?>" />
                    <small class="form-hint"><?= form_error('harga_beli') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nomor Polisi </label>
                <div class="col">
                    <input type="text" class="form-control" name="nomor_polisi" id="nomor_polisi" placeholder="Nomor Polisi" value="<?= $nomor_polisi ?>" />
                    <small class="form-hint"><?= form_error('nomor_polisi') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nomor Rangka </label>
                <div class="col">
                    <input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka" value="<?= $nomor_rangka ?>" />
                    <small class="form-hint"><?= form_error('nomor_rangka') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nomor Mesin </label>
                <div class="col">
                    <input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin" value="<?= $nomor_mesin ?>" />
                    <small class="form-hint"><?= form_error('nomor_mesin') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Tanggal Pembelian </label>
                <div class="col">
                    <input type="text" class="form-control" name="tanggal_pembelian" id="tanggal_pembelian" placeholder="Tanggal Pembelian" value="<?= $tanggal_pembelian ?>" />
                    <small class="form-hint"><?= form_error('tanggal_pembelian') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Tanggal Jatuh Tempo Pajak </label>
                <div class="col">
                    <input type="text" class="form-control" name="tanggal_jatuh_tempo_pajak" id="tanggal_jatuh_tempo_pajak" placeholder="Tanggal Jatuh Tempo Pajak" value="<?= $tanggal_jatuh_tempo_pajak ?>" />
                    <small class="form-hint"><?= form_error('tanggal_jatuh_tempo_pajak') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Tanggal Jatuh Tempo Kir </label>
                <div class="col">
                    <input type="text" class="form-control" name="tanggal_jatuh_tempo_kir" id="tanggal_jatuh_tempo_kir" placeholder="Tanggal Jatuh Tempo Kir" value="<?= $tanggal_jatuh_tempo_kir ?>" />
                    <small class="form-hint"><?= form_error('tanggal_jatuh_tempo_kir') ?></small>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $id ?>" />
        </div>
        <div class="card-footer text-start">
            <button type="submit" class="btn btn-primary"><?= $button ?></button>
            <a href="<?= site_url('t04_armada') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>