<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
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

    }
	
//####################################################################################################################################
// 														UNTUK ADMIN
//####################################################################################################################################

// 4.1 FUNGSI REGIS_SETUJU

    public function regis_setuju($id)
    {
        $registrasi = $this->model->select('*', 'registrasi', array('id_registrasi' => $id));
        $this->load->helper('string');
        $randString = random_string('alnum', 8);

        $userData = array(
            'username' => $registrasi->email,
            'password' => md5($randString . $saltKey = 'esdm2014'),
            'status' => 1,
            'level' => 1,
            'tanggal_daftar' => $registrasi->tanggal_member,
        );

        $this->model->insert('users', $userData);
        $lastIdUser = $this->db->insert_id();
		
		
        $biodata = array(
            'id_user' => $lastIdUser,
            'nama_perusahaan' => $registrasi->nama_perusahaan,
            'direktur_utama' => $registrasi->direktur_utama,
            'contact_person' => $registrasi->contact_person,
            'email' => $registrasi->email,
            'alamat' => $registrasi->alamat,
            'website' => $registrasi->website,
            'deskripsi_perusahaan' => $registrasi->deskripsi_perusahaan,
            'provinsi' => $registrasi->provinsi,
            'kota' => $registrasi->kota,
        );

        $this->model->insert('biodata_perusahaan', $biodata);
        $lastIdBio = $this->db->insert_id();
		
		//default jumlah tenaga kerja WNI & WNA
		$this->model->insert('jumlah_tenaga_kerja', array('id_perusahaan' => $lastIdBio, 'tipe_tenaga_kerja' => 'WNI'));
		$this->model->insert('jumlah_tenaga_kerja', array('id_perusahaan' => $lastIdBio, 'tipe_tenaga_kerja' => 'WNA'));
		$this->model->insert('data_umum', array('id_perusahaan' => $lastIdBio, 'jenis_dokumen' => 12));
		

        $subject = 'Registrasi Berhasil ESDM';
        $message = 'Selamat  registrasi Anda berhasil. <br/>Untuk melanjutkan proses pengajuan SKT/SK Penunjukan PJIT, silahkan kunjungi alamat <a href="http://skt.migas.esdm.go.id/skt/umum/login">ini</a> login dengan data berikut :<br/>' . 'email : ' . $registrasi->email . '<br/>password :' . $randString;
        $message .= '<br/>Untuk menjaga keamanan data Anda, segera login dan ubah password Anda pada menu Pengaturan';
        $message .= '<br/><br/>Terima Kasih.<br/><br/>Hormat Kami,<br/><br/>Direktorat Jenderal Minyak dan Gas Bumi';
        $message .= '<br/><br/><b>*Mohon untuk tidak membalas email ini.</b>';
        $send_email = $this->send_mail($registrasi->email, $subject, $message);

        if ($send_email) {
            $this->model->delete('registrasi', array('id_registrasi' => $id));
            $this->logs('Data' . $registrasi->nama_perusahaan . 'telah dipindahkan dan dihapus dari register');
            redirect('all_admin/daftar_register');
            echo "<script>alert('Berhasil disetujui')</script>";
        } else {
            $this->logs('Data' . $registrasi->nama_perusahaan . 'gagal dihapus');
            redirect('all_admin/daftar_register');
            echo "<script>alert('Gagal mohon ulangi lagi')</script>";
        }
    }

	
//***************************************************************************************************************************
// 3.4 FUNGSI SEND_MAIL

    public function send_mail($to, $subject, $message)
    {
        $config = Array(
			/* 'protocol' => 'smtp',
            'smtp_host' => 'mail.migas.esdm.go.id',// bisa diganti dengan alamat host web mail
            'smtp_port' => 25,
            'smtp_user' => 'skt.migas@migas.esdm.go.id', // misal no-reply@migas.esdm.go.id
            'smtp_pass' => 'dmtpmigas14', // change it to yours // 			
            'mailtype' => 'html',
            'wordwrap' => TRUE */
			'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'ycared@gmail.com', // misal no-reply@migas.esdm.go.id
            'smtp_pass' => 'nbzpgevxlcfdveln', // change it to yours // 			
            'mailtype' => 'html',
            'wordwrap' => TRUE
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('no-reply@migas.esdm.go.id', 'DMTP MIGAS'); // change it to yours
        $this->email->to($to); // change it to yours
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            // echo 'Email sent.';
            return TRUE;
        } else {
            show_error($this->email->print_debugger());
        }
    }
	
