<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class All_admin extends CI_Controller{
	
	
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
        }

    }
//####################################################################################################################################
// 									UNTUK SEMUA ADMIN MIGAS (ADMIN, DTLM, KASIE, KASUBDIT)
//####################################################################################################################################

// 3.1 FUNGSI DAFTAR_MEMBER

    public function daftar_member()
    {
        $c = new grocery_crud();
        $c->set_table('biodata_perusahaan');
        $c->unset_add();
        //$c->unset_delete();
        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->unset_columns('id_user', 'status_user');
        $c->add_action('Detail', 'sR', base_url('all_admin/detail_perusahaan') . '/');
        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');
        $c->unset_fields('tanggal_member', 'tanggal_disetujui', 'status_user', 'tanggal_daftar_member');
        $output = $c->render();
        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1 OR $level == 2) {
                $this->load->view('level' . $level . '/view_list', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.2 FUNGSI DETAIL_PERUSAHAAN

    public function detail_perusahaan($param, $id_permohonan){
        $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id_permohonan));
        $biodata_perusahaan = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
        $data_umum = $this->model->selects('*', 'data_umum', array('id_perusahaan' => $permohonan->id_perusahaan));
        $data_khusus = $this->model->selects('*', 'data_khusus', array('id_perusahaan' => $permohonan->id_perusahaan));
        $keanggotaan_asosiasi = $this->model->selects('*', 'keanggotaan_asosiasi', array('id_perusahaan' => $permohonan->id_perusahaan));
        $tenaga_kerja = $this->model->selects('*', 'tenaga_kerja', array('id_perusahaan' => $permohonan->id_perusahaan));
        $jumlah_tenaga_kerja = $this->model->selects('*', 'jumlah_tenaga_kerja', array('id_perusahaan' => $permohonan->id_perusahaan));
        $pelatihan_tenaga_kerja_internal = $this->model->selects('*', 'pelatihan_tenaga_kerja_internal', array('id_perusahaan' => $permohonan->id_perusahaan));
        $pelatihan_tenaga_kerja_eksternal = $this->model->selects('*', 'pelatihan_tenaga_kerja_eksternal', array('id_perusahaan' => $permohonan->id_perusahaan));
        $peralatan = $this->model->selects('*', 'peralatan', array('id_perusahaan' => $permohonan->id_perusahaan));
        $nilai_investasi = $this->model->selects('*', 'nilai_investasi', array('id_perusahaan' => $permohonan->id_perusahaan));
        $daftar_pekerjaan = $this->model->selects('*', 'daftar_pekerjaan', array('id_perusahaan' => $permohonan->id_perusahaan));
        $sop = $this->model->selects('*', 'sop', array('id_perusahaan' => $permohonan->id_perusahaan));
        $csr = $this->model->selects('*', 'csr', array('id_perusahaan' => $permohonan->id_perusahaan));

        $detail_data = array(
            'biodata_perusahaan' => $biodata_perusahaan,
            'data_umum' => $data_umum,
            'data_khusus' => $data_khusus,
            'keanggotaan_asosiasi' => $keanggotaan_asosiasi,
            'tenaga_kerja' => $tenaga_kerja,
            'jumlah_tenaga_kerja' => $jumlah_tenaga_kerja,
            'pelatihan_tenaga_kerja_internal' => $pelatihan_tenaga_kerja_internal,
            'pelatihan_tenaga_kerja_eksternal' => $pelatihan_tenaga_kerja_eksternal,
            'peralatan' => $peralatan,
            'nilai_investasi' => $nilai_investasi,
            'daftar_pekerjaan' => $daftar_pekerjaan,
            'param' => $param,
            'sop' => $sop,
            'csr' => $csr,
			'permohonan' => $id_permohonan
        );
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/view_detail', $detail_data);
            } else {
                $this->load->view('semua/view_detail', $detail_data);
            }
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.3 FUNGSI DAFTAR_REGISTER

    public function daftar_register()
    {
        $c = new grocery_crud();
        $c->set_table('registrasi');
        $c->unset_add();
        $c->unset_edit();
        // $c->unset_print();
        // $c->unset_export();
        // //$c->unset_delete();

        // unset
        $c->unset_columns('contact_person', 'kota', 'provinsi', 'web', 'website', 'siup');

        $c->columns('nama_perusahaan', 'direktur_utama', 'website', 'npwp', 'file_surat_ket_domisili', 'siup', 'file_siup', 'file_surat_pernyataan', 'surat_ket_domisili', 'tanggal_member');
        $c->set_rules('email', 'Email', 'required|valid_email');
        $c->unset_fields('tanggal_member', 'status', 'pengajuan');
        $c->add_action('Setuju', base_url('assets/img/icon/1.png'), base_url('admin/regis_setuju/') . '/');
        // $c->add_action('Tolak', base_url('assets/img/icon/cancel.png'), base_url('all_admin/regis_tolak/').'/');

        //display as
        $c->display_as('siup', 'Nomor SIUP');
        $c->display_as('npwp', 'Nomor NPWP');
        $c->display_as('file_surat_ket_domisili', 'Surat Keterangan Domisili');
        $c->display_as('file_surat_pernyataan', 'Surat Pernyataan');
        $c->display_as('file_siup', 'SIUP');
        $c->display_as('surat_ket_domisili', 'Nomor Surat Keterangan Domisili');

        $c->field_type('deskripsi_perusahaan', 'text');
        $c->field_type('alamat', 'text');
        $c->unset_texteditor('deskripsi_perusahaan', 'full_text');
        $c->unset_texteditor('alamat', 'full_text');

        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        $c->set_field_upload('file_surat_ket_domisili', 'assets/uploads/file_register');
        $c->set_field_upload('file_siup', 'assets/uploads/file_register');
        $c->set_field_upload('file_surat_permohonan', 'assets/uploads/file_register');
//        $c->set_field_upload('file_surat_pernyataan', 'assets/uploads/file_register');

        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'provinsi', 'kota', 'npwp', 'akte_perusahaan', 'siup', 'file_akte', 'file_siup', 'file_surat_permohonan', 'file_surat_pernyataan');

        $output = $c->render();

        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } else {
            redirect('umum/logout');
        }
    }


