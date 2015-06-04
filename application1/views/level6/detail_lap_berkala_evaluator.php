<?php $this->load->view('includes/header') ?>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend id="judul"></legend>
			</div>
			<div class="formcolumn">
			<h3>Daftar laporan berkala : </h3>
			<table border="1">
				<th>Nama Perusahaan</th>
				<th>No Usaha</th>
				<th>Bidang Usaha</th>
				<th>Laporan Ke-</th>

				<?php foreach ($daftar_lap_berkala as $key => $lap_berkala) :?>
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
					<td><a href="<?= base_url('assets/uploads/file_pelaporan_periodik/'.$lap_berkala->file_pelaporan_periodik) ?>">Lihat</a></td>
				</tr>
			<?php endforeach; ?>

			</table>

			<?php
			echo form_open('all_admin/catatan_lap_berkala');
			echo "<textarea name='catatan_lap_berkala'></textarea>";
			echo "<input type='hidden' name='id_laporan_periodik' value=".$lap_berkala->id_pelaporan_periodik.">";
			echo "<input type='submit' value='catatan'>";
			echo form_close();
			?>

			</div>
		</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>