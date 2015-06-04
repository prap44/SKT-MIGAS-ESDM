		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 
			 if(pathname[3] == 'daftar_pengajuan_skt' || pathname[3] == 'pengajuan_skt_diterima' || pathname[4] == 'pengajuan_skt' || pathname[4] == 'perpanjangan_skt' || pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skt' || pathname[3] == 'detail_perusahaan' && pathname[4] == 'perpanjangan_skt'){
				$('#skt_masuk').addClass('active');
				$('#pengajuan_skt').addClass('active');	
				
			}else if(pathname[3] == 'daftar_pengajuan_skp' || pathname[3] == 'pengajuan_skp_diterima' || pathname[4] == 'pengajuan_skp' || pathname[4] == 'perpanjangan_skp' || pathname[3] == 'histori_disposisi' && pathname[4] == 'pengajuan_skp'){
				$('#skp_masuk').addClass('active');
				$('#pengajuan_skt').addClass('active');	
			}
		});	
		</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
					<li><a href="<?php echo base_url() ?>all_admin/daftar_pengajuan_skt" id="skt_masuk">Pengajuan<br/>SKT Masuk</a></li>
					<li><a href="<?php echo base_url() ?>all_admin/daftar_pengajuan_skp" id="skp_masuk">Pengajuan<br/>SK Penunjukkan Masuk</a></li>
				</ul>
			</div>
		</div>