//***************************************************************************************************************************
// 3.5 FUNGSI REKAPITULASI

    public function rekapitulasi($id_perusahaan, $bahan_penilaian)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('point', 'Point', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect(base_url('all_admin/detail_perusahaan/pengajuan_skt/' . $this->uri->segment(4)));
            // echo "<string>alert('Lengkapi Data!')</script>";
            echo "<script>window.history.back()</script>";
        } else {
            $data = array(
                'id_perusahaan' => $id_perusahaan,
                'penilai' => $this->session->userdata('id_user'),
                'penilai_level' => $this->session->userdata('level'),
                'id_permohonan' => $this->input->post('id_permohonan', TRUE),
                'bahan_penilaian' => urldecode($bahan_penilaian),
                'point' => $this->input->post('point', TRUE),
                'bobot' => $this->input->post('bobot', TRUE),
                'hasil' => $this->input->post('point', TRUE) * $this->input->post('bobot', TRUE),
                'catatan_penilaian' => $this->input->post('catatan_penilaian', TRUE),
            );
            $this->db->insert('rekapitulasi', $data);
            if ($this->db->affected_rows() > 0) {
                 // echo "<script>alert('Berhasil disimpan')</script>"; 
                $this->session->set_flashdata('message', 'Berhasil disimpan');
                // echo "<script>window.history.back()</script>";
                redirect('all_admin/detail_perusahaan/pengajuan_skt/' .  $this->input->post('id_permohonan', TRUE));
            }
        }
    }

