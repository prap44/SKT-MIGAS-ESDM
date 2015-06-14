<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perusahaan extends CI_Controller
{
	
  public function __construct()
  {
    parent::__construct();

    $this->load->database();
    $this->load->helper('url');
    $this->load->library('pagination');

    $this->load->library('grocery_CRUD');

    if ($this->session->userdata('level') == NULL) {
      $this->logs('Anda tidak berhak mengakses halaman ini!');
            // $this->logout();
      redirect('umum/logout');
    }

    $this->glo_jenis_permo = '';
    if ($this->session->userdata('id_permohonan') != NULL) {
      $jenis_permo = $this->model->select('*', 'permohonan', array('id_permohonan' =>$this->session->userdata('id_permohonan')));
            // $this->logs('Anda tidak berhak mengakses halaman ini!');
            // $this->logout();
      if ($jenis_permo->jenis_permohonan != 'SKT Baru') {

        $this->glo_jenis_permo = $jenis_permo->jenis_permohonan;
      }else{
        $this->glo_jenis_permo = '';

      }
    }

        // echo $this->session->userdata('id_permohonan');

  }
//####################################################################################################################################
// 														UNTUK PERUSAHAAN
//####################################################################################################################################	

// 5.1 FUNGSI BIDANG_USAHA

  public function bidang_usaha()
  {
    $bidang_usaha = $this->input->post('bidang_usaha', TRUE);
    $sub_bidang = $this->model->selects('*', 'ref_sub_bidang', array('bidang_usaha' => $bidang_usaha));

    if($sub_bidang != NULL){
     $sub_bidang_obj = '<label class="bidang">Sub Bidang</label>: <select name="sub_bidang" id="sub_bidang" onchange="sub_bidang123()" >';
     $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

     foreach ($sub_bidang as $key => $sbdg) {
      $sub_bidang_obj .= '<option name="sub_bidang" id="sb-' . $sbdg->id_sub_bidang . '"  value="' . $sbdg->id_sub_bidang . '">' . $sbdg->sub_bidang . '</option>';
    }

    $sub_bidang_obj .= '</select>';
  }else{
   $sub_bidang_obj = '';
 }

 $json['bidangusaha'] = $sub_bidang_obj;
        //$json['menu'] = '';
 echo json_encode($json);
}

//***************************************************************************************************************************
// 5.2 FUNGSI SUB_BIDANG

public function sub_bidang()
{
  $sub_bidang = $this->input->post('sub_bidang', TRUE);
  $bagian_sub_bidang = $this->model->selects('*', 'ref_bagian_sub_bidang', array('id_sub_bidang' => $sub_bidang));

  if($bagian_sub_bidang != NULL){
   $bagian_sub_bidang_obj = '<label class="bidang">Bagian Sub Bidang<br/>& Sub Bagian Sub Bidang</label>: <br/>';
			// $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

   foreach ($bagian_sub_bidang as $key => $sbdg) {
    $bagian_sub_bidang_obj .= '<label class="label-bsb" id="label-bsb-'.$sbdg->id_bagian_sub_bidang.'"><input type="checkbox" name="bagian_sub_bidang[]" class="checkbox-bsb" id="bsb-' . $sbdg->id_bagian_sub_bidang . '" onchange="bagian_sub_bidang(' . $sbdg->id_bagian_sub_bidang . ')" value="' . $sbdg->id_bagian_sub_bidang . '"> ' . $sbdg->bagian_sub_bidang . '</label><div class="chk-sbsb" id="sub-bagian-sub-bidang-' . $sbdg->id_bagian_sub_bidang . '"></div>';

  }
}else{
 $bagian_sub_bidang_obj = '';
}

$json['subbidang'] = $bagian_sub_bidang_obj;
        //$json['menu'] = '';
echo json_encode($json);
}

//***************************************************************************************************************************
// 5.3 FUNGSI BAGIAN_SUB_BIDANG

public function bagian_sub_bidang()
{
  $bagian_sub_bidang = $this->input->post('bagian_sub_bidang', TRUE);
  $sub_bagian_sub_bidang = $this->model->selects('*', 'ref_sub_bagian_sub_bidang', array('id_bagian_sub_bidang' => $bagian_sub_bidang));

  if($sub_bagian_sub_bidang != NULL){
   $sub_bagian_sub_bidang_obj = '';
   foreach ($sub_bagian_sub_bidang as $key => $sbsbdg) {
    $sub_bagian_sub_bidang_obj .= '<label class="label-sbsb"><input class="checkbox-sbsb" type="checkbox" id="sbsb-' . $sbsbdg->id_sub_bagian_sub_bidang . '" name="sub_bagian_sub_bidang[]" onchange="sub_bagian_sub_bidang(' . $sbsbdg->id_sub_bagian_sub_bidang . ')" value="' . $bagian_sub_bidang . '.' . $sbsbdg->sub_bagian_sub_bidang . '"> ' . $sbsbdg->sub_bagian_sub_bidang . '</label>';
  }
  $sub_bagian_sub_bidang_obj .= '<input style="margin: 0px 0px 0px 260px !important;" class="checkbox-sbsb" type="checkbox" id="lainnya-'.$bagian_sub_bidang.'" onblur="sub_bagian_sub_bidang_lainnya(\'sbsb-lainnya'.$bagian_sub_bidang.'\', this.id)" /> <label style="margin:0!important; min-width:50px">Lainnya: </label>
  <input type="hidden" name="induk_sbsb[]" value="'.$bagian_sub_bidang.'"><input type="text" id="sbsb-lainnya'.$bagian_sub_bidang.'" name="sub_bagian_sub_bidang_lainnya[]" onkeyup="sub_bagian_sub_bidang_lainnya(this.id, \'lainnya-'.$bagian_sub_bidang.'\')" /><br/>';
  $sub_bagian_sub_bidang_obj .= '<hr/>';
}else{
 $sub_bagian_sub_bidang_obj = '';
}

$json['bagiansubbidang'] = $sub_bagian_sub_bidang_obj;
        //$json['menu'] = '';
echo json_encode($json);
}

//***************************************************************************************************************************
// 5.4 FUNGSI PENGAJUAN

public function pengajuan()
{
  $level = $this->session->userdata('level');

  if ($level != NULL) {
    if ($level == 1) {
      $this->load->view('level1/skt_tabel');
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    redirect('umum/logout');
  }
}

public function revisi_klasifikasi_pengajuan()
{
  $level = $this->session->userdata('level');

  if ($level != NULL) {
    if ($level == 1) {
      $this->load->view('level1/skt_tabel_revisi');
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    redirect('umum/logout');
  }
}

//***************************************************************************************************************************
// 5.21 FUNGSI JENIS_PERMOHONAN_BIDANG_USAHA

public function jenis_permohonan_bidang_usaha(){

  $induk = $this->input->post('induk_sbsb', TRUE);
  $sub_bagian_sub_bidang_lainnya = $this->input->post('sub_bagian_sub_bidang_lainnya', TRUE);
  if($sub_bagian_sub_bidang_lainnya != NULL){
   $count = count($sub_bagian_sub_bidang_lainnya);
   $gabung =array();
   for($i=0; $i<$count; $i++) {
    if($sub_bagian_sub_bidang_lainnya[$i] != NULL){
      $gabung[$i] = $induk[$i].'.'.$sub_bagian_sub_bidang_lainnya[$i];
    }
  }
}
$isi = implode("#", $gabung);
$sub_bidang = $this->input->post('sub_bidang', TRUE);
$bagian_sub_bidang = implode("#", $this->input->post('bagian_sub_bidang', TRUE));
if($this->input->post('sub_bagian_sub_bidang', TRUE) != NULL){
 $sub_bagian_sub_bidang = implode("#", $this->input->post('sub_bagian_sub_bidang', TRUE));
}else{
 $sub_bagian_sub_bidang = NULL;
}

if($isi){
 if($sub_bagian_sub_bidang){
  $gabung_gabung = $sub_bagian_sub_bidang.','.$isi;
}else{
  $gabung_gabung = $isi;
}
}else{
 $gabung_gabung = $sub_bagian_sub_bidang;
}

$data = array(
  'id_perusahaan' => $this->session->userdata('id_perusahaan'),
  'jenis_permohonan' => $this->input->post('jenis_permohonan', TRUE),
  'bidang_usaha' => $this->input->post('bidang_usaha', TRUE),
  'sub_bidang' => $sub_bidang,
  'bagian_sub_bidang' => $bagian_sub_bidang,
  'sub_bagian_sub_bidang' => $gabung_gabung,
  );

  if($this->session->userdata('id_permohonan') != NULL || $this->session->userdata('id_permohonan') != ''){
	$this->model->update('permohonan', $data, array('id_permohonan' => $this->session->userdata('id_permohonan')));
  }else{
	$this->model->insert('permohonan', $data);
  }

$this->session->set_userdata('id_permohonan', $this->db->insert_id());

$sdh_ada_data_umum  = $this->model->select('*', 'data_umum', array('id_perusahaan'=>$this->session->userdata('id_perusahaan'), 'jenis_dokumen'=>12));
if (!$sdh_ada_data_umum) {
  $this->model->insert('data_umum', array('id_perusahaan'=>$this->session->userdata('id_perusahaan'), 'jenis_dokumen'=>12));
}

$sdh_ada__data_tenaga_kerja  = $this->model->select('*', 'jumlah_tenaga_kerja', array('id_perusahaan'=>$this->session->userdata('id_perusahaan')));
if (!$sdh_ada__data_tenaga_kerja) {
  $this->model->insert('jumlah_tenaga_kerja', array('id_perusahaan'=>$this->session->userdata('id_perusahaan'), 'tipe_tenaga_kerja'=>'WNA'));
  $this->model->insert('jumlah_tenaga_kerja', array('id_perusahaan'=>$this->session->userdata('id_perusahaan'), 'tipe_tenaga_kerja'=>'WNI'));
}



$this->logs();
if ($this->db->affected_rows() > 0) {
  redirect(base_url('perusahaan/data_pemohon'));
}

}

//***************************************************************************************************************************
// 5.4 FUNGSI PENGAJUAN

public function pengajuan_skp()
{
  $level = $this->session->userdata('level');

  if ($level != NULL) {
    if ($level == 1) {
      $this->load->view('level1/skp_tabel');
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    redirect('umum/logout');
  }
}	

	//***************************************************************************************************************************
// 5.21 FUNGSI JENIS_PERMOHONAN_BIDANG_USAHA

public function jenis_permohonan_bidang_usaha_skp(){

  $induk = $this->input->post('induk_sbsb', TRUE);
  $sub_bagian_sub_bidang_lainnya = $this->input->post('sub_bagian_sub_bidang_lainnya', TRUE);
  if($sub_bagian_sub_bidang_lainnya != NULL){
   $count = count($sub_bagian_sub_bidang_lainnya);
   $gabung =array();
   for($i=0; $i<$count; $i++) {
    if($sub_bagian_sub_bidang_lainnya[$i] != NULL){
      $gabung[$i] = $induk[$i].'.'.$sub_bagian_sub_bidang_lainnya[$i];
    }
  }
}
$isi = implode(",", $gabung);
$sub_bidang = $this->input->post('sub_bidang', TRUE);
$bagian_sub_bidang = implode(",", $this->input->post('bagian_sub_bidang', TRUE));
$sub_bagian_sub_bidang = implode(",", $this->input->post('sub_bagian_sub_bidang', TRUE));

if($isi){
 $gabung_gabung = $sub_bagian_sub_bidang.','.$isi;
}else{
 $gabung_gabung = $sub_bagian_sub_bidang;
}

$data = array(
  'id_perusahaan' => $this->session->userdata('id_perusahaan'),
  'jenis_permohonan' => $this->input->post('jenis_permohonan', TRUE),
  'bidang_usaha' => $this->input->post('bidang_usaha', TRUE),
  'sub_bidang' => $sub_bidang,
  'bagian_sub_bidang' => $bagian_sub_bidang,
  'sub_bagian_sub_bidang' => $gabung_gabung,
  );

$migrasi = $this->model->select('*', 'dokumen_skt', array('file_dokumen' => $this->input->post('id_dokumen', TRUE)));
echo $migrasi->no_dokumen;
$data2 = array(
  'id_perusahaan' => $this->session->userdata('id_perusahaan'),
  'jenis_dokumen' => 6, 
  'penerbit' => 'Ditjen MIGAS', 
  'nomor' => $migrasi->no_dokumen, 
  'tanggal_terbit' => $migrasi->mulai_masa_berlaku,
  'akhir_masa_berlaku' => $migrasi->akhir_masa_berlaku,
  'file_dokumen' => $migrasi->file_dokumen
  );
$this->model->insert('data_umum', $data2);

$this->model->insert('permohonan', $data);

$this->session->set_userdata('id_permohonan', $this->db->insert_id());
$this->logs();
if ($this->db->affected_rows() > 0) {
  redirect(base_url('perusahaan/data_pemohon'));
}

}

//***************************************************************************************************************************
// 5.5 FUNGSI DATA_UMUM


public function data_umum()
{
  $c = new grocery_crud();
  $c->set_table('data_umum');
  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->where('data_umum.jenis_dokumen !=', 12);
  $this->load->config('grocery_crud');
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $this->config->set_item('grocery_crud_file_upload_max_file_size', '5MB');

            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
    // $c->unset_edit();
  }

  $c->fields('id_perusahaan', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen', 'skrip_waktu');
  $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
  $c->set_field_upload('file_dokumen', 'assets/uploads/files');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  
	$c->callback_field('skrip_waktu', function ($value, $row){
		$output = '<script>
		$("#skrip_waktu_display_as_box").remove();
		$("#skrip_waktu_field_box").remove();
		$( "#field-tanggal_terbit" ).datepicker({ 					
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				     });
		$( "#field-akhir_masa_berlaku" ).datepicker({ 					
					yearRange: "'.(date('Y')-50).':'.(date('Y')+50).'",
				    changeMonth: true,
				    changeYear: true
				     });</script>'; 
		return $output;
  });
  
        // $c->unset_columns('id_perusahaan', 'status', 'catatan_petugas', 'status_pemakaian');
  $c->columns('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');

        //$c->callback_field('jenis_dokumen', array($this, 'callback_jenis_doc'));
  $c->callback_before_upload(array($this,'callback_before_upload_data_umum'));

        // set relation
  $c->set_relation('jenis_dokumen', 'ref_jenis_dokumen', 'jenis_dokumen', array('id_jenis_dokumen !=' => 12));

        // display as
  $c->display_as('jenis_dokumen', 'Dokumen');
  $c->display_as('akhir_masa_berlaku', 'Berlaku Hingga Tanggal');

  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->data_pemohon($output);
  } else {
    return $output;
  }

}

function callback_before_upload_data_umum($files_to_upload, $field_info, $post_array){

 foreach($files_to_upload as $value) {

  $filename = $value['name'];
  $size = $value['size'];
}

if(($post_array['jenis_dokumen'] != 10) && ($size <= 20480))
{
  return true;
}
elseif(($post_array['jenis_dokumen'] == 10) || ($post_array['jenis_dokumen'] == 9) && ($size <= 524288))
{
  return true;    
}
else
{
  return 'Untuk file Akta Perusahaan dan Akta Terkini file maksimal 5Mb. Dan 200Kb untuk file lainnya.';    
}

}

function callback_before_upload_file_data($files_to_upload, $field_info, $post_array){

 foreach($files_to_upload as $value) {

  $filename = $value['name'];
  $size = $value['size'];
}

if($size <= 20480)
{
  return true;
}
else
{
  return 'Maaf, ukuran file yang Anda pilih terlalu besar';    
}

}


public function ganti($fungsi = NULL, $table = NULL, $id = NULL){
 switch ($table) {
   case 'data_umum':
   $id_table = 'id_dokumen';
   break; 
   case 'data_khusus':
   $id_table = 'id_dokumen';
   break; 
   case 'keanggotaan_asosiasi':
   $id_table = 'id_keanggotaan';
   break; 
   case 'pelatihan_tenaga_kerja_internal':
   $id_table = 'id_pelatihan';
   break; 
   case 'pelatihan_tenaga_kerja_eksternal':
   $id_table = 'id_pelatihan';
   break; 
   case 'sertifikasi_tenaga_kerja':
   $id_table = 'id_sertifikasi_tenaga_kerja';
   break; 
   case 'peralatan':
   $id_table = 'id_sarana';
   break; 
   case 'pengalaman_kerja_perusahaan':
   $id_table = 'id_laporan';
   break; 
   case 'nilai_investasi':
   $id_table = 'id_nilai_investasi';
   break; 
   case 'daftar_pekerjaan':
   $id_table = 'id_daftar_pekerjaan';
   break; 
   case 'sop':
   $id_table = 'id_sop';
   break; 
   case 'csr':
   $id_table = 'id_csr';
   break; 
   case 'tenaga_kerja':
   $id_table = 'id_tenaga_kerja';
   break;
   case 'jumlah_tenaga_kerja':
   $id_table = 'id_jumlah_tenaga_kerja';
   break; 
   case 'keanggotaan_asosiasi':
   $id_table = 'id_keanggotaan';
   break; 

   default:
               # code...
   break;
 }

 $ada = NULL;
       //ambil dahulu status pemakaian
 $get_data_by_id = $this->model->select('status_pemakaian', $table, array($id_table => $id));
 $temp = explode(',', $get_data_by_id->status_pemakaian);

 foreach ($temp as $key => $status_pemakaian) {
   if($status_pemakaian == $this->session->userdata('id_permohonan')){
    unset( $temp[$key] );				
  }elseif($status_pemakaian == ''){
    unset( $temp[$key] );
  }
}

	   //$data = array();

$update_status_pemakaian = $this->model->update($table, array('status_pemakaian' => implode(',', $temp)), array($id_table => $id));

if ($update_status_pemakaian) {        
 redirect('perusahaan/'.$fungsi);
}
}

public function pilih($fungsi = NULL, $table = NULL, $id = NULL){
 switch ($table) {
   case 'data_umum':
   $id_table = 'id_dokumen';
   break; 
   case 'data_khusus':
   $id_table = 'id_dokumen';
   break; 
   case 'keanggotaan_asosiasi':
   $id_table = 'id_keanggotaan';
   break; 
   case 'pelatihan_tenaga_kerja_internal':
   $id_table = 'id_pelatihan';
   break; 
   case 'pelatihan_tenaga_kerja_eksternal':
   $id_table = 'id_pelatihan';
   break; 
   case 'sertifikasi_tenaga_kerja':
   $id_table = 'id_sertifikasi_tenaga_kerja';
   break; 
   case 'peralatan':
   $id_table = 'id_sarana';
   break; 
   case 'pengalaman_kerja_perusahaan':
   $id_table = 'id_laporan';
   break; 
   case 'nilai_investasi':
   $id_table = 'id_nilai_investasi';
   break; 
   case 'daftar_pekerjaan':
   $id_table = 'id_daftar_pekerjaan';
   break; 
   case 'sop':
   $id_table = 'id_sop';
   break; 
   case 'csr':
   $id_table = 'id_csr';
   break; 
   case 'tenaga_kerja':
   $id_table = 'id_tenaga_kerja';
   break;
   case 'jumlah_tenaga_kerja':
   $id_table = 'id_jumlah_tenaga_kerja';
   break; 
   case 'keanggotaan_asosiasi':
   $id_table = 'id_keanggotaan';
   break; 

   default:
               # code...
   break;
 }
       //ambil dahulu status pemakaian
 $get_data_by_id = $this->model->select('status_pemakaian', $table, array($id_table => $id));

       // baru gaungkan status pemakaian
 if($get_data_by_id->status_pemakaian != NULL){
   $data_status_pemakaian = array('status_pemakaian' => $get_data_by_id->status_pemakaian.','.$this->session->userdata('id_permohonan'));
 }else{
   $data_status_pemakaian = array('status_pemakaian' => $this->session->userdata('id_permohonan'));
 }
 $update_status_pemakaian = $this->model->update($table, $data_status_pemakaian, array($id_table => $id));

 
 if ($update_status_pemakaian) {        
   redirect('perusahaan/'.$fungsi);
 }
}

//***************************************************************************************************************************
// 5.6 FUNGSI DATA_KHUSUS

public function data_khusus()
{
  $c = new grocery_crud();
  $c->set_table('data_khusus');
  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $this->load->config('grocery_crud');
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        // $c->callback_before_upload(array($this,'callback_before_upload_file_data'));


  $c->columns('jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen', 'action');		
  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  //edited by reza 29 april 2015
  $data_permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan')));
  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
    // $c->unset_edit();

  }


  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','data_khusus', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_dokumen' => $row->id_dokumen));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/data_pemohon/data_khusus/'.$row->id_dokumen.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/data_pemohon/data_khusus/'.$row->id_dokumen.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});		

  $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
  $c->fields('id_perusahaan', 'id_permohonan', 'nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen', 'skrip_waktu');
  $c->set_field_upload('file_dokumen', 'assets/uploads/files');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
  //$c->unset_fields('status', 'id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

 
	$c->callback_field('skrip_waktu', function ($value, $row){
		$output = '<script>
		$("#skrip_waktu_display_as_box").remove();
		$("#skrip_waktu_field_box").remove();
		$( "#field-tanggal_terbit" ).datepicker({ 					
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				     });
		$( "#field-akhir_masa_berlaku" ).datepicker({ 					
					yearRange: "'.(date('Y')-50).':'.(date('Y')+50).'",
				    changeMonth: true,
				    changeYear: true,
				     });
  </script>'; 
		return $output;
  });

        // display as
  $c->display_as('jenis_dokumen', 'Dokumen');
  $c->display_as('akhir_masa_berlaku', 'Berlaku Hingga Taggal');


  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->data_pemohon($output);
  } else {
    return $output;
  }
}


