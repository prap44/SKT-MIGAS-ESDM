<?php $this->load->view('includes/header') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			$("#save-and-go-back-button").attr('value', 'Submit');
			$("#form-button-save").remove();
			$("#cancel-button").remove();
		});
	</script>
	
	<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul"></legend>
		</div>



		<a href="<?= site_url('admin/ref_template_skt') ; ?>">Konsep Dokumen SKT</a>
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