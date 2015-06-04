<?php $this->load->view('includes/header') ?>

	<?php $hide = ''; 	
		$data_permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan')));
			if($data_permohonan != NULL){
				if($data_permohonan->selesai == 0){
					if($data_permohonan->jenis_permohonan == 'SK Penunjukkan Baru' || $data_permohonan->jenis_permohonan == 'Perpanjangan SK Penunjukkan'){
						$hide = '';
						$pesan = 'Anda sedang dalam proses pengajuan permohonan SK Penunjukkan PJIT MIGAS.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan untuk bidang lainnya.<br/>Klik tombol <b>Next</b> untuk melanjutkan proses pengajuan.';
					}else{
						$hide = 'display:none';
						$pesan = 'Anda sedang dalam proses pengajuan permohonan Surat Keterangan Terdaftar(SKT) MIGAS.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan untuk bidang lainnya.<br/>Klik menu <b>Pengajuan SKT</b> untuk melanjutkan proses pengajuan.';
					}
				}elseif($data_permohonan->selesai == 1){
					$hide = 'display:none';
					$pesan = 'Anda sedang dalam proses perbaikan data pengajuan.<br/>Lengkapi data dan selesaikan proses perbaikan pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Mohon diperhatikan, <b>proses perbaikan pengajuan hanya dapat dilakukan 1 (satu) hari saja</b>.<br/>Klik tombol <b>Lihat Catatan</b> untuk melanjutkan proses perbaikan.'; ?>
					<script>	var path = 'Revisi';	</script>	<?php
				}elseif($data_permohonan->selesai == 2){
					$hide = 'display:none';
					$pesan = 'Anda sedang dalam proses pelaporan berkala.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Klik menu <b>Laporan Berkala</b> untuk melanjutkan proses pengajuan.'; ?>
					<script>	var path = 'Periodik';	</script>	<?php
				}
			} ?>
			
	<script>
		$( document ).ready(function() {
			//$("#jika_perpanjangan").hide();
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'pengajuan_skp'){
				$('#judul').text('*KLASIFIKASI BIDANG USAHA YANG DIMOHON');
				<?php if($this->session->userdata('id_permohonan') == NULL || $this->session->userdata('id_permohonan') == 0) {?>
				$('#lanjut').removeAttr("href");
				$('.lanjut').attr("type", "submit");
				<?php }else{ ?>
				$('#keterangan').after('<p style="text-align: center; color: #000; "><?= $pesan ?><br/>-Admin Sistem- </p>');
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/data_pemohon");
				$('#pilihan_bidang').hide();
				<?php } ?>
			 }			
			 
			 $(document).on('submit','form#form_jenis_permohonan',function(){
			   // code
			//var div = document.getElementById("jika_perpanjangan");
			var span = $("#jika_perpanjangan").find("#file_skt");

			   var validate = true;
				$('.option').each(function () {
					// Validate
					if (!$(this).find('input').is(':checked')) {
						alert("Anda belum memilih jenis permohonan yang akan diajukan!");
						validate = false;
					}else if ($(this).find('input').is(':checked')){
						if ($("#no_skt")[0].selectedIndex == 0){
							alert("Anda belum memilih atau tidak memiliki dokumen SKT!");
							validate = false;
						}else if ($("#no_skt")[0].selectedIndex != 0){
							if (!$(".checkbox-bsb").is(':checked')){
								alert("Anda belum memilih Bagian Sub Bidang apa pun untuk diajukan!");
								validate = false;
							}else if($(".checkbox-bsb").is(':checked')){					
								if(span.text() == 'Tidak ada file'){	
									alert("Anda belum memilih atau tidak memiliki dokumen SKT!");				
									validate = false;
								}else{												
									validate = true;
								}
							}
							
						}
					}
				});				
					return validate;
			});
			
			/* $('input[type=radio][name=jenis_permohonan]').change(function() {
				if (this.value == 'SK Penunjukkan Baru') {
					$("#jika_perpanjangan").hide();
				}
				else if (this.value == 'Perpanjangan SK Penunjukkan') {
					$("#jika_perpanjangan").show();
				}
			}); */
		});	
			function bagian_sub_bidang(clicked_id) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/bagian_sub_bidang/'); ?>",
					data: {"bagian_sub_bidang": clicked_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						if(document.getElementById('bsb-' + clicked_id).checked==true){
							$("#sub-bagian-sub-bidang-" + clicked_id).html(response['bagiansubbidang']);
						}else if(document.getElementById('bsb-' + clicked_id).checked==false){
							document.getElementById('sub-bagian-sub-bidang-'+clicked_id).remove();
							$('#label-bsb-'+ (clicked_id+1)).before('<div class="chk-sbsb" id="sub-bagian-sub-bidang-'+ clicked_id +'"></div>');
						}						
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
			function sub_bagian_sub_bidang_lainnya(clicked_id, induk_id){
				if(document.getElementById(clicked_id).value != ''){
					$('#'+induk_id).prop('checked', true);
				}else if(document.getElementById(clicked_id).value == ''){
					$('#'+induk_id).prop('checked', false);
				}
			}

	</script>

		<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul"></legend>
		</div>
			<div class="formcolumn">
				<div name="basicform" id="basicform">
					<div class="frm isian">
						<div class="multistep">
							
							<div class="form-group field-isian">
								<?= form_open(base_url('perusahaan/jenis_permohonan_bidang_usaha_skp'), array("id"=>"form_jenis_permohonan"))?>
									
								<span id="keterangan" style="color:red; font-size:12px"></span>
								<div id="pilihan_bidang">
							
									<div class="column-bidang">
									
										<ul class="option" style="list-style: none; margin-bottom:0!important;">
											<li style="display: inline;"><label class="bidang">Jenis Permohonan</label>:  </li>
												
											<li style="display: inline;"><input name="jenis_permohonan" id="rdbtn1" type="radio" value="SK Penunjukkan Baru"> Baru
												<input name="jenis_permohonan" id="rdbtn2" type="radio" value="Perpanjangan SK Penunjukkan"> Perpanjangan
											</li>
										</ul>
										<div class="list-bidang" id="jika_perpanjangan">
											<label class="bidang">Nomor SKT</label>: 
											
											<?php $nomor_skt = $this->model->selects('*', 'dokumen_skt', array('id_perusahaan' => $this->session->userdata('id_perusahaan'))); 
											if($nomor_skt != NULL){
												echo '<select name="file_dokumen_skt" id="no_skt"><option value="">-Pilih No SKT-</option>';
												foreach($nomor_skt as $nom){
													echo '<option value="'.$nom->file_dokumen.'" name="'.$nom->id_dokumen.'" id="dokumen-'.$nom->id_dokumen.'" onclick="pilih_skt(this)">'.$nom->no_dokumen.'</option>'; ?>
												<script>
													function pilih_skt(clicked) {
														$element = document.getElementById('file_skt');
														$element.innerHTML = '<a href="<?php echo base_url('assets/uploads/file_skt') ?>/'+ clicked.value +'">'+ clicked.value +'</a><input name="id_dokumen" type="hidden" value="'+ clicked.getAttribute("name") +'">';
														$('#file_skt').attr({type: "text"});
													}
												</script>
											<?php
												} echo '</select>
													<br/><label class="bidang">File SKT</label>:  <span id="file_skt">Tidak ada file</span>';
											}else{
												echo '<select disabled id="no_skt"><option value="">Anda belum memiliki SKT MIGAS terdaftar</option></select>
												<br/><label class="bidang">File SKT</label>: <input type="file" style="display:inline" disabled>';
											}  ?>
											
										</div>
										
										
										<div class="list-bidang">
											<label class="bidang">Bidang Usaha</label>:  
												<input disabled value=" Jasa Non Konstruksi" />
												<input id="bidang_usaha" name="bidang_usaha" type="hidden" value="02" />
									 
											<div class="list-bidang" id="sub-bidang">
												<label class="bidang">Sub Bidang</label>: 
												<input name="sub_bidang" id="sub_bidang" value="14" type="hidden">
												<input value=" Inspeksi Teknis" disabled />
											</div>
											<div class="list-bidang" id="bagian-sub-bidang">
												<label class="bidang">Bagian Sub Bidang<br/>& Sub Bagian Sub Bidang</label>: <br/>
												<label class="label-bsb" id="label-bsb-90"><input type="checkbox" name="bagian_sub_bidang[]" class="checkbox-bsb" id="bsb-90" onchange="bagian_sub_bidang(90)" value="90"> Inspeksi Teknis Statutory</label>
												<div class="chk-sbsb" id="sub-bagian-sub-bidang-90"></div>
												
												<label class="label-bsb" id="label-bsb-91"><input type="checkbox" name="bagian_sub_bidang[]" class="checkbox-bsb" id="bsb-91" onchange="bagian_sub_bidang(91)" value="91"> Inspeksi Teknis Voluntary</label>
												<div class="chk-sbsb" id="sub-bagian-sub-bidang-91"></div>
											</div>
											
										</div>									
									</div>
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan" style="<?= $hide?>">
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