//***************************************************************************************************************************
// 5.8 FUNGSI DATA_PEMOHON

function data_pemohon()
    { //multigrid
      $this->config->load('grocery_crud');
      $this->config->set_item('grocery_crud_dialog_forms', true);
      $this->config->set_item('grocery_crud_default_per_page', 10);

      $output2 = $this->data_umum();
      $output3 = $this->data_khusus();

      $js_files =  $output2->js_files + $output3->js_files;
      $css_files =  $output2->css_files + $output3->css_files;
      $output = "<h2>1. Data Umum</h2>" . $output2->output . "<br/><hr/><h2>2. Data Khusus</h2>" . $output3->output .
      '<span style="color:red; font-size:12px">Keterangan: <br/>1. *)Persetujuan Penanaman Modal Asing (dari BKPM) & Izin Usaha Tetap (dari BKPM)* Wajib diisi apabila pemohon merupakan perusahaan Penanaman Modal Asing (PMA).
<br/>2. **)Surat Izin usaha sesuai dengan bidang usaha yang dimohonkan contoh: SIUJK, SIUP, Surat Tanda Pendafataran bagi Agen Tunggal/Distributor, dst.</span>';

$level = $this->session->userdata('level');
if ($level != NULL) {
  if ($level == 1) {
    $this->load->view('level1/skt_tabel', (object)array(
      'js_files' => $js_files,
      'css_files' => $css_files,
      'output' => $output
      ));
  } else {
    redirect('all_users/dashboard');
  }
} elseif ($level == NULL) {
  redirect('umum/logout');
}
}