//***************************************************************************************************************************
// 3.6 FUNGSI DAFTAR_PENGAJUAN_SKT_BARU	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKP)

    public function daftar_pengajuan_skt_baru()
    {
        $c = new grocery_crud();
        //

        $c->set_table('permohonan');
        // $level = $this->session->userdata('level');
        // if ($level == 2) {
            // $where = 2;
        // } elseif ($level == 3) {
            // $where = 4;
        // } elseif ($level == 4) {
            // $where = 6;
        // } elseif ($level == 5) {
            // $where = 7;
        // } elseif ($level == 6) { //eva to kasie
            // $where = 8;
        // }	
		
		// $c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');
		// $levels = $this->model->selects('*', 'users', array('level' => $level));
		
		
			// foreach($levels as $lev){
				// $records = $this->model->selects('*', 'disposisi', array('status_progress' => $where));
				// if($records != NULL){
					// foreach($records as $record){
						// if($record->user_tujuan == $this->session->userdata('id_user')){
							// $c->or_where('permohonan.jenis_permohonan', 'SKT Baru');
						// }else{					
							// $c->where('permohonan.id_perusahaan', '');
						// }
					// }
				// }else{					
					// $c->where('permohonan.id_perusahaan', '');
				// }
			// }

			
			$level = $this->session->userdata('level');
		if ($level == 2) {
		$where = 2;
		} elseif ($level == 3) {
		$where = 4;
		} elseif ($level == 4) {
		$where = 6;
		} elseif ($level == 5) {
		$where = 7;
		} elseif ($level == 6) { //eva to kasie
		$where = 8;
		}

		$c->set_relation('id_permohonan','disposisi','id_permohonan');
		$c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');

		$status_selesainya_1 = $this->model->selects('*', 'permohonan', array('selesai' => 1, 'permohonan.jenis_permohonan' => 'SKT Baru'));

		if ($status_selesainya_1) {

		foreach ($status_selesainya_1 as $key => $selesai_1) {
		// echo $selesai_1->id_permohonan;
		// ambil dului record pading pertma yaitu reord status+progress =2

		$first_start = $this->model->select('*', 'disposisi', array('status_progress' => $where, 'id_permohonan' => $selesai_1->id_permohonan));
		// echo $first_start->id_disposisi;

		//cek apakah punya id_parent
		$cek_py_parent = $this->model->select('*', 'disposisi', array('id_parent' => $first_start->id_disposisi), array('id_disposisi', 'DESC'));
		// echo $cek_py_parent->id_disposisi.'|';
		// echo $cek_py_parent->id_permohonan.'|';


		switch ($cek_py_parent->status_progress) {
    		case 2:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 4:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 6:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 8:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;

    		default:
    		  # code...
    		break;
		}

		//logic sudah jalan sudah benar tinggal id_permohonan di diposisi harus selalui diisi

		}

		}else{
		$c->where('permohonan.id_permohonan', NULL);
		}
			
			
        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->unset_columns('bagian_sub_bidang', 'sub_bagian_sub_bidang', 'selesai');
		$c->display_as('id_perusahaan', 'Nama Perusahaan');

        $c->add_action('Detail', 'sd', 'all_admin/detail_perusahaan/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt")));
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skt($output);
        } else {
            return $output;
        }
    }
	
	
