<?php $this->load->view('includes/header') ?>
	<script>
	$( document ).ready(function() {
			 var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skt_admin_naik'){
				$('#pengajuan_naik').addClass('active');	 
			 }else if(pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skp_admin_naik'){
				$('#pengajuan_naik_skp').addClass('active');	 
			 }else if(pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skp'){
				$('#pengajuan_skp').addClass('active');	 
			 }else if(pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skt'){
				$('#pengajuan_skt').addClass('active');	 
			 }
	});
	</script>
	<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul_histori">*Histori Proses Pengajuan</legend>
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
				<div class="form-group grup-tombol-detail-kanan">
				  <div class="col-lg-10 col-lg-offset-2">
					<a href="<?php echo site_url('all_admin/daftar_'.$this->uri->segment(3)); ?>"><button class="btn btn-primary detail" type="button">Kembali</button></a>
				  </div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>