//***************************************************************************************************************************
// 5.9 FUNGSI KEANGGOTAAN_ASOSIASI

public function keanggotaan_asosiasi()
{
  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_dialog_forms', true);
  $this->config->set_item('grocery_crud_default_per_page', 10);
  $this->load->config('grocery_crud');
  $c = new grocery_crud();
  $c->set_table('keanggotaan_asosiasi');

  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $this->config->set_item('grocery_crud_file_upload_max_file_size', '200KB');


  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->columns('asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi', 'action');

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
    // $c->unset_edit();

  }
	$c->callback_field('skrip_waktu', function ($value, $row){
		$output = '<script>
		$("#skrip_waktu_display_as_box").remove();
		$(".ui-dialog-content").dialog( "option", "height", 350 );
		$("#skrip_waktu_field_box").remove();$( "#field-berlaku_hingga" ).datepicker({ 					
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				     });</script>'; 
		return $output;
  });

  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','keanggotaan_asosiasi', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_keanggotaan' => $row->id_keanggotaan));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/keanggotaan_asosiasi/keanggotaan_asosiasi/'.$row->id_keanggotaan.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/keanggotaan_asosiasi/keanggotaan_asosiasi/'.$row->id_keanggotaan.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});	

  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
  $c->fields('id_perusahaan', 'id_permohonan', 'asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi', 'skrip_waktu');
  $c->required_fields('asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi');

  $c->field_type('id_permohonan', 'hidden', $sess_id_permohonan);
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

  if ($this->session->userdata('id_permohonan') != '') {
    $sess_id_permohonan =  $this->session->userdata('id_permohonan');
  }else{
    $sess_id_permohonan =  '';
  }

  $c->set_field_upload('file_keanggotaan_asosiasi', 'assets/uploads/file_keanggotaan_asosiasi');

        //$c->unset_delete();

  $output = $c->render();
  $this->logs();

  $level = $this->session->userdata('level');
  if ($level != NULL) {
    if ($level == 1) {
      if ($c->getState() == 'add') {
			redirect('perusahaan/keanggotaan_asosiasi');
		}else{
			$this->load->view('level1/skt_tabel', $output);
		}
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    $this->logs();
    redirect('umum/logout');
  }
}


public function struktur_organisasi(){
  $c = new grocery_crud();
  $c->set_table('data_umum');
  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->where('data_umum.jenis_dokumen', 12);
  $this->load->config('grocery_crud');
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $this->config->set_item('grocery_crud_file_upload_max_file_size', '20MB');
  
  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->unset_add();

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
  }

  $c->required_fields('file_dokumen');
  $c->fields('file_dokumen');
  $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        // $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));


  // $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
		//field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset field
        //$c->unset_fields('status', 'catatan_petugas', 'status_pemakaian');

        // unset columns
  $c->unset_columns('id_perusahaan', 'status', 'catatan_petugas', 'status_pemakaian', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'nomor');

        // set relation
  $c->set_relation('jenis_dokumen', 'ref_jenis_dokumen', 'jenis_dokumen');

        // display as
  $c->display_as('jenis_dokumen', 'Dokumen');
  $c->display_as('akhir_masa_berlaku', 'Berlaku Hingga Tanggal');

  $output = $c->render();
  $this->logs();

  $level = $this->session->userdata('level');
  if ($level != NULL) {
    if ($level == 1) {
      $this->load->view('level1/skt_tabel', $output);
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    $this->logs();
    redirect('umum/logout');
  }

}

//***************************************************************************************************************************
// 5.10 FUNGSI TENAGA_KERJA

public function tenaga_kerja(){
  $c = new grocery_crud();
  $c->set_table('tenaga_kerja');

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->columns('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'daftar_sertifikat', 'sertifikat', 'action');	

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
    // $c->unset_edit();

  }
  // $c->unset_mytools();
  $c->callback_column('daftar_sertifikat', function ($value, $row){
   $sertifikats = $this->model->selects('*', 'sertifikasi_tenaga_kerja', array('id_tenaga_kerja' => $row->id_tenaga_kerja));
   $output = '';
   if($sertifikats != NULL){
     foreach($sertifikats as $key => $sertifikat){
      $output .= ($key+1).'. '.$sertifikat->judul_pelatihan.' / '.$sertifikat->nomor_sertifikat.' / '.anchor('assets/uploads/files/file_sertifikat_tenaga_ahli/'.$sertifikat->file_sertifikat, 'Sertifikat').'<br/>';		
    }
  }
  return $output;
});

  $c->callback_column('sertifikat', function ($value, $row){
   $sertifikat = $this->model->selects('*', 'sertifikasi_tenaga_kerja', array('id_tenaga_kerja' => $row->id_tenaga_kerja));
   return '<div style="text-align:center; margin: 0px !important">
   <a class="link-pilih  edit-anchor edit_button" title="Daftar Sertifikat" href="'.base_url().'perusahaan/sertifikasi_tenaga_kerja/add/'.$row->id_tenaga_kerja.'">Tambah</a> | 
   <a class="link-pilih edit-anchor edit_button" title="Tugaskan evaluator" href="'.base_url().'perusahaan/tenaga_kerja/read/'.$row->id_tenaga_kerja.'">Lihat</a></div>';		
 });

  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','tenaga_kerja', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_tenaga_kerja' => $row->id_tenaga_kerja));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   $output = '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/data_tenaga_kerja/tenaga_kerja/'.$row->id_tenaga_kerja.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   $output = '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/data_tenaga_kerja/tenaga_kerja/'.$row->id_tenaga_kerja.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
 return $output;
});

  $c->where('tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
  $this->load->config('grocery_crud');
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  // $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
        //$c->unset_delete();
  $c->required_fields('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah');
  $c->fields('nama_lengkap', 'id_perusahaan', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'no_imta', 'file_imta');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('status', 'enum', array('Permanen', 'Non Permanen'));
  $c->set_field_upload('file_ijazah', 'assets/uploads/file_ijazah_tenaga_ahli');
  $c->set_field_upload('file_imta', 'assets/uploads/file_imta');


  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
  $c->unset_fields('id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian', 'sertifikasi');

        // set relation
  $c->set_relation('jenjang_pendidikan', 'ref_jenjang_pendidikan', 'jenjang_pendidikan', null, 'id_jenjang_pendidikan');

        // display as
  $c->display_as('jenjang_pendidikan', 'Pendidikan Terakhir');
  $c->display_as('jurusan_pendidikan', 'Jurusan');
  $c->display_as('jabatan', 'Jabatan/Keahlian');
  $c->display_as('status', 'Status Kepegawaian');
  $c->display_as('status_pemakaian', 'Status');
  $c->display_as('no_imta', 'No IMTA *)');
  $c->callback_field('no_imta',array($this,'callback_no_imta'));
  $c->display_as('file_imta', 'File IMTA');

  if($c->getState() == 'read'){
   $c->fields('sertifikasi');
   $c->callback_field('sertifikasi', array($this, 'callback_read_sertifikasi'));
 }
 $output = $c->render();
 $this->logs();



 if ($c->getState() != 'list') {

  $this->data_tenaga_kerja($output);

} else {

  return $output;
}
}


public function callback_no_imta($value='', $primary_key  = null)
{
		# code...
  return '<input type="text" value="'.$value.'" name="no_imta"> <span style="color:red; font-size:10px">*) Khusus WNA</span>';
}	

public function callback_read_sertifikasi($value = '', $primary_key = null){
        //You can do it strait forward
  $nama = $this->model->select('*', 'tenaga_kerja', array('id_tenaga_kerja' => $primary_key));
  $sertifikats = $this->model->selects('*', 'sertifikasi_tenaga_kerja', array('id_tenaga_kerja' => $primary_key));
  $output = 	'<script>$("#sertifikasi_display_as_box").remove();
  $("#sertifikasi_input_box").css({"width":"auto","margin-right":"7px","float":"none"});
  $(".ui-dialog-content").dialog( "option", "height", 430 );
  $(".ui-dialog-content").dialog( "option", "width", 750 );
  $("div.crud-form div.mDiv div.ftitle").remove();
  $("#sertifikasi_field_box").css("text-align", "center");
  $(".pDiv").css("border", "none");
  $(".form-div").css("border", "none");
  $("#save-and-go-back-button").remove();
  $("#form-button-save").remove();
  $("#cancel-button").remove();
</script>';
$output .= '<div class="div-sertifikasi" style="padding-top:20px">&laquo; Daftar Sertifikat Tenaga Ahli <b>'.$nama->nama_lengkap.'</b> &raquo;<hr style="border-top: 1px solid #BDC3C7; margin-top:7px; margin-bottom:7px"/><table class="tabel-sertifikasi table table-bordered table-striped" style="margin-bottom:0!important;text-align:center;width:700;"><thead><tr>';
$output .= '<th>Judul Pelatihan</th>';
$output .= '<th>Nomor Sertifikat</th>';
$output .= '<th>File Sertifikat</th>';
$output .= '</tr></thead>';
$output .= '<tbody id="cloneMother">';
$output .= '<tr id="cloneObject1">';
if($sertifikats != NULL){
  foreach($sertifikats as $sertifikat){
    $output .= '<td id="td1_1">'.$sertifikat->judul_pelatihan.'</td>';
    $output .= '<td id="td1_2">'.$sertifikat->nomor_sertifikat.'</td>';
    if($sertifikat->file_sertifikat != NULL){
      $output .= '<td id="td1_3"><a href="'.base_url('assets/uploads/file_sertifikat_tenaga_ahli/'.$sertifikat->file_sertifikat).'">Lihat Dokumen</a></td>';
    }else{
      $output .= '<td id="td1_3">Tidak Ada Dokumen</td>';
    }$output .= '</tr>';
  }
}else{
  $output .='<tr><td colspan="3" style="text-align:left">Tidak ada record</td></tr>';
}
$output .='</tbody>';
$output .= '</table></div>';

		//Or with a view
		//$output = $this->load->view('whatever',array('value'=>$value),true);

return $output;
}

