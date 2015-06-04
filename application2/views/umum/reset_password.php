<html>
<head>
	<title>Sistem Basis Data Internal Penunjang Migas</title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images') ?>/esdmlogo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/bootstrap.min.css">
</head>
<body class="login">

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="account-wall">
				<div class="app-title"><h2>Sistem Basis Data<br/>Internal Usaha Penunjang Migas<br/>(SKT Online)</h2></div>
                <a href="<?= base_url(); ?>"><img class="profile-img" src="<?php echo base_url('assets/images') ?>/esdmlogo.png" alt=""></a>
				<div class="app-title"><h1>Kementerian Energi dan<br/>Sumberdaya Mineral</h1><h2><b>Direktorat Teknik dan Lingkungan Migas</b></h2></div>
				<hr/>
                <?php echo form_open('umum/reset_password', array('class' => 'form-signin')); ?>
                <?= $this->session->flashdata('message');?>
				<?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                <input type="password" name="password" value="<?php echo set_value('password'); ?>"  class="form-control" placeholder="Password baru..." required autofocus>
                <input type="hidden" name="hash" value="<?= $this->uri->segment(3)?>"  class="form-control" >
           
				<br/>
                <button class="btn btn-lg btn-warning btn-block" type="submit">
                    Kirim </button><br/>Sudah memiliki akun? <a href="<?= site_url('umum/login');?>" style="text-decoration: none; color:blue">Login disini</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
