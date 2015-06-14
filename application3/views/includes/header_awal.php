<!doctype html>
<html>
<head>
	<meta content="rahmaniar.sr@gmail.com, ycared@gmail.com, www.karyakami.com" name="creator">
	<title>Sistem Basis Data Internal Penunjang Migas</title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images') ?>/esdmlogo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/carousel.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/forms.css">
	<script src="<?php echo base_url('assets/scripts') ?>/jquery-1.11.1.min.js"></script>
	<script src="<?php echo base_url('assets/scripts') ?>/bootstrap.min.js"></script>

</head>
<body>
		<script>
		$( document ).ready(function() {
			
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == '' || pathname[3] == 'index'){
				$('#info, #cek_status').removeClass();
				$('#home').addClass('active');		 
			 }
				
			 else if(pathname[3] == 'info_skt'){
				$('#home, #cek_status').removeClass();
				$('#info').addClass('active');	 
			 }	
				
			 else if(pathname[3] == 'cek_status' || pathname[3] == 'cari_skt'){
				$('#home, #info').removeClass();
				$('#cek_status').addClass('active');	 
			 }	


			 else if(pathname[3] == 'faq' || pathname[3]){
				$('#home, #info, #cek_status').removeClass();
				$('#faq').addClass('active');	 
			 }		
				
		});
	</script>
	<div class="header">
		<div class="mainhead">
			<div class="rowhead">
				<div class="logo">
					<a href="<?= base_url(); ?>"><img src="<?php echo base_url('assets/images') ?>/esdmlogo.png"></a>
					<div class="logotitle">
						<div class="logotitle1">Kementerian Energi dan Sumberdaya Mineral</div>
						<div class="logotitle2"><b>Direktorat Teknik dan Lingkungan Migas</b></div>
						<div class="logotitle3">Sistem Basis Data Internal Usaha Penunjang Migas</div>
						<div class="logotitle4">Surat Keterangan Terdaftar (SKT) Migas Online</div>
					</div>
				</div>
			</div>
			<div class="sub-nav">
				<div id="sub-navigation">
				    <ul id="nav">
				        <li><a href="<?php echo base_url() ?>" id="home" class="active">Halaman Utama</a></li>
				        <li><a href="<?php echo base_url() ?>umum/info_skt" id="info">Seputar SKT Online</a></li>
				        <li><a href="<?php echo base_url() ?>umum/cek_status" id="cek_status">Cari Status Perizinan</a></li>
				        <li><a href="<?php echo base_url() ?>umum/registrasi/add" id="registrasi">Daftar</a></li>
				        <li><a href="<?php echo base_url() ?>umum/login">Login</a></li>
				        <li><a href="<?php echo base_url() ?>umum/faq" id="faq">FAQ</a></li>
				    </ul>
				</div>
			</div>
		</div>	
	</div>