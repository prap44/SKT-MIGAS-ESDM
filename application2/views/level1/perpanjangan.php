<?php $this->load->view('includes/header') ?>
<?php
//echo validation_errors();
?>
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
                <th rowspan="2">Pilih</th>
            </tr>
            <tr>
                <th>Tanggal Terbit</th>
                <th>Tanggal Berakhir</th>
            </tr>
            </thead>
            <tbody>
            <?php if($data_umum != NULL){
                $i=1; foreach($data_umum as $dt_umum):?>
                    <?php $ref_jenis_dokumen = $this->model->select('*', 'ref_jenis_dokumen', array('id_jenis_dokumen' => $dt_umum->jenis_dokumen)); ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $ref_jenis_dokumen->jenis_dokumen; ?></td>
                        <td><?= $dt_umum->nomor; ?></td>
                        <td><?= $dt_umum->penerbit; ?></td>
                        <td><?= $dt_umum->tanggal_terbit; ?></td>
                        <td><?= $dt_umum->akhir_masa_berlaku; ?></td>
                        <td><?= $dt_umum->file_dokumen; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>

                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
            </tbody>
        </table>
    </div>
    <a href="<?= base_url('hal/data_pemohon')?>"> Tambahkan data umum</a>
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
                <th rowspan="2">Pilih</th>
            </tr>
            <tr>
                <th>Tanggal Terbit</th>
                <th>Tanggal Berakhir</th>
            </tr>
            </thead>
            <tbody>
            <?php if($data_khusus != NULL){
                $i=1; foreach($data_khusus as $dt_khusus):?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $dt_khusus->jenis_dokumen; ?></td>
                        <td><?= $dt_khusus->nomor; ?></td>
                        <td><?= $dt_khusus->penerbit; ?></td>
                        <td><?= $dt_khusus->tanggal_terbit; ?></td>
                        <td><?= $dt_khusus->akhir_masa_berlaku; ?></td>
                        <td><?= $dt_khusus->file_dokumen; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($keanggotaan_asosiasi != NULL){
                $i=1; foreach($keanggotaan_asosiasi as $asosiasi):?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $asosiasi->asosiasi; ?></td>
                        <td><?= $asosiasi->nomor_anggota; ?></td>
                        <td><?= date("d/m/Y",strtotime($asosiasi->berlaku_hingga)); ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
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
                <th colspan="2">Pendidikan Terakhir</th>
                <th colspan="3">Pelatihan</th>
                <th rowspan="2">Pelatihan<br/>Eksternal</th>
                <th rowspan="2">Pilih</th>
            </tr>
            <tr>
                <th>Jenjang</th>
                <th>Jurusan</th>
                <th>Judul<br/>Pelatihan</th>
                <th>Nomor Sertifikat</th>
                <th>Sertifikat</th>
            </tr>
            </thead>
            <tbody>
            <?php if($tenaga_kerja != NULL){
                $i=1; foreach($tenaga_kerja as $pekerja):?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $pekerja->nama_lengkap; ?></td>
                        <td><?= $pekerja->status; ?></td>
                        <td><?= $pekerja->jabatan; ?></td>
                        <td><?= $pekerja->jenjang_pendidikan; ?></td>
                        <td><?= $pekerja->jurusan_pendidikan; ?></td>
                        <td><?= $pekerja->judul_pelatihan; ?></td>
                        <td><?= $pekerja->nomor_sertifikat; ?></td>
                        <td><?= $pekerja->file_sertifikat; ?></td>
                        <td><a>lihat detil</a></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="9">Tidak ada record</td></tr><?php } ?>
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
                <th colspan="7">Pilih</th>
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
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($pelatihan_tenaga_kerja_internal != NULL){
                $i=1; foreach($pelatihan_tenaga_kerja_internal as $pelatihan_internal):?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $pelatihan_internal->jenis_pelatihan; ?></td>
                        <td><?= $pelatihan_internal->keterangan; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
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
                <th>Lainnya</th>
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($pelatihan_tenaga_kerja_eksternal != NULL){
                $i=1; foreach($pelatihan_tenaga_kerja_eksternal as $pelatihan_eksternal):?>
                    <?php if($tenaga_kerja != NULL){
                        $i=1; foreach($tenaga_kerja as $pekerja):?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $pekerja->nama_lengkap; ?></td>
                                <td><?= $pelatihan_eksternal->jenis_pelatihan; ?></td>
                                <td><?= $pelatihan_eksternal->lainnya; ?></td>
                                <td style="text-align:center;"><input type="checkbox" name=""></td>
                            </tr>
                        <?php endforeach; } ?>
                <?php endforeach; }else{ ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($peralatan != NULL){
                $i=1; foreach($peralatan as $alat):
                    if($alat->golongan_alat == 1){ ?>

                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $alat->nama_alat; ?></td>
                            <td><?= $alat->tipe_alat; ?></td>
                            <td><?= $alat->jumlah; ?></td>
                            <td><?= $alat->lokasi; ?></td>
                            <td><?= $alat->status_kepemilikan; ?></td>
                            <td style="text-align:center;"><input type="checkbox" name=""></td>
                        </tr>
                    <?php }else{ ?><tr><td colspan="6">Tidak ada record</td></tr><?php } endforeach; }else{?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($peralatan != NULL){
                $i=1; foreach($peralatan as $alat):
                    if($alat->golongan_alat == 2){ ?>

                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $alat->nama_alat; ?></td>
                            <td><?= $alat->tipe_alat; ?></td>
                            <td><?= $alat->jumlah; ?></td>
                            <td><?= $alat->lokasi; ?></td>
                            <td><?= $alat->status_kepemilikan; ?></td>
                            <td style="text-align:center;"><input type="checkbox" name=""></td>
                        </tr>
                    <?php }else{ ?><tr><td colspan="6">Tidak ada record</td></tr><?php } endforeach; }else{?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($peralatan != NULL){
                $i=1; foreach($peralatan as $alat):
                    if($alat->golongan_alat == 3){ ?>

                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $alat->nama_alat; ?></td>
                            <td><?= $alat->tipe_alat; ?></td>
                            <td><?= $alat->jumlah; ?></td>
                            <td><?= $alat->lokasi; ?></td>
                            <td><?= $alat->status_kepemilikan; ?></td>
                            <td style="text-align:center;"><input type="checkbox" name=""></td>
                        </tr>
                    <?php }else{ ?><tr><td colspan="6">Tidak ada record</td></tr><?php } endforeach; }else{?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
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
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($daftar_pekerjaan != NULL){
                $i=1; foreach($daftar_pekerjaan as $pekerjaan): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $pekerjaan->nama_pekerjaan; ?></td>
                        <td><?= $pekerjaan->pemberi_kerja; ?></td>
                        <td><?= $pekerjaan->lokasi_kerja; ?></td>
                        <td><?= $pekerjaan->tujuan_pelaksanaan; ?></td>
                        <td><?= $pekerjaan->nilai_kontrak; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($sop != NULL){
                $i=1; foreach($sop as $sopx): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $sopx->prosedur; ?></td>
                        <td><?= $sopx->file_manajemen_prosedur_kerja; ?></td>
                        <td><?= $sopx->deskripsi; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
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
                <th>Pilih</th>
            </tr>
            </thead>
            <tbody>
            <?php if($csr != NULL){
                $i=1; foreach($csr as $csrx): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $csrx->waktu; ?></td>
                        <td><?= $csrx->kegiatan; ?></td>
                        <td><?= $csrx->lokasi; ?></td>
                        <td style="text-align:center;"><input type="checkbox" name=""></td>
                    </tr>
                <?php endforeach; }else{ ?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-group grup-tombol-detail-kanan">
        <div class="col-lg-10 col-lg-offset-2">
            <a href="<?php echo site_url('hal/dashboard'); ?>"><button class="btn btn-primary detail" type="button">Gunakan lagi </button></a>
        </div>
    </div>

</div>

<?php $this->load->view('includes/footer') ?>

</body>
</html>