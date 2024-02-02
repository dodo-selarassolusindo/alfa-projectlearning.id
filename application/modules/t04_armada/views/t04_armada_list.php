<link rel="stylesheet" href="<?= base_url('assets/datatables/dataTables.bootstrap5.min.css') ?>"/>

<div class="col-12">
    <div class="card">
        <div class="card-body">

            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4 text-start">
                    <?php echo anchor(site_url('t04_armada/create'), 'Create', 'class="btn btn-primary"'); ?>
                    <?php echo anchor(site_url('t04_armada/excel'), 'Excel', 'class="btn btn-primary"'); ?>
                </div>
                <div class="col-md-4 text-center">
                    <div style="margin-top: 4px"  id="message">
                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    </div>
                </div>
            </div>

            <br>

            <table class="table table-striped nowrap" id="example" style="width:100%">
                <thead>
                    <tr>
                        <th width="80px">No</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Tahun Pembuatan</th>
                        <th>Harga Beli</th>
                        <th>Nomor Polisi</th>
                        <th>Nomor Rangka</th>
                        <th>Nomor Mesin</th>
                        <th>Tanggal Pembelian</th>
                        <th>Tanggal Jatuh Tempo Pajak</th>
                        <th>Tanggal Jatuh Tempo Kir</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/dataTables.bootstrap5.min.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            }
        }

        var table = new DataTable('#example', {
            ajax: '<?= site_url()."t04_armada/json2" ?>',
            processing: true,
            serverSide: true,
            columnDefs: [
                { searchable: false, orderable: false, targets: 0, },
                { searchable: false, orderable: false, targets: 11, },
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo()
                var page = info.iPage
                var length = info.iLength
                var index = page * length + (iDisplayIndex + 1)
                $('td:eq(0)', row).html(index)
            },
            order: [
                [1, 'asc']
            ],
            scrollX: true,
        })

    });
</script>
