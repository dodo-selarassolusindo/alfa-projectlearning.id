<script type="text/javascript">
    var i = 0;
    var update = '<?= $this->uri->segment(2) ?>';
</script>
<div class="col-12">
    <form action="<?php echo $action; ?>" method="post" class="card">
        <div class="card-header">
            <h3 class="card-title"><?= $button ?></h3>
        </div>
        <div class="card-body col-6">
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Tanggal Job Order </label>
                <div class="col">
                    <input type="text" class="form-control date_dmy" name="tanggal_job_order" id="tanggal_job_order" placeholder="Tanggal Job Order" value="<?= date_to_dmy($tanggal_job_order) ?>" />
                    <small class="form-hint"><?= form_error('tanggal_job_order') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Nomor </label>
                <div class="col">
                    <input type="text" class="form-control" name="nomor" id="nomor" placeholder="Nomor" value="<?= $nomor ?>" />
                    <small class="form-hint"><?= form_error('nomor') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Customer </label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="customer" id="customer" placeholder="Customer" value="<?= $customer ?>" /> -->
                    <select class="form-control select2" name="customer">
                        <option value="-1">-</option>
                        <?php foreach($all_customer as $row) { ?>
                        <option value="<?= $row->id ?>" <?= $row->id == $customer ? 'selected' : '' ?>><?= $row->nama ?></option>
                        <?php } ?>
                    </select>
                    <small class="form-hint"><?= form_error('customer') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Shipper </label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="shipper" id="shipper" placeholder="Shipper" value="<?= $shipper ?>" /> -->
                    <select class="form-control select2" name="shipper" id="shipper">
                        <option value="-1">-</option>
                        <?php foreach($all_shipper as $row) { ?>
                        <option value="<?= $row->id ?>" <?= $row->id == $shipper ? 'selected' : '' ?>><?= $row->nama ?></option>
                        <?php } ?>
                    </select>
                    <small class="form-hint"><?= form_error('shipper') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Tanggal Muat </label>
                <div class="col">
                    <input type="text" class="form-control date_dmy" name="tanggal_muat" id="tanggal_muat" placeholder="Tanggal Muat" value="<?= date_to_dmy($tanggal_muat) ?>" />
                    <small class="form-hint"><?= form_error('tanggal_muat') ?></small>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-4 col-form-label required">Lokasi </label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" value="<?= $lokasi ?>" /> -->
                    <select class="form-control select2" name="lokasi" id="lokasi">
                        <option value="-1">-</option>
                        <?php foreach($all_lokasi as $row) { ?>
                        <option value="<?= $row->id ?>" <?= $row->id == $lokasi ? 'selected' : '' ?>><?= $row->nama ?></option>
                        <?php } ?>
                    </select>
                    <small class="form-hint"><?= form_error('lokasi') ?></small>
                </div>
            </div>

            <input type="hidden" name="id" value="<?= $id ?>" />
        </div>

        <!-- detail job order -->
        <div class="card-body col" id="bookmark_table_detail">
            <div class="mb-3 row">
                <label class="col-2 col-form-label required">Detail </label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" value="<?= $lokasi ?>" /> -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Armada</th>
                                <th>Nomor Container</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_detail">

                            <?php if ($this->uri->segment(2) == 'create') { ?>

                            <!-- create -->
                            <tr id="table_row_0">
                                <td>
                                    <select class="form-control select2" name="armada[]">
                                        <option value="-1">-</option>
                                        <?php foreach($all_armada as $row) { ?>
                                            <option value="<?php echo $row->id ?>"><?= $row->merk . ' - ' . $row->nomor_polisi ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="nomor_container[]"></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php } else { ?>

                            <!-- update -->
                            <?php foreach($all_job_order_detail as $key => $detail) { ?>
                                <script type="text/javascript">
                                    ++i
                                </script>
                                <tr id="table_row_<?= $key ?>">
                                    <td>
                                        <select class="form-control select2" name="armada[]">
                                            <option value="-1">-</option>
                                            <?php foreach($all_armada as $row) { ?>
                                                <option value="<?php echo $row->id ?>" <?= $row->id == $detail->armada ? 'selected' : '' ?>><?= $row->merk . ' - ' . $row->nomor_polisi ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="nomor_container[]" value="<?= $detail->nomor_container ?>"></td>
                                    <td><?= $key == 0 ? '&nbsp;' : '<a href="#bookmark_table_detail" onclick="hapus_detail('.$key.')" class="text-danger">Hapus</a>' ?></td>
                                </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><a href="#bookmark_table_detail" onclick="tambah_detail()" class="btn btn-primary mb-2 btn-sm">Tambah Armada</a></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-start">
            <button type="submit" class="btn btn-primary"><?= $button ?></button>
            <a href="<?= site_url('t30_job_order') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#tanggal_job_order').on('change', function () {
            $.ajax({
                type: "POST", //we are using POST method to submit the data to the server side
                url: '<?= site_url() ?>t30_job_order/get_new_nomor_', // get the route value
                data: {tanggal: $(this).val()}, // our serialized array data for server side
                success: function (response) {//once the request successfully process to the server side it will return result here
                	document.getElementById('nomor').value = response;
                },
    	    });
            $('#tanggal_muat').val($(this).val())
        })

        $('#shipper').on('change', function () {
            $.ajax({
                type: "POST", //we are using POST method to submit the data to the server side
                url: '<?= site_url() ?>t02_shipper/get_lokasi_', // get the route value
                data: {id: $(this).val()}, // our serialized array data for server side
                success: function (response) {//once the request successfully process to the server side it will return result here
                	$('#lokasi').val(response).change()
                },
    	    })
        })

    })

    function tambah_detail()
    {
        ++i;
        $('#table_detail').append(
            `
            <tr id="table_row_`+i+`">
                <td>
                    <select class="form-control select2" name="armada[]">
                        <option value="-1">-</option>
                        <?php foreach($all_armada as $row) { ?>
                            <option value="<?= $row->id ?>"><?= $row->merk . ' - ' . $row->nomor_polisi ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="nomor_container[]">
                </td>
                <td>
                    <a href="#bookmark_table_detail" onclick="hapus_detail(`+i+`)" class="text-danger">Hapus</a>
                </td>
            </tr>
            `
        );
        $('.select2').select2();
    }

    function hapus_detail(index)
    {
		$('#table_row_'+index).remove();
	}
</script>
