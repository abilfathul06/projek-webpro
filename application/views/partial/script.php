<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url('assets/admin/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/admin/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('assets/admin/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url('assets/admin/') ?>js/sb-admin-2.min.js"></script>
<script>
    $('.btn-ubah-sup').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#ubahModal').modal('show');
        $.ajax({
            url: `supplier/getAjax/${id}`,
            method: 'POST',
            dataType: 'JSON',
            success: function(data) {
                const {
                    id,
                    nama,
                    alamat,
                    kota,
                    telp
                } = data;
                $('#ubahModal #id').val(id);
                $('#ubahModal #nama').val(nama);
                $('#ubahModal #alamat').val(alamat);
                $('#ubahModal #kota').val(kota);
                $('#ubahModal #telp').val(telp);
            }
        })
    })
    $('.btn-ubah-adm').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#ubahModal').modal('show');
        $.getJSON(`admin/getAjax/${id}`, function(data, status, xhr) {
            const {
                username,
                nama,
                id
            } = data;
            $('#ubahModal #id').val(id);
            $('#ubahModal #username').val(username);
            $('#ubahModal #nama-admin').val(nama);
        })
    })
    let arrayObat = [];
    $('.form-obat table .btn-remove-item').on('click', function() {
        if (arrayObat.length == 0) return alert('Belum ada item obat dipilih!');
        arrayObat = [];
        $('.form-obat table tbody').html('');
        $('.form-obat #data_obat').val('');
        countGrandTotal();
    })
    $('.form-obat .add-item-obat').on('click', function(e) {
        let kode = $('.form-obat #obat').val();
        if (!kode) return alert('Kode obat tidak valid');
        if (arrayObat.filter(item => item.kode == kode).length > 0) return alert('Data Obat Sudah Dipilih');
        if (arrayObat.length == 0) $('.form-obat table tbody .item-kosong').hide();
        $.getJSON(`../obat/getAjax/${kode}`, function(data, status, xhr) {
            let html = `
                <tr id="${data.kode}">
                    <td><button data-kode="${data.kode}" type="button" class="btn-remove-obat btn btn-circle btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                    <td>${data.kode}</td>
                    <td>${data.nama_obat}</td>
                    <td><img src="${data.foto}" width="50"/></td>
                    <td>Rp.${data.harga}</td>
                    <td width="100"><input data-harga="${data.harga}" data-kode="${data.kode}" type="number" class="form-control jumlah" value="1" min="1" /></td>
                    <td>Rp.${data.harga}</td>
                </tr>
                `;
            arrayObat.push({
                kode: data.kode,
                jumlah: 1,
                total: data.harga
            });
            let grand_total = 0;
            arrayObat.forEach(val => grand_total = grand_total + parseInt(val.total));
            $('.form-obat table tbody').append(html)
            $('.form-obat table tfoot').show();
            $('.form-obat .grand-total').html(`<h4>Rp.${grand_total}</h4>`)
            $('.form-obat #data_obat').val(JSON.stringify(arrayObat));
        })
    })
    $('.form-obat table').on('click', '.btn-remove-obat', function() {
        $(this).parent().parent().remove();
        let kode = $(this).data('kode');
        arrayObat = arrayObat.filter(e => e.kode != kode);
        $('.form-obat #data_obat').val(JSON.stringify(arrayObat));
        countGrandTotal();
    })
    $('.form-obat table').on('change', '.jumlah', function() {
        let kode = $(this).data('kode');
        let jumlah = $(this).val();
        let harga = $(this).data('harga');
        let total = harga * jumlah;
        $(`.form-obat #${kode} td:last`).html(`Rp.${total}`)
        objIndex = arrayObat.findIndex((obj => obj.kode == kode));
        arrayObat[objIndex].jumlah = jumlah;
        arrayObat[objIndex].total = total;
        countGrandTotal();
        $('.form-obat #data_obat').val(JSON.stringify(arrayObat));
    })

    function countGrandTotal() {
        let grand_total = 0;
        arrayObat.forEach(val => grand_total = grand_total + parseInt(val.total));
        if (grand_total <= 0) {
            $('.form-obat table tfoot').hide();
            $('.form-obat table tbody .item-kosong').show();
        }
        $('.form-obat .grand-total').html(`<h4>Rp.${grand_total}</h4>`)
    }
    $('.form-obat').on('submit', function(e) {
        e.preventDefault();
        $.post('store', $(this).serialize(), function(data, status, xhr) {
            if (!data.status) {
                $('.error-form').html(data.error);
                let cardOffset = $('#card-transaksi').offset();
                let bodyOffset = $(document).scrollTop();
                if (cardOffset.top <= bodyOffset) {
                    $('html, body').animate({
                        scrollTop: cardOffset.top,
                    }, 1000)
                }
                return;
            }
            document.location.href = '../transaksi';
        }, 'json');
    })


    let arrayObatBeli = [];
    $('.form-obat-beli table .btn-remove-item').on('click', function() {
        if (arrayObatBeli.length == 0) return alert('Belum ada item obat dipilih!');
        arrayObatBeli = [];
        $('.form-obat-beli table tbody').html('');
        $('.form-obat-beli #data_obat').val('');
        countGrandTotalBeli();
    })
    $('.form-obat-beli .add-item-obat').on('click', function(e) {
        let kode = $('.form-obat-beli #obat').val();
        if (!kode) return alert('Kode obat tidak valid');
        if (arrayObatBeli.filter(item => item.kode == kode).length > 0) return alert('Data Obat Sudah Dipilih');
        if (arrayObatBeli.length == 0) $('.form-obat-beli table tbody .item-kosong').hide();
        $.getJSON(`../obat/getAjax/${kode}`, function(data, status, xhr) {
            let html = `
                <tr id="${data.kode}">
                    <td><button data-kode="${data.kode}" type="button" class="btn-remove-obat btn btn-circle btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                    <td>${data.kode}</td>
                    <td>${data.nama_obat}</td>
                    <td><img src="${data.foto}" width="50"/></td>
                    <td>Rp.${data.harga_beli}</td>
                    <td width="100"><input data-harga_beli="${data.harga_beli}" data-kode="${data.kode}" type="number" class="form-control jumlah" value="1" min="1" /></td>
                    <td>Rp.${data.harga_beli}</td>
                </tr>
                `;
            arrayObatBeli.push({
                kode: data.kode,
                jumlah: 1,
                total: data.harga_beli
            });
            let grand_total_beli = 0;
            arrayObatBeli.forEach(val => grand_total_beli = grand_total_beli + parseInt(val.total));
            $('.form-obat-beli table tbody').append(html)
            $('.form-obat-beli table tfoot').show();
            $('.form-obat-beli .grand-total').html(`<h4>Rp.${grand_total_beli}</h4>`)
            $('.form-obat-beli #data_obat').val(JSON.stringify(arrayObatBeli));
        })
    })
    $('.form-obat-beli table').on('click', '.btn-remove-obat', function() {
        $(this).parent().parent().remove();
        let kode = $(this).data('kode');
        arrayObatBeli = arrayObatBeli.filter(e => e.kode != kode);
        $('.form-obat-beli #data_obat').val(JSON.stringify(arrayObatBeli));
        countGrandTotalBeli();
    })
    $('.form-obat-beli table').on('change', '.jumlah', function() {
        let kode = $(this).data('kode');
        let jumlah = $(this).val();
        let harga = $(this).data('harga_beli');
        let total = harga * jumlah;
        $(`.form-obat-beli #${kode} td:last`).html(`Rp.${total}`)
        objIndex = arrayObatBeli.findIndex((obj => obj.kode == kode));
        arrayObatBeli[objIndex].jumlah = jumlah;
        arrayObatBeli[objIndex].total = total;
        countGrandTotalBeli();
        $('.form-obat-beli #data_obat').val(JSON.stringify(arrayObatBeli));
    })

    function countGrandTotalBeli() {
        let grand_total_beli = 0;
        arrayObatBeli.forEach(val => grand_total_beli = grand_total_beli + parseInt(val.total));
        if (grand_total_beli <= 0) {
            $('.form-obat-beli table tfoot').hide();
            $('.form-obat-beli table tbody .item-kosong').show();
        }
        $('.form-obat-beli .grand-total').html(`<h4>Rp.${grand_total_beli}</h4>`)
    }
    $('.form-obat-beli').on('submit', function(e) {
        e.preventDefault();
        $.post('store', $(this).serialize(), function(data, status, xhr) {
            if (!data.status) {
                $('.error-form').html(data.error);
                let cardOffset = $('#card-transaksi').offset();
                let bodyOffset = $(document).scrollTop();
                if (cardOffset.top <= bodyOffset) {
                    $('html, body').animate({
                        scrollTop: cardOffset.top,
                    }, 1000)
                }
                return;
            }
            document.location.href = '../pembelian';
        }, 'json');
    })
</script>
<script src="<?php echo base_url('assets/admin/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url('assets/admin/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>