//***************************************************************************************************************************
// 5.11 FUNGSI SERTIFIKASI_TENAGA_KERJA

public function sertifikasi_tenaga_kerja()
{
  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_dialog_forms', true);
  $this->config->set_item('grocery_crud_default_per_page', 10);
  $this->load->config('grocery_crud');
  $c = new grocery_crud();
  $c->set_table('sertifikasi_tenaga_kerja');
  $c->where('sertifikasi_tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));

  $c->fields('id_perusahaan', 'id_tenaga_kerja', 'judul_pelatihan', 'nomor_sertifikat', 'file_sertifikat', 'skrip');
  $c->required_fields('id_tenaga_kerja', 'judul_pelatihan', 'nomor_sertifikat', 'file_sertifikat');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

  $c->callback_field('skrip', function ($value, $row){
    return '<script>
    $("#skrip_display_as_box").remove();
    $("#skrip_field_box").remove();

    $("div.crud-form div.mDiv div.ftitle").remove();
    $(".ptogtitle").remove();
    $(".ui-dialog-content").dialog( "option", "height", 350 );
    $("#save-and-go-back-button").attr("value", "Submit");
    $("#form-button-save").remove();
    $("#cancel-button").remove();
  </script>';
});

        // unset columns
		//$c->unset_read();
		// $c->unset_delete();
		// $c->unset_print();
		// $c->unset_export();

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->set_lang_string('insert_success_message',
   'Data Anda berhasil disimpan.
   <script type="text/javascript">
    window.location = "'.site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__)).'";
  </script>
  <div style="display:none">
   '
   );

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
		  //$c->unset_edit();
  }

  $c->unset_columns('id_perusahaan');
  $c->display_as('id_tenaga_kerja', 'Nama Tenaga Kerja');
  $c->set_field_upload('file_sertifikat', 'assets/uploads/file_sertifikat_tenaga_ahli');

        // set relation
  $c->set_relation('id_tenaga_kerja', 'tenaga_kerja', 'nama_lengkap', array('id_tenaga_kerja' => $this->uri->segment(4)));


  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'add' || $c->getState() != 'success') {
    redirect('perusahaan/data_tenaga_kerja');
  }else{
   $this->data_tenaga_kerja($output);
 }
}

//***************************************************************************************************************************
// 5.11 FUNGSI JUMLAH_TENAGA_KERJA

public function jumlah_tenaga_kerja()
{
  $c = new grocery_crud();
  $c->set_table('jumlah_tenaga_kerja');
  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $this->load->config('grocery_crud');        
  $c->required_fields('tipe_tenaga_kerja', 'sd', 'smp', 'sma', 'diploma', 'sarjana', 'pasca_sarjana', 'doktor');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    $c->unset_read();
  }

		//field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset columns
  $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
  $c->unset_fields('catatan_petugas', 'status_pemakaian', 'tipe_tenaga_kerja');
  $c->unset_delete();
  $c->unset_add();

        // set relation

        // display as
  $c->display_as('tipe_tenaga_kerja', 'Tenaga Kerja');
  $c->display_as('sd', 'SD');
  $c->display_as('smp', 'SMP');
  $c->display_as('sma', 'SMA');
  $c->display_as('diploma', 'Diploma (D-3)');


  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->tenaga_kerja($output);
  } else {
    return $output;
  }
}

//***************************************************************************************************************************
// 5.12 FUNGSI DATA_TENAGA_KERJA

function data_tenaga_kerja()
    { //multigrid
      $this->config->load('grocery_crud');
      $this->config->set_item('grocery_crud_dialog_forms', true);
      $this->config->set_item('grocery_crud_default_per_page', 10);
      $this->config->set_item('grocery_crud_file_upload_max_file_size', '1024KB');

      $output1 = $this->tenaga_kerja();
      $output3 = $this->jumlah_tenaga_kerja();

      $js_files = $output1->js_files + $output3->js_files;
      $css_files = $output1->css_files + $output3->css_files;

      $output = '<h2>1. Daftar Tenaga Kerja Ahli sesuai Bidang Usaha yang dimohon /  Quality
      Assurance / Quality Control (QA/QC)</h2><span style="color:red; font-size:12px">(dilampirkan ijazah terakhir, sertifikat kompetensi, dan riwayat pekerjaan untuk setiap tenaga ahli).</span>' . $output1->output . '
      <span style="color:red; font-size:12px">Keterangan: 
        <br/>- Pelatihan yang dicantumkan hanya pelatihan yang berhubungan dengan posisi atau jabatan.  
        <br/>- Untuk warga negara asing, wajib mencantumkan nomor IMTA pada kolom KETERANGAN.
        <br/></span><hr/><h2>2. Jumlah Tenaga Kerja</h2>' . $output3->output;

        $level = $this->session->userdata('level');
        if ($level != NULL) {
          if ($level == 1) {
            $this->load->view('level1/skt_tabel', (object)array(
              'js_files' => $js_files,
              'css_files' => $css_files,
              'output' => $output
              ));
          } else {
            redirect('all_users/dashboard');
          }
        } elseif ($level == NULL) {
          redirect('umum/logout');
        }
      }

//***************************************************************************************************************************
// 5.13 FUNGSI PELATIHAN_TENAGA_KERJA_INTERNAL

      public function pelatihan_tenaga_kerja_internal(){
        $c = new grocery_crud();
        $c->set_table('pelatihan_tenaga_kerja_internal');


        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();

        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

        $c->columns('jenis_pelatihan', 'keterangan', 'action');		

        if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
          $c->unset_delete();
          // $c->unset_edit();
          $c->unset_read();	   
        }

        $c->callback_column('action', function ($value, $row){
          $ada = NULL;
          $cek_if_has_data_permohonan = select('status_pemakaian','pelatihan_tenaga_kerja_internal', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_pelatihan' => $row->id_pelatihan));
          $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
          if($cek_if_has_data_permohonan != NULL){
           foreach ($temp as $key => $status_pemakaian) {
            if($status_pemakaian == $this->session->userdata('id_permohonan')){
             $ada = 'ada';
           }
         }                
       }
       if($ada != NULL){
         return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_internal/'.$row->id_pelatihan.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
       }else{
         return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_internal/'.$row->id_pelatihan.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
       }	
     });

$c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
$this->load->config('grocery_crud');
        //$c->unset_delete();
$c->required_fields('jenis_pelatihan', 'keterangan');
$c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/pelatihan_tenaga_kerja")));

        //field type
$c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
$c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
$c->field_type('keterangan', 'text');
        // unset columns
$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
$c->unset_fields('catatan_petugas', 'status_pemakaian');

$output = $c->render();
$this->logs();

if ($c->getState() != 'list') {
  $this->pelatihan_tenaga_kerja($output);
} else {
  return $output;
}

}

//***************************************************************************************************************************
// 5.14 FUNGSI PELATIHAN_TENAGA_KERJA_EKSTERNAL

public function pelatihan_tenaga_kerja_eksternal()
{
  $c = new grocery_crud();
  $c->set_table('pelatihan_tenaga_kerja_eksternal');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();
  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->columns('id_tenaga_kerja', 'jenis_pelatihan', 'action');             	

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    // $c->unset_edit();
    $c->unset_read();	   
  }

  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','pelatihan_tenaga_kerja_eksternal', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_pelatihan' => $row->id_pelatihan));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_eksternal/'.$row->id_pelatihan.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_eksternal/'.$row->id_pelatihan.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});


  $c->where('pelatihan_tenaga_kerja_eksternal.id_perusahaan', $this->session->userdata('id_perusahaan'));
  $this->load->config('grocery_crud');
        //$c->unset_delete();
  $c->required_fields('id_tenaga_kerja', 'jenis_pelatihan');
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/pelatihan_tenaga_kerja")));

        //field type
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        // unset columns
  $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
  $c->unset_fields('catatan_petugas', 'status_pemakaian');

        // set relation
  $c->set_relation('id_tenaga_kerja', 'tenaga_kerja', 'nama_lengkap', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));

        // display as
  $c->display_as('id_tenaga_kerja', 'Nama Tenaga Kerja');


  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->pelatihan_tenaga_kerja($output);
  } else {
    return $output;
  }
}

//***************************************************************************************************************************
// 5.15 FUNGSI PELATIHAN_TENAGA_KERJA

function pelatihan_tenaga_kerja()
    { //multigrid
      $this->config->load('grocery_crud');
      $this->config->set_item('grocery_crud_dialog_forms', true);
      $this->config->set_item('grocery_crud_default_per_page', 10);

      $output1 = $this->pelatihan_tenaga_kerja_internal();

      $output2 = $this->pelatihan_tenaga_kerja_eksternal();

      $js_files = $output1->js_files + $output2->js_files;
      $css_files = $output1->css_files + $output2->css_files;
      $output = "<h2>1. Tabel Pelatihan Tenaga Kerja inhouse </h2>" . $output1->output . "<br/><hr/><h2>2. Tabel Program Pelatihan Tenaga Kerja eksternal</h2>" . $output2->output . '<span style="color:red; font-size:12px">Keterangan:
      <br/>*) Disesuaikan dengan bidang usaha yang dimohonkan.</span>';

$level = $this->session->userdata('level');
if ($level != NULL) {
  if ($level == 1) {
    $this->load->view('level1/skt_tabel', (object)array(
      'js_files' => $js_files,
      'css_files' => $css_files,
      'output' => $output
      ));
  } elseif ($level == NULL) {
    redirect('all_users/dashboard');
  }
} elseif ($level == NULL) {
  redirect('umum/logout');
}
}

//***************************************************************************************************************************
// 5.16 FUNGSI PERALATAN

public function peralatan_utama(){
  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_dialog_forms', true);
  $this->config->set_item('grocery_crud_default_per_page', 10);
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $this->config->set_item('grocery_crud_file_upload_max_file_size', '200KB');
        // $this->config->set_item('grocery_crud_file_upload_max_file_size', '2000');
  $c = new grocery_crud();
  $c->set_table('peralatan');

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat', 'action');			    	
	
  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    // $c->unset_edit();
    $c->unset_read();	   
  }

  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $row->id_sarana));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});


  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->where('golongan_alat', 'Peralatan Utama');

        // unset field
  $c->unset_fields('catatan_petugas', 'status_pemakaian');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian');
  $c->fields('id_perusahaan', 'golongan_alat', 'id_permohonan', 'nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat', 'keterangan');
  $c->required_fields('nama_alat', 'golongan_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat', 'keterangan');
  $c->callback_field('keterangan', function ($value, $row){
    return '<script>
    CKEDITOR.replace( "keterangan",
    {
      removePlugins: "toolbar",
      height : 200,
      width : 550,
      tabSpaces : 4,
      readOnly : true
    });</script>
</script>
<textarea id="keterangan" name="keterangan" class="ckeditor">'.$value.'</textarea>';
});
  $c->callback_field('lokasi', array($this, 'callback_daftar_lokasi'));


  // $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
  $c->field_type('catatan', 'text');
  $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
  $c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');

  // $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));

  $c->field_type('golongan_alat', 'hidden', 'Peralatan Utama');
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

  $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        // $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->pelatihan_tenaga_kerja($output);
  } else {
    return $output;
  }
}