//***************************************************************************************************************************
// 3.7 FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT)

    public function daftar_pengajuan_skt_perpanjangan()
    {
        $c = new grocery_crud();
        $c->set_table('permohonan');

        $level = $this->session->userdata('level');
         if ($level == 2) {
             $where = 2;
         } elseif ($level == 3) {
             $where = 4;
         } elseif ($level == 4) {
             $where = 6;
         } elseif ($level == 5) {
             $where = 7;
         } elseif ($level == 6) {
             $where = 8;
         }
		
		$records1 = selects('*', 'permohonan', array('selesai' => 1, 'jenis_permohonan' => 'Perpanjangan SKT'));
		if($records1 != NULL){
			foreach($records1 as $recs1){
				$c->basic_model->set_query_str("SELECT permohonan.id_permohonan, permohonan.jenis_permohonan, permohonan.bidang_sub_bidang, disposisi.user_tujuan, disposisi.id_disposisi, disposisi.id_parent, disposisi.id_perusahaan, biodata_perusahaan.nama_perusahaan FROM permohonan INNER JOIN disposisi ON disposisi.id_permohonan = permohonan.id_permohonan INNER JOIN biodata_perusahaan ON biodata_perusahaan.id_perusahaan = permohonan.id_perusahaan
				WHERE permohonan.selesai = 1 AND permohonan.jenis_permohonan = 'Perpanjangan SKT' ORDER BY disposisi.id_disposisi DESC LIMIT 1");
			}
		}else{
			// echo 'failed 2';
		}
			
		/* $level = $this->session->userdata('level');
		if ($level == 2) {
		$where = 2;
		} elseif ($level == 3) {
		$where = 4;
		} elseif ($level == 4) {
		$where = 6;
		} elseif ($level == 5) {
		$where = 7;
		} elseif ($level == 6) { //eva to kasie
		$where = 8;
		}

		$c->set_relation('id_permohonan','disposisi','id_permohonan');
		$c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');

		$status_selesainya_1 = $this->model->selects('*', 'permohonan', array('selesai' => 1, 'permohonan.jenis_permohonan' => 'Perpanjangan SKT'));

		if ($status_selesainya_1) {

		foreach ($status_selesainya_1 as $key => $selesai_1) {
		// echo $selesai_1->id_permohonan;
		// ambil dului record pading pertma yaitu reord status+progress =2

		$first_start = $this->model->select('*', 'disposisi', array('status_progress' => $where, 'id_permohonan' => $selesai_1->id_permohonan));
		// echo $first_start->id_disposisi;

		//cek apakah punya id_parent
		$cek_py_parent = $this->model->select('*', 'disposisi', array('id_parent' => $first_start->id_disposisi), array('id_disposisi', 'DESC'));
		// echo $cek_py_parent->id_disposisi.'|';
		// echo $cek_py_parent->id_permohonan.'|';


		switch ($cek_py_parent->status_progress) {
    		case 2:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 4:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 6:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;
    		case 8:
    		  $c->where('permohonan.id_permohonan', $cek_py_parent->id_permohonan);
    		break;

    		default:
    		# code...
    		break;
		}

		//logic sudah jalan sudah benar tinggal id_permohonan di diposisi harus selalui diisi

		}

		}else{
		$c->where('permohonan.id_permohonan', NULL);
		}	 */
			
			
			

        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->unset_columns('bagian_sub_bidang', 'sub_bagian_sub_bidang', 'selesai');
		$c->display_as('id_perusahaan', 'Nama Perusahaan');
		
        $c->add_action('Detail', 'sd', 'all_admin/detail_perusahaan/perpanjangan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skt($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 3.8 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN)

    function daftar_pengajuan_skt()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->daftar_pengajuan_skt_baru();

        $output2 = $this->daftar_pengajuan_skt_perpanjangan();

        $js_files = $output1->js_files + $output2->js_files;
        $css_files = $output1->css_files + $output2->css_files;
        $output = '<h2>1. Pengajuan SKT Baru</h2>' . $output1->output . '<br/><hr/><h2>2. Pengajuan Perpanjangan SKT</h2>' . $output2->output . '<br/>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', (object)array(
                'js_files' => $js_files,
                'css_files' => $css_files,
                'output' => $output
            ));
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.6 FUNGSI DAFTAR_PENGAJUAN_SKP_BARU	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKP)

    public function daftar_pengajuan_skp_baru(){
        $c = new grocery_crud();

        $c->set_table('permohonan');
        $level = $this->session->userdata('level');
        if ($level == 2) {
            $where = 2;
        } elseif ($level == 3) {
            $where = 4;
        } elseif ($level == 4) {
            $where = 6;
        } elseif ($level == 5) {
            $where = 7;
        } elseif ($level == 6) {
            $where = 8;
        }		
		
		$c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');
		$level = $this->model->selects('*', 'users', array('level' => $level));
		
		
			foreach($level as $lev){
				$records = $this->model->selects('*', 'disposisi', array('status_progress' => $where));
				if($records != NULL){
					foreach($records as $record){
						if($record->user_tujuan == $lev->id_user){
							$c->where('permohonan.jenis_permohonan', 'SK Penunjukkan Baru');
						}else{					
							$c->where('permohonan.id_perusahaan', '');
						}
					}
				}else{					
					$c->where('permohonan.id_perusahaan', '');
				}
			}
		
        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->unset_columns('bagian_sub_bidang', 'sub_bagian_sub_bidang', 'selesai');
		$c->display_as('id_perusahaan', 'Nama Perusahaan');

        $c->add_action('Detail', 'sd', 'all_admin/detail_perusahaan/pengajuan_skp');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skp")));
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skp($output);
        } else {
            return $output;
        }
    }
	
	
