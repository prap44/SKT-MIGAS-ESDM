<?php $this->load->view('includes/header') ?>
<?php
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $where = 3;
        } elseif ($level == 5) {
            $where = 10;
        } elseif ($level == 6) {
            $where = 102;
        } 

?>
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>SKT Baru</legend>
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
						<?php  $ada= NULL;
						if($data != NULL){
						$i=1; foreach ($data as  $permohonan){ 
							if ($permohonan->jenis_permohonan == 'SKT Baru') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										<tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td><?= '<a href="'.site_url('all_admin/detail_perusahaan/revisi_skt/'.$permohonan->id_permohonan).'">Detail</a>'; ?></td>
										</tr>
						<?php	 $ada = 'ada'; } 
							}
						}
						?>						
						<?php } } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}  ?>
					</tbody>
				</table>
				</div>
			</div>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Perpanjangan SKT</legend>
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
						<?php   $ada= NULL;
						if($data != NULL){
							$i=1; foreach ($data as  $permohonan){ 
							if ($permohonan->jenis_permohonan == 'Perpanjangan SKT') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan)); 
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										<tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td><?= '<a href="'.site_url('all_admin/detail_perusahaan/revisi_skt/'.$permohonan->id_permohonan).'">Detail</a>'; ?></td>
										</tr>
						<?php	 $ada = 'ada'; } 
							}
						}
						?>						
						<?php } } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}  ?>
					</tbody>
				</table>
			</div>			
		</div>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>SK Penunjukkan Baru</legend>
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
						$i=1; foreach ($data as  $permohonan){ 
							if ($permohonan->jenis_permohonan == 'SK Penunjukkan Baru') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan)); 
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										<tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td><?= '<a href="'.site_url('all_admin/detail_perusahaan/revisi_skp/'.$permohonan->id_permohonan).'">Detail</a>'; ?></td>
										</tr>
						<?php	 $ada = 'ada'; } 
							}
						}
						?>						
						<?php } } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}  ?>
					</tbody>
				</table>
			</div>			
		</div>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Perpanjangan SK Penunjukkan</legend>
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
						<?php  $ada= NULL;						
						if($data != NULL){
						$i=1; foreach ($data as  $permohonan){ 
							if ($permohonan->jenis_permohonan == 'Perpanjangan SK Penunjukkan') {
							$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
							if($select_last_disposisi_by_id_permohonan != NULL){
								if ($select_last_disposisi_by_id_permohonan->status_progress == $where) {
										$select_perusahaan = select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan)); 
										$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
										$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang)); ?>
										<tr>
										<td><?= $i++; ?></td>
										<td><?= $select_perusahaan->nama_perusahaan; ?></td>
										<td><?= $bidang_usaha->bidang_usaha; ?></td>
										<td><?= $sub_bidang->sub_bidang; ?></td>
										<td><?= '<a href="'.site_url('all_admin/detail_perusahaan/revisi_skp/'.$permohonan->id_permohonan).'">Detail</a>'; ?></td>
										</tr>
						<?php	 $ada = 'ada'; } 
							}
						}
						?>						
						<?php } } 
						
						if($ada == NULL){
							echo '<tr><td colspan="5">Tidak Ada Record</td></tr>';
						}  ?>
					</tbody>
				</table>
			</div>			
		</div>		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>
