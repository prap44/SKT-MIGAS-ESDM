<?php $this->load->view('includes/header') ?>
		<script>
			$( document ).ready(function() {
				var pathname = window.location.pathname;
				 pathname = pathname.split("/");
				 
				 if(pathname[3] == 'kotak_masuk'){
					$('#kotak_keluar').removeClass();
					$('#kotak_masuk').addClass('active');
					$('#judul').text('*Kotak Masuk');	 
					
				}else if(pathname[3] == 'kotak_keluar'){
					$('#kotak_masuk').removeClass();
					$('#kotak_keluar').addClass('active');
					$('#judul').text('*Kotak Keluar');	 
					
				}
				
			});	
		</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
					<li><a href="<?php echo base_url() ?>all_admin/kotak_masuk" id="kotak_masuk">Kotak<br/>Masuk</a></li>
					<li><a href="<?php echo base_url() ?>all_admin/kotak_keluar" id="kotak_keluar">Kotak<br/>Keluar</a></li>
				</ul>
			</div>
		</div>
	
	<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul"></legend>
		</div>

		<div class="formcolumn">
			<div name="basicform" id="basicform">
				
				<div class="frm isian">
					<div class="multistep">
						<div class="form-group field-isian">
						 <?php echo $output ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>