public function peralatan_pendukung(){
  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_dialog_forms', true);
  $this->config->set_item('grocery_crud_default_per_page', 10);
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $c = new grocery_crud();
  $c->set_table('peralatan');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));	    	

  $c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'action');

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    // $c->unset_edit();
    $c->unset_read();	   
  }


  $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $row->id_sarana));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});



  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->where('golongan_alat', 'Peralatan Pendukung');

        // unset field
  $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat', 'keterangan');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
  $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

  $c->field_type('catatan', 'text');
  $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
  $c->callback_field('lokasi', array($this, 'callback_daftar_lokasi'));

  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));

  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
  $c->field_type('golongan_alat', 'hidden', 'Peralatan Pendukung');

  $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
  // $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->pelatihan_tenaga_kerja($output);
  } else {
    return $output;
  }
}

public function peralatan_keselamatan(){
  $this->config->load('grocery_crud');
  $this->config->set_item('grocery_crud_dialog_forms', true);
  $this->config->set_item('grocery_crud_default_per_page', 10);
  $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  $c = new grocery_crud();
  $c->set_table('peralatan');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();

  $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

  $c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'action');

  if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
    $c->unset_delete();
    // $c->unset_edit();
    $c->unset_read();	   
  }


  $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
  $c->callback_column('action', function ($value, $row){
    $ada = NULL;
    $cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $row->id_sarana));
    $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
    if($cek_if_has_data_permohonan != NULL){
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $ada = 'ada';
     }
   }                
 }
 if($ada != NULL){
   return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
 }else{
   return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/peralatan/peralatan/'.$row->id_sarana.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
 }	
});

  $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->where('golongan_alat', 'Peralatan Keselamatan dan Kesehatan Kerja');

        // unset field
  $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat', 'keterangan');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
  $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

  $c->field_type('catatan', 'text');
  $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
  $c->callback_field('lokasi', array($this, 'callback_daftar_lokasi'));

  $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));

  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
  $c->field_type('golongan_alat', 'hidden', 'Peralatan Keselamatan dan Kesehatan Kerja');

  $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
  // $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

  $output = $c->render();
  $this->logs();

  if ($c->getState() != 'list') {
    $this->pelatihan_tenaga_kerja($output);
  } else {
    return $output;
  }
}

//***************************************************************************************************************************
// 5.15 FUNGSI PERALATAN

    function peralatan(){ //multigrid
      $this->config->load('grocery_crud');
      $this->config->set_item('grocery_crud_dialog_forms', true);
      $this->config->set_item('grocery_crud_default_per_page', 10);
      //$this->config->set_item('grocery_crud_file_upload_max_file_size', '20MB');

      $output1 = $this->peralatan_utama();
      $output2 = $this->peralatan_pendukung();
      $output3 = $this->peralatan_keselamatan();

      $js_files = $output1->js_files + $output2->js_files + $output3->js_files;
      $css_files = $output1->css_files + $output2->css_files + $output3->css_files;
      $output = "<h2>1. Peralatan Utama </h2>" . $output1->output . "<br/><hr/><h2>2. Peralatan Pendukung</h2>" . $output2->output. "<br/><hr/><h2>3. Peralatan Keselamatan Kerja</h2>" . $output3->output;

      $level = $this->session->userdata('level');
      if ($level != NULL) {
        if ($level == 1) {
          $this->load->view('level1/skt_tabel', (object)array(
            'js_files' => $js_files,
            'css_files' => $css_files,
            'output' => $output
            ));
        } elseif ($level == NULL) {
          redirect('all_users/dashboard');
        }
      } elseif ($level == NULL) {
        redirect('umum/logout');
      }
    }

	function nilai_investasi()
    { //multigrid
      $this->config->load('grocery_crud');
      $this->config->set_item('grocery_crud_dialog_forms', true);
      $this->config->set_item('grocery_crud_default_per_page', 10);

      $output1 = $this->nilai_investasi_awal();
      $output2 = $this->nilai_investasi_terkini();

      $js_files =  $output1->js_files + $output2->js_files;
      $css_files =  $output1->css_files + $output2->css_files;
      $output = "<h2>1. Nilai Investasi Awal</h2>" . $output1->output . "<br/><hr/><h2>2. Nilai Investasi Terkini</h2>" . $output2->output .
      '<span style="color:red; font-size:12px">Keterangan: <br/>1. *)Persetujuan Penanaman Modal Asing (dari BKPM) & Izin Usaha Tetap (dari BKPM)* Wajib diisi apabila pemohon merupakan perusahaan Penanaman Modal Asing (PMA).
<br/>2. **)Surat Izin usaha sesuai dengan bidang usaha yang dimohonkan contoh: SIUJK, SIUP, Surat Tanda Pendafataran bagi Agen Tunggal/Distributor, dst.</span>';

$level = $this->session->userdata('level');
if ($level != NULL) {
  if ($level == 1) {
    $this->load->view('level1/skt_tabel', (object)array(
      'js_files' => $js_files,
      'css_files' => $css_files,
      'output' => $output
      ));
  } else {
    redirect('all_users/dashboard');
  }
} elseif ($level == NULL) {
  redirect('umum/logout');
}
}

//***************************************************************************************************************************
// 5.17 FUNGSI NILAI_INVESTASI

    public function nilai_investasi_awal(){
	  $this->config->load('grocery_crud');
	  $this->config->set_item('grocery_crud_dialog_forms', true);
	  $this->config->set_item('grocery_crud_default_per_page', 10);
      $c = new grocery_crud();
      $c->set_table('nilai_investasi');;
      $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
      $c->where('jenis_investasi', 'awal');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


      $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

      if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
        $c->unset_delete();
        $c->unset_read();
		//$c->unset_edit();
      }

        // unset field
      $c->fields('id_perusahaan', 'nama_investor', 'negara_asal', 'mata_uang', 'nominal_investasi', 'persentase', 'jenis_investasi');
      $c->columns('nama_investor', 'negara_asal', 'mata_uang', 'nominal_investasi', 'persentase');

        // unset columtn
      //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian', 'file_nilai_investasi');
      //$c->unset_fields('catatan_petugas', 'status_kepemilikan', 'file_nilai_investasi', 'status_pemakaian');

      $c->field_type('jenis_investasi', 'hidden', 'awal');
      $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
      $c->required_fields('nama_investor', 'negara_asal', 'nominal_investasi', 'persentase', 'mata_uang');
        //$c->set_field_upload('file_nilai_investasi', 'assets/uploads/file_nilai_investasi');

      $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
      $c->callback_field('mata_uang', array($this, 'callback_mata_uang'));
      $c->callback_column('nominal_investasi', function ($value, $row){
        /* if($row->mata_uang == 'Rupiah'){
			$fmt = new NumberFormatter( 'IND', NumberFormatter::CURRENCY );
			return $fmt->formatCurrency($value, "IDR");
       }elseif($row->mata_uang == 'Dollar Amerika'){
			$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
			return $fmt->formatCurrency($value, "USD");
       } */
	   return $this->formatUang($value, $row->mata_uang);
     });	
      $c->callback_field('persentase', function ($value, $row){
        return '<input type="text" value="'.$value.'" name="persentase" style="width: 30%"><br/><span style="color:red; font-size:10px">*) Gunakan tanda titik (.) untuk menuliskan nilai desimal<br/>*) Angka maksimal adalah 100</span>';
    });	
      $c->display_as('persentase', 'Persentase %');
	$c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/nilai_investasi")));

        //$c->unset_delete();
      $output = $c->render();

      $level = $this->session->userdata('level');
      if ($level != NULL) {
        if ($level == 1) {
          if ($c->getState() != 'list') {
			$this->nilai_investasi($output);
		  } else {
			return $output;
		  }
        } else {
          redirect('all_users/dashboard');
        }
      } elseif ($level == NULL) {
        redirect('umum/logout');
      }
    }

