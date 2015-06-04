<?php $this->load->view('includes/header') ?>			

		<div class="columnfull">
			<div class="formcolumn">
				<form name="basicform" id="basicform" method="post" action="yourpage.html">

					<div id="sf3" class="frm">
					  <fieldset class="multistep">
						<legend>Ubah Password</legend>

						<div class="form-group field-isian">
						  <label class="col-lg-2 control-label" for="upass1">Password: </label>
						  <div class="col-lg-6">
							<input type="password" placeholder="Your Password" id="upass1" name="upass1" class="form-control" autocomplete="off">
						  </div>
						
						  <label class="col-lg-2 control-label" for="upass1">Confirm Password: </label>
						  <div class="col-lg-6">
							<input type="password" placeholder="Confirm Password" id="upass2" name="upass2" class="form-control" autocomplete="off">
						  </div>
						</div>

						<div class="clearfix" style="height: 10px;clear: both;"></div>

						<div class="form-group grup-tombol-kanan">
						  <div class="col-lg-10 col-lg-offset-2">
							<button class="btn btn-primary" type="button">Submit </button> 
							<img src="spinner.gif" alt="" id="loader" style="display: none">
						  </div>
						</div>

					  </fieldset>
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="<?php echo base_url('assets/scripts') ?>/jquery.validate.min.js"></script>
		<script type="text/javascript">
		  
		  jQuery().ready(function() {

			// validate form on keyup and submit
			var v = jQuery("#basicform").validate({
			  rules: {
			  },
			  errorElement: "span",
			  errorClass: "help-inline-error",
			});
			
		</script>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>