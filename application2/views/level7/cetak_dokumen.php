<?php $this->load->view('includes/header') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			$('#judul').text('Print & Upload Dokumen');
			$('#cetak_dokumen').addClass('active');	
			$("#save-and-go-back-button").attr('value', 'Simpan');
			$(".ftitle-left").text('Print Dokumen');
			$("#form-button-save").remove();
			$("#cancel-button").remove();
		});

	</script>
	<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul">*Cetak & Upload Dokumen SKT</legend>
		</div>
		<div class="formcolumn">
			<div name="basicform" id="basicform">
				
				<div class="frm isian">
					<div class="multistep">
						<div class="form-group field-isian">
								<?php if(isset($output)){ echo $output; }?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>