//***************************************************************************************************************************
// 5.17 FUNGSI NILAI_INVESTASI

    public function nilai_investasi_terkini(){
	  $this->config->load('grocery_crud');
	  $this->config->set_item('grocery_crud_dialog_forms', true);
	  $this->config->set_item('grocery_crud_default_per_page', 10);
      $c = new grocery_crud();
      $c->set_table('nilai_investasi');;
      $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
      $c->where('jenis_investasi', 'terkini');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


      $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

      if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
        $c->unset_delete();
        $c->unset_read();
		//$c->unset_edit();
      }


            //$c->add_action('Pilih', 'sd', 'perusahaan/pilih/peralatan','ui-icon-plus');
        // }

        // unset field
      $c->fields('id_perusahaan', 'nama_investor', 'negara_asal', 'mata_uang', 'nominal_investasi', 'persentase', 'jenis_investasi');
      $c->columns('nama_investor', 'negara_asal', 'mata_uang', 'nominal_investasi', 'persentase');


      $c->field_type('jenis_investasi', 'hidden', 'terkini');
      $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
      $c->required_fields('nama_investor', 'negara_asal', 'nominal_investasi', 'persentase', 'mata_uang');
      

      $c->callback_before_upload(array($this,'callback_before_upload_file_data'));
      $c->callback_field('mata_uang', array($this, 'callback_mata_uang'));
      $c->callback_column('nominal_investasi', function ($value, $row){
        return $this->formatUang($value, $row->mata_uang);
     });	
      $c->callback_field('persentase', function ($value, $row){
        return '<input type="text" value="'.$value.'" name="persentase" style="width: 30%"><br/><span style="color:red; font-size:10px">*) Gunakan tanda titik (.) untuk menuliskan nilai desimal<br/>*) Angka maksimal adalah 100</span>';
    });	
      $c->display_as('persentase', 'Persentase %');
	$c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/nilai_investasi")));

        //$c->unset_delete();
      $output = $c->render();

      $level = $this->session->userdata('level');
      if ($level != NULL) {
        if ($level == 1) {
          if ($c->getState() != 'list') {
			$this->nilai_investasi($output);
		  } else {
			return $output;
		  }
        } else {
          redirect('all_users/dashboard');
        }
      } elseif ($level == NULL) {
        redirect('umum/logout');
      }
    }

	
	public function formatUang($value, $currency){
		/* if($currency == 'Rupee India'){
			$fmt = new NumberFormatter( 'en_IN', NumberFormatter::CURRENCY );		
		}else{
			$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );		
		}
		
		if($currency == 'Rupiah Indonesia'){
			$currency = 'IDR';
		}elseif($currency == 'Dollar Amerika'){
			$currency = 'USD';
		}elseif($currency == 'Dollar Singapura'){
			$currency = 'SGD';
		}elseif($currency == 'Baht Thailand'){
			$currency = 'THB';
		}elseif($currency == 'Peso Argentina'){
			$currency = 'ARS';
		}elseif($currency == 'Ringgit Malaysia'){
			$currency = 'MYR';
		}elseif($currency == 'Yen Jepang'){
			$currency = 'JPY';
		} */
		
		if($currency == 'INR'){
			$fmt = new NumberFormatter( 'en_IN', NumberFormatter::CURRENCY );		
		}else{
			$fmt = new NumberFormatter( 'IND', NumberFormatter::CURRENCY );		
		}
		
		$sMyPattern = "¤ #,##0.00;-¤ #,##0.00";
		$fmt->setPattern($sMyPattern);
		return $fmt->formatCurrency($value, $currency);
	}
	
    public function callback_mata_uang($value='', $primary_key  = null)
    {
		# code...
      /* return '<select name="mata_uang" class="form-control select-center" required >
      <option class="opt-center" value="">-- Pilih Mata Uang --</option>
      <option class="opt-center" value="Rupiah Indonesia">Rupiah Indonesia</option>
      <option class="opt-center" value="Baht Thailand">Baht Thailand</option>
      <option class="opt-center" value="Dollar Amerika">Dollar Amerika</option>
      <option class="opt-center" value="Dollar Singapura">Dollar Singapura</option>
      <option class="opt-center" value="Peso Argentina">Peso Argentina</option>
      <option class="opt-center" value="Ringgit Malaysia">Ringgit Malaysia</option>
      <option class="opt-center" value="Yen Jepang">Yen Jepang</option>
    </select>'; */
      return '<select name="mata_uang" class="form-control select-center" required >
      <option class="opt-center" value="">-- Pilih Mata Uang --</option>
      <option class="opt-center" value="IDR">Rupiah Indonesia</option>
      <option class="opt-center" value="THB">Baht Thailand</option>
      <option class="opt-center" value="SAR">Dinar Saudi Arabia</option>
      <option class="opt-center" value="USD">Dollar Amerika</option>
      <option class="opt-center" value="SGD">Dollar Singapura</option>
      <option class="opt-center" value="EUR">Euro Uni Eropa</option>
      <option class="opt-center" value="INR">Rupee India</option>
      <option class="opt-center" value="KRW">Won Korea Selatan</option>
      <option class="opt-center" value="MXN">Peso Mexiko</option>
      <option class="opt-center" value="GBP">Pound Sterling Inggris</option>
      <option class="opt-center" value="MYR">Ringgit Malaysia</option>
      <option class="opt-center" value="JPY">Yen Jepang</option>
    </select>';
  }

    public function callback_daftar_lokasi($value='', $primary_key  = null)
    {
		# code...
      return '<script>
				$(document).ready(function (){
				    $( "#field-lokasi" ).autocomplete({
						source: function(request, response) {
							$.ajax({
								url: "'.site_url("perusahaan/suggestcities").'",
								data: { lokasi: $("#field-lokasi").val()},
								dataType: "json",
								type: "POST",
								success: function(data){
									response(data);
								}   
							});
						},
					});
				});
				 </script><style>.ui-autocomplete{ height: 200px; overflow-y: scroll; overflow-x: hidden; }</style>
				<input type="text" value="'.$value.'" name="lokasi" id="field-lokasi">';
  }

    public function callback_waktu($value='', $primary_key  = null)
    {
		# code...
      return '<script>
			  $( "#waktu" ).datepicker({
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				});
				 </script>
				 
				 <input id="waktu" name="waktu" value="" maxlength="10" class="datepicker-input" type="text">
				 <a aria-disabled="false" role="button" class="datepicker-input-clear ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" tabindex="-1"><span class="ui-button-text">kosongkan</span></a>';
  }
  
  public function suggestcities(){
		$lokasi = $this->input->post('lokasi',TRUE);
        //$rows = $this->model->getKantor($lokasi);
        $rows = like('ref_kota', 'kota', $lokasi);
        $json_array = array();
        foreach ($rows as $row)
            $json_array[]=$row->kota;
        echo json_encode($json_array);
	}

//***************************************************************************************************************************
// 5.18 FUNGSI PENGALAMAN_KERJA

  function pengalaman_kerja(){
	$this->config->load('grocery_crud');
	$this->config->set_item('grocery_crud_dialog_forms', true);
	$this->config->set_item('grocery_crud_default_per_page', 10);
	$this->load->config('grocery_crud');
    $c = new grocery_crud();
    $c->set_table('daftar_pekerjaan');

    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    $c->columns('nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak', 'action');		

    if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
      $c->unset_delete();
      // $c->unset_edit();
      $c->unset_read();	   
    }

    $c->callback_column('action', function ($value, $row){
      $ada = NULL;
      $cek_if_has_data_permohonan = select('status_pemakaian','daftar_pekerjaan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_daftar_pekerjaan' => $row->id_daftar_pekerjaan));
      $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
      if($cek_if_has_data_permohonan != NULL){
       foreach ($temp as $key => $status_pemakaian) {
        if($status_pemakaian == $this->session->userdata('id_permohonan')){
         $ada = 'ada';
       }
     }                
   }
   if($ada != NULL){
     return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/pengalaman_kerja/daftar_pekerjaan/'.$row->id_daftar_pekerjaan.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
   }else{
     return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/pengalaman_kerja/daftar_pekerjaan/'.$row->id_daftar_pekerjaan.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
   }	
 });
	$c->callback_field('lokasi_kerja', function ($value, $row){
		return '<script>
			  
				    $( "#lokasi_kerja" ).autocomplete({
						source: function(request, response) {
							$.ajax({
								url: "'.site_url("perusahaan/suggestcities").'",
								data: { lokasi: $("#lokasi_kerja").val()},
								dataType: "json",
								type: "POST",
								success: function(data){
									response(data);
								}   
							});
						},
					});
				 </script><style>.ui-autocomplete{ height: 200px; overflow-y: scroll; overflow-x: hidden; }</style>
				<input type="text" value="'.$value.'" name="lokasi_kerja" id="lokasi_kerja">';
  });
  
	$c->callback_field('skrip_waktu', function ($value, $row){
		$output = '<script>
		$("#skrip_waktu_display_as_box").remove();
		$("#skrip_waktu_field_box").remove();
		$( "#field-tanggal_terbit_kontrak" ).datepicker({ 					
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				     });
		$( "#field-tanggal_habis_kontrak" ).datepicker({ 					
					yearRange: "'.(date('Y')-50).':'.(date('Y')+50).'",
				    changeMonth: true,
				    changeYear: true
				     });</script>'; 
		return $output;
  });

    $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
    $this->load->config('grocery_crud');
        //$c->unset_delete();
    $c->required_fields('nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak');
    $c->fields('id_perusahaan', 'id_permohonan', 'nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak', 'tanggal_terbit_kontrak', 'tanggal_habis_kontrak', 'skrip_waktu');
    // $c->set_relation('lokasi_kerja', 'ref_kota', 'kota', null, 'id_kota');
        //field type
    $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
    $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // display as
    $c->display_as('id_daftar_pekerjaan', 'Nama Pekerjaan');
        //$c->display_as('k3l', 'K3L');
        //$c->display_as('iso', 'ISO');

        // unset columns
        //$c->unset_columns('id_perusahaan', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');
    //$c->unset_fields('catatan_petugas', 'status_pemakaian');

    $output = $c->render();
    $this->logs();

    $level = $this->session->userdata('level');
    if ($level != NULL) {
      if ($level == 1) {
        if ($c->getState() == 'add') {
			redirect('perusahaan/pengalaman_kerja');
		}else{
			$this->load->view('level1/skt_tabel', $output);
		}
      } else {
        redirect('all_users/dashboard');
      }
    } elseif ($level == NULL) {
      redirect('umum/logout');
    }
  }

//***************************************************************************************************************************
// 5.19 FUNGSI SOP

  public function sop(){
    $c = new grocery_crud();
    $c->set_table('sop');
    $this->config->load('grocery_crud');
    $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
    $this->config->set_item('grocery_crud_file_upload_max_file_size', '5MB');

    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));       

    $c->columns('prosedur', 'deskripsi', 'file_manajemen_prosedur_kerja', 'action');		

    if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
      $c->unset_delete();
      // $c->unset_edit();
      $c->unset_read();	   
    }

    $c->callback_column('action', function ($value, $row){
      $ada = NULL;
      $cek_if_has_data_permohonan = select('status_pemakaian','sop', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sop' => $row->id_sop));
      $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
      if($cek_if_has_data_permohonan != NULL){
       foreach ($temp as $key => $status_pemakaian) {
        if($status_pemakaian == $this->session->userdata('id_permohonan')){
         $ada = 'ada';
       }
     }                
   }
   if($ada != NULL){
     return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/sop/sop/'.$row->id_sop.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
   }else{
     return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/sop/sop/'.$row->id_sop.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
   }	
 });

    // $c->callback_before_upload(array($this,'callback_before_upload_file_data'));

    $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));

    $c->required_fields('prosedur', 'deskripsi', 'file_manajemen_prosedur_kerja');

    $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
    $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

    $c->unset_fields('catatan_petugas','id_permohonan', 'status_pemakaian');
        //$c->unset_columns('id_perusahaan','catatan_petugas','id_permohonan', 'status_pemakaian');
        //$c->unset_delete();
    $c->field_type('deskripsi', 'text');
    $c->display_as('prosedur', 'Prosedur Yang Digunakan');
    $c->display_as('deskripsi', 'Deskripsi Singkat Penerapan Prosedur');

    $c->set_field_upload('file_manajemen_prosedur_kerja', 'assets/uploads/file_sistem_manajemen_dan_prosedur_kerja_teknis');

    $output = $c->render();
    $this->logs();

    $level = $this->session->userdata('level');
    if ($level != NULL) {
      if ($level == 1) {
        $this->load->view('level1/skt_tabel', $output);
      } else {
        redirect('all_users/dashboard');
      }
    } elseif ($level == NULL) {
      redirect('umum/logout');
    }
  }