//***************************************************************************************************************************
// 4.2 FUNGSI REGIS_TOLAK

    public function regis_tolak($id)
    {
        $level = $this->session->userdata('level');
        $perusahaan = $this->model->select('*', 'registrasi', array('id_registrasi' => $id));

        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('level2/email_regis_tolak');
        } else {

            $to = $perusahaan->email;
            $subject = $this->input->post('subject');
            $message = $this->input->post('message').'<br/><br/><b>*Mohon untuk tidak membalas email ini. Terimakasih.</b>';

            $msg = $this->send_mail($to, $subject, $message);
            if ($msg){
                $action = $this->model->delete('registrasi', array('id_registrasi' => $id));

                if ($action) {
                    $this->logs('akun ' . $perusahaan->email . ' ditolak');
                    echo "<script>window.history.back()</script>";
                    echo "<script>alert('Email penolakan berhasil dikirim!')</script>";
                    if ($level != NULL) {
                        // $this->daftar_register();
                        redirect('all_admin/daftar_register');
                    } elseif ($level == NULL) {
                        redirect('umum/logout');
                    }
                }
           }
        }
    }

//***************************************************************************************************************************
// 4.3 FUNGSI REVISI_PENGAJUAN_SKT

    public function revisi_pengajuan_skt($link_back=NULL, $id=NULL){
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');
        $output = array('param' => $link_back);
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('level2/email_revisi', $output);
        } else {
            $var = $this->model->select('*', 'permohonan', array('id_permohonan' => $id));
            $id= select('id_perusahaan', 'biodata_perusahaan', array('id_perusahaan' => $var->id_perusahaan));
                    
            $perusahaan = $this->model->select('email, id_user', 'biodata_perusahaan', array('id_perusahaan' => $id->id_perusahaan));
            $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $id->id_perusahaan, 'id_permohonan' => $var->id_permohonan, 'id_parent' => NULL));
            $subject = $this->input->post('subject', TRUE);
            $message = $this->input->post('message', TRUE);
            $send_email = $this->send_mail($perusahaan->email, $subject, $message);
            // $send_email = TRUE;
            if ($this->session->userdata('level') == 2) {
                $status_progress = 3;
            } elseif ($this->session->userdata('level') == 5) {
                $status_progress = 10;
            }
            if ($send_email) {
                $data_disposisi = array(
                    'id_parent' => $last_disposisi->id_disposisi,
                    'id_perusahaan' => $id->id_perusahaan,
                    'user_asal' => $this->session->userdata('id_user'),
                    'user_tujuan' => $perusahaan->id_user,
					'catatan_user_asal' => 'Revisi pengajuan',
                    'status_progress' => $status_progress,
                    'id_permohonan' => $var->id_permohonan
                );
                $check = $this->model->insert('disposisi', $data_disposisi);
                $this->logs('email revisi pengjuan skt BERHASIL di kirim ke pada' . $perusahaan->email);

                if ($check) {

                    $this->session->set_flashdata('message', 'Email berhasil dikirim');
                    $this->load->view('level2/email_revisi', $output);
                }
            } else {

                $this->session->set_flashdata('message', 'Email gagal dikirim');
                $this->load->view('level2/email_revisi', $output);
                $this->logs('email revisi pengjuan skt GAGAL di kirim ke pada' . $perusahaan->email);
            }
			
			redirect('all_admin/daftar_'.$link_back);
        }
    }

