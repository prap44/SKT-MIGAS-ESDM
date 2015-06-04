<!DOCTYPE html>

<html lang="en">

<head>
	<title>Sistem Basis Data Internal Penunjang Migas</title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images') ?>/esdmlogo.png">
	<link rel="stylesheet" href="<?php echo base_url('assets/styles') ?>/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/styles') ?>/style.css">
	<script src="<?php echo base_url('assets/scripts') ?>/jquery-1.11.1.min.js"></script>

	<meta charset=utf-8 />
	<?php if(isset($output)){ ?>
	<?php foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
	<?php foreach($js_files as $file): ?>
		<script src="<?php echo $file; ?>"></script>
	<?php endforeach; } ?>
</head>
<body>
	<script type="text/javascript">
		$(document).ready(function (){
			$("#save-and-go-back-button").attr('value', 'Submit');
			$("#form-button-save").remove();
			$("#cancel-button").remove();
			$("#field-email").blur(function(){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('umum/cek_em'); ?>",
					cache: false,				
					data:'email=' + $("#field-email").val(),
					success: function(response){	
						try{
							if(response == 'false'){
								alert("Email sudah digunakan!");
								$("#field-email").val("");
								$("#field-email").attr("style", "border-color:red; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1) inset, 0px 0px 8px red; ");
							}else{						
								$("#field-email").removeAttr("style");
							}										
						}catch(e) {		
							alert('Exception while request..');
						}		
					},
					error: function(){						
						alert('Error while request..');
					}
				});
			});
				   
		});
</script>

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
		</div>	
	</div>
	<div class="content">
		<div class="idealsteps-container">


			<div class="idealsteps-wrap"> 
				<div class="main-content">
					<?php if(isset($output)){ echo $output; }elseif(isset($respon)){ echo $respon; }?>
				</div>


				<footer class="footer">
					Copyright © 2014 ESDM. All Rights Reserved. 
				</footer>

			</div>
		</div>
	</div>
	<script src="<?php echo base_url('assets/scripts/chained.min.js'); ?>"></script>
</body>

</html>
