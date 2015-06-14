<?php $this->load->view('includes/header') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			$("#save-and-go-back-button").attr('value', 'Simpan');
			$("#form-button-save").remove();
			//$("#cancel-button").remove();
			
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'pelaporan_periodik' && pathname[4] == ''){
				$('#lanjut').hide();
			 }
		});
	</script>
	
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
						 <?php if($this->uri->segment(2) == 'pelaporan_periodik'){ ?>
						 <div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a id="lanjut" href="<?= base_url('perusahaan/submit_laporan_berkala') ?>"><button class="btn btn-primary lanjut" type="button">Selesai<span class="fa fa-arrow-right"></span></button></a>
									</div>
								</div>
						 <?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>