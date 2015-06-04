<!doctype html>
<html>
<head>
	<meta content="rahmaniar.sr@gmail.com, ycared@gmail.com, www.karyakami.com" name="creator">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Sistem Basis Data Internal Penunjang Migas</title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images') ?>/esdmlogo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/forms.css">
	<script src="<?php echo base_url('assets/scripts') ?>/jquery-1.11.1.min.js"></script>
	<script src="<?php echo base_url('assets/ckeditor') ?>/ckeditor.js"></script>
</head>
<body>
		<?php if(isset($msg)){
			echo $msg;
		}?>
		
	<div class="header">
		<div class="mainhead">
			<div class="rowhead">
				<div class="logo">
					<?php if($this->session->userdata('level') != NULL){
						$link_logo = base_url('all_users/dashboard');
					}else{
						$link_logo= base_url();
					}

					?>
					<a href="<?= $link_logo; ?>"><img src="<?php echo base_url('assets/images') ?>/esdmlogo.png"></a>
					
					<div class="logotitle">
						<div class="logotitle1">Kementerian Energi dan Sumberdaya Mineral</div>
						<div class="logotitle2"><b>Direktorat Teknik dan Lingkungan Migas</b></div>
						<div class="logotitle3">Sistem Basis Data Internal Usaha Penunjang Migas</div>
						<div class="logotitle4">Surat Keterangan Terdaftar (SKT) Migas Online</div>
					</div>
				</div>
			</div>
			
			<div class="navigation">
				<div id="navigation" >				    
					<div class="runningtext"><h2><?= $this->session->userdata('nama_user_online'); ?> <a href="<?= base_url('umum/logout')?>" >Logout</a></h2></div>						
				</div>
			</div>
		</div>	
	</div>
	
	<script>
		$( document ).ready(function() {
			$(".ftitle-left").text("Sistem Basis Data Internal Usaha Penunjang MIGAS \u00A9 2014");		
			<?php if(isset($popup)){
				echo 'alert("'.$popup.'");';
				} ?>
				
			<?php if($this->session->flashdata('message')){
				echo 'alert("'.$this->session->flashdata('message').'");';
				} ?>			
		});
	</script>
	<!-- Load grocerycrud css and js -->
	
	<div class="content">
	<?php if(isset($output)){ ?>
	<?php foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
	<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
	<?php endforeach; }?>
	
	<!-- Load page menu -->
	
	<?php
		$level = $this->session->userdata('level');
		if($level != NULL){
			$this->load->view('includes/menu_level'.$level);	
		}
	?>