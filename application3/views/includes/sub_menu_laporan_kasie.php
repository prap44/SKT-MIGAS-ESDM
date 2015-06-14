		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 
			 if(pathname[3] == 'laporan_berkala_kasie'){
				$('#masuk').addClass('active');
				$('#judul').text('*Pelaporan Berkala');	 
				
			}else if(pathname[3] == 'laporan_dievaluasi_kasie'){
				$('#dievaluasi').addClass('active');
				$('#judul').text('*Pelaporan Berkala');	 
				
			}
			
		});	
		</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
					<li><a href="<?php echo base_url() ?>all_admin/laporan_berkala_kasie" id="masuk">Laporan<br/>Masuk</a></li>
					<li><a href="<?php echo base_url() ?>all_admin/laporan_dievaluasi_kasie" id="dievaluasi">Laporan Sudah<br/>Dievaluasi</a></li>
				</ul>
			</div>
		</div>