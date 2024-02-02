
<div class="col-12">
    <form action="<?php echo $action; ?>" method="post" class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $button ?></h3>
        </div>
        <div class="card-body col-6">
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Kode </label>
                <div class="col">
                    <input type="text" class="form-control" name="kode" id="kode" placeholder="Kode" value="<?= $kode ?>" />
                    <small class="form-hint"><?= form_error('kode') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Nama </label>
                <div class="col">
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?= $nama ?>" />
                    <small class="form-hint"><?= form_error('nama') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Alamat </label>
                <div class="col">
                    <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat"><?= $alamat ?></textarea>
                    <small class="form-hint"><?= form_error('alamat') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Kota </label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="kota" id="kota" placeholder="Kota" value="<?= $kota ?>" /> -->
                    <select class="form-control select2" name="kota">
                        <?php foreach($lokasi as $row) { ?>
                        <option value="<?= $row->id ?>" <?= $row->id == $kota ? 'selected' : '' ?>><?= $row->nama ?></option>
                        <?php } ?>
                    </select>
                    <small class="form-hint"><?= form_error('kota') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Contact Person </label>
                <div class="col">
                    <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="<?= $contact_person ?>" />
                    <small class="form-hint"><?= form_error('contact_person') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-3 col-form-label required">Telepon </label>
                <div class="col">
                    <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Telepon" value="<?= $telepon ?>" />
                    <small class="form-hint"><?= form_error('telepon') ?></small>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $id ?>" />
        </div>
        <div class="card-footer text-start">
            <button type="submit" class="btn btn-primary"><?= $button ?></button>
            <a href="<?= site_url('t02_shipper') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
