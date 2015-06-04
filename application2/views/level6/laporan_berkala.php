<?php $this->load->view('includes/header') ?>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>*Daftar Pengajuan Laporan Berkala</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nama Perusahaan</th>
							<th>No Usaha</th>
							<th>Bidang Usaha</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
							if($daftar_lap_berkala){
							foreach ($daftar_lap_berkala as $key => $lap_berkala){ ?>
						<?php
							$perusahaan = select('*', 'biodata_perusahaan', array('id_perusahaan' => $lap_berkala->id_perusahaan));
							$skt = select('*', 'dt_dokumen_skt', array('id_permohonan' => $lap_berkala->id_permohonan));
							$mohon = select('*', 'permohonan', array('id_permohonan' => $lap_berkala->id_permohonan));
						 ?>
						<tr>
							<td><?= $perusahaan->nama_perusahaan; ?></td>
							<td><?= $skt->no_dokumen; ?></td>
							<td><?php
									if ($mohon->bidang_usaha == 01) {
										echo "Jasa Kontruksi";
									}elseif ($mohon->bidang_usaha == 02) {
										echo "Jasa Non Kontruksi";
									}else{
										echo "Industri Penunjang";
									}
								?>
							</td>
							<td><a href="<?= site_url('all_admin/detail_lap_berkala_evaluator/'. $mohon->id_permohonan); ?>">Detail</a></td>
						</tr>
					<?php $ada = 'ada'; } 
					} ?>
					<?php if($ada == NULL){ ?>
						<td colspan="5">Tidak ada record</td>
					<?php	} ?>
					</tbody>
				</table>
			</div>
		</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>