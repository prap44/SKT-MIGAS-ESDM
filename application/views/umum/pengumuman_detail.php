<?php $this->load->view('includes/header_awal') ?>


<div class="content">
    <div class="maincontent">

        <div class="leftcol">
            <div class="boxcontent">
                <div class="category">
                    Pengumuman
                </div>
                <div class="leftcont">
                        <div class="titlecontent">
                            <?php echo $judul; ?>
                        </div>
                        <div class="statuscontent">By <?php echo $penulis; ?> | Posted On : <?php echo date('d F Y', strtotime($tanggal_terbit)); ?></div>
                        <div class="bodycontent">
                            <p><?php echo $isi; ?> </p>
                        </div>



                </div>
            </div>
        </div>


        <div class="rightcol">
            <div class="category">
                Konten Lain
            </div>
            <?php foreach($results as $data) { ?>
                <div class="newscol">
                    <div class="newsdetail">
                        <div class="newsdate"><?php echo $data->penulis; ?> | <?php echo date('d F Y', strtotime($data->tanggal_terbit)); ?></div>
                        <div class="newstitle"><a href="<?php echo base_url('umum/detail/'.$data->id_pengumuman); ?>"><?php echo $data->judul; ?></a></div>
                    </div>
                </div>
            <?php }	?>
        </div>
    </div>

<?php $this->load->view('includes/footer_awal')?>