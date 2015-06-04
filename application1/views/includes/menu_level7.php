		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'dashboard' || pathname[3] == 'login'){
				$('#dashboard').addClass('active');	
				$('#judul').text('*Selamat Datang, Admin <?= $this->session->userdata('nama_user_online'); ?>');	 
			 }
			 
			 else if(pathname[3] == 'daftar_dokumen_siap_terbit'){
				$('#cetak_dokumen').addClass('active');	
				$('#judul').text('*Daftar Dokumen Siap Cetak');	 
			 }
			 
			 else if(pathname[3] == 'print_dokumen'){
				$('#cetak_dokumen').addClass('active');	
				$('#judul').text('*Print dan Upload Dokumen');	 
			 }	
			 
			 else if(pathname[3] == 'kotak_masuk' || pathname[3] == 'kotak_keluar'){
				$('#kotak_pesan').addClass('active');		 	 
			 }
			 
			 else if(pathname[3] == 'pengaturan'){
				$('#pengaturan').addClass('active');	
				$('#judul').text('');	 	 
			 }	 
			 
			 else if(pathname[3] == 'cek_status_admin'){
				$('#cek_status').addClass('active');		 	 
			 }	
			 
		});
	</script>
	
		<div class="dashboardmenu">
			<ul class="nav nav-tabs" role="tablist">
			  <li role="presentation" id="dashboard"><a href="<?php echo base_url('all_users/dashboard/');  ?>" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/dashboard-icon.png" class="tab-icon"><br/>Home</a></li>
			  <li role="presentation" id="cetak_dokumen"><a href="<?php echo base_url() ?>all_admin/daftar_dokumen_siap_terbit" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pelaporan-icon.png" class="tab-icon"><br/>Dokumen Siap Cetak</a></li>
			  <li role="presentation" id="kotak_pesan"><a href="<?php echo base_url() ?>all_admin/kotak_pesan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/mail-icon.png" class="tab-icon"><br/>Riwayat<br/>Pekerjaan</a></li>
			  <li role="presentation" id="cek_status"><a href="<?php echo base_url() ?>all_admin/cek_status_admin" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/search-icon.png" class="tab-icon"><br/>Cari Status<br/>Perizinan</a></li>
			  <li role="presentation" id="pengaturan"><a href="<?php echo base_url() ?>all_users/pengaturan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengaturan-icon.png" class="tab-icon"><br/>Pengaturan</a></li>
			</ul>
		</div>