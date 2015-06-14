<?php $this->load->view('includes/header') ?>
<?php
        $level = $this->session->userdata('level');
        if ($level == 5) {
            $where = 9;
        } elseif ($level == 4) {
            $where = 11;
        } elseif ($level == 3) {
            $where = 12;
        } elseif ($level == 2) {
            $where = 13;
        }elseif ($level == 6) {
            $where = 8;
        }

?>
	<?php if($this->session->userdata('level') == 2){ ?>
		<?php $this->load->view('includes/sub_menu_pengajuan') ?>
	<?php } ?>
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Pengajuan SK Penunjukkan Baru Disetujui</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Perusahaan</th>
							<th>Bidang Usaha</th>
							<th>Sub bidang</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada= NULL;
						if($data != NULL){
						$i=1; foreach ($data as  $permohonan):							
						if ($permohonan->jenis_permohonan == 'SK Penunjukkan Baru') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										<tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td style="text-align:right">
											<?= '<a href="'.site_url('all_admin/detail_evaluasi/pengajuan_skp/'.$permohonan->id_permohonan).'">Detail</a> |
											<a href="'.site_url('all_admin/histori_disposisi/pengajuan_skp_admin_naik/'.$permohonan->id_permohonan).'">Histori Progress</a>'; ?>
											<?php if($this->session->userdata('level') != 2):
												if($this->session->userdata('level') != 6){ ?>
												<?= ' | <a href="'.site_url('all_admin/pengajuan_skp_diterima_naik/add/'.$permohonan->id_permohonan).'">Nota</a>'; } ?>
												
											<?php endif; $ada = 'ada'; ?>

										</td>
										</tr>
						<?php	} 
							}
						} endforeach; } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}
						?>
					</tbody>
				</table>
				</div>
			</div>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Pengajuan Perpanjangan SK Penunjukkan Disetujui</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Perusahaa</th>
							<th>Bidang Usaha</th>
							<th>Sub bidang</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php  $ada= NULL;
							if($data != NULL){
							$i=1; foreach ($data as  $permohonan): 
							if ($permohonan->jenis_permohonan == 'Perpanjangan SK Penunjukkan') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										</tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td style="text-align:right">
											<?= '<a href="'.site_url('all_admin/detail_evaluasi/pengajuan_skp/'.$permohonan->id_permohonan).'">Detail</a> |
											<a href="'.site_url('all_admin/histori_disposisi/pengajuan_skp_admin_naik/'.$permohonan->id_permohonan).'">Histori Progress</a>'; ?>
											<?php if($this->session->userdata('level') != 2):
												if($this->session->userdata('level') != 6){ ?>
												<?= ' | <a href="'.site_url('all_admin/pengajuan_skp_diterima_naik/add/'.$permohonan->id_permohonan).'">Nota</a>'; } ?>
												
											<?php endif; $ada = 'ada'; ?>

										</td>
										</tr>
						<?php	} 
							}
						} endforeach; } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}
						?>
					</tbody>
				</table>
			</div>			
		</div>		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>
