<?php $this->load->view('includes/header') ?>
	<script type="text/javascript">
		$(document).ready(function (){
			CKEDITOR.replace( 'content_template',
			{
				height : 1050,
				width : 650,
				tabSpaces : 4,
				resize_enabled : false
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
							<?= form_open(base_url('all_admin/simpan_konsep/'.$this->uri->segment(3).'/'.$this->uri->segment(4)), array("id"=>"form_jenis_permohonan"))?>
									
								<div class="column-bidang">
									 <div class="pure-control-group textarea-ckeditor">
										<textarea name="content_template" id="content_template" class="ckeditor"><?= $konsep_skt?></textarea>
										<input type="hidden" name="no_skt_sementara" value="<?= $no_skt_sementara ?>">
									</div>						
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a href="<?= base_url('all_admin/detail_perusahaan/'.$this->uri->segment(3).'/'.$this->uri->segment(4)) ?>"><button class="btn btn-primary lanjut" type="button">Kembali<span class="fa fa-arrow-right"></span></button></a>
										<button class="btn btn-success lanjut" type="submit">Nota<span class="fa fa-arrow-right"></span></button>
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