//***************************************************************************************************************************
// 3.7 FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT)

    public function daftar_pengajuan_skp_perpanjangan()
    {
        $c = new grocery_crud();
        //$permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan'));

        $c->set_table('permohonan');

        $level = $this->session->userdata('level');
        if ($level == 2) {
            $where = 2;
        } elseif ($level == 3) {
            $where = 4;
        } elseif ($level == 4) {
            $where = 6;
        } elseif ($level == 5) {
            $where = 7;
        } elseif ($level == 6) { //eva to kasie
            $where = 8;
        }		
		
		
		$c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');
		$level = $this->model->selects('*', 'users', array('level' => $level));
		
		
			foreach($level as $lev){
				$records = $this->model->selects('*', 'disposisi', array('status_progress' => $where));
				if($records != NULL){
					foreach($records as $record){
						if($record->user_tujuan == $lev->id_user){
							$c->or_where('permohonan.jenis_permohonan', 'Perpanjangan SK Penunjukkan');
						}else{					
							$c->where('permohonan.id_perusahaan', '');
						}
					}
				}else{					
					$c->where('permohonan.id_perusahaan', '');
				}
			}	

        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->unset_columns('bagian_sub_bidang', 'sub_bagian_sub_bidang', 'selesai');
		$c->display_as('id_perusahaan', 'Nama Perusahaan');
		
        $c->add_action('Detail', 'sd', 'all_admin/detail_perusahaan/perpanjangan_skp');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skp")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skp($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 3.8 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN)

    function daftar_pengajuan_skp()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->daftar_pengajuan_skp_baru();

        $output2 = $this->daftar_pengajuan_skp_perpanjangan();

        $js_files = $output1->js_files + $output2->js_files;
        $css_files = $output1->css_files + $output2->css_files;
        $output = '<h2>1. Pengajuan SK Penunjukkan Baru</h2>' . $output1->output . '<br/><hr/><h2>2. Pengajuan Perpanjangan SK Penunjukkan</h2>' . $output2->output . '<br/>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', (object)array(
                'js_files' => $js_files,
                'css_files' => $css_files,
                'output' => $output
            ));
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.9 FUNGSI DETAIL_EVALUASI

    /*Pusing muyeng-muyeng*/
    public function detail_evaluasi($param, $id_perusahaan)
    {
        $id_perusahaanx = $this->model->select('id_perusahaan','disposisi', array('id_disposisi' => $id_perusahaan));
        $id_perusahaan = $id_perusahaanx->id_perusahaan;
        $evaluator = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 6, 'id_perusahaan' => $id_perusahaan));
        $dtlm = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 3, 'id_perusahaan' => $id_perusahaan));
        $admin = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 2, 'id_perusahaan' => $id_perusahaan));
        $kasubdit = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 4, 'id_perusahaan' => $id_perusahaan));
        $kasie = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 5, 'id_perusahaan' => $id_perusahaan));

        $data = array(
            'evaluator' => $evaluator,
            'admin' => $admin,
            'direktur' => $dtlm,
            'kasubdit' => $kasubdit,
            'kasie' => $kasie,
        );
        $this->load->view('semua/view_rekap', $data);
    }

