<?php $this->load->view('includes/header') ?>

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

<?php $this->load->view('includes/footer') ?>
	
</body>
</html>