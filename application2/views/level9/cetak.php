<?php $this->load->view('includes/header') ?>
	<?php $this->load->view('includes/sub_menu_print') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			CKEDITOR.replace( 'content_template',
			{
				toolbar : 'Tu',
				height : 500,
				width : 650,
				tabSpaces : 4,
				readOnly : true
			});
		});

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
								<div class="column-bidang">
									 <div class="pure-control-group textarea-ckeditor">
										<textarea name="content_template" id="content_template" class="ckeditor"><?= $temp_skt->konten_konsep ?></textarea>
										<input type="hidden" name="no_skt" value="<?= $temp_skt->no_skt_sementara ?>">
									</div>						
								</div>		
								<div class="column-bidang">
									 <div class="pure-control-group textarea-ckeditor">
										<input type="file" name="userfile">
									</div>						
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a href="<?= base_url('all_admin/daftar_dokumen_siap_terbit') ?>"><button class="btn btn-primary lanjut" type="button">Kembali<span class="fa fa-arrow-right"></span></button></a>
										<a href="<?= base_url('all_admin/upload_dokumen_skt/add/'.$this->uri->segment(3)) ?>"><button class="btn btn-primary lanjut" type="button">Upload<span class="fa fa-arrow-right"></span></button></a>
									</div>
								</div>
								<br/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>