//***************************************************************************************************************************
// 3.10 FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN_NAIK	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN_NAIK)

    public function daftar_pengajuan_skt_baru_admin_naik()
    {
        $c = new grocery_crud();
        $permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'SKT Baru'));

        $c->set_table('disposisi');

        $level = $this->session->userdata('level');
        if ($level == 5) {
            $c->where('status_progress', 9);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 9));
        } elseif ($level == 4) {
            $c->where('status_progress', 11);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 11));
        } elseif ($level == 3) {
            $c->where('status_progress', 12);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 12));
        } elseif ($level == 2) {
            $c->where('status_progress', 13);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 13));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per = '';
        }
        // $c->where('id_perusahaan', $permohonan->id_perusahaan);
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'SKT Baru', 'id_perusahaan' => $id_per));

        if($permohonan != NULL){
            foreach ($permohonan as $key => $value) {
                $c->where('id_perusahaan', $value->id_perusahaan);
            }
        }else{
            $c->where('id_perusahaan', '');
        }


        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->unset_columns('id_user', 'status_user', 'contact_person', 'alamat', 'provinsi', 'website', 'deskripsi_perusahaan', 'status_progress', 'keterangan');

        // $c->add_action('Revisi','sR', base_url('all_admin/revisi_pengajuan_skt/').'/');
        // $c->add_action('Lanjut','text', base_url('all_admin/pengajuan_skt_diterima/add').'/');
        $c->add_action('Detail Evaluasi', 'sd', 'all_admin/detail_evaluasi/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt")));

        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');

        $c->unset_fields('tanggal_member', 'status_user', 'keterangan');
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skt_admin($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 3.11 FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN_NAIK	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN_NAIK)

    public function daftar_pengajuan_skt_perpanjangan_admin_naik()
    {
        $c = new grocery_crud();
        $permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan SKT'));

        $c->set_table('disposisi');

        $level = $this->session->userdata('level');
        if ($level == 5) {
            $c->where('status_progress', 9);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 9));
        } elseif ($level == 4) {
            $c->where('status_progress', 11);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 11));
        } elseif ($level == 3) {
            $c->where('status_progress', 12);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 12));
        } elseif ($level == 2) {
            $c->where('status_progress', 13);
            $id_per = $this->model->select('id_perusahaan', 'disposisi', array('status_progress' => 13));
        }
        if($id_per != NULL){
           $id_per = $id_per->id_perusahaan;
        }else{
            $id_per = '';
        }
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan SKT', 'id_perusahaan' => $id_per));

        if($permohonan != NULL){
            foreach ($permohonan as $key => $value) {
                $c->where('id_perusahaan', $value->id_perusahaan);
            }
        }else{
            $c->where('id_perusahaan', '');
        }


        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->unset_columns('id_user', 'status_user', 'contact_person', 'alamat', 'provinsi', 'website', 'deskripsi_perusahaan', 'status_progress', 'keterangan');

        // $c->add_action('Revisi','sR', base_url('all_admin/revisi_pengajuan_skt/').'/');
        // $c->add_action('Lanjut','text', base_url('all_admin/pengajuan_skt_diterima/add').'/');
        $c->add_action('Detail Evaluasi', 'sd', 'all_admin/detail_evaluasi/perpanjangan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt")));

        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');

        $c->unset_fields('tanggal_member', 'status_user', 'keterangan');
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->daftar_pengajuan_skt_admin($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 3.12 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN_NAIK	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN_NAIK & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN_NAIK)

    function daftar_pengajuan_skt_admin_naik()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->daftar_pengajuan_skt_baru_admin_naik();

        $output2 = $this->daftar_pengajuan_skt_perpanjangan_admin_naik();

        $js_files = $output1->js_files + $output2->js_files;
        $css_files = $output1->css_files + $output2->css_files;
        $output = '<h2>1. Pengajuan SKT Baru</h2>' . $output1->output . '<br/><hr/><h2>2. Pengajuan Perpanjangan SKT</h2>' . $output2->output . '<br/>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', (object)array(
                'js_files' => $js_files,
                'css_files' => $css_files,
                'output' => $output
            ));
        } else {
            redirect('umum/logout');
        }
    }

    /*end pusing muyeng-munyeng*/
//***************************************************************************************************************************
// 3.13 FUNGSI PENGAJUAN_SKT_DITERIMA

    public function pengajuan_skt_diterima(){
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();
        // echo $this->uri->segment(4);
        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)));
        // echo $last_disposisi->id_perusahaan;
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));

        $levels = $this->session->userdata('level');
        $c->display_as('id_perusahaan', 'nama perusahaan');

        //lagi di level 2 didisposisi ke level 4

        if ($levels == 2) {
            $level = 3;
            $c->field_type('status_progress', 'hidden', '4');
        } elseif ($levels == 3) {
            $level = 4;
            $c->field_type('status_progress', 'hidden', '6');
        } elseif ($levels == 4) {
            $level = 5;
            $c->field_type('status_progress', 'hidden', '7');
        } elseif ($levels == 5) {
            $level = 6;
            $c->field_type('status_progress', 'hidden', '8');
        } elseif ($levels == 6) {
            $level = 5;
            $c->field_type('status_progress', 'hidden', '9');
        }

        /* if ($level == 2) {
            $level = 3;
        } elseif ($level == 3) {
            $level = 4;
        } elseif ($level == 4) {
            $level = 5;
        } elseif ($level == 5) {
            $level = 6;
        } elseif ($level == 6) {
            $level = 5;
        } */

        $l4 = $this->model->selects('*', 'users', array('level' => $level));

        foreach ($l4 as $key => $l4o) {
            # code...
            $ListUser = array($l4o->id_user => $l4o->nama_lengkap);
        }

        $c->field_type('user_tujuan', 'dropdown', $ListUser);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan');
        // $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima'));

        // $c->required_fields('user_tujuan', 'id_perusahaan');
        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        $level_user = $this->session->userdata('level');
        if ($state == 'success') {
            if ($level_user == 6) {
                redirect('all_admin/konsep_dokumen_skt/add/' . $last_disposisi->id_perusahaan);
            } else {
                redirect(base_url('all_admin/daftar_semua_pengajuan_skt'));
            }
        }
    
        if ($level_user != NULL) {
            $this->load->view('level' . $level_user . '/view_list', $output);
        } elseif ($level_user == NULL) {
            redirect('umum/logout');
        }
    }



