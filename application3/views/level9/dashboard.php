<?php $this->load->view('includes/header') ?>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend id="judul"></legend>
			</div>
			<div class="formcolumn">
				<form class="pure-form pure-form-aligned">
				    <fieldset>
						<div class="form-group">
						  <div class="col-lg-10">
				        <?php
						if($this->session->userdata('status_lap_periodik') != NULL){
							if($this->session->userdata('status_lap_periodik') == 0){ ?>
												
						  <b><label for="name">*Laporan periodik sedang TIDAK AKTIF. Klik untuk mengaktifkan</label></b> 
							<a href="<?php echo base_url('admin/aktivasi_laporan/aktif') ?>"><button class="btn btn-primary detail" type="button">Aktifkan</button></a>
							
						<?php	}elseif($this->session->userdata('status_lap_periodik') == 1){ ?>
						
						  <b><label for="name">*Laporan periodik sedang AKTIF. Klik untuk menonaktifkan</label></b>
							<a href="<?php echo base_url('admin/aktivasi_laporan/nonaktif') ?>"><button class="btn btn-danger detail" type="button">Nonaktifkan</button></a>
						  
						<?php	}
						}else{ ?>
						
						  <b><label for="name">*Laporan periodik sedang TIDAK AKTIF. Klik untuk mengaktifkan</label></b>
							<a href="<?php echo base_url('admin/aktivasi_laporan/aktif') ?>"><button class="btn btn-primary detail" type="button">Aktifkan</button></a>
						  
						<?php	}    ?>
						  </div>
						</div>
				    </fieldset>
				</form>
			</div>
			<?php 
				if(isset($daftar_tugas)){ echo $daftar_tugas; }
			?>
		</div>

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>