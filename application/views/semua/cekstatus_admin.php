<?php $this->load->view('includes/header') ?>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>*Cek Status Perizinan</legend>
			</div>
			<form action="<?= base_url('all_admin/cek_status_admin');?>" method="GET">
				<div class="form-group field-isian text-center">
					<input style="padding:2px 2px 2px 5px" name="keyword" placeholder="Kata kunci..." value="<?php if($this->input->get('keyword', TRUE) != NULL){ echo $this->input->get('keyword', TRUE); }?>"/>
					<select name="jenis_skt" style="padding:2px">
						<option value="0">Pilih kriteria</option>
						<option value="nama" <?php if($this->input->get('jenis_skt', TRUE) == 'nama'){ echo 'selected="selected"'; }?>>Nama Perusahaan</option>
						<option value="no_skt" <?php if($this->input->get('jenis_skt', TRUE) == 'no_skt'){ echo 'selected="selected"'; }?>>No SKT</option>
						<option value="no_verify" <?php if($this->input->get('jenis_skt', TRUE) == 'no_verify'){ echo 'selected="selected"'; }?>>No Verifikasi</option>
					</select>
					<input type="submit" value="Cari">
				</div>
			</form>
			<div class="tablesection">
			<hr/>
				<?php $ada = NULL;
				if (isset($response) && $response != NULL){?>
					<table class="table table-bordered table-striped">
						<thead>
							<th>No Dokumen</th>
							<th>Nama Perusahaan</th>
							<th>Mulai Berlaku</th>
							<th>Akhir Berlaku</th>
							<th>Dokumen</th>
						</thead>
						<tbody>
			<?php	foreach ($response as $key => $respon) {
						echo "<tr>";
						echo '<td>'.$respon->no_dokumen.'</td>';
						echo '<td>'.$respon->nama_perusahaan.'</td>';
						echo '<td>'.$respon->mulai_masa_berlaku.'</td>';
						echo '<td>'.$respon->akhir_masa_berlaku.'</td>';
						echo '<td>'.anchor(base_url('assets/uploads/file_skt/'.$respon->file_dokumen), 'Lihat dokumen').'</td>';
						echo "</tr>";
						$ada = 'ada';
					} ?>	
						<tbody>
				</table>
		<?php	}else{
					if (isset($psn)) {
						echo $psn;
					}
				}
				
			?>
			<?php if(isset($links)){ echo $links; } ?>
			</div>
		</div>
	

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>