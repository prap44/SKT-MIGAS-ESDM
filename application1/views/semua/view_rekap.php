<?php $this->load->view('includes/header') ?>
<?php $level = $this->session->userdata('level'); ?>
	<script type="text/javascript">
		$(document).ready(function (){
			$('#konsep').hide();
			$('#sembunyi').hide();
			
			$('#lihat').click(function(){
				$('#konsep').show();
				$('#sembunyi').show();
				$('#lihat').hide();
			});
			$('#sembunyi').click(function(){
				$('#konsep').hide();
				$('#sembunyi').hide();
				$('#lihat').show();
			});
			<?php if($level == 5){ ?>
			CKEDITOR.replace( 'content_template',
			{
				toolbar : 'Kasie',
				height : 1050,
				width : 650,
				tabSpaces : 4
			});
			<?php }else{ ?>
			CKEDITOR.replace( 'content_template',
			{
				toolbar : 'Rekap',
				width : 650,
				tabSpaces : 4,
				readOnly : true
			});			
			<?php } ?>
		});

	</script>
<?php
if ($this->session->userdata('level') != 5 || $this->session->userdata('level') != 2) {
	$rdo = 'readonly';
	$hide = 'style="display:none"';
	if ($this->session->userdata('level') == 3){
		$hide_3 = 'style="display:none"';
	}else{		
		$hide_3 = '';
	}
}else{
	$rdo ='';
	$hide ='';
	$hide_3 = '';
}

echo validation_errors();
?>	
	
<!--********************************	Penilaian Admin	**************************************-->		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<?php
				$id_perusahaanx = $this->model->select('id_perusahaan','disposisi', array('id_permohonan' => $this->uri->segment(4)));
        
				?>
				<legend>Rekapitulasi <?php $nama = $this->model->select('nama_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $id_perusahaanx->id_perusahaan)); echo $nama->nama_perusahaan; ?></legend>
			</div>
			<div style="text-align:center; width:auto">
				<?php	$var = NULL; 
						if($this->uri->segment(3) == 'pengajuan_skt'){
							$var = 'SKT';
						}elseif($this->uri->segment(3) == 'pengajuan_skp'){
							$var = 'SK Penunjukkan';
						} ?>
				<div class="pure-control-group">
					 <label>Konsep <?= $var.' '.$nama->nama_perusahaan ?></label>						
				</div>
					<button id="lihat" onclick="tampil('konsep')" class="btn btn-primary">Lihat</button>
					<button id="sembunyi" onclick="tampil()" class="btn btn-primary">Sembunyikan</button>
				 <div class="pure-control-group" style="margin-left:200px" id="konsep">		
				<hr style="width:650px"/>
				<br/>
					<?php if($this->session->userdata('level') == 5) { ?>
					<form action="<?= base_url('all_admin/save_doc_kasie/'.$param.'/'.$this->uri->segment(4)) ?>" method="POST">
					<textarea name="content_template" id="content_template" class="ckeditor"><?php echo $konsep_skt->konten_konsep; ?></textarea>
					</form>
					<?php }else{ ?>
					<textarea name="content_template" id="content_template" class="ckeditor"><?php echo $konsep_skt->konten_konsep; ?></textarea>
					<?php } ?>
				<br/>
				<hr style="width:650px"/>
				<br/>
				</div>						
			</div>	
			<div class="tablesection">			
			<h2>1. Penilaian Evaluator</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Bahan Penilaian</th>
							<th>Catatan Penilaian</th>
						</tr>
					</thead>
					<tbody>
						<?php if($evaluator != NULL){						
						$i=1; foreach($evaluator as $mineva):?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $mineva->bahan_penilaian; ?></td>
							<td><?= $mineva->catatan_penilaian; ?></td>
						</tr>
						<?php 
						endforeach; ?>
						<?php }else{?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
				<?php if($dok_pendukung != NULL){ ?>
				<p>Berita acara presentasi : <?= anchor('assets/uploads/file_skp/'.$dok_pendukung->berita_acara_presentasi, 'Dokumen') ?></p>
				<p>Berita acara visitasi : <?= anchor('assets/uploads/file_skp/'.$dok_pendukung->berita_acara_visitasi, 'Dokumen') ?></p>
				<?php } ?>
			</div>
			<hr/>
			
			<div class="form-group grup-tombol-detail-kanan">
			  <div class="col-lg-10 col-lg-offset-2">
				<a class="btn btn-warning" href="<?=site_url('all_admin/daftar_'.$param.'_admin_naik'); ?>">Kembali</a>
				
				<?php if($this->session->userdata('level')==2){ ?>
					<a class="btn btn-danger" href="<?=site_url('admin/revisi_pengajuan_skt/'.$param.'/'.$this->uri->segment(4))?>" >Revisi</a>
				<?php }?>
				
				<?php if($this->session->userdata('level')==5){ 
						if($this->uri->segment(3)!='revisi_skp'){?>
							<a class="btn btn-danger" href="<?=site_url('all_admin/detail_perusahaan/'.$param.'_admin_naik/'.$this->uri->segment(4))?>" >Revisi</a>
						<?php }elseif($this->uri->segment(3)!='revisi_skt'){?>
							<a class="btn btn-danger" href="<?=site_url('all_admin/detail_perusahaan/'.$param.'_admin_naik/'.$this->uri->segment(4))?>" >Revisi</a>
						<?php }else{ ?>
							<a class="btn btn-danger" href="<?=site_url('admin/revisi_pengajuan_skt/'.$param.'/'.$this->uri->segment(4))?>" >Revisi</a>
				<?php } }?>
				
					<a class="btn btn-primary" href="<?=site_url('all_admin/'.$param.'_diterima_naik/add/'.$this->uri->segment(4))?>">					
					<?php if($this->session->userdata('level')==3){?>
						Terbitkan					
					<?php }else{ ?>
						Lanjut
					<?php } ?>
					</a>
			
			  </div>
			</div>
			
		</div>
		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>