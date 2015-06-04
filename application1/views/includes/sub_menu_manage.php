		<script>
		$( document ).ready(function() {
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 
			 if(pathname[3] == 'menej_pengumuman'){
				$('#kelola').addClass('active');
				$('#pengumuman').addClass('active');
				$('#judul').text('Manage Pengumuman');	 
				
			}else if(pathname[3] == 'menej_ref'){
				$('#kelola').addClass('active');
				$('#referensi').addClass('active');
				$('#judul').text('Manage Referensi');	 
				
			}else if(pathname[3] == 'daftar_member'){
				$('#kelola').addClass('active');
				$('#member').addClass('active');
				$('#judul').text('Daftar Perusahaan');	 
				
			}
			
		});	
		</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
					<li><a href="<?php echo base_url() ?>admin/menej_pengumuman" id="pengumuman">Kelola<br/>Pengumuman</a></li>
					<li><a href="<?php echo base_url() ?>admin/menej_ref" id="referensi">Kelola<br/>Referensi</a></li>
					<li><a href="<?php echo base_url() ?>all_admin/daftar_member" id="member">Daftar Member<br/>Perusahaan</a></li>
				</ul>
			</div>
		</div>