<?php $this->load->view('includes/header'); $laporan = NULL; $link_submit = NULL; ?>
	<script>	var path = '';	</script>
	<?php 	$data_permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan')));
			$bid = $this->model->select('*', 'ref_bidang_usaha', array('id_bidang_usaha' => $data_permohonan->bidang_usaha));
			$subbid = $this->model->select('*', 'ref_sub_bidang', array('id_sub_bidang' => $data_permohonan->sub_bidang));
			if($data_permohonan->bagian_sub_bidang != NULL){
				$bagsubbid = $this->model->select('*', 'ref_bagian_sub_bidang', array('id_bagian_sub_bidang' => $data_permohonan->bagian_sub_bidang));
			}else{
				$bagsubbid = NULL;
			}
			if($data_permohonan != NULL){
				if($data_permohonan->selesai == 0){
					if($data_permohonan->jenis_permohonan == 'SKT Baru' || $data_permohonan->jenis_permohonan == 'Perpanjangan SKT'){
						$pesan = 'Anda sedang dalam proses pengajuan permohonan Surat Keterangan Terdaftar(SKT) MIGAS.<br/>SKT yang Anda ajukan untuk Bidang <b>'.$bid->bidang_usaha.'</b> Sub Bidang <b>'.$subbid->sub_bidang.'</b>.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Klik tombol <b>Next</b> untuk melanjutkan proses pengajuan.'; 
						$hide = "$('.lanjut, #revisi').show();";
						$link_submit = base_url() .'perusahaan/disposisi_user_to_admin/'. $this->session->userdata('id_perusahaan'); ?> 
						<script>	var path = 'SKT';	</script><?php
					$laporan = 'non-active';
					}else{
						$pesan = 'Anda sedang dalam proses pengajuan permohonan SK Penunjukkan PJIT MIGAS.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Klik menu <b>Pengajuan SK Penunjukkan PJIT</b> untuk melanjutkan proses pengajuan.';
						$hide = "$('.lanjut').hide();";
						$link_submit = base_url() .'perusahaan/disposisi_user_to_admin/'. $this->session->userdata('id_perusahaan'); ?>
						<script>	var path = 'SKP';	</script>	<?php
					$laporan = 'non-active';
					}
				}elseif($data_permohonan->selesai == 1){
					$hide = "$('.lanjut').hide();";
					$pesan = 'Anda sedang dalam proses perbaikan data pengajuan.<br/>Lengkapi data dan selesaikan proses perbaikan pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Mohon diperhatikan, <b>proses perbaikan pengajuan hanya dapat dilakukan 1 (satu) hari saja</b>.<br/>Klik tombol <b>Lihat Catatan</b> untuk melanjutkan proses perbaikan.';
					$link_submit = base_url() .'perusahaan/submit_revisi/'; ?>
					<script>	var path = 'Revisi'; </script>	<?php
					$laporan = 'non-active';
				}elseif($data_permohonan->selesai == 2){
					$hide = "$('.lanjut').hide();";
					$pesan = 'Anda sedang dalam proses pelaporan berkala.<br/>Lengkapi data dan selesaikan proses pengajuan yang sedang berjalan untuk bisa mengajukan permohonan lainnya.<br/>Klik menu <b>Laporan Berkala</b> untuk melanjutkan proses pengajuan.';
					$link_submit = base_url() .'all_admin/detail_perusahaan/laporan_periodik/'.$this->session->userdata('id_permohonan'); ?>
					<script>	var path = 'Periodik';	</script>	<?php
					$laporan = 'active';
				} 
			} ?>

	<script>
		$( document ).ready(function() {
			$("#jika_perpanjangan").hide();
			$(".active-result").attr("onchange", "coba()");
			$('#pilihan_bidang').hide();
			$('#kirim').hide();
			$('#revisi').hide();
			$('#kirim2').hide();
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[4] == 'add'){
				$('#kembali').hide();
				$('#lanjut').hide();
				$('#kirim').hide();
			 }
			 if(pathname[3] == 'pengajuan'){
				$('.sub-nav').hide();	
				$('#kembali').hide();	
				$('#judul').text('*KLASIFIKASI BIDANG USAHA YANG DIMOHON');	
				<?php if($this->session->userdata('id_permohonan') == NULL || $this->session->userdata('id_permohonan') == 0) {?>
				$('#lanjut').removeAttr("href");
				$('.lanjut').attr("type", "submit");
				$('#pilihan_bidang').show();
				<?php }else{ ?>
				$('#keterangan').after('<p style="text-align: center; color: #000; "><?= $pesan ?><br/>-Admin Sistem- </p>');
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/data_pemohon");
				<?= $hide ?>
				<?php } ?>
			 }
			 
			 else if(pathname[3] == 'data_pemohon'){
				$('#keanggotaan_asosiasi, #tenaga_kerja, #pelatihan_pekerja, #peralatan, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#data_pemohon').addClass('active');		
				$('#kembali').hide();	
				$('#judul').text('A. DATA PEMOHON');	 
				// $('#keterangan').text('');	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/keanggotaan_asosiasi");
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'keanggotaan_asosiasi'){
				$('#data_pemohon, #tenaga_kerja, #pelatihan_pekerja, #struktur_organisasi, #peralatan, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#keanggotaan_asosiasi').addClass('active');	
				$('#judul').text('B. KEANGGOTAAN ASOSIASI PERUSAHAAN ');	
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/struktur_organisasi"); 
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/data_pemohon"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'struktur_organisasi'){
				$('#data_pemohon, #tenaga_kerja, #pelatihan_pekerja, #keanggotaan_asosiasi, #peralatan, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#struktur_organisasi').addClass('active');	
				$('#judul').text('C. STRUKTUR ORGANISASI PERUSAHAAN ');	
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/data_tenaga_kerja"); 
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/keanggotaan_asosiasi"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'data_tenaga_kerja'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #pelatihan_pekerja, #peralatan, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#tenaga_kerja').addClass('active');	
				$('#judul').text('D. DATA TENAGA KERJA');	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/pelatihan_tenaga_kerja");
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/struktur_organisasi"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'pelatihan_tenaga_kerja'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #peralatan, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#pelatihan_pekerja').addClass('active');	
				$('#judul').text('E. PELATIHAN TENAGA KERJA ');	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/peralatan");
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/data_tenaga_kerja"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'peralatan'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #pelatihan_pekerja, #nilai_investasi, #pengalaman, #sop, #csr').removeClass();
				$('#peralatan').addClass('active');	
				$('#judul').text('F. DATA PERALATAN ');	 	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/nilai_investasi");
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/pelatihan_tenaga_kerja"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'nilai_investasi'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #pelatihan_pekerja, #peralatan, #pengalaman, #sop, #csr').removeClass();
				$('#nilai_investasi').addClass('active');	
				$('#judul').text('G. NILAI INVESTASI ');	 
				$('#keterangan').text('(diisi sesuai nilai investasi yang tercantum pada Akte Perusahaan atau Surat Persetujuan Penanaman Modal dari BKPM, dilampirkan bukti investasi) ');	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/pengalaman_kerja");
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/peralatan"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'pengalaman_kerja'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #pelatihan_pekerja, #peralatan, #nilai_investasi, #sop, #csr').removeClass();
				$('#pengalaman').addClass('active');	
				$('#judul').text('H. PENGALAMAN KERJA / PERFORMANCE  ');	
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/sop"); 
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/nilai_investasi"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'sop'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #pelatihan_pekerja, #peralatan, #nilai_investasi, #pengalaman, #csr').removeClass();
				$('#sop').addClass('active');	
				$('#judul').text('I. DATA SISTEM MANAJEMEN DAN PROSEDUR KERJA TEKNIS (SOP)');	 
				$('#keterangan').text('(dilampirkan dokumen sistem manajemen dan prosedur kerja teknis sesuai bidang usaha yang dimohon)');	 
				$('#lanjut').attr("href", "<?php echo base_url() ?>perusahaan/csr");
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/pengalaman_kerja"); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'csr'){
				$('#data_pemohon, #keanggotaan_asosiasi, #struktur_organisasi, #tenaga_kerja, #pelatihan_pekerja, #peralatan, #nilai_investasi, #pengalaman, #sop').removeClass();
				$('#csr').addClass('active');	
				$('#judul').text('J. DATA  CORPORATE SOCIAL RESPONSIBILITY  (CSR) DAN  COMMUNITY DEVELOPMENT (CD)');	
				$('#lanjut').hide();
				$('#kembali').attr("href", "<?php echo base_url() ?>perusahaan/sop"); 
				$('#kirim').show(); 
				$('#kirim2').show(); 
				if(path == 'SKT'){
					$('#dashboard,  #pengajuan_lisensi, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan').addClass('active');
				}else if(path == 'SKP'){
					$('#dashboard,  #pengajuan, #status, #pelaporan, #pengaturan').removeClass();
					$('#pengajuan_lisensi').addClass('active');
				}else if(path == 'Periodik'){
					$('#dashboard, #pengajuan, #pengajuan_lisensi,#perpanjangan, #status, #pengaturan').removeClass();
					$('#pelaporan').addClass('active');	
				}else if(path == 'Revisi'){
					$('#pengajuan, #pengajuan_lisensi, #perpanjangan, #status, #pelaporan, #pengaturan').removeClass();
					$('#dashboard').addClass('active');	
				}
			 }
			 
			 else if(pathname[3] == 'status_progress'){
				$('.sub-nav').hide();	
				$('#lanjut, #kirim').hide(); 
			 }
			 	
			
				$(".kirim").click(function (e) {
					$.ajax({
						type: "POST",
						url: "<?php echo base_url('perusahaan/cek_kelengkapan/'); ?>",
						data: {"bagian_sub_bidang": "ya"},
						dataType:"json",
						success: function (response) {
							if(response['response'] == ''){
								if((response['response2'] != '') && (response['response3'] == '')){
									alert("Mohon perhatian Anda.\nPersentase "+ response['response2'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else if((response['response3'] != '') && (response['response2'] == '')){
									alert("Mohon perhatian Anda.\nPersentase "+ response['response2'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else if((response['response2'] != '') && (response['response3'] != '')){
									alert("Mohon perhatian Anda.\nPersentase "+ response['response2'] +" dan Persentase "+ response['response3'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else{
									window.location = "<?php if(isset($link_submit)){ echo $link_submit; } ?>";
								}
							}else{
								if(response['response2'] == '' && response['response3'] == ''){
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else if(response['response2'] != '' && response['response3'] == ''){
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nDan persentase "+ response['response2'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else if(response['response3'] != '' && response['response2'] == ''){
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nDan persentase "+ response['response3'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}else if(response['response2'] != '' && response['response3'] != ''){
									alert("Mohon perhatian Anda.\nData-data berikut belum terisi atau terpilih satu pun untuk permohonan yang akan diajukan: \n"+ response['response'] +".\nDan persentase "+ response['response2'] +" serta persentase "+ response['response3'] +".\nHarap isikan data perusahaan Anda yang valid!");
								}
							}						
						},error: function(response){
							alert("Koneksi terputus!");
							location.reload(true);
							e.preventDefault();
						}
					});			
				});
				
			 $(document).on('submit','form#form_jenis_permohonan',function(){
			   // code
			   var validate = true;
				$('.option').each(function () {
					// Validate
					if (!$(this).find('input').is(':checked')) {
						alert("Anda belum memilih jenis permohonan yang akan diajukan!");
						validate = false;
					}else if ($(this).find('input').is(':checked')){
						if ($("#bidang_usaha")[0].selectedIndex == 0){
							alert("Anda belum memilih Bidang apa pun untuk diajukan!");
							validate = false;
						}else if ($("#bidang_usaha")[0].selectedIndex != 0){
							if ($("#sub_bidang")[0].selectedIndex == 0){
								alert("Anda belum memilih Sub Bidang apa pun untuk diajukan!");
								validate = false;
							}else{
								if (!$(".checkbox-bsb").is(':checked')){
									alert("Anda belum memilih Bagian Sub Bidang apa pun untuk diajukan!");
									validate = false;
								}else{
									validate = true;
								}
							}
						}
					}
				});				
					return validate;
			
			});
			
			$('input[type=radio][name=jenis_permohonan]').change(function() {
				if (this.value == 'SKT Baru') {
					$("#jika_perpanjangan").hide();
				}
				else if (this.value == 'Perpanjangan SKT') {
					$("#jika_perpanjangan").show();
				}
			});
		});		  
	
		
		
		function bidang_usaha123(clicked_id){
			var valueBidangUsaha = $('#bidang_usaha').val();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/bidang_usaha/'); ?>",
					data: {"bidang_usaha": valueBidangUsaha, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						$("#sub-bidang").html(response['bidangusaha']);	
						$(".label-bsb").remove();
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
			function sub_bidang123(clicked_id) {
				var valueSubBidangUsaha = $('#sub_bidang').val();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/sub_bidang/'); ?>",
					data: {"sub_bidang": valueSubBidangUsaha, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						$("#bagian-sub-bidang").html(response['subbidang']);
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
			function bagian_sub_bidang(clicked_id) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('perusahaan/bagian_sub_bidang/'); ?>",
					data: {"bagian_sub_bidang": clicked_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
					dataType:"json",
					success: function (response) {
						if(document.getElementById('bsb-' + clicked_id).checked==true){
							$("#sub-bagian-sub-bidang-" + clicked_id).html(response['bagiansubbidang']);
						}else if(document.getElementById('bsb-' + clicked_id).checked==false){
							document.getElementById('sub-bagian-sub-bidang-'+clicked_id).remove();
							$('#label-bsb-'+ (clicked_id+1)).before('<div class="chk-sbsb" id="sub-bagian-sub-bidang-'+ clicked_id +'"></div>');
						}						
					},error: function(response){
						alert("Koneksi terputus!");
						location.reload(true);
					}
				});
			}
			
			function sub_bagian_sub_bidang_lainnya(clicked_id, induk_id){
				if(document.getElementById(clicked_id).value != ''){
					$('#'+induk_id).prop('checked', true);
				}else if(document.getElementById(clicked_id).value == ''){
					$('#'+induk_id).prop('checked', false);
				}
			}
			
	</script>
		<div class="sub-nav">
			<div id="sub-navigation">
				<ul>
				    <!-- <li><a href="<?php echo base_url() ?>perusahaan/pengajuan" id="pilih_bidang">1. Pilih<br/>Bidang</a></li> -->
					<li><a href="<?php echo base_url() ?>perusahaan/data_pemohon" id="data_pemohon">1. Data<br/>Pemohon</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/keanggotaan_asosiasi" id="keanggotaan_asosiasi">2. Keanggotaan<br/>Asosiasi</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/struktur_organisasi" id="struktur_organisasi">3. Struktur<br/>Organisasi</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/data_tenaga_kerja" id="tenaga_kerja">4. Tenaga<br/>Kerja</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/pelatihan_tenaga_kerja" id="pelatihan_pekerja">5. Pelatihan<br/>Tenaga Kerja</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/peralatan" id="peralatan">6. Peralatan</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/nilai_investasi" id="nilai_investasi">7. Nilai<br/>Investasi</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/pengalaman_kerja" id="pengalaman">8. Pengalaman</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/sop" id="sop">9. SOP</a></li>
					<li><a href="<?php echo base_url() ?>perusahaan/csr" id="csr">10. CSR<br/>& CD</a></li>
				</ul>
			</div>
		</div>
		<div class="columnfull">
		<div class="borderbottomdotted">
			<legend id="judul"></legend>
		</div>
			<div class="formcolumn">
				<div name="basicform" id="basicform">
					<div class="frm isian">
						<div class="multistep">
							
							<div class="form-group field-isian">
								
								<?php if(isset($output)){
										echo $output;

									}elseif(isset($status)){
										// echo $status;
										foreach ($status as $key => $value) {
										# code...
										echo $value->id_disposisi.'[]'.$value->id_parent.')';
										echo $value->tanggal_masuk.'-';
										echo $value->jenis_permohonan.'-';
										// echo $value->status_progress;
										$stat = $this->model->select('*', 'ref_status_progres', array('key_status' => $value->status_progress));
										echo $stat->status_progress.'<br/>';
									}


										// echo $status->id_disposisi;

									} ?>
								<?= form_open(base_url('perusahaan/jenis_permohonan_bidang_usaha'), array("id"=>"form_jenis_permohonan"))?>
									
								<span id="keterangan" style="color:red; font-size:12px"></span>
								<div id="pilihan_bidang">
							
									<div class="column-bidang">
										<ul class="option" style="list-style: none;">
										<li style="display: inline;"><label class="bidang">Jenis Permohonan</label>:  </li>
											
										<li style="display: inline;"><input name="jenis_permohonan" id="rdbtn1" type="radio" value="SKT Baru"> Baru
											<input name="jenis_permohonan" id="rdbtn2" type="radio" value="Perpanjangan SKT"> Perpanjangan
										</li></ul>
										<div class="list-bidang">
											<label class="bidang">Bidang Usaha</label>:  
											<select id="bidang_usaha" name="bidang_usaha" onchange="bidang_usaha123();">
												<option value="">Pilih Bidang Usaha</option>
												<?php $selects_bidang_usaha = selects('*', 'ref_bidang_usaha');
													foreach ($selects_bidang_usaha as $key => $bidang_usaha):
												 ?>

												<option id="<?=  $bidang_usaha->id_bidang_usaha; ?>" onclick="bidang_usaha(this.id)" value="<?=  $bidang_usaha->id_bidang_usaha; ?>"><?=  $bidang_usaha->bidang_usaha; ?></option>
											<?php endforeach; ?>
											</select>
									 
											<div class="list-bidang" id="sub-bidang"></div>
											<div class="list-bidang" id="bagian-sub-bidang"></div>
											
										</div>								
									</div>
								</div>						
								<div class="clearfix" style="height: 10px;clear: both;"></div>
								<div class="form-group grup-tombol-kanan">
									<div class="col-lg-10 col-lg-offset-2">
										<a id="kembali" href="#"><button class="btn btn-success kembali" type="button">Before<span class="fa fa-arrow-right"></span></button></a>
										<a id="revisi" href="<?= base_url('perusahaan/revisi_klasifikasi_pengajuan') ?>"><button class="btn btn-warning kembali" type="button">Ubah Pengajuan<span class="fa fa-arrow-right"></span></button></a>
										<a id="lanjut" href="#"><button class="btn btn-primary lanjut" type="button">Next<span class="fa fa-arrow-right"></span></button></a>
										<?php if($laporan == 'non-active'){ ?>
											<!--<p id="kirim2">Setelah mengupdate data-data maka anda harus membuat Laporan periodik</p>-->
											<a id="kirim" class="kirim"><button class="btn btn-success lanjut" type="button">Submit</button></a>
										<?php }elseif($laporan == 'active'){ ?>
										<a id="kirim" class="kirim"><button class="btn btn-success lanjut" type="button">Buat Laporan</button></a>
										<?php } ?>
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