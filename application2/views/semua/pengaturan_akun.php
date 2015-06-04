<?php $this->load->view('includes/header'); ?>	
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>*Ubah Password</legend>
			</div>
			<div class="formcolumn">
		<?= form_open(site_url('all_users/pengaturan'),array('id' => 'basicform', 'name' => 'basicform')); echo validation_errors(); ?> 

        <div id="sf3" class="frm">
			
            <div class="form-group field-isian">              
				<label class="col-lg-2 control-label" for="current_password">Password: </label>
				<div class="col-lg-6">
				<input type="password" placeholder="Password Anda Saat Ini" id="current_password" name="current_password" class="form-control" autocomplete="off" required>
				<?= $this->session->flashdata('current_password');?>
				<?php echo form_error('current_password', '<div class="text-error">', '</div>'); ?>
				</div>

				<label class="col-lg-2 control-label" for="new_password">Password Baru: </label>
				<div class="col-lg-6">
				<input type="password" placeholder="Password Baru" id="new_password" name="new_password" class="form-control" autocomplete="off" required>
				<?= $this->session->flashdata('new_password');?>
				
				</div>

				<label class="col-lg-2 control-label" for="confirm_password">Konfirm Password: </label>
				<div class="col-lg-6">
				<input type="password" placeholder="Ulangi Password Baru Anda" id="confirm_password" name="confirm_password" class="form-control" autocomplete="off" required>
				<?= $this->session->flashdata('new_password');?>
				
				</div>
            </div>

            <div class="clearfix" style="height: 10px;clear: both;"></div>

            <div class="form-group grup-tombol-detail-kanan">
              <div class="col-lg-10 col-lg-offset-2">
                <button class="btn btn-primary" type="submit">Submit </button> 
                <img src="spinner.gif" alt="" id="loader" style="display: none">
              </div>
            </div>

        </div>
      </form>
			</div>
		</div>

<?php $this->load->view('includes/footer') ?>	
	
</body>
</html>