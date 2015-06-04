<?php $this->load->view('includes/header') ?>
<?= base_url('perusahaan/pelaporan_periodik') ?>
		<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul"></legend>
		</div>
			<div class="formcolumn">
				<div name="basicform" id="basicform">
					<div class="frm isian">
						<div class="multistep">
							
							<div class="form-group field-isian">
								<?= form_open(base_url('perusahaan/laporan_berkala'), array("id"=>"form_jenis_permohonan"))?>
									
								<span id="keterangan" style="color:red; font-size:12px"></span>
								<div id="pilihan_bidang">
							
									<div class="column-bidang">
										<div class="list-bidang" id="jika_perpanjangan">
											<label class="bidang">Nomor SKT</label>: 
											
											<?php $nomor_skt = $this->model->selects('*', 'dokumen_skt', array('id_perusahaan' => $this->session->userdata('id_perusahaan'))); 
											if($nomor_skt != NULL){
												echo '<select><option value="">-Pilih No SKT-</option>';
												foreach($nomor_skt as $no){
													echo '<option value="'.$no->no_dokumen.'" id="'.$no->no_dokumen.'" onclick="pilih_skt(this.id)">'.$no->no_dokumen.'</option></select>
													<br/><label class="bidang">File SKT</label>:  <span id="file_skt">Tidak ada file</span>'; ?>
												<script>
													function pilih_skt(clicked_id) {
														$element = document.getElementById('file_skt');
														$element.innerHTML = '<a href="<?php echo base_url('assets/uploads/file_skt/'.$no->file_dokumen) ?>"><?php echo $no->file_dokumen ?></a><input type="hidden" name="file_dokumen_skt" value="<?php echo $no->id_dokumen ?>">';
														$('#file_skt').attr({type: "text"});
													}
												</script>
											<?php
												}
											}else{
												echo '<select disabled><option value="">Anda Belum Memiliki SKT MIGAS Terdaftar</option></select>
												<br/><label class="bidang">File SKT</label>: <input type="file" style="display:inline" disabled>';
											}  ?>
											
										</div>
										<div class="list-bidang">
											<label class="bidang">Bidang Usaha</label>:  
											<select id="bidang_usaha" name="bidang_usaha">
												<option value="">Pilih Bidang Usaha</option>
												<option id="01" onclick="bidang_usaha(this.id)" value="01">Jasa Konstruksi</option>
												<option id="02" onclick="bidang_usaha(this.id)" value="02">Jasa Non Konstruksi</option>
												<option id="03" onclick="bidang_usaha(this.id)" value="03">Industri Penunjang</option>
											</select>
									 
											<div class="list-bidang" id="sub-bidang"></div>
											<div class="list-bidang" id="bagian-sub-bidang"></div>
											
										</div>								
									</div>
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a id="lanjut" href="#"><button class="btn btn-primary lanjut" type="button">Next<span class="fa fa-arrow-right"></span></button></a>
									</div>
								</div>
								<br/>								
								</form>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
		

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>