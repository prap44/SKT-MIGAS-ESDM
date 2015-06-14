<?php $this->load->view('includes/header') ?>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend id="judul"></legend>
			</div>
			<div class="formcolumn">
				<form class="pure-form pure-form-aligned">
				    <fieldset>
						<div class="form-group">
						  <div class="col-lg-10">
								<?php 
				if(isset($daftar_tugas)){ echo $daftar_tugas; }
			?>
						  </div>
						</div>
				    </fieldset>
				</form>
			</div>
			
		</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>