//***************************************************************************************************************************
// 3.14 FUNGSI PENGAJUAN_SKT_DITERIMA_NAIK

    public function pengajuan_skt_diterima_naik()
    {
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');

        //$c->unset_delete();
        $c->unset_edit();

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));

        $level = $this->session->userdata('level');

        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        // /$c->field_type('catatan', 'text');

        $c->display_as('id_perusahaan', 'nama perusahaan');

        //lagi di level 2 didisposisi ke level 4

        if ($level == 6) {
            $c->field_type('status_progress', 'hidden', '9');
        } elseif ($level == 5) {
            # code...
            $c->field_type('status_progress', 'hidden', '11');
        } elseif ($level == 4) {
            $c->field_type('status_progress', 'hidden', '12');
        } elseif ($level == 3) {
            $c->field_type('status_progress', 'hidden', '13');
        }

        //untuk polih user tujuan
        if ($level == 6) {
            $level = 5;
        } elseif ($level == 5) {
            $level = 4;
        } elseif ($level == 4) {
            $level = 3;
        } elseif ($level == 3) {
            $level = 2;
        }

        $l4 = $this->model->selects('*', 'users', array('level' => $level));

        foreach ($l4 as $key => $l4o) {
            # code...
            $ListUser = array($l4o->id_user => $l4o->nama_lengkap);
        }

        $c->field_type('user_tujuan', 'dropdown', $ListUser);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan', 'id_permohonan');

        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima_naik'));

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            $level = $this->session->userdata('level');
            if ($level == 6) {
                redirect('all_admin/konsep_dokumen_skt/add/' . $this->uri->segment(4));
            } elseif ($level == 5) {
                redirect('all_admin/no_dokumen_skt/add/' . $this->uri->segment(4));
            } else {
                redirect(base_url('all_admin/daftar_pengajuan_skt_admin_naik'));    
            }
        }

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }


    public function konsep_dokumen_skt($da = NULL, $id = NULL)
    {
                $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');
        $dap = $this->model->select('id_perusahaan', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));
        $c = new grocery_crud();
        $c->set_table('dokumen_skt');
        $c->unset_fields('no_dokumen', 'deskripsi', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'id_permohonan', 'status');
        $c->set_field_upload('file_dokumen', 'assets/uploads/file_skt');

        if($dap != NULL){
            $dap = $dap->id_perusahaan;
        }else {
            $dap = '';
        }
        $c->field_type('id_perusahaan', 'hidden', $dap);

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            redirect(site_url('all_admin/daftar_semua_pengajuan_skt'));
        }

        $level_user = $this->session->userdata('level');
        $this->load->view('level' . $level_user . '/view_list', $output);

    }

    public function no_dokumen_skt($da = NULL, $id = NULL)
    {
        // echo $da;
        $id_per = $this->model->select('id_perusahaan', 'disposisi', array('id_disposisi' => $id));
        $c = new grocery_crud();
        $c->set_table('dokumen_skt');
        // $c->unset_fields('no_dokumen', 'deskripsi', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'id_permohonan', 'status');
        $c->unset_fields('status', 'id_permohonan', 'file_dokumen');
        // $c->set_field_upload('file_dokumen','assets/uploads/file_skt');

        $c->required_fields('no_dokumen', 'mulai_masa_berlaku', 'akhir_masa_berlaku');
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per = '';
        }

        $c->field_type('id_perusahaan', 'hidden', $id_per);

               $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            redirect(site_url('all_admin/daftar_pengajuan_skt_admin_naik'));
        }

        $level_user = $this->session->userdata('level');
        $this->load->view('level' . $level_user . '/view_list', $output);

    }
	

