<?php $this->load->view('includes/header') ?>
<?php
echo validation_errors();
?>			
		<?php if($param == "pengajuan_skt"){
			$link = "daftar_pengajuan_skt";
		}elseif(($param == "revisi_skp") || ($param == "revisi_skt")){
			if($this->session->userdata('level') == 5){
				$link ="daftar_revisi_kasie";
			}else{
				$link ="daftar_revisi_admin";
			}
		}elseif($param == "pengajuan_skp"){
			$link ="daftar_pengajuan_skp";
		}elseif($param == "pengajuan_skp_admin_naik"){
			$link = "detail_evaluasi/pengajuan_skp/".$this->uri->segment(4);
		}elseif($param == "pengajuan_skt_admin_naik"){
			$link = "detail_evaluasi/pengajuan_skt/".$this->uri->segment(4);
		}else{
			$link ="";
		}?>
		<div style="display:block;height:40px; margin:0!impotant; padding:0!important;">	
			<div class="form-group grup-tombol-detail-kanan">
			  <div class="col-lg-10 col-lg-offset-2">
				<a href="<?php echo site_url('all_admin/'.$link); ?>"><button class="btn btn-primary detail" type="button">Kembali </button></a>
			  </div>
			</div>							
		</div>	
		<?php $this->load->view('includes/content/all_details_no_catatan') ?>
			<div class="form-group grup-tombol-detail-kanan">
			  <div class="col-lg-10 col-lg-offset-2">
				<a class="btn btn-success" href="<?=site_url('all_admin/'.$param.'_diterima/add/'.$this->uri->segment(4))?>">Lanjutkan</a>
			  </div>
			</div>
			
		</div>		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>