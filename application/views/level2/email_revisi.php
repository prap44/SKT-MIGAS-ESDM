<?php $this->load->view('includes/header') ?>
		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Kirim Email Revisi</legend>
			</div>
			<div class="formcolumn">
				<?php echo form_open('admin/revisi_pengajuan_skt/'.$this->uri->segment(3).'/'.$this->uri->segment(4), array('id' => 'basicform')); 
					$var = $this->model->select('id_perusahaan', 'permohonan', array('id_permohonan' => $this->uri->segment(4)));
					$var = $this->model->select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $var->id_perusahaan));
					?>
				
					<div class="frm">					
						<h2><?php echo validation_errors();
							if(isset($msg)){
								echo $msg;
							}?></h2>
						<div class="form-group field-isian">
						  <label class="col-lg-2 control-label" for="upass1">Subjek: </label>
						  <div class="col-lg-6">
							<input name="subject" type="text" value="Pemberitahuan Revisi" class="form-control" autocomplete="off"/>
						  </div>
						
						  <label class="col-lg-2 control-label" for="upass1">Isi Email: </label>
						  <div class="col-lg-6">
							<textarea name="message" class="ckeditor" autocomplete="off">Dear, <?= $var->nama_perusahaan ?>.<br/>Terima kasih, Saudara telah mengajukan permohonan pendaftaran perusahaan usaha penunjang. Data/dokumen yang Saudara kirimkan masih belum sesuai/lengkap. Mohon perbaiki data/dokumen sesuai dengan pemberitahuan yang telah kami kirimkan di Dashboard akun Anda.<br/><br/>Terima kasih.<br/>Hormat Kami,<br/><br/>Direktorat Jenderal Minyak dan Gas Bumi</textarea>
						  </div>
						</div>

						<div class="clearfix" style="height: 10px;clear: both;"></div>

						<div class="form-group grup-tombol-detail-kanan">
						  <div class="col-lg-10 col-lg-offset-2">
							<a href="<?php echo base_url('all_admin/detail_perusahaan/'.$param.'/'.$this->uri->segment(4)); ?>"><button class="btn btn-danger" type="button">Kembali </button></a> &nbsp;
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