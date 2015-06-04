<!doctype html>
<html>
<head>
	<title>Purwarupa Alpha Version</title>
	<link rel="stylesheet" type="text/css" href="<?php echo STYLS ?>layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo STYLS ?>bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo STYLS ?>forms.css">

</head>
<body>

	<div class="header">
		<div class="mainhead">
			<div class="rowhead">
				<div class="logo">
					<img src="<?php echo IMGS ?>esdmlogo.png">
					<div class="logotitle">
						<div class="logotitle1">Kementerian Energi dan Sumberdaya Mineral</div>
						<div class="logotitle2"><b>Direktorat Teknik dan Lingkungan Migas</b></div>
						<div class="logotitle3">Sistem Basis Data Internal Penunjang Migas</div>
					</div>
				</div>
				<div class="headright">
					<ul>
						<li><a href="#">Link 4</a></li>
						<li><a href="#">Link 3</a></li>
						<li><a href="#">Link 2</a></li>
						<li><a href="#">Link 1</a></li>
					</ul>
					<form>
						<input type="text"> <input type="submit" value="Search">
					</form>
				</div>
			</div>
			<div class="navigation">
				<div id="navigation">				    
					<div class="runningtext">
						<marquee scrollamount="3" onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 3, 0);">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
						</marquee>
					</div>						
				</div>
			</div>
		</div>	
	</div>


	<div class="content">
	
		<div class="dashboardmenu">
			<ul class="nav nav-tabs" role="tablist">
			  <li role="presentation"><a href="<?php echo site_url() ?>/hal/index" role="tab" data-toggle="tab"><img src="<?php echo IMGS ?>icon/home.png" class="tab-icon"><br/>Dashboard</a></li>
			  <li role="presentation"><a href="<?php echo site_url() ?>/hal/pengajuan" role="tab" data-toggle="tab"><img src="<?php echo IMGS ?>icon/pengajuan.png" class="tab-icon"><br/>Pengajuan SKT</a></li>
			  <li role="presentation" class="active"><a href="<?php echo site_url() ?>/hal/status_pengajuan" role="tab" data-toggle="tab"><img src="<?php echo IMGS ?>icon/berkas.png" class="tab-icon"><br/>Status Pengajuan</a></li>
			  <li role="presentation"><a href="<?php echo site_url() ?>/hal/pengaturan" role="tab" data-toggle="tab"><img src="<?php echo IMGS ?>icon/pengaturan.png" class="tab-icon"><br/>Pengaturan</a></li>
			</ul>
		</div>

		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Status Proses Pengajuan</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No.</th>
							<th>Posisi</th>
							<th>Tanggal Mulai</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="output">
					</tbody>
				</table>
			</div>

		</div>

		<div class="footer">
			<div class="footertop">
				<div class="footcolumn">
					<div class="foottitle">Tentang Kami</div>
					<div class="footcontent">
						<ul>
							<li>Visi dan Misi</li>
							<li>Fungsi dan Tugas</li>
							<li>Struktur Organisasi</li>
							<li>Rencana Strategis</li>
							<li>Profil Pimpinan</li>
							<li>Undang-Undang Pendukung</li>
						</ul>
					</div>
				</div>
				<div class="footcolumn">
					<div class="foottitle">Indeks Berita</div>
					<div class="footcontent">
						<ul>
							<li>Siaran Pers</li>
							<li>Berita</li>
							<li>Berita Media</li>
							<li>Indeks Berita Lainnya</li>
						</ul>
					</div>
				</div>
				<div class="footcolumn">
					<div class="foottitle">Layanan Publik</div>
					<div class="footcontent">
						<ul>
							<li>Pengaduan Masyarakat</li>
							<li>Informasi Publik</li>
							<li>Layanan Publik Lainnya</li>
						</ul>
					</div>
				</div>
				<div class="footcolumn">
					<div class="foottitle">Publikasi</div>
					<div class="footcontent">
						<ul>
							<li>Laporan Perminyakan</li>
							<li>Laporan Tahunan</li>
							<li>Download Undang-Undang</li>
							<li>Download Profil Pimpinan</li>
						</ul>
					</div>
				</div>

				<div class="addresscolumn">
					<div class="foottitle">Informasi Kontak</div><br>
					<div class="isiaddress">
						GEDUNG XYZ<br>
						Jln. Apa Saja Kav. C2 Tembaga<br>
						Jakarta Selatan 18757<br>
						Telp : (021) 7875789<br>
						Faks : (021) 7854343<br>
						Email : iniemail@email.com
					</div>
				</div>
			</div>
			<div class="footerbottom"></div>
		</div>
	</div>
	
</body>
</html>