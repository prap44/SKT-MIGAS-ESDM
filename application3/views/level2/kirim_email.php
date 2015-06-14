<?php $this->load->view('includes/header') ?>
		
		<div class="columnfull">
			<div class="formcolumn">
				<?php echo form_open('hal/revisi_pengajuan_skt/'.$this->uri->segment(3), array('id' => 'basicform'));
					?>
				
					<div class="frm">
					  <fieldset class="multistep">
						<legend>Kirim Email Penolakkan</legend>
						<h2><?php echo validation_errors();
							if(isset($msg)){
								echo $msg;
							}?></h2>
						<div class="form-group field-isian">
						  <label class="col-lg-2 control-label" for="upass1">Subjek: </label>
						  <div class="col-lg-6">
							<input name="subject" type="text" value="<?php
								if($param == 'regist_baru'){
									echo 'Pemberitahuan Registrasi';
								}elseif($param == 'pengajuan_skt'){
									echo 'Pemberitahuan Revisi';								
								}								
								?>" class="form-control" autocomplete="off"/>
						  </div>
						
						  <label class="col-lg-2 control-label" for="upass1">Isi Email: </label>
						  <div class="col-lg-6">
							<textarea name="message" class="form-control" autocomplete="off"><?php
								if($param == 'regist_baru'){									 
								$var = $this->model->select('nama_perusahaan', 'registrasi', array('id_registrasi' => $this->uri->segment(3)));
									echo  'Dear, '.$var->nama_perusahaan.'. Email Penolakkan Default';
								}elseif($param == 'pengajuan_skt'){
									$var = $this->model->select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $this->uri->segment(3)));
									echo   'Dear, '.$var->nama_perusahaan.'. Email Revisi Default';								
								}								
								?></textarea>
						  </div>
						</div>

						<div class="clearfix" style="height: 10px;clear: both;"></div>

						<div class="form-group grup-tombol-detail-kanan">
						  <div class="col-lg-10 col-lg-offset-2">
							<a href="<?php echo base_url('hal/daftar_register'); ?>"><button class="btn btn-danger" type="button">Kembali </button></a> &nbsp;
							<button class="btn btn-primary" type="submit">Kirim </button> 
						  </div>
						</div>

					  </fieldset>
					</div>
				</form>
			</div>
		</div>
		
<?php $this->load->view('includes/footer') ?>
	
</body>
</html>