//***************************************************************************************************************************
// 4.12 FUNGSI DAFTAR_REVISI_SKT_ADMIN

    public function daftar_revisi_admin()
    {
  //       $c = new grocery_crud();
		// $level = $this->session->userdata('level');
  //       $c->set_table('permohonan'); 
  //       if ($level == 2) {
  //           // $where = 32; //dulunya 32 dari disposisi perusahaan ke admin lg ada revisi
  //           $where = 3;
  //       } elseif ($level == 5) {
  //           $where = 102;
  //       }  	
		
		// $c->set_relation('id_perusahaan','biodata_perusahaan','nama_perusahaan');
		// $level = $this->model->selects('*', 'users', array('level' => $level));
		
		
		// 	foreach($level as $lev){
		// 		$records = $this->model->selects('*', 'disposisi', array('status_progress' => $where));
		// 		if($records != NULL){
		// 			foreach($records as $record){
		// 				if($record->user_tujuan == $lev->id_user){
		// 					$c->where('permohonan.jenis_permohonan', 'SK Penunjukkan Baru');
		// 					$c->or_where('permohonan.jenis_permohonan', 'Perpanjangan SK Penunjukkan');
		// 					$c->or_where('permohonan.jenis_permohonan', 'Perpanjangan SKT');
		// 					$c->or_where('permohonan.jenis_permohonan', 'SKT Baru');
		// 				}else{					
		// 					$c->where('permohonan.id_perusahaan', '');
		// 				}
		// 			}
		// 		}else{					
		// 			$c->where('permohonan.id_perusahaan', '');
		// 		}
		// 	}
		
  //       $c->unset_add();
  //       //$c->unset_delete();
  //       $c->unset_edit();
  //       $c->unset_read();
  //       $c->unset_columns('bagian_sub_bidang', 'sub_bagian_sub_bidang', 'selesai');
		// $c->display_as('id_perusahaan', 'Nama Perusahaan');

  //       $c->add_action('Detail', 'sd', 'all_admin/detail_perusahaan/revisi_admin');
  //       $output = $c->render();

  //       $this->logs();
  //       $level = $this->session->userdata('level');
  //       if ($level != NULL) {
  //           $this->load->view('level' . $level . '/view_list', $output);
  //       } else {
  //           redirect('umum/logout');
  //       }

                $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('test/revisi', array('data' => $select_permohonan));


    }
	

     public function daftar_revisi_kasie()
    {
                $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('test/daftar_revisi_kasie', array('data' => $select_permohonan));
    }

//***************************************************************************************************************************
// 2.6 FUNGSI TRACK_DISPOSISI

    public function track_disposisi($id_perusahaan)
    {
        $track_disposisi = $this->model->selects('*', 'disposisi', array('id_perusahaan' => $id_perusahaan), NULL, array('id_disposisi', 'asc'), NULL);
        $this->load->view('view_track_disposisi', $track_disposisi);
    }

	

//***************************************************************************************************************************
// 2.4 FUNGSI LOGS

    public function logs($user_data = NULL)
    {
        $this->load->library('user_agent');
        $this->load->model('model');
        $this->load->helper('url');

        $logData = array(
            'ip_address' => $this->getUserIP(),
            'user_agent' => $this->agent->agent_string(),
            'url' => $this->uri->uri_string(),
            'who' => $this->session->userdata('id_user'),
            'user_data' => $user_data
        );

        $this->model->insert('logs', $logData);

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
	
		public function update_pengajuan_skt_diterima($post_array, $primary_key)
    {

        $level = $this->session->userdata('level');
        // jika di user level 2 status progras ke 6
        if ($level == 2) {
            $status_progress = 4;
        } elseif ($level == 3) {
            $status_progress = 6;
        } elseif ($level == 4) {
            $status_progress = 7;
        } elseif ($level == 5) {
            $status_progress = 8;
        }


        $data = array(
            'status_progress' => $status_progress,
            // 'tanggal_disetujui' => date('Y-m-d H:i:s')
        );
        $this->db->update('biodata_perusahaan', $data, array('id_perusahaan' => $post_array['id_perusahaan']));
        return TRUE;
    }


    public function update_pengajuan_skt_diterima_naik($post_array, $primary_key)
    {

        $level = $this->session->userdata('level');
        // jika di user level 2 status progras ke 6
        if ($level == 6) {
            $status_progress = 9;
        } elseif ($level == 5) {
            $status_progress = 11;
        } elseif ($level == 4) {
            $status_progress = 12;
        } elseif ($level == 3) {
            $status_progress = 13;
        }

		$disposisi = $this->model->select('*', 'disposisi', array('id_disposisi' => $post_array['id_parent']));
		
        $data = array(
            'id_permohonan' => $disposisi->id_permohonan
            // 'tanggal_disetujui' => date('Y-m-d H:i:s')
        );
        $this->db->update('disposisi', $data, array('id_perusahaan' => $post_array['id_perusahaan']));
        return TRUE;
    }
	
	public function daftar_semua_pengajuan_skt($value=''){
		$select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
		view('test/test', array('data' => $select_permohonan));
	}
	
}