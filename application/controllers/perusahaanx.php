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
			$sub_bidang_obj = '<label class="bidang">Sub Bidang</label>: <select name="sub_bidang" id="sub_bidang">';
			$sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

			foreach ($sub_bidang as $key => $sbdg) {
				$sub_bidang_obj .= '<option name="sub_bidang" id="sb-' . $sbdg->id_sub_bidang . '" onclick="sub_bidang(' . $sbdg->id_sub_bidang . ')" value="' . $sbdg->id_sub_bidang . '">' . $sbdg->sub_bidang . '</option>';
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
		$isi = implode(",", $gabung);
        $sub_bidang = $this->input->post('sub_bidang', TRUE);
        $bagian_sub_bidang = implode(",", $this->input->post('bagian_sub_bidang', TRUE));
        $sub_bagian_sub_bidang = implode(",", $this->input->post('sub_bagian_sub_bidang', TRUE));
		
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
		
        $this->model->insert('permohonan', $data);

        $this->session->set_userdata('id_permohonan', $this->db->insert_id());
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
		
		$migrasi = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $this->input->post('id_dokumen', TRUE)));
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

            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


                $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }else{

    }

        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset field
        $c->unset_fields('status', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        // $c->unset_columns('id_perusahaan', 'status', 'catatan_petugas', 'status_pemakaian');
        $c->columns('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');

		$c->callback_field('jenis_dokumen', array($this, 'callback_jenis_doc'));
		
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

	public function callback_jenis_doc($value = NULL, $primary_key = NULL){		
		$ref_dokumen = $this->model->selects('*', 'ref_jenis_dokumen', array('id_jenis_dokumen !=' => 9));
		$result = '<select name="jenis_dokumen" id="jenis_dokumen">';
		foreach($ref_dokumen as $ref_doc){
			$dt_umum = $this->model->select('*', 'data_umum', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'jenis_dokumen' => $ref_doc->id_jenis_dokumen));
			if(!$dt_umum){
				$result .= '<option value="'.$ref_doc->id_jenis_dokumen.'">'.$ref_doc->jenis_dokumen.'</option>';
			}
			
		}
		$result .= '</select>';
		return $result;
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


		$c->columns('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen', 'status');		
    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }else{

    }
	
	$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/data_pemohon/data_khusus','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','data_khusus', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_dokumen' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/data_pemohon/data_khusus/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/data_pemohon/data_khusus/'.$primary_key;
				}	
        });
		
        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
        $c->unset_fields('status', 'id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns

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
        $c = new grocery_crud();
        $c->set_table('keanggotaan_asosiasi');


        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }
           	
		$c->columns('asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/keanggotaan_asosiasi/keanggotaan_asosiasi','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','keanggotaan_asosiasi', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_keanggotaan' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/keanggotaan_asosiasi/keanggotaan_asosiasi/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/keanggotaan_asosiasi/keanggotaan_asosiasi/'.$primary_key;
				}	
        });
        // }



        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
        $c->required_fields('asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        if ($this->session->userdata('id_permohonan') != '') {
            $sess_id_permohonan =  $this->session->userdata('id_permohonan');
        }else{
            $sess_id_permohonan =  '';
        }
        
        $c->field_type('id_permohonan', 'hidden', $sess_id_permohonan);
        $c->set_field_upload('file_keanggotaan_asosiasi', 'assets/uploads/file_keanggotaan_asosiasi');

        $c->unset_fields('catatan_petugas', 'status_pemakaian');
        //$c->unset_delete();

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

		
	public function struktur_organisasi(){
        $c = new grocery_crud();
        $c->set_table('data_umum');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('data_umum.jenis_dokumen', 12);
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
  
        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

          $c->unset_add();

        if(!$sl_permohonan){      
          $c->unset_delete();
          $c->unset_read();
        }

        $c->required_fields('file_dokumen');
        $c->fields('file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        // $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

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

    public function tenaga_kerja()
    {
        $c = new grocery_crud();
        $c->set_table('tenaga_kerja');

        // if($this->glo_jenis_permo != ''){
        // $c->unset_delete();
        //    // $c->unset_read();
        // $c->unset_edit();
            
          	        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_edit();
    }
		$c->columns('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'status_pemakaian');		
		$c->callback_column('status_pemakaian', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/data_tenaga_kerja/tenaga_kerja','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','tenaga_kerja', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_tenaga_kerja' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/data_tenaga_kerja/tenaga_kerja/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/data_tenaga_kerja/tenaga_kerja/'.$primary_key;
				}	
        });
        // }

        $c->where('tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        //$c->unset_delete();
        $c->required_fields('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah');
        $c->fields('nama_lengkap', 'id_perusahaan', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'no_imta', 'file_imta', 'sertifikasi');
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

		$c->callback_field('sertifikasi', array($this, 'callback_sertifikasi'));
        // display as
        $c->display_as('jenjang_pendidikan', 'Pendidikan Terakhir');
        $c->display_as('jurusan_pendidikan', 'Jurusan');
        $c->display_as('jabatan', 'Jabatan/Keahlian');
        $c->display_as('status', 'Status Kepegawaian');
        $c->display_as('status_pemakaian', 'Status');
        $c->display_as('no_imta', 'No IMTA *)');
         $c->callback_field('no_imta',array($this,'callback_no_imta'));
        $c->display_as('file_imta', 'File IMTA');
		
		$c->callback_after_insert(array($this,'callback_before_insert_or_update'));		
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
	public function callback_before_insert_or_update($post_array, $primary_key){
		$juduls = $post_array['judul_pelatihan'];
		$nomors = $post_array['nomor_sertifikat'];
		$file_sertifikat = $post_array['file_sertifikat'];

		$config = array();
		$config['upload_path'] = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png|pdf';
		$config['max_size'] = '200000';

		$this->load->library('upload', $config);

		/* if ( ! $this->upload->do_upload($file_sertifikat)) {
			echo $this->upload->display_errors(); 
		} else { */ 
			$this->upload->do_upload($file_sertifikat);
			$data_file = $this->upload->data(); 
			
			$count = count($juduls); 
			$datas = array(); 
			for($i=0; $i<$count; $i++) { 
				$datas[$i] = array( 	'id_tenaga_kerja' => $primary_key, 
									'id_perusahaan' => $this->session->userdata('id_perusahaan'),
									'judul_pelatihan' => $juduls[$i],
									'nomor_sertifikat' => $nomors[$i],
									'file_sertifikat' => $file_sertifikat[$i]
								);
			} 
			
			$this->db->insert_batch('sertifikasi_tenaga_kerja', $datas); return TRUE; 
		//} 
	}
	
//***************************************************************************************************************************
// 5.11 FUNGSI CALLBACK_SERTIFIKASI
	
    public function callback_sertifikasi(){
        //You can do it strait forward
		$output = '<script> $(document).ready(function (){ var counter = 2;	var limit = 11;	';
		$output .= '$("#btn_add").click(function(){';
		$output .= 'if (counter == limit){ alert("Anda terlalu banyak menambahkan data!");	} ';
		$output .= 'else {';
		$output .= 'var clone1 = $("#cloneObject1").clone(); ';
		$output .= 'clone1.attr("id","cloneObject" +counter); clone1.empty(); ';
		$output .= 'clone1.append("<td id=\'td"+counter+"_1\'></td><td id=\'td"+counter+"_2\'></td><td id=\'td"+counter+"_3\'></td><td id=\'td"+counter+"_4\'></td>"); ';
		$output .= 'clone1.appendTo("#cloneMother"); ';
		$output .= 'var clone2 = $("#judul_pelatihan1").clone().val(""); clone2.attr("id","judul_pelatihan" +counter); ';
		$output .= 'clone2.appendTo("#td"+counter+"_1"); ';
		$output .= 'var clone3 = $("#nomor_sertifikat1").clone().val(""); clone3.attr("id","nomor_sertifikat" + counter); ';
		$output .= 'clone3.appendTo("#td"+counter+"_2"); ';
		$output .= 'var clone4 = $("#btn_add").clone(); clone4.attr({id: "btn_del"+counter, type:"button", goto:"cloneObject"+counter, name:"btn_del", class:"input-del"}).click(function(){$delrow = $(this).attr("goto"); $("#"+ $delrow).remove();}); ';
		$output .= 'clone4.appendTo("#td"+counter+"_3"); counter++; ';
		$output .= '} }); ';
		$output .= '';
		$output .= '}); ';
		$output .= '</script>';
		$output .= '<div class="div-sertifikasi"><table class="tabel-sertifikasi"><thead><tr>';
		$output .= '<th class="title-sertifikat">Judul Pelatihan</th>';
		$output .= '<th class="title-sertifikat">Nomor Sertifikat</th>';
		$output .= '<th></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody id="cloneMother"><tr id="cloneObject1">';
		$output .= '<td id="td1_1"><input class="input-sertifikat" type="text" name="judul_pelatihan[]" placeholder="Judul.." id="judul_pelatihan1" required /></td>';
		$output .= '<td id="td1_2"><input class="input-sertifikat" type="text" name="nomor_sertifikat[]" placeholder="Nomor.." id="nomor_sertifikat1" /></td>';
		$output .= '<td id="td1_3"><input class="input-add" type="button" name="btn_add" id="btn_add" /></td>';
		$output .= '</tr></tbody>';
		$output .= '</table></div>';

		//Or with a view
		//$output = $this->load->view('whatever',array('value'=>$value),true);

		return $output;
    }	
	
    public function callback_read_sertifikasi($value = '', $primary_key = null){
        //You can do it strait forward
		$sertifikats = $this->model->selects('*', 'sertifikasi_tenaga_kerja', array('id_tenaga_kerja' => $primary_key));
		$output = '<div class="div-sertifikasi"><table class="tabel-sertifikasi"><thead><tr>';
		$output .= '<th class="title-sertifikat">Judul Pelatihan</th>';
		$output .= '<th class="title-sertifikat">Nomor Sertifikat</th>';
		$output .= '<th class="title-sertifikat">File Sertifikat</th>';
		$output .= '</tr></thead>';
		$output .= '<tbody id="cloneMother">';
		$output .= '<tr id="cloneObject1">';
		foreach($sertifikats as $sertifikat){
		$output .= '<td id="td1_1">'.$sertifikat->judul_pelatihan.'</td>';
		$output .= '<td id="td1_2">'.$sertifikat->nomor_sertifikat.'</td>';
        if($sertifikat->file_sertifikat != NULL){
        $output .= '<td id="td1_3"><a href="'.base_url('assets/uploads/file_sertifikat_tenaga_ahli/'.$sertifikat->file_sertifikat).'">Lihat Dokumen</a></td>';
        }else{
            $output .= '<td id="td1_3">Tidak Ada Dokumen</td>';
        }$output .= '</tr>';
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
        $c = new grocery_crud();
        $c->set_table('sertifikasi_tenaga_kerja');
        $c->where('sertifikasi_tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        
        $c->required_fields('id_tenaga_kerja', 'judul_pelatihan');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset columns
		//$c->unset_read();
		// $c->unset_delete();
		// $c->unset_print();
		// $c->unset_export();

        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }



        $c->unset_columns('id_perusahaan');
        $c->display_as('id_tenaga_kerja', 'Nama Tenaga Kerja');
        $c->set_field_upload('file_sertifikat', 'assets/uploads/file_sertifikat_tenaga_ahli');

        // set relation
        $c->set_relation('id_tenaga_kerja', 'tenaga_kerja', 'nama_lengkap', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));


        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_tenaga_kerja($output);
        } else {
            return $output;
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

    if(!$sl_permohonan){      
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
            $this->data_tenaga_kerja($output);
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

        $output1 = $this->tenaga_kerja();
        $output2 = $this->sertifikasi_tenaga_kerja();
        $output3 = $this->jumlah_tenaga_kerja();

        $js_files = $output1->js_files + $output2->js_files + $output3->js_files;
        $css_files = $output1->css_files + $output2->css_files + $output3->css_files;

        $output = '<h2>1. Daftar Tenaga Kerja Ahli sesuai Bidang Usaha yang dimohon /  Quality
		Assurance / Quality Control (QA/QC)</h2><span style="color:red; font-size:12px">(dilampirkan ijazah terakhir, sertifikat kompetensi, dan riwayat pekerjaan untuk setiap tenaga ahli).</span>' . $output1->output . '
		<span style="color:red; font-size:12px">Keterangan: 
		<br/>- Pelatihan yang dicantumkan hanya pelatihan yang berhubungan dengan posisi atau jabatan.  
		<br/>- Untuk warga negara asing, wajib mencantumkan nomor IMTA pada kolom KETERANGAN.
		<br/></span>
		<hr/><h2>2. Sertifikasi Tenaga Kerja</h2>' . $output2->output.'<hr/><h2>3. Jumlah Tenaga Kerja</h2>' . $output3->output;

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

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }



		$c->columns('jenis_pelatihan', 'keterangan', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_internal','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','pelatihan_tenaga_kerja_internal', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_pelatihan' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_internal/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_internal/'.$primary_key;
				}	
        });
        // }


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

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }
             	
		$c->columns('id_tenaga_kerja', 'jenis_pelatihan', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_eksternal','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','pelatihan_tenaga_kerja_eksternal', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_pelatihan' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_eksternal/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/pelatihan_tenaga_kerja/pelatihan_tenaga_kerja_eksternal/'.$primary_key;
				}	
        });
        // }


        /* $tenaga_kerja = $this->model->select('*', 'tenaga_kerja', array('id_perusahaan' => $this->session->userdata('id_perusahaan'))); */
        $c->where('pelatihan_tenaga_kerja_eksternal.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('id_tenaga_kerja', 'jenis_pelatihan');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/pelatihan_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        //$c->field_type('jenis_pelatihan', 'multiselect', array("Mutu" => "Mutu", "K3L" => "K3L", "ISO" => "ISO"));
        //$c->field_type('jenis_pelatihan','dropdown', array('1' => 'active', '2' => 'private','3' => 'spam' , '4' => 'deleted'));


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
        $c = new grocery_crud();
        $c->set_table('peralatan');

                // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit(); 


        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }


             	
		$c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/peralatan/peralatan','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/peralatan/peralatan/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/peralatan/peralatan/'.$primary_key;
				}	
        });
        // }

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Utama');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        $c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Utama');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

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

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }
		$c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/peralatan/peralatan','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/peralatan/peralatan/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/peralatan/peralatan/'.$primary_key;
				}	
        });
        // }



        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Pendukung');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Pendukung');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

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

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }


             	
		$c->columns('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/peralatan/peralatan','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','peralatan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sarana' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/peralatan/peralatan/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/peralatan/peralatan/'.$primary_key;
				}	
        });
        // }

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Keselamatan dan Kesehatan Kerja');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Keselamatan dan Kesehatan Kerja');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

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

