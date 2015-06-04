
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Detail Permohonan</legend>
			</div>
			<div class="formcolumn">
					<?= form_open(site_url('all_admin/rekapitulasi/'.$biodata_perusahaan->id_perusahaan.'/Biodata Perusahaan'),array("class"=>"pure-form pure-form-aligned rekapitulasi")); echo validation_errors();		
					$dt_permohonan = select('*', 'permohonan', array('id_permohonan' => $permohonan));
					$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $dt_permohonan->bidang_usaha));
					$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $dt_permohonan->sub_bidang));
					$bagian_sub_bidang = select('*','ref_bagian_sub_bidang', array('id_bagian_sub_bidang' => $dt_permohonan->sub_bidang)); 
					if($dt_permohonan->sub_bagian_sub_bidang != NULL){
					$exps1 = explode(',', $dt_permohonan->sub_bagian_sub_bidang);
						foreach($exps1 as $key => $exp1){
							$exps2 = explode('.', $exp1);
							$exp2[] = $exps2[1];
						} 
					}
					?>
				    <fieldset>
				        <div class="pure-control-group">
				            <label style="min-width:250px!important">Bidang Usaha</label>
				            : <b><?= $bidang_usaha->bidang_usaha; ?></b>
				       <div class="jarak"></div>
				            <label style="min-width:250px!important">Sub Bidang Usaha</label>
				            : <b><?= $sub_bidang->sub_bidang; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label style="min-width:250px!important">Bagian Sub Bidang Usaha</label>
				            : <b><?= $bagian_sub_bidang->bagian_sub_bidang; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label style="min-width:250px!important; vertical-align:top!important">Sub Bagian Sub Bidang Usaha</label>
				            <span style="vertical-align:top!important">:</span> <b>
							<?php if(isset($exp2)){
								foreach($exp2 as $key => $items){
									echo ($key+1).'. '.$items.'<br/>';
								}
							}else{
								echo '_';
							}
					?></b>
				        </div>

				    </fieldset>
				</form>
			</div>
		</div>
<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Detail Perusahaan</legend>
						  
			</div>
					
				
	<?php $rekaps = $this->model->selects('*', 'rekapitulasi', array('id_permohonan' => $this->uri->segment(4), 'penilai_level' => $this->session->userdata('level')));
		
		if($rekaps){
			foreach($rekaps as $key => $rek){
				if($rek->bahan_penilaian == 'Biodata Perusahaan'){
					$cat_biodata = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Data Umum'){
					$cat_dum = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Data Khusus'){
					$cat_dus = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Keanggotaan Asosiasi'){
					$cat_kea = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Struktur Organisasi'){
					$cat_str = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Data Tenaga Kerja'){
					$cat_dtk = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Jumlah Tenaga Kerja'){
					$cat_jtk = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Pelatihan Tenaga Kerja Internal'){
					$cat_pti = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Pelatihan Tenaga Kerja Eksternal'){
					$cat_pte = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Peralatan'){
					$cat_alat = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Nilai Investasi'){
					$cat_niv = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'Pengalaman Kerja'){
					$cat_pkj = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'SOP'){
					$cat_sp = $rek->catatan_penilaian;
				}elseif($rek->bahan_penilaian == 'CSR'){
					$cat_cr = $rek->catatan_penilaian;
				}
			}
		}
		?>	  
			<div class="formcolumn">
					<?= form_open(site_url('all_admin/rekapitulasi/'.$biodata_perusahaan->id_perusahaan.'/Biodata Perusahaan'),array("class"=>"pure-form pure-form-aligned rekapitulasi")); echo validation_errors(); ?> 
				    <fieldset>
				        <div class="pure-control-group">
				            <label for="name">Nama Perusahaan</label>
				            : <b><?= $biodata_perusahaan->nama_perusahaan; ?></b>
				       <div class="jarak"></div>
				            <label for="name">Direktur Utama</label>
				            : <b><?= $biodata_perusahaan->direktur_utama; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Contact Person</label>
				            : <b><?= $biodata_perusahaan->contact_person; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Email Perusahaan</label>
				            : <b><?= $biodata_perusahaan->email; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Alamat</label>
				            : <b><?= $biodata_perusahaan->alamat.', Kota '.$biodata_perusahaan->kota.', Provinsi '.$biodata_perusahaan->provinsi; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Website</label>
				            : <b><?= $biodata_perusahaan->website; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Deskripsi Perusahaan</label>
				            : <b><?= $biodata_perusahaan->deskripsi_perusahaan; ?></b>
				        </div>
						<input type="hidden" name="link" value="<?= $this->uri->segment(3) ?>">
						<input type="hidden" name="id_permohonan" value="<?= $this->uri->segment(4) ?>">

				    </fieldset>
				</form>
			</div>
		</div>
