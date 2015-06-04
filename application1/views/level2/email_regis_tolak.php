<?php $this->load->view('includes/header') ?>
		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Kirim Email Revisi</legend>
			</div>
			<div class="formcolumn">
				<?php echo form_open('admin/regis_tolak/'.$this->uri->segment(3), array('id' => 'basicform')); 
					// $var = $this->model->select('id_perusahaan', 'permohonan', array('id_permohonan' => $this->uri->segment(4)));
					// $var = $this->model->select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $var->id_perusahaan));
					?>
				
					<div class="frm">					
						<h2><?php echo validation_errors();
							if(isset($msg)){
								echo $msg;
							}

							$email_template_tolak = select('*', 'email_template', array('id_email_template' => 1))

							?></h2>
						<div class="form-group field-isian">
						  <label class="col-lg-2 control-label" for="upass1">Subjek: </label>
						  <div class="col-lg-6">
							<input name="subject" type="text" value="<?= $email_template_tolak->subject_email_template; ?>" class="form-control" autocomplete="off"/>
						  </div>
						
						  <label class="col-lg-2 control-label" for="upass1">Isi Email: </label>
						  <div class="col-lg-6">
							<textarea name="message" class="ckeditor" autocomplete="off">Mohon maaf registrasi Anda ditolak. <br/>Untuk pengajuan SKT/SK Penunjukkan PJIT, silahkan Anda registrasi ulang. <br/>Terima kasih. <br/><br/>Hormat Kami, <br/><br/> Direktorat Jendral Minyak dan Gas Bumi.</textarea>
						  </div>
						</div>

						<div class="clearfix" style="height: 10px;clear: both;"></div>

						<div class="form-group grup-tombol-detail-kanan">
						  <div class="col-lg-10 col-lg-offset-2">
							<!-- <a href="<?php echo base_url('all_admin/detail_perusahaan/'.$param.'/'.$this->uri->segment(4)); ?>"><button class="btn btn-danger" type="button">Kembali </button></a> &nbsp; -->
							<button class="btn btn-primary" type="submit">Kirim </button> 
						  </div>
						</div>

					</div>
				</form>
			</div>
		</div>
		
<?php $this->load->view('includes/footer') ?>
	
</body>
</html>