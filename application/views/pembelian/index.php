<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="col-lg-12">
		<h1 class="h3 mb-4 text-gray-800">Pembelian</h1>
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">List transaksi pembelian</h6>
			</div>
			<div class="card-body">
				<?php if ($this->session->flashdata('info')) : ?>
					<div class="alert alert-danger">
						<?php echo $this->session->flashdata('info'); ?>
					</div>
				<?php endif; ?>
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal Pembelian</th>
								<th>Admin</th>
								<th>Pembelian</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; ?>
							<?php foreach ($pembelian as $tr) : ?>
								<tr>
									<td><?php echo $no++; ?></td>
									<td><span class="badge badge-dark"><?php echo $tr->tgl; ?></span></td>
									<td><?php echo $tr->admin; ?></td>
									<td>
										<?php
										$total = 0;
										foreach ($tr->obat as $o) :

										?>
											<span class="text "><?php echo $o->nama_obat; ?> ( <?php echo $o->jumlah; ?> ) = Rp. <?php echo number_format($o->jumlah * $o->harga_beli, 2, ',', '.'); ?></span>
											<br>
										<?php
											$total += $o->jumlah * $o->harga_beli;
										endforeach
										?>
										<hr>
										Total = Rp. <?php echo number_format($total, 2, ',', '.'); ?>
									</td>
									<td>
										<a href="<?php echo site_url('pembelian/hapus/') . $tr->id; ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->