<?php $this->load->view('includes/header') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			$("#save-and-go-back-button").attr('value', 'Submit');
			$("#form-button-save").remove();
			$("#cancel-button").remove();
			 $("#flex1 tbody tr td:last").remove();
			 $("#flex1 thead tr th:last").remove();
		});
	</script>
	<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul">*Laporan Berkala</legend>
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