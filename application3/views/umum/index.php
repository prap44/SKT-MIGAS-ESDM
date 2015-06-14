
<?php $this->load->view('includes/header_awal') ?>
	
	<div class="content">
		<div class="slider">
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
				<li data-target="#myCarousel" data-slide-to="3"></li>
				<li data-target="#myCarousel" data-slide-to="4"></li>
			  </ol>
			  <div class="carousel-inner">
				<div class="item bg bg1 active"></div>
				<div class="item bg bg2"></div>
				<div class="item bg bg3"></div>
				<div class="item bg bg4"></div>
				<div class="item bg bg5"></div>
			  </div>
			  <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			  <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
			</div>
		</div>

		<div class="maincontent">

			<div class="leftcol">
				<div class="boxcontent">
					<div class="category">
						Pengumuman
					</div>
					<div class="leftcont">
					<?php
					function word_limiter($content, $word_limit) {
						$words = explode(" ",$content);
						return implode(" ",array_splice($words,0,$word_limit));
					}

					foreach($results as $data) { ?>
						<div class="titlecontent">
							<?php echo $data->judul; ?>
						</div>
						<div class="statuscontent">By <?php echo $data->penulis; ?> | Posted On : <?php echo date('d F Y', strtotime($data->tanggal_terbit)); ?></div>
						<div class="bodycontent">
							<p><?php
								$words = explode(" ",$data->isi);
								echo implode(" ",array_splice($words,0,40));

								 ?> <a href="<?php echo base_url('umum/detail/'. $data->id_pengumuman); ?>">Selengkapnya...</a></p>
						</div>
					<?php }	?>
					   
					   
						
					</div>
				</div>
					<?= $links ?>
			</div>


			<div class="rightcol">
				<div class="category">
					Konten Lain
				</div>
				<?php foreach($results as $data) { ?>
				<div class="newscol">
					<div class="newsdetail">
						<div class="newsdate"><?php echo $data->penulis; ?> | <?php echo date('d F Y', strtotime($data->tanggal_terbit)); ?></div>
						<div class="newstitle">
							<a href="<?php echo base_url('umum/detail/'.$data->id_pengumuman); ?>"><?php echo $data->judul; ?></a>
						</div>

					</div>
				</div>
				<?php }	?>
			</div>
		</div>

			
<?php $this->load->view('includes/footer_awal') ?>