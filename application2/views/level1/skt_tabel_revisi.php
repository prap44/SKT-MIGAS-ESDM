<?php $this->load->view('includes/header'); ?>

	<script>
		$( document ).ready(function() {
				$('#pengajuan').addClass('active');
				$(".kirim").click(function (e) {
					$.ajax({
						type: "POST",
						url: "<?php echo base_url('perusahaan/cek_kelengkapan/'); ?>",
						data: {"bagian_sub_bidang": "ya"},
						dataType:"json",
						success: function (response) {
							if(response['response'] == ''){
								if(response['response2'] != ''){
									alert("Mohon perhatian Anda.\nPersentase "+ response['response2'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else{
									window.location = "<?= base_url() .'perusahaan/disposisi_user_to_admin/'. $this->session->userdata('id_perusahaan'); ?>";
								}
							}else{
								if(response['response2'] == ''){
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else{
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nDan persentase "+ response['response2'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}
							}						
						},error: function(response){
							alert("Koneksi terputus!");
							location.reload(true);
							e.preventDefault();
						}
					});			
				});
				
			 $(document).on('submit','form#form_jenis_permohonan',function(){
			   // code
			   var validate = true;
				$('.option').each(function () {
					// Validate
					if (!$(this).find('input').is(':checked')) {
						alert("Anda belum memilih jenis permohonan yang akan diajukan!");
						validate = false;
					}else if ($(this).find('input').is(':checked')){
						if ($("#bidang_usaha")[0].selectedIndex == 0){
							alert("Anda belum memilih Bidang apa pun untuk diajukan!");
							validate = false;
						}else if ($("#bidang_usaha")[0].selectedIndex != 0){
							if ($("#sub_bidang")[0].selectedIndex == 0){
								alert("Anda belum memilih Sub Bidang apa pun untuk diajukan!");
								validate = false;
							}else{
								if (!$(".checkbox-bsb").is(':checked')){
									alert("Anda belum memilih Bagian Sub Bidang apa pun untuk diajukan!");
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
			
			$('input[type=radio][name=jenis_permohonan]').change(function() {
				if (this.value == 'SKT Baru') {
					$("#jika_perpanjangan").hide();
				}
				else if (this.value == 'Perpanjangan SKT') {
					$("#jika_perpanjangan").show();
				}
			});
		});		  
	
		
		
		function bidang_usaha(clicked_id){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/bidang_usaha/'); ?>",
					data: {"bidang_usaha": clicked_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						$("#sub-bidang").html(response['bidangusaha']);	
						$(".label-bsb").remove();
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
			function sub_bidang(clicked_id) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/sub_bidang/'); ?>",
					data: {"sub_bidang": clicked_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						$("#bagian-sub-bidang").html(response['subbidang']);
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
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
			<legend id="judul">*REVISI KLASIFIKASI BIDANG USAHA YANG DIMOHON</legend>
		</div>
			<div class="formcolumn">
				<div name="basicform" id="basicform">
					<div class="frm isian">
						<div class="multistep">
							
							<div class="form-group field-isian">
								<?php if(isset($output)){
										echo $output;

									} ?>
								<?= form_open(base_url('perusahaan/jenis_permohonan_bidang_usaha'), array("id"=>"form_jenis_permohonan"))?>
									
								<span id="keterangan" style="color:red; font-size:12px"></span>
								<div id="pilihan_bidang">
							
									<div class="column-bidang">
										<ul class="option" style="list-style: none;">
										<li style="display: inline;"><label class="bidang">Jenis Permohonan</label>:  </li>
											
										<li style="display: inline;"><input name="jenis_permohonan" id="rdbtn1" type="radio" value="SKT Baru"> Baru
											<input name="jenis_permohonan" id="rdbtn2" type="radio" value="Perpanjangan SKT"> Perpanjangan
										</li></ul>
										<div class="list-bidang">
											<label class="bidang">Bidang Usaha</label>:  
											<select id="bidang_usaha" name="bidang_usaha">
												<option value="">Pilih Bidang Usaha</option>
												<?php $selects_bidang_usaha = selects('*', 'ref_bidang_usaha');
													foreach ($selects_bidang_usaha as $key => $bidang_usaha):
												 ?>

												<option id="<?=  $bidang_usaha->id_bidang_usaha; ?>" onclick="bidang_usaha(this.id)" value="<?=  $bidang_usaha->id_bidang_usaha; ?>"><?=  $bidang_usaha->bidang_usaha; ?></option>
											<?php endforeach; ?>
											</select>
									 
											<div class="list-bidang" id="sub-bidang"></div>
											<div class="list-bidang" id="bagian-sub-bidang"></div>
											
										</div>								
									</div>
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a id="kirim" class="kirim"><button class="btn btn-success lanjut" type="button">Submit</button></a>
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