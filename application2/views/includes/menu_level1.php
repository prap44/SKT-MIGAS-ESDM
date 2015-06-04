
		<div class="dashboardmenu">
			<ul class="nav nav-tabs" role="tablist">
			  <li role="presentation" id="dashboard"><a href="<?php echo base_url('all_users/dashboard/');  ?>" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/dashboard-icon.png" class="tab-icon"><br/>Home</a></li>
			  <li role="presentation" id="pengajuan"><a href="<?php echo base_url() ?>perusahaan/pengajuan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengajuan-icon.png" class="tab-icon"><br/>Pengajuan SKT</a></li>
			  <li role="presentation" id="pengajuan_lisensi"><a href="<?php echo base_url() ?>perusahaan/pengajuan_skp" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengajuan-icon.png" class="tab-icon"><br/>Pengajuan<br/>SK Penunjukkan<br/>PJIT</a></li>
			  <li role="presentation" id="pelaporan"><a href="<?php echo base_url() ?>perusahaan/laporan_berkala" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pelaporan-icon.png" class="tab-icon"><br/>Laporan<br/>Berkala</a></li>
			  <li role="presentation" id="pengaturan"><a href="<?php echo base_url() ?>all_users/pengaturan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengaturan-icon.png" class="tab-icon"><br/>Pengaturan</a></li>
			</ul>
		</div>
	<script>
		$( document ).ready(function() {
			<?php if($this->session->userdata('status_lap_periodik') == 0){ ?>
			$('#pelaporan').click(function(){
				alert('Maaf, Menu Laporan Berkala sedang tidak diizinkan berproses.');
			});
			<?php } ?>
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'dashboard' || pathname[3] == 'login' || pathname[3] == 'detail_perusahaan'){
				$('#dashboard').addClass('active');	
				$('#judul').text('Selamat Datang, Admin <?= $this->session->userdata('nama_user_online'); ?>');	 
			 }
			 
			 else if(pathname[3] == 'pengajuan'){
				$('#pengajuan').addClass('active');
			 }
			 
			 else if(pathname[3] == 'pengajuan_skp'){
				$('#pengajuan_lisensi').addClass('active');
			 }
			 
			 else if(pathname[3] == 'status_progress'){
				$('#status').addClass('active');	
				$('#judul').text('Status Progress Pengajuan');	 
			 }
			 
			 else if(pathname[3] == 'laporan_berkala' || pathname[2] == 'perusahaan' && pathname[3] == 'pelaporan_periodik' ){
				$('#pelaporan').addClass('active');	
				$('#judul').text('Laporan Periodik');	 		 
			 }
			 			 
			 else if(pathname[3] == 'pengaturan'){
				$('#pengaturan').addClass('active');	
				$('#judul').text('');	 	 
			 }		
			 
		});
	</script>