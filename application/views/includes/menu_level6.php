		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'dashboard' || pathname[3] == 'login'){
				$('#dashboard').addClass('active');	
				$('#judul').text('*Selamat Datang, Admin <?= $this->session->userdata('nama_user_online'); ?>');	 
			 }
			 
			 else if(pathname[3] == 'daftar_pengajuan_skt' || pathname[3] == 'pengajuan_skt_diterima_naik' || pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skt'){
				$('#pengajuan_skt').addClass('active');	
				$('#judul').text('*Pengajuan SKT');	 
			 }		
			 
			 else if(pathname[3] == 'daftar_pengajuan_skp' || pathname[3] == 'pengajuan_skp_diterima_naik' || pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skp' || pathname[3] == 'detail_perusahaan' && pathname[4] == 'pengajuan_skp'){
				$('#pengajuan_skp').addClass('active');	
				$('#judul').text('*Pengajuan SK Penunjukkan Masuk');	 
			 }		
			 
			 else if(pathname[3] == 'konsep_dokumen_skp'){
				$('#pengajuan_skp').addClass('active');	
				$('#judul').text('*Lampirkan Berita Acara');	 
			 }	
			 
			 else if(pathname[3] == 'konsep_dokumen_skt' && pathname[4] == 'pengajuan_skp'){
				$('#pengajuan_skp').addClass('active');	
				$('#judul').text('*Desain Konsep SK Penunjukkan');	 
			 }	
			 
			 else if(pathname[3] == 'konsep_dokumen_skt' && pathname[4] == 'pengajuan_skt'){
				$('#pengajuan_skt').addClass('active');	
				$('#judul').text('*Desain Konsep SKT');	 
			 }	 
			 
			 else if(pathname[3] == 'detail_perusahaan' && pathname[4] == 'pengajuan_skt'){
				$('#pengajuan_skt').addClass('active');	
				$('#judul').text('Detil Perusahaan Pemohon SKT');	 
			 }
			 
			 else if(pathname[3] == 'kotak_masuk' || pathname[3] == 'kotak_keluar'){
				$('#kotak_pesan').addClass('active');		 	 
			 }
			 
			 else if(pathname[3] == 'pengaturan'){
				$('#pengaturan').addClass('active');	
				$('#judul').text('');	 	 
			 }
			 
			 else if(pathname[3] == 'detail_perusahaan' && pathname[4] == 'revisi_skt' || pathname[3] == 'daftar_revisi_evaluator'){
				$('#perubahan_data').addClass('active');	
				$('#judul').text('');	 	 
			 }
			 
			 else if(pathname[3] == 'laporan_berkala_evaluator'){
				$('#pelaporan').addClass('active');		 	 
			 }	
			 
			 else if(pathname[3] == 'cek_status_admin'){
				$('#cek_status').addClass('active');		 	 
			 }	
			 
		});
	</script>
	
		<div class="dashboardmenu">
			<ul class="nav nav-tabs" role="tablist">
			  <li role="presentation" id="dashboard"><a href="<?php echo base_url('all_users/dashboard/');  ?>" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/dashboard-icon.png" class="tab-icon"><br/>Home</a></li>
			  <li role="presentation" id="pengajuan_skt"><a href="<?php echo base_url() ?>all_admin/daftar_pengajuan_skt" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pelaporan-icon.png" class="tab-icon"><br/>Pengajuan SKT</a></li>
			  <li role="presentation" id="pengajuan_skp"><a href="<?php echo base_url() ?>all_admin/daftar_pengajuan_skp" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pelaporan-icon.png" class="tab-icon"><br/>Pengajuan SK Penunjukkan</a></li>
			  <li role="presentation" id="perubahan_data"><a href="<?php echo base_url() ?>all_admin/daftar_revisi_evaluator" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pelaporan-icon.png" class="tab-icon"><br/>Daftar revisi</a></li>
			  <li role="presentation" id="pelaporan"><a href="<?= site_url('all_admin/laporan_berkala_evaluator'); ?>" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/lap_berkala.png" class="tab-icon"><br/>Laporan<br/>Berkala</a></li>
			  <li role="presentation" id="kotak_pesan"><a href="<?php echo base_url() ?>all_admin/kotak_pesan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/mail-icon.png" class="tab-icon"><br/>Riwayat<br/>Pekerjaan</a></li>
			  <li role="presentation" id="cek_status"><a href="<?php echo base_url() ?>all_admin/cek_status_admin" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/search-icon.png" class="tab-icon"><br/>Cari Status<br/>Perizinan</a></li>
			  <li role="presentation" id="pengaturan"><a href="<?php echo base_url() ?>all_users/pengaturan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengaturan-icon.png" class="tab-icon"><br/>Pengaturan</a></li>
			</ul>
		</div>