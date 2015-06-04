<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Umum extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('pagination');

        $this->load->library('grocery_CRUD');

        // if ($this->session->userdata('level') == NULL) {
        //     $this->logs('Anda tidak berhak mengakses halaman ini!');
        //     // $this->logout();
        // }

    }
	

//***************************************************************************************************************************
// 1.3 FUNGSI REGISTRASI

    public function registrasi(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
		$this->config->set_item('grocery_crud_file_upload_max_file_size', '200KB');
        $c = new grocery_crud();
        $c->set_table('registrasi');
        // $c->unset_back_to_list();
        $c->unset_edit();
        //$c->unset_delete();
        $c->unset_read();
        $c->unset_list();

        if ($this->uri->segment(2) == 'registrasi' && $this->uri->segment(3) == NULL) {
            redirect($this->router->fetch_class() . '/' . $this->router->fetch_method() . '/add');
        }


        $c->set_rules('email', 'Email', 'required|valid_email');
        // unset field
//        $c->unset_fields('tanggal_member', 'pengajuan', '');

        $c->unset_fields('tanggal_member', 'pengajuan','file_surat_pernyataan');
        $c->field_type('deskripsi_perusahaan', 'text');
        $c->field_type('alamat', 'text');
        $c->callback_add_field('syarat_ketentuan', function () {
            return '<input type="radio" maxlength="50" value="1" name="syarat_ketentuan">&nbsp;&nbsp;Dengan mencetang tanda ini berarti Anda telah menyetujui syarat & ketentuan yang berlaku';
        });
        $c->display_as('surat_ket_domisili', 'Nomor Surat Keterangan Domisili');
        $c->display_as('siup', 'Nomor SIUP/Perizinan Lainnya');
        $c->display_as('npwp', 'Nomor NPWP');
        $c->display_as('file_siup', 'SIUP/Perizinan Lainnya');
        $c->display_as('file_surat_ket_domisili', 'Surat Keterangan Domisili');

        $c->unset_texteditor('deskripsi_perusahaan', 'full_text');
        $c->unset_texteditor('alamat', 'full_text');
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        $c->set_field_upload('file_surat_ket_domisili', 'assets/uploads/file_register');
        $c->set_field_upload('file_siup', 'assets/uploads/file_register');

        $c->required_fields('syarat_ketentuan','nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'provinsi', 'kota', 'npwp', 'akte_perusahaan', 'siup', 'file_akte', 'file_siup');
        
        $c->set_relation('provinsi', 'ref_provinsi', 'provinsi');
		$c->callback_field('email', function ($value, $row){
				return '<input type="text" value="'.$value.'" name="email" id="field-email"> <span style="color:red; font-size:10px">*) Pastikan alamat email yang Anda masukkan valid</span>';
        });	
		$c->callback_field('kota', function ($value, $row){
		return '<script>
				$(document).ready(function (){
				    $( "#field-kota" ).autocomplete({
						source: function(request, response) {
							$.ajax({
								url: "'.site_url("umum/suggestcities").'",
								data: { kota: $("#field-kota").val()},
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
				<input type="text" value="'.$value.'" name="kota" id="field-kota">';
  });
        //$c->callback_insert(array($this, 'after_registrasi'));


        if ($this->uri->segment(3) == 'success') {
            redirect('umum/after_registrasi');
        }


        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();


        $this->logs();
        $this->load->view('umum/registrasi', $output);
    }
	
	public function suggestcities(){
		$kota = $this->input->post('kota',TRUE);
        //$rows = $this->model->getKantor($lokasi);
        $rows = like('ref_kota', 'kota', $kota);
        $json_array = array();
        foreach ($rows as $row)
            $json_array[]=$row->kota;
        echo json_encode($json_array);
	}

    public function after_registrasi()
    {
        $data = '<h1>Selamat! Registrasi Anda Berhasil!</h1>';
        $output = array('respon' => '<div class="tengah"><h1>Selamat! Registrasi Anda Berhasil!</h1><h2>Kami akan mengirimkan kode aktifasi akun Anda.</h2><h3>Silahkan klik <a href="' . base_url() . '">Link Ini</a> untuk kembali ke menu utama</h3></div>');
        $this->load->view('umum/registrasi', $output);
    }

    function cek_em()
    {
        $mail = $this->input->post('email', true);
        $users = $this->model->select('*', 'users', array('username' => $mail));
        $regists = $this->model->select('*', 'registrasi', array('email' => $mail));

        $status = "true"; // email masih bisa di daftarkan
        if ($regists == NULL) {
            if ($users == NULL) {
                $status = "true"; //email masih bisa di daftarkan
            } elseif ($users != NULL) {
                $status = "false"; //email masih  TIDAK bisa di daftarkan
            }
        } else {
            $status = "false";
        }
        echo $status;
    }
	
	//***************************************************************************************************************************
// 2.2 FUNGSI LOGIN

    function login()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('captcha', 'captcha', 'trim|required');

        $this->load->helper('captcha');
        $vals = array(
            'word' => mt_rand(77, 9999),
            'img_path' => './assets/captcha/',
            'img_url' => base_url('assets') . '/captcha/',
            'img_width' => '300',
            'expiration' => 7200
        );

        $cap = create_captcha($vals);

        $data = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );

        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
        // delete capcha
        $expiration = time() - 300; // Two hour limit
        $this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

        if ($this->session->userdata('id_user') == NULL) {
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_userdata('mycaptcha', $cap['word']);
                $this->load->view('umum/login', array('captchaImg' => $cap['image']));
            } else {

                $captcha_word = $this->session->userdata('mycaptcha');
                if ($this->input->post('captcha', TRUE) == $captcha_word) {

                    $where = array(
                        'username' => $this->input->post('email', TRUE),
                        'password' => md5($this->input->post('password', TRUE) . 'esdm2014'),
                        'status' => 1
                    );

                    $auth = $this->model->select('*', 'users', $where);
					$aktivasi = $this->model->select('*', 'aktivasi_laporan_periodik', null, array('id_aktivasi', 'DESC'));
					if($aktivasi != NULL){
						$lap_periodik = $aktivasi->status_aktivasi;
					}else{
						$lap_periodik = 0;
					}

                    if ($auth != NULL) {
                        if ($auth->level == 1) {
                            $data_perusahaan = $this->model->select('id_perusahaan, nama_perusahaan', 'biodata_perusahaan', array('id_user' => $auth->id_user));

                            $permohonan = $this->model->select('id_permohonan', 'permohonan', array('id_perusahaan' => $data_perusahaan->id_perusahaan, 'selesai' => 0), null, array('id_perusahaan' => $data_perusahaan->id_perusahaan, 'selesai' => NULL));
                            if ($permohonan != NULL) {
                                $id_permohonan = $permohonan->id_permohonan;
                            } elseif ($permohonan == NULL) {
                                $id_permohonan = '';
                            }
                            $add_session = array(
                                'id_user' => $auth->id_user,
                                'level' => $auth->level,
                                'id_perusahaan' => $data_perusahaan->id_perusahaan,
                                'nama_user_online' => $data_perusahaan->nama_perusahaan,
                                'id_permohonan' => $id_permohonan,
								'status_lap_periodik' => $lap_periodik
                            );
                            $this->session->set_userdata($add_session);
                            $this->logs();
                            // $this->dashboard();
                            redirect('all_users/dashboard');
                        } else {
                            $add_session = array(
                                'id_user' => $auth->id_user,
                                'level' => $auth->level,
                                'nama_user_online' => $auth->nama_lengkap,
								'status_lap_periodik' => $lap_periodik
                            );
                            $this->session->set_userdata($add_session);
                            $this->logs();
                            // $this->dashboard();
                            redirect('all_users/dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="text-danger">Email atau password salah!</div>');
                        redirect('umum/login');
                        // $this->login();
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="text-danger">Kode verifikasi salah!</div>');
                    redirect('umum/login');
                }

            }
        } elseif ($this->session->userdata('id_user') != NULL) {
            // $this->dashboard();
            redirect('all_users/dashboard');
        }
        $this->logs();
    }

//***************************************************************************************************************************
// 2.3 FUNGSI LOGOUT

    public function logout()
    {
        $this->logs();
        $this->session->sess_destroy();

        redirect('umum/login');
    }
	
	    public function cari_skt(){  
            $post = $this->input->get(NULL, TRUE);
			$config = array();
			$config["base_url"] = base_url() . "umum/cari_skt?keyword=".$post['keyword'].'&jenis_skt='.$post['jenis_skt'];
			$config["per_page"] = 10;
			if(isset($post['per_page'])){
			$page = ($post['per_page']) ? $post['per_page'] : 0;	
			}else{
			$page = 0;	
			}
			$config['page_query_string']=true;			
			

            /* if ($post['jenis_skt'] != '0') { */
				if ($post['jenis_skt'] == 'no_verify') {
					$id_permohonan = NULL;
					$caris = $this->model->selects('*', 'verifikasi');
					foreach($caris as $key => $cari){
						$de_code = $this->encrypt->decode($cari->serial_num);
						if($de_code == $post['keyword']){
							$id_permohonan = $cari->id_permohonan;
						}
					}
					if($id_permohonan != NULL){
						$data["response"] = $this->model->selects('*', 'v_dokumen_skt', array('id_permohonan' => $id_permohonan));
						$data["total"] = $this->model->selects('*', 'v_dokumen_skt', array('id_permohonan' => $id_permohonan));
					}else{
						$data["response"] = NULL;
						$data["total"] = 0;
					}
					
				}elseif ($post['jenis_skt'] == 'nama') {
					$data["response"] = $this->model->search_like('v_dokumen_skt', 'nama_perusahaan', $post['keyword'], array('status' => 1), array($config["per_page"], $page));
					$data["total"] = $this->model->select_like('v_dokumen_skt', 'nama_perusahaan', $post['keyword'], array('status' => 1));
					
				}else{
					$data["response"] = $this->model->search_like('v_dokumen_skt', 'no_dokumen', $post['keyword'], array('status' => 1), array($config["per_page"], $page));
					$data["total"] = $this->model->select_like('v_dokumen_skt', 'no_dokumen', $post['keyword'], array('status' => 1));
				}
			/* }else{
				$data["response"] = NULL;
				$data["total"] = 0;
			} */
			$config["total_rows"] = count($data["total"]);
			$config["uri_segment"] = 4;
			//pagination customization using bootstrap styles
			$config['full_tag_open'] = '<div class="pagination pagination-centered"><ul class="page_test">'; // I added class name 'page_test' to used later for jQuery
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';

			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';

			$config['next_link'] = 'Next &rsaquo;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = '&lsaquo; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';

			
			$this->pagination->initialize($config);
			
			$data["links"] = $this->pagination->create_links();
			$data["msg"] = '<table class="table table-bordered table-striped"><thead><th>No Dokumen</th><th>Nama Perusahaan</th><th>Mulai Berlaku</th><th>Akhir Berlaku</th><th>Dokumen</th></thead><tbody><tr><td colspan="5">Data tidak ditemukan!</td></tr></table>';  
			
			$this->load->view('umum/cekstatus', $data);
        
    }
	
	// 1.1 FUNGSI INDEX

    public function index()
    {
        $config = array();
        $config["base_url"] = base_url() . "umum/index";
        $config["total_rows"] = $this->model->record_count('pengumuman');
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;
		//pagination customization using bootstrap styles
		$config['full_tag_open'] = '<div class="pagination pagination-centered"><ul class="page_test">'; 
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next &rsaquo;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lsaquo; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->model->my_pagination($config["per_page"], $page, 'pengumuman', 'id_pengumuman');
        $data["links"] = $this->pagination->create_links();

        $level = $this->session->userdata('level');
        // if ($level != NULL) {
        //     redirect('all_users/dashboard');
        // } elseif ($level == NULL) {
            $this->load->view('umum/index', $data);
        // }
    }
	
    public function info_skt(){
        $this->load->view('umum/info');
    }
    	public function faq(){
		$this->load->view('umum/faq');
	}
	
	public function cek_status(){
		$this->load->view('umum/cekstatus');
	}
	
	public function detail($id_pengumuman){
        $detail = $this->model->select('*', 'pengumuman', array('id_pengumuman' => $id_pengumuman));
        $result = $this->model->selects('*', 'pengumuman', NULL, NULL, NULL, array(5,0));

        $data = array(
            'penulis' => $detail->penulis,
            'judul' => $detail->judul,
            'isi' => $detail->isi,
            'tanggal_terbit' => $detail->tanggal_terbit,
            'results' => $result
        );
        $this->load->view('umum/pengumuman_detail', $data);
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

    public function send_mail($to, $subject, $message){
        $config = Array(
			'protocol' => 'smtp',
            'smtp_host' => 'mail.migas.esdm.go.id',// bisa diganti dengan alamat host web mail
            'smtp_port' => 25,
            'smtp_user' => 'skt.migas@migas.esdm.go.id', // misal no-reply@migas.esdm.go.id
            'smtp_pass' => 'dmtpmigas14', // change it to yours //  		
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

    public function forgot_password(){
        $this->form_validation->set_rules('email', 'Email', 'required');
        $email = $this->input->post('email', TRUE);
        if ($this->form_validation->run() == FALSE) {
            echo view('umum/forgot_password');
        }else{
            $randString = random_string('alnum', 8);
            $sl_email = select('*', 'users', array('username' => $email));
            $subject = 'Lupa password';
            $message = 'Silahkan klik link di bawah ini untuk mereset password baru: <br/><br/>'. site_url('umum/reset_password/'.$randString);
            $message .= '<br/><br/><b>*Mohon untuk tidak membalas email ini. Terimakasih.</b>';
            if ($sl_email) {
                update('users', array('hash' => $randString), array('username' => $email));
                $sent = $this->send_mail($email, $subject, $message);
                if ($sent) {
                    // echo "Silahkan cek email anda!";
                    // echo '<a href="'.site_url().'">kembali</a>';
                    $this->session->set_flashdata('message', '<div class="text-danger">Silahkan cek email Anda</div>');
                     redirect('umum/forgot_password');
                }
            }else {
                $message=  "Email tidak terdaftar!";
                // echo '<a href="'.site_url().'">kembali</a>';
                $this->session->set_flashdata('message', '<div class="text-danger">Email tidak terdaftar</div>');
                redirect('umum/forgot_password');
            }
        }
    }

    public function reset_password()
    {
        # code...
        $this->form_validation->set_rules('password', 'Email', 'required');
        $pwd = $this->input->post('password', TRUE);
        $hash = $this->input->post('hash', TRUE);

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('umum/reset_password');
                }else{
                    if ($hash != '') {

                        $sl_em = select('*', 'users', array('hash' => $hash));
                       
                        if ($sl_em) {
                        
                            $update_pwd = update('users', array('password' => md5($pwd.'esdm2014'), 'hash' => NULL), array('id_user'=> $sl_em->id_user));
                                if ($update_pwd) {
                                    $this->session->set_flashdata('message', '<div class="text-success">Password sudah diperbarui!</div>');
                                    redirect('umum/login');
                                }else{
                                    $this->session->set_flashdata('message', '<div class="text-danger">Gagal diperbarui!</div>');
                                    redirect('umum/reset_password/'.$randString);
                                }
                        
                    }else{
                        $this->session->set_flashdata('message', '<div class="text-danger">Tidak ada data!</div>');
                        redirect('umum/reset_password/'.$hash);
                    }
                }
                }
    }
	
	
}