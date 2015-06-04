<?php $this->load->view('includes/header') ?>
<?php
if ($this->session->userdata('level') == 2) {
	if (($this->uri->segment(3) == 'revisi_skp') || ($this->uri->segment(3) == 'revisi_skt')) {
		$hide ='style="display:none"';
		$hide_6 = 'style="display:none"';
	}else{
		$hide ='';
		$hide_6 = '';
	}
		$rdo ='';
		$unhide_6 = 'style="display:none"';
}elseif($this->session->userdata('level') == 6){
	$rdo = 'readonly';
	$hide ='style="display:none"';
	$hide_6 = 'style="display:none"';
	$unhide_6 = '';
}else{
	$rdo = 'readonly';
	$hide = 'style="display:none"';
	$unhide_6 = 'style="display:none"';
	$hide_6 = '';
}
echo validation_errors();
/* 
if($this->session->flashdata('message') != NULL){
	?><script>alert("<?= $this->session->flashdata('message') ?>")</script><?php
} */

// $id_per = $this->model->selec

?>			
		<?php $this->load->view('includes/content/all_details_no_catatan') ?>
			<div class="form-group grup-tombol-detail-kanan">
			  <div class="col-lg-10 col-lg-offset-2">
				<a class="btn btn-danger" href="<?=site_url('admin/revisi_pengajuan_skt/'.$param.'/'.$this->uri->segment(4))?>" <?= $hide; ?>>Revisi</a>
				<a class="btn btn-success" href="<?=site_url('all_admin/'.$param.'_diterima/add/'.$this->uri->segment(4))?>" <?= $hide_6; ?>>Disposisi</a>
				<a class="btn btn-success" href="<?=site_url('all_admin/'.$param.'_diterima_naik/add/'.$this->uri->segment(4))?>" <?= $unhide_6; ?>>Terima</a>
			  </div>
			</div>
			
		</div>		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>