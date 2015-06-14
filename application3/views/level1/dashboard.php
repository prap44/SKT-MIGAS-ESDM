<?php $this->load->view('includes/header') ?>
<?php
if(isset($msg)){
	echo $msg;
}
?>

<div class="columnfull">
	<div class="borderbottomdotted">
		<legend>Selamat Datang, <?= $this->session->userdata('nama_user_online'); ?></legend>
	</div>
	<div class="formcolumn">
		<div name="basicform" id="basicform">
			<div class="frm isian">
				<div class="multistep">
					<div class="form-group field-isian">
						<?php
						if(isset($notif)){
							echo $notif;
						}?>

						<?php if($this->session->userdata('status_lap_periodik') == 1){
							echo '<h3>*Mohon agar Anda melakukan proses pelaporan periodik di menu Laporan Berkala</h3>';
							
							$notif_lap_berkala = selects('*', 'pelaporan_periodik', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
							echo "<table border=1>";
							echo "<tr><th>Semseter</th>";
							echo "<th>Bidang</th><th>Status</th>
						</tr>";
						foreach ($notif_lap_berkala as $key => $lap_berkala) {
							$permohonan = select('*', 'permohonan', array('id_permohonan' => $lap_berkala->id_permohonan));
							$semester = select('*', 'ref_semester', array('id_semester' => $lap_berkala->semester));

							if($lap_berkala->status_laporan == 3){
								$sts = 'Diterima';
							}elseif ($lap_berkala->status_laporan == 4) {
								$sts = 'Ditolak, ulangi pengajuan Anda!';
							}

							echo "<tr>";
							echo "<td>".$semester->semester."</td>";
							echo "<td>".$permohonan->sub_bagian_sub_bidang."</td>";
							echo "<td>".$sts."</td>";
							echo "</tr>";
						}
						echo "</table><br>";
					} ?>
					<h3>*Untuk pengisian atau perbaikan form pengajuan SKT dapat dilakukan di menu Pengajuan SKT.</h3>
					<hr/>
					<?php if($data_permohonan):?>

						<table class="table table-bordered table-striped">
							<!-- <th>Perusahaan</th> -->
							<th>Jenis Permohonan</th>
							<th>Bidang Usaha</th>
							<th>Sub Bidang</th>
							<th>Status Progress</th>
							<?php
							echo '<h1>Status pengajuan</h1>';
							foreach ($data_permohonan as $dt_permohonan):
								$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $dt_permohonan->bidang_usaha));
							$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $dt_permohonan->sub_bidang));
							echo "<tr>";
							echo '<td>'. $dt_permohonan->jenis_permohonan.'</td>';
							echo '<td>'. $bidang_usaha->bidang_usaha.'</td>';
							echo '<td>'. $sub_bidang->sub_bidang.'</td>';
							echo '<td>Sedang dalam proses</td>';
							echo "</tr>";
							endforeach;
							?>
						</table>
					<?php endif; ?>

					<?php if ($daftar_skt != NULL){?>
					<h1>Sertifikat SKT</h1>
					<table style="border-color:#000" class="table table-bordered table-striped">
						<!-- <th>Perusahaan</th> -->
						<th>No Dokumen</th>
						<th>Deskripsi</th>
						<th>Mulai Berlaku</th>
						<th>Akhir Berlaku</th>
						<th>File</th>
						<!-- <th>Keterangan</th> -->
						<?php
						if($daftar_skt != NULL){
							foreach ($daftar_skt as $key => $value) {
										// echo $value->id_permohonan;
								echo "<tr>";
								echo '<td>'.$value->no_dokumen.'</td>';
								echo '<td>'.$value->deskripsi.'</td>';
								echo '<td>'.$value->mulai_masa_berlaku.'</td>';
								echo '<td>'.$value->akhir_masa_berlaku.'</td>';
								echo '<td> <a href="'.base_url('assets/uploads/file_skt/'.$value->file_dokumen).'">Dokumen</a></td>';
										// echo '<td>Anda harus mendaftarkan laporan periodik pertama <a href='.site_url('all_admin/detail_perusahaan/laporan_periodik/'.$value->id_permohonan).'>disini!</a></td>';
								echo '</tr>';
							}
						}

						?>
					</table>
					<?php }?>
				</div>
			</div>
		</div>
	</div>				    
</div>
</div>

<?php $this->load->view('includes/footer') ?>

</body>
</html>