//***************************************************************************************************************************
// 5.20 FUNGSI CSR

  public function csr(){
	$this->config->load('grocery_crud');
	$this->config->set_item('grocery_crud_dialog_forms', true);
	$this->config->set_item('grocery_crud_default_per_page', 10);
	$this->load->config('grocery_crud');
    $c = new grocery_crud();
    $c->set_table('csr');

    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    $c->columns('kegiatan', 'waktu', 'lokasi', 'action');		

    if(!$this->session->userdata('laporan_berkala') == 'aktif'){      
      $c->unset_delete();
      // $c->unset_edit();
      $c->unset_read();	   
    }

    $c->callback_column('action', function ($value, $row){
      $ada = NULL;
      $cek_if_has_data_permohonan = select('status_pemakaian','csr', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_csr' => $row->id_csr));
      $temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
      if($cek_if_has_data_permohonan != NULL){
       foreach ($temp as $key => $status_pemakaian) {
        if($status_pemakaian == $this->session->userdata('id_permohonan')){
         $ada = 'ada';
       }
     }                
   }
   if($ada != NULL){
     return '<a class="link-pilih" href="'.base_url().'perusahaan/ganti/csr/csr/'.$row->id_csr.'" title="Klik untuk merubah"><img class="btn-pilih">Dipilih</a>';
   }else{
     return '<a class="link-pilih" href="'.base_url().'perusahaan/pilih/csr/csr/'.$row->id_csr.'" title="Klik untuk merubah"><img class="btn-ganti">Tidak Dipilih</a>';
   }	
 });


    $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));

    $c->required_fields('kegiatan', 'waktu', 'lokasi');
    $c->fields('id_perusahaan', 'kegiatan', 'waktu', 'lokasi', 'skrip_waktu');
    // $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

    $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
    $c->set_field_upload('file_csr', 'assets/uploads/file_csr');

    //$c->unset_fields('catatan_petugas', 'status_pemakaian');
	$c->callback_field('lokasi', array($this, 'callback_daftar_lokasi'));
	$c->field_type('skrip', 'hidden');

	$c->callback_field('skrip_waktu', function ($value, $row){
		$output = '<script>
		$("#skrip_waktu_display_as_box").remove();
		$(".ui-dialog-content").dialog( "option", "height", 350 );
		$("#skrip_waktu_field_box").remove();$( "#field-waktu" ).datepicker({ 					
					yearRange: "'.(date('Y')-110).':'.date('Y').'",
				    changeMonth: true,
				    changeYear: true
				     });</script>'; 
		return $output;
  });
        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
    $c->display_as('file_csr', 'File CSR');
        //$c->unset_delete();

    $output = $c->render();
    $this->logs();

    $level = $this->session->userdata('level');
    if ($level != NULL) {
      if ($level == 1) {
		if ($c->getState() == 'add') {
			redirect('perusahaan/csr');
		}else{
			$this->load->view('level1/skt_tabel', $output);
		}
      } else {
        redirect('all_users/dashboard');
      }
    } elseif ($level == NULL) {
      redirect('umum/logout');
    }
  }

  public function cek_kelengkapan(){
		//echo 'ya'.$this->session->userdata('temporary');
    $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan')));

    $biodata_perusahaan = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
    $data_umum = $this->model->selects('*', 'data_umum', array('id_perusahaan' => $permohonan->id_perusahaan));
    $data_khusus = $this->model->selects('*', 'data_khusus', array('id_perusahaan' => $permohonan->id_perusahaan));
    $keanggotaan_asosiasi = $this->model->selects('*', 'keanggotaan_asosiasi', array('id_perusahaan' => $permohonan->id_perusahaan));
    $tenaga_kerja = $this->model->selects('*', 'tenaga_kerja', array('id_perusahaan' => $permohonan->id_perusahaan));
    $jumlah_tenaga_kerja = $this->model->selects('*', 'jumlah_tenaga_kerja', array('id_perusahaan' => $permohonan->id_perusahaan));
    $pelatihan_tenaga_kerja_internal = $this->model->selects('*', 'pelatihan_tenaga_kerja_internal', array('id_perusahaan' => $permohonan->id_perusahaan));
    $pelatihan_tenaga_kerja_eksternal = $this->model->selects('*', 'pelatihan_tenaga_kerja_eksternal', array('id_perusahaan' => $permohonan->id_perusahaan));
    $peralatan = $this->model->selects('*', 'peralatan', array('id_perusahaan' => $permohonan->id_perusahaan));
    $nilai_investasi_awal = $this->model->selects('*', 'nilai_investasi', array('id_perusahaan' => $permohonan->id_perusahaan, 'jenis_investasi' => 'awal'));
    $nilai_investasi_kini = $this->model->selects('*', 'nilai_investasi', array('id_perusahaan' => $permohonan->id_perusahaan, 'jenis_investasi' => 'terkini'));
    $daftar_pekerjaan = $this->model->selects('*', 'daftar_pekerjaan', array('id_perusahaan' => $permohonan->id_perusahaan));
    $sop = $this->model->selects('*', 'sop', array('id_perusahaan' => $permohonan->id_perusahaan));
    $csr = $this->model->selects('*', 'csr', array('id_perusahaan' => $permohonan->id_perusahaan));

    $tidak_ada = array();
    if($data_umum != NULL){
     $i=1; foreach($data_umum as $dt_umum){
      if(($dt_umum->jenis_dokumen == 9) && ($dt_umum->file_dokumen != NULL)){
        $strk = 'ada';
      }elseif($dt_umum->jenis_dokumen != 9){
        $du = 'ada';
      }
    }
  }

 //  if(!isset($strk)){
 //   $tidak_ada[0] = 'Struktur Organisasi';
 // }

  if(!isset($du)){
   $tidak_ada[1] = 'Data Umum';
 }

 if($data_khusus != NULL){
   $i=1; foreach($data_khusus as $dt_khusus){
    if($dt_khusus->status_pemakaian != NULL){
     $temp = explode(',', $dt_khusus->status_pemakaian);						
     foreach ($temp as $key => $status_pemakaian) {
      if($status_pemakaian == $this->session->userdata('id_permohonan')){
       $dk = 'Data Khusus';
     }
   }
 }
}
}	

if(!isset($dk)){
 $tidak_ada[2] = 'Data Khusus';
}

if($keanggotaan_asosiasi != NULL){
 $i=1; foreach($keanggotaan_asosiasi as $asosiasi){
  if($asosiasi->status_pemakaian != NULL){
   $temp = explode(',', $asosiasi->status_pemakaian);

   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $ka = 'Keanggotaan Asosiasi';
   }
 }
}
}
}

if(!isset($ka)){
 $tidak_ada[3] = 'Keanggotaan Asosiasi';
}

if($tenaga_kerja != NULL){
 $i=1; foreach($tenaga_kerja as $pekerja){
  if($pekerja->status_pemakaian != NULL){
   $temp = explode(',', $pekerja->status_pemakaian);				
   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $ta = 'Tenaga Kerja';
   }
 }
}
}
}

if(!isset($ta)){
 $tidak_ada[4] = 'Tenaga Kerja';
}

if($pelatihan_tenaga_kerja_internal != NULL){
 $i=1; foreach($pelatihan_tenaga_kerja_internal as $pelatihan_internal){
  if($pelatihan_internal->status_pemakaian != NULL){
   $temp = explode(',', $pelatihan_internal->status_pemakaian);				
   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $pti = 'Pelatihan Tenaga Kerja Internal';
   }
 }
}
}
}

if(!isset($pti)){
 $tidak_ada[5] = 'Pelatihan Tenaga Kerja Internal';
}

if($pelatihan_tenaga_kerja_eksternal != NULL){						
 $i=1; foreach($pelatihan_tenaga_kerja_eksternal as $pelatihan_eksternal){	
  $i=1; foreach($tenaga_kerja as $pekerja){
   if($pelatihan_eksternal->status_pemakaian != NULL){
    $temp = explode(',', $pelatihan_eksternal->status_pemakaian);						
    foreach ($temp as $key => $status_pemakaian) {
     if($status_pemakaian == $this->session->userdata('id_permohonan')){
      $pte = 'Pelatihan Tenaga Kerja Internal';
    }
  }
}
}
}
}

if(!isset($pte)){
 $tidak_ada[6] = 'Pelatihan Tenaga Kerja Internal';
}

if($peralatan != NULL){	
 $i=1; foreach($peralatan as $alat){
  if($alat->golongan_alat == 'Peralatan Utama'){
   if($alat->status_pemakaian != NULL){
    $temp = explode(',', $alat->status_pemakaian);						
    foreach ($temp as $key => $status_pemakaian) {
     if($status_pemakaian == $this->session->userdata('id_permohonan')){
      $utama = 'ada';
    }
  }						
}
}elseif($alat->golongan_alat == 'Peralatan Pendukung'){
 if($alat->status_pemakaian != NULL){
  $temp = explode(',', $alat->status_pemakaian);						
  foreach ($temp as $key => $status_pemakaian) {
   if($status_pemakaian == $this->session->userdata('id_permohonan')){
    $pendukung = 'ada';
  }
}						
}
}elseif($alat->golongan_alat == 'Peralatan Keselamatan dan Kesehatan Kerja'){
 if($alat->status_pemakaian != NULL){
  $temp = explode(',', $alat->status_pemakaian);						
  foreach ($temp as $key => $status_pemakaian) {
   if($status_pemakaian == $this->session->userdata('id_permohonan')){
    $keselamatan = 'ada';
  }
}						
}
}
}
}

if(!isset($utama)){
 $tidak_ada[7] = 'Peralatan Utama';
}elseif(!isset($pendukung)){
 $tidak_ada[8] = 'Peralatan Pendukung';
}elseif(!isset($keselamatan)){
 $tidak_ada[9] = 'Peralatan Keselamatan dan Kesehatan Kerja';
}		

if($daftar_pekerjaan != NULL){	
 $i=1; foreach($daftar_pekerjaan as $pekerjaan){
  if($pekerjaan->status_pemakaian != NULL){
   $temp = explode(',', $pekerjaan->status_pemakaian);						
   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $dpk = 'Pengalaman Kerja';
   }
 }
}
}
}	

if(!isset($dpk)){
 $tidak_ada[10] = 'Pengalaman Kerja';
}

if($sop != NULL){
 $i=1; foreach($sop as $sopx){ 
  if($sopx->status_pemakaian != NULL){
   $temp = explode(',', $sopx->status_pemakaian);						
   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $sp = 'SOP';
   }
 }
}
}
}

if(!isset($sp)){
 $tidak_ada[11] = 'SOP';
}

if($csr != NULL){
 $i=1; foreach($csr as $csrx){ 
  if($csrx->status_pemakaian != NULL){
   $temp = explode(',', $csrx->status_pemakaian);						
   foreach ($temp as $key => $status_pemakaian) {
    if($status_pemakaian == $this->session->userdata('id_permohonan')){
     $cs = 'CSR';
   }
 }
}
}
}

if(!isset($cs)){
 $tidak_ada[12] = 'CSR';
}

$nilai_awal = 0;
if($nilai_investasi_awal != NULL){						
 $i=1; foreach($nilai_investasi_awal as $invest1){
  $nilai_awal = $nilai_awal + $invest1->persentase;				
}
}

$niv1 = '';
if($nilai_awal != 0){
 if($nilai_awal < 100){
  $niv1 = 'Nilai Investasi Awal perusahaan Anda belum mencapai angka 100%';
}elseif($nilai_awal > 100){
  $niv1 = 'Nilai Investasi Awal perusahaan Anda melebihi angka 100%';
}
}

$nilai_kini = 0;
if($nilai_investasi_kini != NULL){						
 $i=1; foreach($nilai_investasi_kini as $invest2){
  $nilai_kini = $nilai_kini + $invest2->persentase;				
}
}

$niv2 = '';
if($nilai_kini != 0){
 if($nilai_kini < 100){
  $niv2 = 'Nilai Investasi Terkini perusahaan Anda belum mencapai angka 100%';
}elseif($nilai_kini > 100){
  $niv2 = 'Nilai Investasi Terkini perusahaan Anda melebihi angka 100%';
}
}

foreach($tidak_ada as $key => $tdkada){
 if( empty( $tdkada ) ){
  unset( $tidak_ada[$key] );
}
}

$json['response'] = implode(', ', $tidak_ada);
$json['response2'] = $niv1;
$json['response3'] = $niv2;
echo json_encode($json);

}

//***************************************************************************************************************************
// 5.22 FUNGSI STATUS_PROGRESS