//***************************************************************************************************************************
// 5.17 FUNGSI NILAI_INVESTASI

    public function nilai_investasi()
    {
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        $c = new grocery_crud();
        $c->set_table('nilai_investasi');;
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }

            
            //$c->add_action('Pilih', 'sd', 'perusahaan/pilih/peralatan','ui-icon-plus');
        // }

        // unset field
        $c->unset_fields('catatan_petugas', 'status_kepemilikan', 'file_nilai_investasi', 'status_pemakaian');

        // unset columtn
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian', 'file_nilai_investasi');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->required_fields('nama_investor', 'negara_asal', 'nominal_investasi', 'persentase');
        //$c->set_field_upload('file_nilai_investasi', 'assets/uploads/file_nilai_investasi');

        $c->display_as('persentase', 'Persentase %');

        //$c->unset_delete();
        $output = $c->render();

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
// 5.18 FUNGSI PENGALAMAN_KERJA

    function pengalaman_kerja(){
        $c = new grocery_crud();
        $c->set_table('daftar_pekerjaan');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();
                    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }
             	
		$c->columns('nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/pengalaman_kerja/daftar_pekerjaan','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','daftar_pekerjaan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_daftar_pekerjaan' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/pengalaman_kerja/daftar_pekerjaan/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/pengalaman_kerja/daftar_pekerjaan/'.$primary_key;
				}	
        });

        // }


        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak');
        $c->set_relation('lokasi_kerja', 'ref_kota', 'kota', null, 'id_kota');
        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // display as
        $c->display_as('id_daftar_pekerjaan', 'Nama Pekerjaan');
        $c->display_as('k3l', 'K3L');
        $c->display_as('iso', 'ISO');

        // unset columns
        //$c->unset_columns('id_perusahaan', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

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
// 5.19 FUNGSI SOP

    public function sop()
    {


        $c = new grocery_crud();
        $c->set_table('sop');

        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();  

                    $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }          
             	
		$c->columns('prosedur', 'deskripsi', 'file_manajemen_prosedur_kerja', 'status');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/sop/sop','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','sop', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_sop' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/sop/sop/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/sop/sop/'.$primary_key;
				}	
        });
        // }

        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

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

    public function csr()
    {
        $c = new grocery_crud();
        $c->set_table('csr');
        // if($this->glo_jenis_permo != ''){
            // $c->unset_delete();
            // $c->unset_read();
            // $c->unset_edit();


        $sl_permohonan = select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('id_permohonan'), 'selesai' => 2));

    if(!$sl_permohonan){      
      $c->unset_delete();
      $c->unset_read();
      $c->unset_edit();
    }

             	
		$c->columns('kegiatan', 'waktu', 'lokasi');		
		$c->callback_column('status', function ($value, $row){
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
					return 'Dipilih';
				}else{
					return '';
				}	
        });		
		$c->add_action('Pilih', base_url('assets/grocery_crud/themes/flexigrid/css/images/success.png'), 'perusahaan/pilih/csr/csr','ui-icon-plus',function ($primary_key , $row){
				$ada = NULL;
				$cek_if_has_data_permohonan = select('status_pemakaian','csr', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'id_csr' => $primary_key));
				$temp = explode(',', $cek_if_has_data_permohonan->status_pemakaian);
				if($cek_if_has_data_permohonan != NULL){
					foreach ($temp as $key => $status_pemakaian) {
						if($status_pemakaian == $this->session->userdata('id_permohonan')){
							$ada = 'ada';
						}
					}                
				 }
				if($ada != NULL){
					return base_url('perusahaan/ganti').'/csr/csr/'.$primary_key;
				}else{
					return base_url('perusahaan/pilih').'/csr/csr/'.$primary_key;
				}	
        });
        // }


        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        
        $c->required_fields('kegiatan', 'waktu', 'lokasi');
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->set_field_upload('file_csr', 'assets/uploads/file_csr');

        $c->unset_fields('catatan_petugas', 'status_pemakaian');
        //$c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->display_as('file_csr', 'File CSR');
        //$c->unset_delete();

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

 public function disposisi_user_to_admin($id_perusahaan)
    {

        $admin = $this->model->select('id_user', 'users', array('level' => 2, 'status' => 1));
        $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
       
        $data_disposisi = array(
            'id_perusahaan' => $id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $admin->id_user,
            'catatan' => $this->input->post('catatan', TRUE),
            'status_progress' => 2,
            'id_permohonan' => $this->session->userdata('id_permohonan')
        );

		$this->session->set_userdata('id_permohonan', '');
        $this->model->insert('disposisi', $data_disposisi);
        $this->model->update('permohonan', array('selesai' => 1), array('id_perusahaan' => $id_perusahaan));
        echo "<script>alert('Terima kasih telah melengkapi proses registrasi, data Anda segera akan kami proses')</script>";
        $this->session->set_flashdata('message', 'Registrasi anda berhasil!');
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
             	
		$c->columns('id_permohonan', 'no_dokumen', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'file_dokumen');		
		
        $c->where('dokumen_skt.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
		$c->set_field_upload('file_dokumen', 'assets/uploads/file_skt');
		$c->display_as('id_permohonan', 'Bidang Jasa');
		$c->add_action('Proses Laporan | ', 'sd', 'perusahaan/proses_laporan/proses','ui-icon-plus');
		$c->add_action('Update Data | ', 'sd', 'perusahaan/proses_laporan/update','ui-icon-plus');
		$c->add_action('View Data', 'sd', 'perusahaan/proses_laporan/view','ui-icon-plus');
		$c->callback_column('id_permohonan', function ($value, $row){
				$ada = NULL;
				$data_permohonan = select('*','permohonan', array('id_permohonan' => $value));
				$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $data_permohonan->bidang_usaha));
				$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $data_permohonan->sub_bidang));
				return $bidang_usaha->bidang_usaha.'/'.$sub_bidang->sub_bidang;	
        });	
        //$c->set_relation('id_permohonan', 'ref_bidang_usaha', 'bidang_usaha');
		
       // $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/view_list', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
	}
	
	public function proses_laporan($action = NULL, $id = NULL){
		if($action == 'update'){
      $sl_dokumen_skt= select('*', 'dokumen_skt', array('id_dokumen' => $id));
      $this->session->set_userdata('id_permohonan', $sl_dokumen_skt->id_permohonan);
			redirect('perusahaan/data_pemohon');

		}elseif($action == 'view'){			
      $recs = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $id));
      redirect('all_admin/detail_perusahaan/laporan_periodik/'.$recs->id_permohonan);
    }elseif($action == 'proses'){
			$recs = $this->model->select('*', 'dokumen_skt', array('id_dokumen' => $id));
			redirect('perusahaan/pelaporan_periodik/'.$recs->id_permohonan);
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

    public function pelaporan_periodik($permohonan = NULL){
        # code...

        $c = new grocery_crud();
        $c->set_table('pelaporan_periodik');
        $c->set_field_upload('file_pelaporan_periodik', 'assets/uploads/file_pelaporan_periodik');
		    $c->columns('semester', 'file_pelaporan_periodik');
		    $c->fields('semester', 'id_permohonan', 'file_pelaporan_periodik');
        $c->field_type('id_permohonan', 'hidden', $permohonan);
		    
        $c->where('id_permohonan', $permohonan);
        $c->set_relation('semester', 'ref_semester', 'semester');
        
		$output = $c->render();
        $this->logs();

        view('level1/view_list', $output);
		if ($c->getState() != 'add') {
             $c->change_field_type('semester', 'readonly');
        }
		
        // if ($c->getState() != 'list') {
        //     // $this->menej_ref($output);
        // } else {
        //     return $output;
        // }
    }

    public function submit_laporan_berkala()
    {
      $permohonan = $this->model->select('id_permohonan', 'permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'selesai' => 0));
      $this->session->set_userdata('id_permohonan', $permohonan->id_permohonan);
      redirect('perusahaan/laporan_berkala');
    }
	
}