<!--********************************	Data Pemohon	**************************************-->		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>A. Data Pemohon</legend>
			</div>
			<div class="tablesection">
			<h2>1. Data Umum</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Dokumen</th>
							<th rowspan="2">Nomor</th>
							<th rowspan="2">Penerbit</th>
							<th colspan="2">Masa Berlaku</th>
							<th rowspan="2">File Dokumen</th>
						</tr>
						<tr>
							<th>Tanggal Terbit</th>
							<th>Tanggal Berakhir</th>
						</tr>
					</thead>
					<tbody>
						<?php if($data_umum != NULL){						
						$i=1; foreach($data_umum as $dt_umum):?>
						<?php $ref_jenis_dokumen = $this->model->select('*', 'ref_jenis_dokumen', array('id_jenis_dokumen' => $dt_umum->jenis_dokumen));
							if($dt_umum->jenis_dokumen != 9){ ?>
						<tr>						
							<td><?= $i++; ?></td>
							<td><?= $ref_jenis_dokumen->jenis_dokumen; ?></td>
							<td><?= $dt_umum->nomor; ?></td>
							<td><?= $dt_umum->penerbit; ?></td>
							<td><?= $dt_umum->tanggal_terbit; ?></td>
							<td><?= $dt_umum->akhir_masa_berlaku; ?></td>
							<td>
							<?php if($dt_umum->file_dokumen){ ?>
								<?php if($dt_umum->jenis_dokumen == 5){ ?>
									<?= anchor('assets/uploads/file_skt/'.$dt_umum->file_dokumen, 'Dokumen'); ?>
								<?php }else{ ?>
									<?= anchor('assets/uploads/files/'.$dt_umum->file_dokumen, 'Dokumen'); ?>
								<?php } 
							 }else{ ?>
							Tidak ada dokumen
							<?php } ?>
							</td>
						</tr>
						<?php } endforeach; }else{ ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			<div class="tablesection">
			<h2>2. Data Khusus</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Dokumen</th>
							<th rowspan="2">Nomor</th>
							<th rowspan="2">Penerbit</th>
							<th colspan="2">Masa Berlaku</th>
							<th rowspan="2">File Dokumen</th>
						</tr>
						<tr>
							<th>Tanggal Terbit</th>
							<th>Tanggal Berakhir</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($data_khusus != NULL){	
						$i=1; foreach($data_khusus as $dt_khusus):
						if($dt_khusus->status_pemakaian != NULL){
							$temp = explode(',', $dt_khusus->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
								<tr>
									<td><?= $i++; ?></td>
									<td><?= $dt_khusus->jenis_dokumen; ?></td>
									<td><?= $dt_khusus->nomor; ?></td>
									<td><?= $dt_khusus->penerbit; ?></td>
									<td><?= $dt_khusus->tanggal_terbit; ?></td>
									<td><?= $dt_khusus->akhir_masa_berlaku; ?></td>
									<td>
									<?php if($dt_khusus->file_dokumen){ ?>
									<?= anchor('assets/uploads/files/'.$dt_khusus->file_dokumen, 'Dokumen'); 
									 }else{ ?>
									Tidak ada
									<?php } ?>
									</td>
								</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Keanggotaan Asosiasi	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>B. Keanggotaan Asosiasi Perusahaan</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Asosiasi</th>
							<th>Nomor Anggota</th>
							<th>Berlaku Hingga</th>
							<th>File Dokumen</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($keanggotaan_asosiasi != NULL){	
						$i=1; foreach($keanggotaan_asosiasi as $asosiasi):
						if($asosiasi->status_pemakaian != NULL){
							$temp = explode(',', $asosiasi->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $asosiasi->asosiasi; ?></td>
							<td><?= $asosiasi->nomor_anggota; ?></td>
							<td><?= date("d/m/Y",strtotime($asosiasi->berlaku_hingga)); ?></td>
							<td><?= anchor('assets/uploads/file_keanggotaan_asosiasi/'.$asosiasi->file_keanggotaan_asosiasi, 'Dokumen') ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Struktur Organisasi	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>C. Struktur Organisasi Perusahaan</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nama File</th>
							<th>File Dokumen</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($data_umum != NULL){						
						$i=1; foreach($data_umum as $dt_umum):?>
						<?php $ref_jenis_dokumen = $this->model->select('*', 'ref_jenis_dokumen', array('id_jenis_dokumen' => $dt_umum->jenis_dokumen));
						if($dt_umum->jenis_dokumen == 9){ ?>
						<tr>
							<td>Struktur organisasi</td>
							<td><?= anchor('assets/uploads/files/'.$dt_umum->file_dokumen, 'Dokumen'); ?></td>
						</tr>
						<?php $ada = 'ada'; } endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="2">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Data Tenaga Kerja		**************************************-->			
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>D. Data Tenaga Kerja</legend>
			</div>
			<div class="tablesection">
			<h2>1. Daftar Tenaga Kerja Ahli Sesuai Bidang Usaha</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Nama Lengkap</th>
							<th rowspan="2">Status</th>
							<th rowspan="2">Posisi/<br/>Jabatan</th>
							<th colspan="3">Pendidikan Terakhir</th>
							<th rowspan="2">Sertifikat</th>
							<th rowspan="2">File IMTA</th>
						</tr>
						<tr>
							<th>Jenjang</th>
							<th>Jurusan</th>
							<th>Ijazah</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($tenaga_kerja != NULL){
						$i=1; foreach($tenaga_kerja as $pekerja):
						if($pekerja->status_pemakaian != NULL){
							$temp = explode(',', $pekerja->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ 
									$jenjang = $this->model->select('*', 'ref_jenjang_pendidikan', array('id_jenjang_pendidikan' => $pekerja->jenjang_pendidikan));
									$sertifikats = $this->model->selects('*', 'sertifikasi_tenaga_kerja', array('id_tenaga_kerja' => $pekerja->id_tenaga_kerja)); ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerja->nama_lengkap; ?></td>
							<td><?= $pekerja->status; ?></td>
							<td><?= $pekerja->jabatan; ?></td>
							<td><?= $jenjang->jenjang_pendidikan; ?></td>
							<td><?= $pekerja->jurusan_pendidikan; ?></td>
							<td style="text-align: center">
							<?php if($pekerja->file_ijazah){ ?>
							<?= anchor('assets/uploads/file_ijazah_tenaga_ahli/'.$pekerja->file_ijazah, 'Dokumen'); 
							 }else{ ?>
							Tidak ada
							<?php } ?>
							</td>
							<td>
							<?php if($sertifikats != NULL){ 
								foreach($sertifikats as $key => $sertifikat){ 
									$output = ($key+1).'. '.$sertifikat->nomor_sertifikat.' / '.$sertifikat->judul_pelatihan.' / ';
									if($sertifikat->file_sertifikat != NULL){
										$output .= anchor('assets/uploads/file_sertifikat_tenaga_ahli/'.$sertifikat->file_sertifikat, 'Dokumen').'<br/>';
									}else{
										$output .= '<span style="color:red">Tidak ada file</span><br/>';
									}
									echo $output;
								}
							}else{
										echo 'Tidak ada record';
							} ?>
							</td>
							<td style="text-align: center">
							<?php if($pekerja->file_imta){ ?>
							<?= anchor('assets/uploads/file_imta/'.$pekerja->file_imta, 'Dokumen'); 
							 }else{ ?>
							Tidak ada
							<?php } ?>
							</td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

			<hr/>

			<div class="tablesection">
			<h2>2. Jumlah Tenaga Kerja</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Tenaga Kerja</th>
							<th colspan="7">Jumlah Tenaga Kerja Berdasarkan Jenjang Pendidikan Terakhir</th>
						</tr>
						<tr>
							<th>Sekolah Dasar<br/>(SD)</th>
							<th>Sekolah Menengah Pertama<br/>(SMP)</th>
							<th>Sekolah Menengah Atas<br/>(SMA)</th>
							<th>Diploma</th>
							<th>Sarjana</th>
							<th>Paska Sarjana</th>
							<th>Doktor</th>
						</tr>
					</thead>
					<tbody>
						<?php if($jumlah_tenaga_kerja != NULL){						
						$i=1; foreach($jumlah_tenaga_kerja as $jml_pekerja):?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $jml_pekerja->tipe_tenaga_kerja; ?></td>
							<td><?= $jml_pekerja->sd; ?></td>
							<td><?= $jml_pekerja->smp; ?></td>
							<td><?= $jml_pekerja->sma; ?></td>
							<td><?= $jml_pekerja->diploma; ?></td>
							<td><?= $jml_pekerja->sarjana; ?></td>
							<td><?= $jml_pekerja->paska_sarjana; ?></td>
							<td><?= $jml_pekerja->doktor; ?></td>
						</tr>
						<?php endforeach; }else{ ?><tr><td colspan="9">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Pelatihan Tenaga Kerja Internal		**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>E. Pelatihan Tenaga Kerja</legend>
			</div>
			<div class="tablesection">
			<h2>1. Tabel Pelatihan Tenaga Kerja inhouse</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Jenis Pelatihan</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($pelatihan_tenaga_kerja_internal != NULL){		
						$i=1; foreach($pelatihan_tenaga_kerja_internal as $pelatihan_internal):
						if($pelatihan_internal->status_pemakaian != NULL){
							$temp = explode(',', $pelatihan_internal->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pelatihan_internal->jenis_pelatihan; ?></td>
							<td><?= $pelatihan_internal->keterangan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

			<hr/>
			<div class="tablesection">
			<h2>2. Tabel Program Pelatihan Tenaga Kerja eksternal</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Tenaga Kerja</th>
							<th>Jenis Pelatihan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($pelatihan_tenaga_kerja_eksternal != NULL){						
						$i=1; foreach($pelatihan_tenaga_kerja_eksternal as $pelatihan_eksternal):?>
						<?php if($tenaga_kerja != NULL){	
						$i=1; foreach($tenaga_kerja as $pekerja):
						if($pelatihan_eksternal->status_pemakaian != NULL){
							$temp = explode(',', $pelatihan_eksternal->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerja->nama_lengkap; ?></td>
							<td><?= $pelatihan_eksternal->jenis_pelatihan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

		</div>	
<!--********************************	Peralatan		**************************************-->		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>F. Data Peralatan</legend>
			</div>
			<div class="tablesection">
			<h2>1. Peralatan Utama</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
							<th>File Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){	
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Utama'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
							<td>
							<?php if($alat->file_kepemilikan_alat){ ?>
							<?= anchor('assets/uploads/file_kepemilikan_peralatan/'.$alat->file_kepemilikan_alat, 'Dokumen'); 
							 }else{ ?>
							Tidak ada
							<?php } ?>
							</td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div class="tablesection">
			<h2>2. Peralatan Pendukung</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Pendukung'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div class="tablesection">
			<h2>3. Peralatan Keselamatan Dan Kesehatan Kerja</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){	
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Keselamatan dan Kesehatan Kerja'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Nilai Investasi	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>G. Nilai Investasi</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Investor</th>
							<th>Negara Asal</th>
							<th>Nominal Investasi</th>
							<th>Persentase</th>
						</tr>
					</thead>
					<tbody>
						<?php if($nilai_investasi != NULL){						
						$i=1; foreach($nilai_investasi as $invest):?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $invest->nama_investor; ?></td>
							<td><?= $invest->negara_asal; ?></td>
							<td><?= $invest->nominal_investasi; ?></td>
							<td><?= $invest->persentase; ?></td>
						</tr>
						<?php endforeach; }else{ ?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Pengalaman Kerja	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>H. Pengalaman Kerja / Performance</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Pekerjaan</th>
							<th>Pemberi Kerja</th>
							<th>Lokasi</th>
							<th>Tujuan Pelaksanaan</th>
							<th>Nilai Kontrak</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($daftar_pekerjaan != NULL){	
						$i=1; foreach($daftar_pekerjaan as $pekerjaan):
						if($pekerjaan->status_pemakaian != NULL){
							$temp = explode(',', $pekerjaan->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerjaan->nama_pekerjaan; ?></td>
							<td><?= $pekerjaan->pemberi_kerja; ?></td>
							<td><?= $pekerjaan->lokasi_kerja; ?></td>
							<td><?= $pekerjaan->tujuan_pelaksanaan; ?></td>
							<td><?= $pekerjaan->nilai_kontrak; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>


		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>I. Data Sistem Manajemen Dan Prosedur Kerja Teknis (SOP)</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Prosedur</th>
							<th>Dokumen Prosedur </th>
							<th>Deskripsi</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($sop != NULL){	
						$i=1; foreach($sop as $sopx): 
						if($sopx->status_pemakaian != NULL){
							$temp = explode(',', $sopx->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $sopx->prosedur; ?></td>
							<td>
							<?php if($sopx->file_manajemen_prosedur_kerja){ ?>
							<?= anchor('assets/uploads/file_sistem_manajemen_dan_prosedur_kerja_teknis/'.$sopx->file_manajemen_prosedur_kerja, 'Dokumen'); 
							 }else{ ?>
							Tidak ada
							<?php } ?>
							</td>
							<td><?= $sopx->deskripsi; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>


		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>J. Data Corporate Social Responsibility (CSR) Dan Community Development</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Waktu</th>
							<th>Kegiatan</th>
							<th>Lokasi</th>
							<th>File Dokumen</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($csr != NULL){
						$i=1; foreach($csr as $csrx): 
						if($csrx->status_pemakaian != NULL){
							$temp = explode(',', $csrx->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $csrx->waktu; ?></td>
							<td><?= $csrx->kegiatan; ?></td>
							<td><?= $csrx->lokasi; ?></td>
							<td>
							<?php if($csrx->file_csr){ ?>
							<?= anchor('assets/uploads/file_csr/'.$csrx->file_csr, 'Dokumen'); 
							 }else{ ?>
							Tidak ada
							<?php } ?>
							</td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>