//***************************************************************************************************************************
// 4.4 FUNGSI REF_GOLONGAN_ALAT

    public function ref_golongan_alat()
    {
        $c = new grocery_crud();
        $c->set_table('ref_golongan_alat');
        $c->required_fields('golongan_alat');
 $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.5 FUNGSI REF_JENIS_DOKUMEN

    public function ref_jenis_dokumen()
    {
        $c = new grocery_crud();
        $c->set_table('ref_jenis_dokumen');
        $c->required_fields('jenis_dokumen');
 $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.6 FUNGSI REF_PROVINSI

    public function ref_provinsi()
    {
        $c = new grocery_crud();
        $c->set_table('ref_provinsi');
        $c->required_fields('provinsi');
        $c->fields('provinsi');
        $c->columns('provinsi');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.7 FUNGSI REF_KOTA

    public function ref_kota()
    {
        $c = new grocery_crud();
        $c->set_table('ref_kota');
        $c->required_fields('kota');
        $c->fields('id_provinsi', 'kota');
		$c->display_as('id_provinsi', 'Provinsi');
        $c->columns('kota');
		$c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
        $c->set_relation('id_provinsi', 'ref_provinsi', 'provinsi');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.8 FUNGSI REF_SUB_BIDANG

    public function ref_bidang_usaha()
    {
        $c = new grocery_crud();
        $c->set_table('ref_bidang_usaha');
        $c->required_fields('bidang_usaha');
        
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

    public function ref_sub_bidang()
    {
        $c = new grocery_crud();
        $c->set_table('ref_sub_bidang');
        $c->required_fields('sub_bidang');

         $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
         $c->set_relation('bidang_usaha', 'ref_bidang_usaha', 'bidang_usaha');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.9 FUNGSI REF_BAGIAN_SUB_BIDANG

    public function ref_bagian_sub_bidang()
    {
        $c = new grocery_crud();
        $c->set_table('ref_bagian_sub_bidang');
        $c->required_fields('bagian_sub_bidang');
         $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
         $c->set_relation('id_sub_bidang', 'ref_sub_bidang', 'sub_bidang');
		$c->display_as('id_sub_bidang', 'Sub Bidang');
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 4.10 FUNGSI REF_SUB_BAGIAN_SUB_BIDANG

    public function ref_sub_bagian_sub_bidang()
    {
        $c = new grocery_crud();
        $c->set_table('ref_sub_bagian_sub_bidang');
        $c->required_fields('sub_bagian_sub_bidang');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
		$c->display_as('id_bagian_sub_bidang', 'Bagian Sub Bidang');

         $c->set_relation('id_bagian_sub_bidang', 'ref_bagian_sub_bidang', 'bagian_sub_bidang');
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

	//***************************************************************************************************************************
// 4.10 FUNGSI REF_SUB_BAGIAN_SUB_BIDANG

    public function ref_level_user()
    {
        $c = new grocery_crud();
        $c->set_table('ref_level_user');
        $c->required_fields('kode_level', 'level_user');
		$c->fields('kode_level', 'level_user');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
        }
    }

	//***************************************************************************************************************************
// 4.10 FUNGSI REF_SUB_BAGIAN_SUB_BIDANG

     public function tambah_user()
     {
         $c = new grocery_crud();
         $c->set_table('users');
		 $c->where('level !=', 1);
         $c->required_fields('username', 'password', 'level', 'nama_lengkap');
         $c->fields('username', 'password', 'level', 'nama_lengkap');
         $c->set_relation('level', 'ref_level_user', 'level_user');
         $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
		 //$c->callback_before_insert(array($this,'callback_before_tambah_user'));	
		 $c->columns('username', 'level');
		 $c->field_type('password', 'hidden', 'dmtp2014');
		 
         $output = $c->render();
         $this->logs();

         if ($c->getState() != 'list') {
             $this->menej_ref($output);
         } else {
             return $output;
         }
    }

	function encrypt_password_callback($post_array) {
		$post_array['password'] = md5($post_array['password'] . $saltKey = 'esdm2014');		  
		return $post_array;
	}
	
	
	public function ref_template_skt($value=''){
        $c = new grocery_crud();
        $c->set_table('ref_template_skt');
         $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
		 
        $output = $c->render();
        if ($c->getState() != 'list') {
             $this->menej_ref($output);
         } else {
             return $output;
         }
    }
	

	
	    public function ref_semester()
    {
        $c = new grocery_crud();
        $c->set_table('ref_semester');
        $c->unset_delete();
        $c->unset_columns('is_delete');
        $c->where('is_delete', 0);
        $c->fields('semester');

        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/menej_ref")));
        $c->add_action('More', '', 'admin/soft_delete','delete-icon');
         
        $output = $c->render();
        if ($c->getState() != 'list') {
             $this->menej_ref($output);
         } else {
             return $output;
         }
    }
	
    public function soft_delete($id='')
    {
        $upd = update('ref_semester', array('is_delete' => 1), array('id_semester' => $id));

        if ($upd) {
            redirect('admin/menej_ref');
        }
    }
	
	
//***************************************************************************************************************************
// 4.11 FUNGSI MENEJ_REF

    function menej_ref()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->ref_golongan_alat();
        $output2 = $this->ref_jenis_dokumen();
        $output3 = $this->ref_provinsi();
        $output4 = $this->ref_kota();
        $output5 = $this->ref_sub_bidang();
        $output6 = $this->ref_bagian_sub_bidang();
        $output7 = $this->ref_sub_bagian_sub_bidang();
        $output8 = $this->ref_bidang_usaha();
        $output9 = $this->ref_level_user();
        $output10 = $this->tambah_user();
        $output11 = $this->ref_template_skt();
        $output12 = $this->ref_semester();

        $js_files = $output11->js_files + $output1->js_files + $output2->js_files + $output3->js_files + $output4->js_files + $output5->js_files + $output6->js_files + $output7->js_files + $output8->js_files;
        $css_files = $output11->css_files + $output1->css_files + $output2->css_files + $output3->css_files + $output4->css_files + $output5->css_files + $output6->css_files + $output7->css_files + $output8->css_files;
					$output =   '<div class="recenttitle">Referensi Golongan Alat</div><center>' . $output1->output . '</center></div>
        <div class="column_data"><div class="recenttitle">Referensi Bidang Usaha</div><center>' . $output8->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Sub Bidang</div><center>' . $output5->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Bagian Sub Bidang</div><center>' . $output6->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Sub Bagian Sub Bidang</div><center>' . $output7->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Provinsi</div><center>' . $output3->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Jenis Dokumen Data Umum</div><center>' . $output2->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Kota</div><center>' . $output4->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Tambah User</div><center>' . $output10->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Kategori Level User</div><center>' . $output9->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Semester (Laporan Berkala)</div><center>' . $output12->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Template SKT</div><center>' . $output11->output . '</center>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 9 || $level == 8) {
                $this->load->view('level'.$level.'/manage_ref', (object)array(
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
// 4.13 FUNGSI CATATAN_PETUGAS

/*     public function catatan_petugas($table = NULL, $id = NULL)
    {


        $id_per= $this->model->select('id_perusahaan', 'permohonan', array('id_permohonan' =>$id));
		$status = $this->model->select('*', $table, array('id_perusahaan' => $id_per->id_perusahaan));
		$temp = explode(',', $status->status_pemakaian);
		
		
        $this->form_validation->set_rules('catatan_petugas', 'Catatan', 'required');
        if ($this->form_validation->run() == FALSE) {
            // redirect('all_admin/detail_perusahaan/pengajuan_skt/'.$id);
            echo "catatan diperlukan";
        } else {
			
			foreach ($temp as $key => $status_pemakaian) {
				if($table != 'data_umum'){
					if($status_pemakaian == $id){
						$catat = $this->model->update($table, array('catatan_petugas' => $this->input->post('catatan_petugas', TRUE)), array('id_perusahaan' => $id_per->id_perusahaan));
					}
				}else{
					$catat = $this->model->update($table, array('catatan_petugas' => $this->input->post('catatan_petugas', TRUE)), array('id_perusahaan' => $id_per->id_perusahaan));
				}
			}

            if ($catat) {
                redirect('all_admin/detail_perusahaan/pengajuan_skt/' . $id);
            }
        }
    }
	 */
	public function kelola_sistem(){
		redirect('admin/menej_pengumuman');
	}
//***************************************************************************************************************************
// 4.14 FUNGSI MENEJ_PENGUMUMAN

    public function menej_pengumuman()
    {
        $c = new grocery_crud();
        $c->set_table('pengumuman');
        $c->unset_fields('tanggal_terbit');
        $c->unset_columns('penulis');
        $c->required_fields('judul', 'isi');
        $c->order_by('id_pengumuman', 'DESC');

        $c->field_type('penulis', 'hidden', $this->session->userdata('nama_user_online'));
        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 9 || $level == 8) {
                $this->load->view('level'.$level.'/manage_pengumuman', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
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
	
	public function aktivasi_laporan($param){
		if($param == 'aktif'){
			$aktif = 1;
		}else{
			$aktif = 0;
		}
		$data = array('status_aktivasi' => $aktif);
		$this->model->insert('aktivasi_laporan_periodik', $data);
		$this->session->set_userdata('status_lap_periodik', $aktif);
		$level = $this->session->userdata('level');
		redirect('all_users/dashboard');
	}
//***************************************************************************************************************************
// 2.5 FUNGSI GETUSERIP

    // public function getUserIP()
    // {
    //     $client = @$_SERVER['HTTP_CLIENT_IP'];
    //     // $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    //     $forward = @$_SERVER['HTTP_CLIENT_IP'];
    //     $remote = $_SERVER['REMOTE_ADDR'];

    //     if (filter_var($client, FILTER_VALIDATE_IP)) {
    //         $ip = $client;
    //     } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    //         $ip = $forward;
    //     } else {
    //         $ip = $remote;
    //     }
    //     return $ip;
    // }

}