public function status_progress()
{
  $id = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
  $stat = $this->model->select('*', 'ref_status_progres', array('key_status' => $id->status_progress));
  $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'status_progress' => $id->status_progress));
  $dis = $this->model->selects('*', 'view_disposisi_permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'status_progress' => 2));        

  $output = array('status' => $dis);

        // echo var_dump($output);

  $level = $this->session->userdata('level');
  if ($level != NULL) {
    if ($level == 1) {
      $this->load->view('level1/skt_tabel', $output);
    } else {
      redirect('all_users/dashboard');
    }
  } elseif ($level == NULL) {
    redirect('umum/logout');
  }

}

public function disposisi_user_to_admin(){

  $admin = $this->model->select('id_user', 'users', array('level' => 2, 'status' => 1));
  $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));

  $data_disposisi = array(
    'id_perusahaan' => $this->session->userdata('id_perusahaan'),
    'user_asal' => $this->session->userdata('id_user'),
    'user_tujuan' => $admin->id_user,
    'status_progress' => 2,
    'catatan_user_asal' => 'Pegajuan masuk',
    'id_permohonan' => $this->session->userdata('id_permohonan')
    );

  $this->model->insert('disposisi', $data_disposisi);
  $this->model->update('permohonan', array('selesai' => 1), array('id_permohonan' => $this->session->userdata('id_permohonan'),'id_perusahaan' => $this->session->userdata('id_perusahaan')));

  $permo = $this->model->select('*', 'permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'selesai' => 0));
  if($permo){
   $this->session->set_userdata('id_permohonan', $permo->id_permohonan);
 }else{
   $this->session->set_userdata('id_permohonan', '');
 }
 echo "<script>alert('Terima kasih telah melengkapi proses pengajuan SKT MIGAS, data Anda segera akan kami proses')</script>";
 $this->session->set_flashdata('message', 'Pengajuan anda berhasil!');
 redirect(base_url('all_users/dashboard'));
}


//***************************************************************************************************************************
// 2.4 FUNGSI LOGS

public function logs($user_data = NULL)
{
  $this->load->library('user_agent');
  $this->load->model('model');
  $this->load->helper('url');

  $logData = array(
    'ip_address' => $this->input->ip_address(),
    'user_agent' => $this->agent->agent_string(),
    'url' => $this->uri->uri_string(),
    'who' => $this->session->userdata('id_user'),
    'user_data' => $user_data
    );

  $this->model->insert('logs', $logData);

}

public function laporan_berkala(){
  $c = new grocery_crud();
  $c->set_table('dokumen_skt');
        // if($this->glo_jenis_permo != ''){
  $c->unset_operations();

  $c->columns('bidang_jasa','no_dokumen', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'file_dokumen','pilihan');		
  $c->set_relation('id_permohonan', 'permohonan', 'id_permohonan');
  $c->where('selesai', 2);
  $c->where('dokumen_skt.id_perusahaan', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->set_field_upload('file_dokumen', 'assets/uploads/file_skt');
  $c->display_as('bidang_jasa', 'Bidang Jasa');

		//$c->add_action('Proses', 'sd', 'perusahaan/proses_laporan/update','ui-icon-plus');
		/* $c->add_action('(2) Buat Laporan', 'sd', 'perusahaan/proses_laporan/view','ui-icon-plus');
		$c->add_action('(3) Upload Laporan  ', 'sd', 'perusahaan/proses_laporan/proses','ui-icon-plus'); */
		
		$c->callback_column('pilihan', function ($value, $row){
			$pelaporan = $this->model->select('*', 'pelaporan_periodik', array('id_permohonan' => $row->id_permohonan, 'status_laporan' => 2), array('id_pelaporan_periodik', 'desc'));
			if($pelaporan != NULL){
				return '<a href="'.base_url().'perusahaan/proses_laporan/update/'.$row->id_permohonan.'">Proses</a> | Revisi <a class="link-pilih" title="Edit data" href="'.base_url().'perusahaan/edit_laporan_berkala/'.$row->id_permohonan.'"><img class="mybtn-edit"></a>';
			}else{
				return '<a href="'.base_url().'perusahaan/proses_laporan/update/'.$row->id_permohonan.'">Proses</a>';
			}
    }); 

		$c->callback_column('bidang_jasa', function ($value, $row){
      $ada = NULL;
      $data_permohonan = select('*','permohonan', array('id_permohonan' => $row->id_permohonan));
      $bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $data_permohonan->bidang_usaha));
      $sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $data_permohonan->sub_bidang));
      return $bidang_usaha->bidang_usaha.'/'.$sub_bidang->sub_bidang;	
    });	

    $output = $c->render();
    $this->logs();

    $level = $this->session->userdata('level');
    $status = $this->session->userdata('status_lap_periodik');
    if ($level != NULL) {
      if (($level == 1) && ($status == 1)) {
        $this->load->view('level1/view_list', $output);
      } else {
        redirect('all_users/dashboard');
      }
    } elseif ($level == NULL) {
      redirect('umum/logout');
    }
  }

  public function proses_laporan($action = NULL, $id = NULL){
   $level = $this->session->userdata('level');
   $id_permo = $this->session->userdata('id_permohonan');
   $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id_permo));
   if($action == 'update'){
    $this->session->set_userdata('id_permohonan', $id);
    $this->session->set_userdata('laporan_berkala', 'aktif');
    $id_permo = $this->session->userdata('id_permohonan');
    $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id));
    if($permohonan->selesai == 2){
      $recs = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $id));
      redirect('perusahaan/data_pemohon');
    }else{
     redirect('all_users/dashboard');
   }

 }elseif($action == 'view'){			
  if($permohonan->selesai == 2){
    $recs = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $id));
    redirect('all_admin/detail_perusahaan/laporan_periodik/'.$recs->id_permohonan);
  }else{
   redirect('all_users/dashboard');
 }
}elseif($action == 'proses'){
  if($permohonan->selesai == 2){
    $recs = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $id));
    redirect('perusahaan/pelaporan_periodik/'.$recs->id_permohonan);
  }else{
   redirect('all_users/dashboard');
 }
}
}

//***************************************************************************************************************************
// 2.5 FUNGSI GETUSERIP

public function getUserIP()
{
  $client = @$_SERVER['HTTP_CLIENT_IP'];
        // $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $forward = @$_SERVER['HTTP_CLIENT_IP'];
  $remote = $_SERVER['REMOTE_ADDR'];

  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }
  return $ip;
}

public function pelaporan_periodik(){
  $c = new grocery_crud();
  $c->set_table('pelaporan_periodik');
  $permohonan = $this->session->userdata('id_permohonan');
  $c->unset_delete();
  $c->unset_read();
  $c->unset_print();
  $c->unset_export();
  $c->unset_mytools();
  $c->set_field_upload('file_pelaporan_periodik', 'assets/uploads/file_pelaporan_periodik');
  $c->columns('semester', 'file_pelaporan_periodik', 'status_laporan');
  $c->fields('semester', 'id_permohonan', 'file_pelaporan_periodik', 'id_evaluator', 'id_perusahaan','status_laporan', 'create_at', 'catatan_evaluator');
  $c->field_type('id_permohonan', 'hidden', $permohonan);
  $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
  $c->field_type('status_laporan', 'hidden', '1');        
  $c->field_type('create_at', 'hidden', strftime('%Y-%m-%d'));        
  $c->field_type('catatan_evaluator', 'hidden');     

  $c->callback_after_update(function($post_array,$primary_key){
   $this->db->update('pelaporan_periodik', array('catatan_evaluator' => NULL), array('id_pelaporan_periodik' => $primary_key));
   return TRUE;
 });

  $c->callback_column('status_laporan', function ($value, $row){
    if($value == 1){
      return 'Diajukan';
    }elseif($value == 2){
      return 'Revisi | <a class="link-pilih" title="Edit data" href="'.base_url().'perusahaan/pelaporan_periodik/edit/'.$row->id_pelaporan_periodik.'"><img class="mybtn-edit"></a>';
    }elseif($value == 3){
      return 'Disetujui';
    }elseif($value == 4){
      return 'Ditolak';
    }
  }); 

  $c->where('id_permohonan', $permohonan);
  $c->set_relation('semester', 'ref_semester', 'semester', array('is_delete' => 0));


  $sl_permohonan = select('user_tujuan', 'disposisi', array('id_permohonan' => $permohonan, 'status_progress' => 8), array('id_disposisi', 'DESC'));


  $smohon = select('*', 'users', array('id_user' => $sl_permohonan->user_tujuan, 'level'=> 6));

            //$retVal = ($smohon) ? $smohon->id_user : 0 ;
  $c->field_type('id_evaluator', 'hidden',$smohon->id_user);

  $output = $c->render();
  $this->logs();

  $level = $this->session->userdata('level');
  $status = $this->session->userdata('status_lap_periodik');
  if ($level != NULL) {
    if (($level == 1) && ($status == 1)) {
     $this->load->view('level1/view_list', $output);
   } else {
     redirect('all_users/dashboard');
   }
 } elseif ($level == NULL) {
  redirect('umum/logout');
}

if ($c->getState() != 'add') {
  $c->change_field_type('semester', 'readonly');
  if ($c->getState() == 'success') {
   redirect('perusahaan/pelaporan_periodik');
 }
}
}

public function submit_laporan_berkala()
{
  $permohonan = $this->model->select('id_permohonan', 'permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'selesai' => 0));
  $this->session->set_userdata('id_permohonan', $permohonan->id_permohonan);
  redirect('perusahaan/laporan_berkala');
}

public function edit_laporan_berkala($id_permohonan)
{
  $this->session->set_userdata('id_permohonan', $id_permohonan);
  redirect('perusahaan/pelaporan_periodik/');
}

public function revisi_pengajuan($id_permohonan){
  $this->session->set_userdata('id_permohonan', $id_permohonan);
  redirect('perusahaan/data_pemohon');
}

public function submit_revisi(){
		//$admin = $this->model->select('id_user', 'users', array('level' => 2, 'status' => 1));
  $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_permohonan' => $this->session->userdata('id_permohonan')), array('id_disposisi', 'desc')); 

  if($disposisi->status_progress == 3){
   $status = 2;
 }elseif($disposisi->status_progress == 10){
   $status = 7;
 }

 $data_disposisi = array(
   'id_perusahaan' => $disposisi->id_perusahaan,
   'id_parent' => $disposisi->id_parent,
   'user_asal' => $this->session->userdata('id_user'),
   'user_tujuan' => $disposisi->user_asal,
   'catatan_user_asal' => 'Pengajuan telah direvisi',
   'status_progress' => $status,
   'id_permohonan' => $this->session->userdata('id_permohonan')
   );

 $this->model->insert('disposisi', $data_disposisi);

 $permo = $this->model->select('*', 'permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'selesai' => 0));
 if($permo){
   $this->session->set_userdata('id_permohonan', $permo->id_permohonan);
 }else{
   $this->session->set_userdata('id_permohonan', '');
 }

 echo "<script>alert('Terima kasih telah melengkapi proses pengajuan SKT MIGAS, data Anda segera akan kami proses')</script>";
 $this->session->set_flashdata('message', 'Perbaikan pengajuan anda akan segera diproses');
 redirect(base_url('all_users/dashboard'));
}

}