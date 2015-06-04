		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 
			 if(pathname[3] == 'proses_dokumen'){
				$('#referensi').removeClass();
				$('#cetak_dokumen').addClass('active');
				$('#print_skt').addClass('active');
				$('#judul').text('Print & Upload Dokumen');	 
				
			}else if(pathname[3] == 'menej_ref'){
				$('#pengumuman').removeClass();
				$('#cetak_dokumen').addClass('active');
				$('#print_laporan').addClass('active');
				$('#judul').text('Manage Referensi');	 
				
			}
			
		});	
		</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
					<li><a href="<?php echo base_url() ?>all_admin/proses_dokumen/edit/"<?= $this->uri->segment(4) ?> id="print_skt">Print<br/>Dokumen SKt</a></li>
					<li><a href="<?php echo base_url() ?>admin/menej_ref" id="print_laporan">Print<br/>Laporan Berkala</a></li>
					<li><a href="<?php echo base_url() ?>admin/menej_ref" id="referensi">Upload<br/>Laporan Berkala</a></li>
				</ul>
			</div>
		</div>