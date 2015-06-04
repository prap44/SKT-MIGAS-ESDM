<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hal extends CI_Controller
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
        }

    }

//##########################################################################################################################################
//############################################									############################################################
//############################################		BARU 2 DESEMBER 2014		############################################################
//############################################									############################################################
//##########################################################################################################################################

// 1.1 FUNGSI INDEX

    public function index()
    {
        $config = array();
        $config["base_url"] = base_url() . "welcome/example1";
        $config["total_rows"] = $this->model->record_count('pengumuman');
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->model->
        fetch_countries($config["per_page"], $page, 'pengumuman');
        $data["links"] = $this->pagination->create_links();

        $level = $this->session->userdata('level');
        // if ($level != NULL) {
        //     redirect('hal/dashboard');
        // } elseif ($level == NULL) {
            $this->load->view('umum/index', $data);
        // }
    }


//***************************************************************************************************************************
// 1.2 FUNGSI DASHBOARD

    public function dashboard()
    {
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $status = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan')), array('id_disposisi', 'desc'));
                $daftar_skt = $this->model->selects('*', 'dokumen_skt', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
                

                $data = array(
                    'daftar_skt' => $daftar_skt
                    );
                if ($status) {
                    if ($status->status_progress == 3) {
                        $notif = 'Anda mendapatkan catatan revisi dari Admin! &nbsp <a href="' . base_url('hal/detail_perusahaan/pengajuan_skt/' . $this->session->userdata('id_perusahaan')) . '"><button class="btn btn-sm btn-primary detail" type="button">Lihat Catatan</button></a>';
                        $this->load->view('level1/dashboard', array('notif' => $notif));
                    } else {
                        // $notif = 'Anda mendapatkan catatan revisi dari Admin! &nbsp <a href="'.base_url('hal/detail_perusahaan/pengajuan_skt/'.$this->session->userdata('id_perusahaan')) .'"><button class="btn btn-sm btn-primary detail" type="button">Lihat Catatan</button></a>';
                        $this->load->view('level1/dashboard',$data);
                    }
                } else {
                    $this->load->view('level' . $level . '/dashboard',$data);
                }
            } else {
                $this->load->view('level' . $level . '/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 1.3 FUNGSI REGISTRASI

    public function registrasi()
    {
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
        $c->display_as('siup', 'Nomor SIUP');
        $c->display_as('npwp', 'Nomor NPWP');
        $c->display_as('file_siup', 'SIUP');
        $c->display_as('file_surat_ket_domisili', 'Surat Keterangan Domisili');

        $c->unset_texteditor('deskripsi_perusahaan', 'full_text');
        $c->unset_texteditor('alamat', 'full_text');
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        $c->set_field_upload('file_surat_ket_domisili', 'assets/uploads/file_register');
        $c->set_field_upload('file_siup', 'assets/uploads/file_register');
//        $c->set_field_upload('file_surat_pernyataan', 'assets/uploads/file_register');
        $c->set_field_upload('file_struktur_organisasi', 'assets/uploads/file_register');

        $c->required_fields('syarat_ketentuan','nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'provinsi', 'kota', 'npwp', 'akte_perusahaan', 'siup', 'file_akte', 'file_siup', 'file_struktur_organisasi');
        $c->set_relation('kota', 'ref_kota', 'kota', null, 'id_kota');
        $c->set_relation('provinsi', 'ref_provinsi', 'provinsi', null, 'id_provinsi');
        //$c->callback_insert(array($this, 'after_registrasi'));


        if ($this->uri->segment(3) == 'success') {
            // $data = '<h1>Selamat! Registrasi Anda Berhasil!</h1>';
            // $output = array('output' => '<h1>Selamat! Registrasi Anda Berhasil!</h1>');
            // $this->load->view('umum/registrasi', $output);
            redirect('hal/after_registrasi');
        }


        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();


        $this->logs();
        $this->load->view('umum/registrasi', $output);
    }

    public function after_registrasi()
    {
        $data = '<h1>Selamat! Registrasi Anda Berhasil!</h1>';
        $output = array('respon' => '<div class="form-div"><h1>Selamat! Registrasi Anda Berhasil!</h1><h2>Kami akan mengirimkan kode aktifasi akun Anda dalam waktu maksimal 2 X 24 jam</h2><h3>Silahkan klik <a href="' . base_url('hal/index') . '">Link Ini</a> untuk kembali ke menu utama</h3></div>');
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

//####################################################################################################################################
// 														UNTUK SEMUA USER
//####################################################################################################################################

// 2.1 FUNGSI PENGATURAN

    public function pengaturan()
    {
        $this->form_validation->set_rules('current_password', 'Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Konfirm Password', 'trim|required');

        if ($this->session->userdata('id_user') != NULL) {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('semua/pengaturan_akun');
            } else {
                $where = array(
                    'id_user' => $this->session->userdata('id_user'),
                    'password' => md5($this->input->post('current_password', TRUE) . 'esdm2014'),
                    'status' => 1
                );

                $auth = $this->model->select('*', 'users', $where);
                if ($auth != NULL) {
                    if ($this->input->post('current_password', TRUE) != $this->input->post('new_password', TRUE)) {
                        if ($this->input->post('new_password', TRUE) == $this->input->post('confirm_password', TRUE)) {
                            $update = $this->model->update('users', array('password' => md5($this->input->post('new_password', TRUE) . 'esdm2014')), $where);
                            if ($update) {
                                $this->session->set_flashdata('message', 'Password berhasil diubah');
                                $this->load->view('semua/pengaturan_akun');
                            }
                        } else {
                            $this->session->set_flashdata('new_password', '<div class="text-error">Password tidak sama!</div>');
                            redirect('hal/pengaturan');
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Anda memasukkan password baru yang sama dengan password lama Anda. \nPassword tidak diubah!');
                        $this->load->view('semua/pengaturan_akun');
                    }
                } else {
                    $this->session->set_flashdata('current_password', '<div class="text-error">Password salah!</div>');
                    redirect('hal/pengaturan');
                }
            }
        } elseif ($this->session->userdata('id_user') == NULL) {
            redirect('hal/dashboard');
        }
        $this->logs();
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
            'expiration' => 300
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

                    if ($auth != NULL) {
                        if ($auth->level == 1) {
                            $data_perusahaan = $this->model->select('id_perusahaan, nama_perusahaan', 'biodata_perusahaan', array('id_user' => $auth->id_user));

                            $permohonan = $this->model->select('id_permohonan', 'permohonan', array('id_perusahaan' => $data_perusahaan->id_perusahaan, 'selesai' => 0));
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
                                'id_permohonan' => $id_permohonan
                            );
                            $this->session->set_userdata($add_session);
                            $this->logs();
                            // $this->dashboard();
                            redirect('hal/dashboard');
                        } else {
                            $add_session = array(
                                'id_user' => $auth->id_user,
                                'level' => $auth->level,
                                'nama_user_online' => $auth->nama_lengkap
                            );
                            $this->session->set_userdata($add_session);
                            $this->logs();
                            // $this->dashboard();
                            redirect('hal/dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="text-danger">Email atau password salah!</div>');
                        redirect('hal/login');
                        // $this->login();
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="text-danger">Kode verifikasi salah!</div>');
                    redirect('hal/login');
                }

            }
        } elseif ($this->session->userdata('id_user') != NULL) {
            // $this->dashboard();
            redirect('hal/dashboard');
        }
        $this->logs();
    }

//***************************************************************************************************************************
// 2.3 FUNGSI LOGOUT

    public function logout()
    {
        $this->logs();
        $this->session->sess_destroy();

        redirect('hal/login');
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

//***************************************************************************************************************************
// 2.6 FUNGSI TRACK_DISPOSISI

    public function track_disposisi($id_perusahaan)
    {
        $track_disposisi = $this->model->selects('*', 'disposisi', array('id_perusahaan' => $id_perusahaan), NULL, array('id_disposisi', 'asc'), NULL);
        $this->load->view('view_track_disposisi', $track_disposisi);
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
        $c->add_action('Detail', 'sR', base_url('hal/detail_perusahaan') . '/');
        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');
        $c->unset_fields('tanggal_member', 'tanggal_disetujui', 'status_user', 'tanggal_daftar_member');
        $output = $c->render();
        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1 OR $level == 2) {
                $this->load->view('level' . $level . '/view_list', $output);
            } else {
                redirect('hal/dashboard');
            }
        } else {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 3.2 FUNGSI DETAIL_PERUSAHAAN

    public function detail_perusahaan($param, $id_perusahaan)
    {
        $biodata_perusahaan = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $id_perusahaan));
        $data_umum = $this->model->selects('*', 'data_umum', array('id_perusahaan' => $id_perusahaan));
        $data_khusus = $this->model->selects('*', 'data_khusus', array('id_perusahaan' => $id_perusahaan));
        $keanggotaan_asosiasi = $this->model->selects('*', 'keanggotaan_asosiasi', array('id_perusahaan' => $id_perusahaan));
        $tenaga_kerja = $this->model->selects('*', 'tenaga_kerja', array('id_perusahaan' => $id_perusahaan));
        $jumlah_tenaga_kerja = $this->model->selects('*', 'jumlah_tenaga_kerja', array('id_perusahaan' => $id_perusahaan));
        $pelatihan_tenaga_kerja_internal = $this->model->selects('*', 'pelatihan_tenaga_kerja_internal', array('id_perusahaan' => $id_perusahaan));
        $pelatihan_tenaga_kerja_eksternal = $this->model->selects('*', 'pelatihan_tenaga_kerja_eksternal', array('id_perusahaan' => $id_perusahaan));
        $peralatan = $this->model->selects('*', 'peralatan', array('id_perusahaan' => $id_perusahaan));
        $nilai_investasi = $this->model->selects('*', 'nilai_investasi', array('id_perusahaan' => $id_perusahaan));
        $daftar_pekerjaan = $this->model->selects('*', 'daftar_pekerjaan', array('id_perusahaan' => $id_perusahaan));
        $sop = $this->model->selects('*', 'sop', array('id_perusahaan' => $id_perusahaan));
        $csr = $this->model->selects('*', 'csr', array('id_perusahaan' => $id_perusahaan));

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
        );
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/view_detail', $detail_data);
            } else {
                $this->load->view('semua/view_detail', $detail_data);
            }
        } else {
            redirect('hal/logout');
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
        $c->add_action('Setuju', base_url('assets/img/icon/1.png'), base_url('hal/regis_setuju/') . '/');
        // $c->add_action('Tolak', base_url('assets/img/icon/cancel.png'), base_url('hal/regis_tolak/').'/');

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
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 3.4 FUNGSI SEND_MAIL

    function send_mail($to, $subject, $message)
    {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'ycared@gmail.com', // change it to yours
            'smtp_pass' => 'nbzpgevxlcfdveln', // change it to yours
            'mailtype' => 'html',
            // 'charset' => 'iso-$this->session->userdata('id_perusahaan')$this->session->userdata('id_perusahaan')59-1',
            'wordwrap' => TRUE
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('ycared@gmail.com'); // change it to yours
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
// 3.5 FUNGSI REKAPITULASI

    public function rekapitulasi($id_perusahaan, $bahan_penilaian)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('point', 'Point', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect(base_url('hal/detail_perusahaan/pengajuan_skt/' . $this->uri->segment(4)));
            // echo "<string>alert('Lengkapi Data!')</script>";
            echo "<script>window.history.back()</script>";
        } else {
            $data = array(
                'id_perusahaan' => $id_perusahaan,
                'penilai' => $this->session->userdata('id_user'),
                'penilai_level' => $this->session->userdata('level'),
                'id_permohonan' => '',
                'bahan_penilaian' => urldecode($bahan_penilaian),
                'point' => $this->input->post('point', TRUE),
                'bobot' => $this->input->post('bobot', TRUE),
                'hasil' => $this->input->post('point', TRUE) * $this->input->post('bobot', TRUE),
                'catatan_penilaian' => $this->input->post('catatan_penilaian', TRUE),

            );
            $this->db->insert('rekapitulasi', $data);
            if ($this->db->affected_rows() > 0) {
                /* echo "<script>alert('Berhasil disimpan')</script>"; */
                $this->session->set_flashdata('message', 'Berhasil disimpan');
                redirect('hal/detail_perusahaan/pengajuan_skt/' . $id_perusahaan);
            }
        }
    }

//***************************************************************************************************************************
// 3.6 FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN)

    public function daftar_pengajuan_skt_baru_admin()
    {
        $c = new grocery_crud();
        //

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 2) {
            $c->where('status_progress', 2);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 2));
        } elseif ($level == 3) {
            $c->where('status_progress', 4);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 4));
        } elseif ($level == 4) {
            $c->where('status_progress', 6);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 6));
        } elseif ($level == 5) {
            $c->where('status_progress', 7);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 7));

        } elseif ($level == 6) { //eva to kasie
            $c->where('status_progress', 8);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 8));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per= '';
        }
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

        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt_admin")));

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
// 3.7 FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN)

    public function daftar_pengajuan_skt_perpanjangan_admin()
    {
        $c = new grocery_crud();
        //$permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan'));

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 2) {
            $c->where('status_progress', 2);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 2));
        } elseif ($level == 3) {
            $c->where('status_progress', 4);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 4));
        } elseif ($level == 4) {
            $c->where('status_progress', 6);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 6));
        } elseif ($level == 5) {
            $c->where('status_progress', 7);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 7));
        } elseif ($level == 6) { //eva to kasie
            $c->where('status_progress', 8);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 8));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per= '';
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

        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt_admin")));

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
// 3.8 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN)

    function daftar_pengajuan_skt_admin()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->daftar_pengajuan_skt_baru_admin();

        $output2 = $this->daftar_pengajuan_skt_perpanjangan_admin();

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
            redirect('hal/logout');
        }
    }
//***************************************************************************************************************************
// 3.6 FUNGSI DAFTAR_PENGAJUAN_SKP_BARU	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKP)

    public function daftar_pengajuan_skp_baru()
    {
        $c = new grocery_crud();
        //

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 2) {
            $c->where('status_progress', 2);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 2));
        } elseif ($level == 3) {
            $c->where('status_progress', 4);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 4));
        } elseif ($level == 4) {
            $c->where('status_progress', 6);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 6));
        } elseif ($level == 5) {
            $c->where('status_progress', 7);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 7));

        } elseif ($level == 6) { //eva to kasie
            $c->where('status_progress', 8);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 8));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per= '';
        }
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'SK Penunjukkan Baru', 'id_perusahaan' => $id_per));

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

        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/pengajuan_skp');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skp")));

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
// 3.7 FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKT)

    public function daftar_pengajuan_skp_perpanjangan()
    {
        $c = new grocery_crud();
        //$permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan'));

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 2) {
            $c->where('status_progress', 2);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 2));
        } elseif ($level == 3) {
            $c->where('status_progress', 4);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 4));
        } elseif ($level == 4) {
            $c->where('status_progress', 6);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 6));
        } elseif ($level == 5) {
            $c->where('status_progress', 7);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 7));
        } elseif ($level == 6) { //eva to kasie
            $c->where('status_progress', 8);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 8));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per= '';
        }
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan SK Penunjukkan', 'id_perusahaan' => $id_per));

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

        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt_admin")));

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
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 3.9 FUNGSI DETAIL_EVALUASI

    /*Pusing muyeng-muyeng*/
    public function detail_evaluasi($param, $id_perusahaan)
    {

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
        $permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Baru'));

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 5) {
            $c->where('status_progress', 9);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 9));
        } elseif ($level == 4) {
            $c->where('status_progress', 11);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 11));
        } elseif ($level == 3) {
            $c->where('status_progress', 12);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 12));
        } elseif ($level == 2) {
            $c->where('status_progress', 13);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 13));
        }
        if($id_per != NULL){
            $id_per = $id_per->id_perusahaan;
        }else{
            $id_per = '';
        }
        // $c->where('id_perusahaan', $permohonan->id_perusahaan);
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'Baru', 'id_perusahaan' => $id_per));

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

        // $c->add_action('Revisi','sR', base_url('hal/revisi_pengajuan_skt/').'/');
        // $c->add_action('Lanjut','text', base_url('hal/pengajuan_skt_diterima/add').'/');
        $c->add_action('Detail Evaluasi', 'sd', 'hal/detail_evaluasi/pengajuan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt_admin")));

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
        $permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan'));

        $c->set_table('biodata_perusahaan');

        $level = $this->session->userdata('level');
        if ($level == 5) {
            $c->where('status_progress', 9);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 9));
        } elseif ($level == 4) {
            $c->where('status_progress', 11);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 11));
        } elseif ($level == 3) {
            $c->where('status_progress', 12);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 12));
        } elseif ($level == 2) {
            $c->where('status_progress', 13);
            $id_per = $this->model->select('id_perusahaan', 'biodata_perusahaan', array('status_progress' => 13));
        }
        if($id_per != NULL){
           $id_per = $id_per->id_perusahaan;
        }else{
            $id_per = '';
        }
        $permohonan = $this->model->selects('*', 'permohonan', array('jenis_permohonan' => 'Perpanjangan', 'id_perusahaan' => $id_per));

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

        // $c->add_action('Revisi','sR', base_url('hal/revisi_pengajuan_skt/').'/');
        // $c->add_action('Lanjut','text', base_url('hal/pengajuan_skt_diterima/add').'/');
        $c->add_action('Detail Evaluasi', 'sd', 'hal/detail_evaluasi/perpanjangan_skt');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/daftar_pengajuan_skt_admin")));

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
            redirect('hal/logout');
        }
    }

    /*end pusing muyeng-munyeng*/
//***************************************************************************************************************************
// 3.13 FUNGSI PENGAJUAN_SKT_DITERIMA

    public function pengajuan_skt_diterima()
    {
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->uri->segment(4)));
        $level = $this->session->userdata('level');
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);

        $c->display_as('id_perusahaan', 'nama perusahaan');

        //lagi di level 2 didisposisi ke level 4

        if ($level == 2) {
            $c->field_type('status_progress', 'hidden', '4');
        } elseif ($level == 3) {
            $c->field_type('status_progress', 'hidden', '6');
        } elseif ($level == 4) {
            $c->field_type('status_progress', 'hidden', '7');
        } elseif ($level == 5) {
            $c->field_type('status_progress', 'hidden', '8');
        } elseif ($level == 6) {
            $c->field_type('status_progress', 'hidden', '9');
        }

        if ($level == 2) {
            $level = 3;
        } elseif ($level == 3) {
            $level = 4;
        } elseif ($level == 4) {
            $level = 5;
        } elseif ($level == 5) {
            $level = 6;
        } elseif ($level == 6) {
            $level = 5;
        }

        $l4 = $this->model->selects('*', 'users', array('level' => $level));

        foreach ($l4 as $key => $l4o) {
            # code...
            $ListUser = array($l4o->id_user => $l4o->nama_lengkap);
        }

        $c->field_type('user_tujuan', 'dropdown', $ListUser);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $this->uri->segment(4)));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan', 'id_permohonan');
        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima'));

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {


            if ($level == 6) {
                redirect('hal/konsep_dokumen_skt/add/' . $this->uri->segment(4));
            } else {
                redirect(base_url('hal/daftar_pengajuan_skt_admin'));
            }
        }
        $level_user = $this->session->userdata('level');
        if ($level_user != NULL) {
            $this->load->view('level' . $level_user . '/view_list', $output);
        } elseif ($level_user == NULL) {
            redirect('hal/logout');
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

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->uri->segment(4)));

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
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $this->uri->segment(4)));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan', 'id_permohonan');

        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima_naik'));

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            $level = $this->session->userdata('level');
            if ($level == 6) {
                redirect('hal/konsep_dokumen_skt/add/' . $this->uri->segment(4));
            } elseif ($level == 5) {
                redirect('hal/no_dokumen_skt/add/' . $this->uri->segment(4));
            } else {
                redirect(base_url('hal/daftar_pengajuan_skt_admin_naik'));
            }
        }

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }


    public function konsep_dokumen_skt($da = NULL, $id = NULL)
    {
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
        $level_user = $this->session->userdata('level');
        $this->load->view('level' . $level_user . '/view_list', $output);

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
        $subject = 'Registrasi Berhasil ESDM';
        $message = 'Silahkan login dengan data berikut<br/>' . 'email : ' . $registrasi->email . '<br/>password :' . $randString;
        $send_email = $this->send_mail($registrasi->email, $subject, $message);

        if ($send_email) {
            $this->model->delete('registrasi', array('id_registrasi' => $id));
            $this->logs('Data' . $registrasi->nama_perusahaan . 'telah dipindahkan dan dihapus dari register');
            redirect('hal/daftar_register');
            echo "<script>alert('Berhasil disetujui')</script>";
        } else {
            $this->logs('Data' . $registrasi->nama_perusahaan . 'gagal dihapus');
            redirect('hal/daftar_register');
            echo "<script>alert('Gagal mohon ulangi lagi')</script>";
        }
    }

//***************************************************************************************************************************
// 4.2 FUNGSI REGIS_TOLAK

    public function regis_tolak($id)
    {
        $level = $this->session->userdata('level');
        $perusahaan = $this->model->select('*', 'registrasi', array('id_registrasi' => $id));
        $action = $this->model->delete('registrasi', array('id_registrasi' => $id));
        if ($action) {
            $this->logs('akun ' . $perusahaan->email . ' ditolak');
            if ($level != NULL) {
                $this->daftar_register();
            } elseif ($level == NULL) {
                redirect('hal/logout');
            }
        }
    }

//***************************************************************************************************************************
// 4.3 FUNGSI REVISI_PENGAJUAN_SKT

    public function revisi_pengajuan_skt($link_back, $id)
    {
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');
        $output = array('param' => $link_back);
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('level2/email_revisi', $output);
        } else {

            $perusahaan = $this->model->select('email, id_user', 'biodata_perusahaan', array('id_perusahaan' => $id));
            $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $id));
            $subject = $this->input->post('subject', TRUE);
            $message = $this->input->post('message', TRUE);
            //$send_email = $this->send_mail($perusahaan->email, $subject, $message);
            $send_email = TRUE;
            if ($this->session->userdata('level') == 2) {
                $status_progress = 3;
            } elseif ($this->session->userdata('level') == 5) {
                $status_progress = 10;
            }
            if ($send_email) {
                $data_disposisi = array(
                    'id_parent' => $last_disposisi->id_disposisi,
                    'id_perusahaan' => $last_disposisi->id_perusahaan,
                    'user_asal' => $this->session->userdata('id_user'),
                    'user_tujuan' => $perusahaan->id_user,
                    'status_progress' => $status_progress
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
        }
    }

//***************************************************************************************************************************
// 4.4 FUNGSI REF_GOLONGAN_ALAT

    public function ref_golongan_alat()
    {
        $c = new grocery_crud();
        $c->set_table('ref_golongan_alat');
        $c->required_fields('golongan_alat');
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

    public function ref_sub_bidang()
    {
        $c = new grocery_crud();
        $c->set_table('ref_sub_bidang');
        $c->required_fields('sub_bidang');
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
        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->menej_ref($output);
        } else {
            return $output;
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

        $js_files = $output1->js_files + $output2->js_files + $output3->js_files + $output4->js_files + $output5->js_files + $output6->js_files + $output7->js_files;
        $css_files = $output1->css_files + $output2->css_files + $output3->css_files + $output4->css_files + $output5->css_files + $output6->css_files + $output7->css_files;
        $output = '<div class="recenttitle">Referensi Golongan Alat</div><center>' . $output2->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Provinsi</div><center>' . $output3->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Kota</div><center>' . $output4->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Sub Bidang</div><center>' . $output5->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Bagian Sub Bidang</div><center>' . $output6->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Sub Bagian Sub Bidang</div><center>' . $output7->output . '</center></div>
		<div class="column_data"><div class="recenttitle">Referensi Jenis Dokumen Data Umum</div><center>' . $output1->output . '</center>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 2) {
                $this->load->view('level2/manage_ref', (object)array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
                ));
            } elseif ($level == NULL) {
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 4.12 FUNGSI DAFTAR_REVISI_SKT_ADMIN

    public function daftar_revisi_skt_admin()
    {
        $c = new grocery_crud();
        //$permohonan = $this->model->select('*', 'permohonan', array('jenis_permohonan' => 'Baru'));
        if ($this->session->userdata('level') == 2) {
            $status_progress = 32;
        } elseif ($this->session->userdata('level') == 5) {
            $status_progress = 102;
        }
        $c->set_table('biodata_perusahaan');
        $c->where('status_progress', $status_progress);
        //$c->where('id_perusahaan', $permohonan->id_perusahaan);
        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();
        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->unset_columns('id_user', 'status_user', 'contact_person', 'alamat', 'provinsi', 'website', 'deskripsi_perusahaan', 'status_progress', 'keterangan');

        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/revisi_skt');

        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');

        $c->unset_fields('tanggal_member', 'status_user', 'keterangan');
        $output = $c->render();

        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } else {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 4.13 FUNGSI CATATAN_PETUGAS

    public function catatan_petugas($table = NULL, $id = NULL)
    {
        $this->form_validation->set_rules('catatan_petugas', 'Catatan', 'required');
        if ($this->form_validation->run() == FALSE) {
            // redirect('hal/detail_perusahaan/pengajuan_skt/'.$id);
            echo "catatan diperlukan";
        } else {
            $catat = $this->model->update($table, array('catatan_petugas' => $this->input->post('catatan_petugas', TRUE)), array('id_perusahaan' => $id));

            if ($catat) {
                redirect('hal/detail_perusahaan/pengajuan_skt/' . $id);
            }
        }
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
        $c->field_type('penulis', 'hidden', $this->session->userdata('nama_user_online'));
        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 2) {
                $this->load->view('level2/view_list', $output);
            } else {
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }


//####################################################################################################################################
// 														UNTUK PERUSAHAAN
//####################################################################################################################################	

// 5.1 FUNGSI BIDANG_USAHA

    public function bidang_usaha()
    {
        $bidang_usaha = $this->input->post('bidang_usaha', TRUE);
        $sub_bidang = $this->model->selects('*', 'ref_sub_bidang', array('bidang_usaha' => $bidang_usaha));

        $sub_bidang_obj = '<label>Sub Bidang</label>: <select name="sub_bidang" id="sub_bidang">';
        $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

        foreach ($sub_bidang as $key => $sbdg) {
            $sub_bidang_obj .= '<option name="sub_bidang" id="sb-' . $sbdg->id_sub_bidang . '" onclick="sub_bidang(' . $sbdg->id_sub_bidang . ')" value="' . $sbdg->id_sub_bidang . '">' . $sbdg->sub_bidang . '</option>';
        }

        $sub_bidang_obj .= '</select>';

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

        $bagian_sub_bidang_obj = '<label>Bagian Sub Bidang<br/>& Sub Bagian Sub Bidang</label>: <br/>';
        // $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

        foreach ($bagian_sub_bidang as $key => $sbdg) {
            $bagian_sub_bidang_obj .= '<input class="checkbox-bsb" type="checkbox" name="bagian_sub_bidang[]" id="bsb-' . $sbdg->id_bagian_sub_bidang . '" onchange="bagian_sub_bidang(' . $sbdg->id_bagian_sub_bidang . ')" value="' . $sbdg->bagian_sub_bidang . '"> ' . $sbdg->bagian_sub_bidang . '<div class="chk-sbsb" id="sub-bagian-sub-bidang-' . $sbdg->id_bagian_sub_bidang . '"></div>';

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
        $sub_bagian_sub_bidang_obj = '';
        foreach ($sub_bagian_sub_bidang as $key => $sbsbdg) {
            $sub_bagian_sub_bidang_obj .= '<input class="checkbox-sbsb" type="checkbox" id="sbsb-' . $sbsbdg->id_sub_bagian_sub_bidang . '" name="sub_bagian_sub_bidang[]" onchange="sub_bagian_sub_bidang(' . $sbsbdg->id_sub_bagian_sub_bidang . ')" value="' . $sbsbdg->sub_bagian_sub_bidang . '"> ' . $sbsbdg->sub_bagian_sub_bidang . '<br/>';
        }
        $sub_bagian_sub_bidang_obj .= '<hr/>';

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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.5 FUNGSI DATA_UMUM

    public function data_umum()
    {
        $c = new grocery_crud();
        $c->set_table('data_umum');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        // $c->unset_delete();
        // $c->unset_export();
        // $c->unset_print();


        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset field
        $c->unset_fields('status', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'status', 'catatan_petugas', 'status_pemakaian');

        // set relation
        $c->set_relation('jenis_dokumen', 'ref_jenis_dokumen', 'jenis_dokumen');

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

//***************************************************************************************************************************
// 5.6 FUNGSI DATA_KHUSUS

    public function data_khusus()
    {
        $c = new grocery_crud();
        $c->set_table('data_khusus');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        
		
		if($this->session->userdata('id_permohonan') != ''){
			// $c->unset_delete();
			// $c->unset_read();
			// $c->unset_edit();
			
			// $c->add_action('Pilih', 'sd', 'pilih/data','ui-icon-plus');
		}
		
		
        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
        $c->unset_fields('status', 'id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'status', 'id_sub_bidang', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');

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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.9 FUNGSI KEANGGOTAAN_ASOSIASI

    public function keanggotaan_asosiasi()
    {
        $c = new grocery_crud();
        $c->set_table('keanggotaan_asosiasi');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            $this->logs();
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.10 FUNGSI TENAGA_KERJA

    public function tenaga_kerja()
    {
        $c = new grocery_crud();
        $c->set_table('tenaga_kerja');
        $c->where('tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        //$c->unset_delete();
        //$c->required_fields('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah');
        $c->fields('nama_lengkap', 'id_perusahaan', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'sertifikasi');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('status', 'enum', array('Permanen', 'Non Permanen'));
        $c->set_field_upload('file_ijazah', 'assets/uploads/file_ijazah_tenaga_ahli');
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
        $c->unset_fields('id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian', 'sertifikasi');

        // set relation
        $c->set_relation('jenjang_pendidikan', 'ref_jenjang_pendidikan', 'jenjang_pendidikan', null, 'id_jenjang_pendidikan');

		$c->callback_field('sertifikasi', array($this, 'callback_sertifikasi'));
        // display as
        $c->display_as('jenjang_pendidikan', 'Pendidikan Terakhir');
        $c->display_as('jurusan_pendidikan', 'Jurusan');
        $c->display_as('jabatan', 'Keahlian');
        $c->display_as('status', 'Status Kepegawaian');
		
		$c->callback_after_insert(array($this,'callback_before_insert_or_update'));		

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_tenaga_kerja($output);
        } else {
            return $output;
        }

    }


    public function callback_before_insert_or_update($post_array, $primary_key){
	
		//echo '<script> alert('.json_encode($post_array['judul_pelatihan'][1]).'); </script>';
		$juduls = $post_array['judul_pelatihan'];
		$nomors = $post_array['nomor_sertifikat'];
		
		//if($primary_key != NULL){
			//if($juduls != ""){
					$count = count($juduls);
					$data = array();
					for($i=0; $i<$count; $i++) {
						$data[$i] = array(
							'id_tenaga_kerja' => $primary_key,
							'id_perusahaan' => $this->session->userdata('id_perusahaan'),
							'judul_pelatihan' => $juduls[$i],
							'nomor_sertifikat' => $nomors[$i],
							'file_sertifikat' => NULL
							);
					}
				//}
				
        
				$this->db->insert_batch('sertifikasi_tenaga_kerja', $data);				
				return TRUE;
			//} 
			
			
	}
	
//***************************************************************************************************************************
// 5.11 FUNGSI CALLBACK_SERTIFIKASI
	
    public function callback_sertifikasi(){
        //You can do it strait forward
		$output = '<script> $(document).ready(function (){ var counter = 2;	var limit = 11;	';
		$output .= '$("#btn_add").click(function(){';
		$output .= 'if (counter == limit){ alert("Anda terlalu banyak menambahkan 10 data!");	} ';
		$output .= 'else {';
		$output .= 'var clone1 = $("#cloneObject1").clone(); ';
		$output .= 'clone1.attr("id","cloneObject" +counter); clone1.empty(); ';
		$output .= 'clone1.append("<td id=\'td"+counter+"_1\'></td><td id=\'td"+counter+"_2\'></td><td id=\'td"+counter+"_3\'></td><td id=\'td"+counter+"_4\'></td>"); ';
		$output .= 'clone1.appendTo("#cloneMother"); ';
		$output .= 'var clone2 = $("#judul_pelatihan1").clone().val(""); clone2.attr("id","judul_pelatihan" +counter); ';
		$output .= 'clone2.appendTo("#td"+counter+"_1"); ';
		$output .= 'var clone3 = $("#nomor_sertifikat1").clone().val(""); clone3.attr("id","nomor_sertifikat" + counter); ';
		$output .= 'clone3.appendTo("#td"+counter+"_2"); ';
		$output .= 'var clone4 = $("#file_sertifikat1").clone().val(""); clone4.attr("id","file_sertifikat" + counter); ';
		$output .= 'clone4.appendTo("#td"+counter+"_3"); ';
		$output .= 'var clone5 = $("#btn_add").clone(); clone5.attr({id: "btn_del"+counter, onclick:"delput("+counter+")", name:"btn_del", class:"input-del"}); ';
		$output .= 'clone5.appendTo("#td"+counter+"_4"); counter++; ';
		$output .= '} }); ';
		$output .= 'function delput(d){';
		$output .= '$("#cloneObject" + d).remove();';
		$output .= '}';
		$output .= '}); </script>';
		$output .= '<div class="div-sertifikasi"><table class="tabel-sertifikasi"><thead><tr>';
		$output .= '<th class="title-sertifikat">Judul Pelatihan</th>';
		$output .= '<th class="title-sertifikat">Nomor Sertifikat</th>';
		$output .= '<th class="title-sertifikat">File Sertifikat</th>';
		$output .= '<th></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody id="cloneMother"><tr id="cloneObject1">';
		$output .= '<td id="td1_1"><input class="input-sertifikat" type="text" name="judul_pelatihan[]" placeholder="Judul.." id="judul_pelatihan1" required /></td>';
		$output .= '<td id="td1_2"><input class="input-sertifikat" type="text" name="nomor_sertifikat[]" placeholder="Nomor.." id="nomor_sertifikat1" /></td>';
		$output .= '<td id="td1_3"><input class="input-sertifikat" type="file" name="file_sertifikat[]" placeholder="File" id="file_sertifikat1" /></td>';
		$output .= '<td id="td1_4"><input class="input-add" type="button" name="btn_add" id="btn_add" /></td>';
		$output .= '</tr></tbody>';
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
		$c->unset_add();
		$c->unset_print();
		$c->unset_export();
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
        //$c->unset_delete();
        $c->required_fields('tipe_tenaga_kerja', 'sd', 'smp', 'sma', 'diploma', 'sarjana', 'pasca_sarjana', 'doktor');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        // set relation
        $c->set_relation('tipe_tenaga_kerja', 'ref_tipe_tenaga_kerja', 'tipe_tenaga_kerja');

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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.13 FUNGSI PELATIHAN_TENAGA_KERJA_INTERNAL

    public function pelatihan_tenaga_kerja_internal()
    {
        $c = new grocery_crud();
        $c->set_table('pelatihan_tenaga_kerja_internal');
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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
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

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Utama');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian');
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

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Pendukung');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
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

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Keselamatan dan Kesehatan Kerja');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
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

        // unset field
        $c->unset_fields('catatan_petugas', 'status_kepemilikan', 'file_nilai_investasi');

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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }


//***************************************************************************************************************************
// 5.18 FUNGSI PENGALAMAN_KERJA

    function pengalaman_kerja()
    { //multigrid
        $c = new grocery_crud();
        $c->set_table('daftar_pekerjaan');
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
        $c->unset_columns('id_perusahaan', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.19 FUNGSI SOP

    public function sop()
    {


        $c = new grocery_crud();
        $c->set_table('sop');

        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        
        $c->required_fields('prosedur', 'deskripsi', 'file_manajemen_prosedur_kerja');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        $c->unset_fields('catatan_petugas','id_permohonan', 'status_pemakaian');
        $c->unset_columns('id_perusahaan','catatan_petugas','id_permohonan', 'status_pemakaian');
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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.20 FUNGSI CSR

    public function csr()
    {
        $c = new grocery_crud();
        $c->set_table('csr');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        
        $c->required_fields('kegiatan', 'waktu', 'lokasi');
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->set_field_upload('file_csr', 'assets/uploads/file_csr');

        $c->unset_fields('catatan_petugas', 'status_pemakaian');
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->display_as('file_csr', 'File CSR');
        //$c->unset_delete();

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }
    }

//***************************************************************************************************************************
// 5.21 FUNGSI JENIS_PERMOHONAN_BIDANG_USAHA

    public function jenis_permohonan_bidang_usaha()
    {

        if ($this->input->post('bidang_usaha', TRUE) == '01') {
            $bidang_usaha = 'Jasa Konstruksi';
        } elseif ($this->input->post('bidang_usaha', TRUE) == '02') {
            $bidang_usaha = 'Jasa Non Konstruksi';
        } else {
            $bidang_usaha = 'Industri Penunjang';
        }

        $sub_bidang = $this->model->select('*', 'ref_sub_bidang', array('id_sub_bidang' => $this->input->post('sub_bidang', TRUE)));
        $bagian_sub_bidang = implode(", ", $this->input->post('bagian_sub_bidang', TRUE));
        $sub_bagian_sub_bidang = implode(", ", $this->input->post('sub_bagian_sub_bidang', TRUE));
        $data = array(
            'id_perusahaan' => $this->session->userdata('id_perusahaan'),
            'jenis_permohonan' => $this->input->post('jenis_permohonan', TRUE),
            'bidang_sub_bidang' => $bidang_usaha . '/' . $sub_bidang->sub_bidang,
            'bagian_sub_bidang' => $bagian_sub_bidang,
            'sub_bagian_sub_bidang' => $sub_bagian_sub_bidang,
        );
        $this->model->insert('permohonan', $data);

        $this->session->set_userdata('id_permohonan', $this->db->insert_id());
        $this->logs();
        if ($this->db->affected_rows() > 0) {
            redirect(base_url('hal/data_pemohon'));
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
                redirect('hal/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('hal/logout');
        }

    }


//##########################################################################################################################################
//#############################################										########################################################
//#############################################		END OF BARU 2 DESEMBER 2014		########################################################
//#############################################										########################################################
//##########################################################################################################################################


    /* umum */


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


        $data = array(
            'status_progress' => $status_progress,
            // 'tanggal_disetujui' => date('Y-m-d H:i:s')
        );
        $this->db->update('biodata_perusahaan', $data, array('id_perusahaan' => $post_array['id_perusahaan']));
        return TRUE;
    }


    public function disposisi_admin_to_dtlm($id_perusahaan)
    {
        $perusahaan = $this->model->select('email, id_user', 'biodata_perusahaan', array('id_perusahaan' => $id_perusahaan));
        $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $id_perusahaan));

        $dtlm = $this->model->select('*', 'users', array('level' => 3, 'status' => 1));

        $data_disposisi = array(
            'id_parent' => $last_disposisi->id_disposisi,
            'id_perusahaan' => $last_disposisi->id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $dtlm->id_user
        );
        $check = $this->model->insert('disposisi', $data_disposisi);

        if ($check) {
            // $pesan = array('msg' => '<script>alert("Email berhasil dikirim");</script>', 'param' => 'pengajuan_skt');
            // $this->load->view('level2/kirim_email', $pesan);
        }

    }


    /* end view umum */

    ###############################################################################################################################
    /*
    *	DTLM SKT
    */
    ###############################################################################################################################


    public function daftar_pengajuan_skt_baru_dtlm()
    {
        if ($this->session->userdata('level') != 3) {
            $this->logout();
        }

        $c = new grocery_crud();

        $c->set_table('biodata_perusahaan');
        $c->where('status_progress', 4);
        $c->unset_add();
        //$c->unset_delete();
        $c->unset_edit();
        $c->unset_read();

        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->unset_columns('id_user', 'status_user', 'contact_person', 'alamat', 'provinsi', 'website', 'deskripsi_perusahaan', 'status_progress', 'tanggal_disetujui');

        $c->add_action('Revisi', 'sR', base_url('hal/revisi_pengajuan_skt_dtlm/add') . '/');
        $c->add_action('Lanjut', 'text', base_url('hal/pengajuan_skt_diterima_dtlm/add') . '/');
        $c->add_action('Detail', 'sd', 'hal/detail_perusahaan/pengajuan_skt');

        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');

        $c->unset_fields('tanggal_member', 'status_user');
        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        }
    }


    public function revisi_pengajuan_skt_dtlm($id_perusahaan = NULL)
    {
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();

        // $c->field_type('id_perusahaan', 'hidden', $id_perusahaan);

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->uri->segment(4)));
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('catatan', 'text');

        $c->display_as('id_perusahaan', 'Nama perusahaan');

        $query = $this->db->query('SELECT * FROM users WHERE level=2 AND status=1')->result_array();
        foreach ($query as $key => $row) {
            $listDtlm = array($row['id_user'] => $row['nama_lengkap']);
        }


        $c->field_type('user_tujuan', 'dropdown', $listDtlm);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $this->uri->segment(4)));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai');
        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima'));

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            redirect(base_url('hal/daftar_pengajuan_skt_baru_dtlm'));
        }

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } else {
            $this->logs();
            $this->logout();
        }
    }


    public function pengajuan_skt_diterima_dtlm($id_perusahaan = NULL)
    {
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');

        //$c->unset_delete();
        $c->unset_edit();

        // $c->field_type('id_perusahaan', 'hidden', $id_perusahaan);

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->uri->segment(4)));
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('catatan', 'text');

        $c->display_as('id_perusahaan', 'nama_perusahaan');

        $query = $this->db->query('SELECT * FROM users WHERE level=4 AND status=1')->result_array();
        foreach ($query as $key => $row) {
            $listDtlm = array($row['id_user'] => $row['nama_lengkap']);
        }


        $c->field_type('user_tujuan', 'dropdown', $listDtlm);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $this->uri->segment(4)));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai');
        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima'));

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();
        if ($state == 'success') {
            redirect(base_url('hal/daftar_pengajuan_skt_baru_admin'));
        }

        $this->load->view('level2/view_list', $output);
    }


    /*
    * masukan table disposisi dari user ke admin
    */
    public function disposisi_user_to_admin($id_perusahaan)
    {

        $admin = $this->model->select('id_user', 'users', array('level' => 2, 'status' => 1));
        $disposisi = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
        if ($disposisi->status_progress == 1) {
            $status_progress = 2;
        } elseif ($disposisi->status_progress == 3) {
            $status_progress = 32;
        } elseif ($disposisi->status_progress == 10) {
            $status_progress = 102;
        } elseif ($disposisi->status_progress == 32) {
            $status_progress = 32;
        } elseif ($disposisi->status_progress == 102) {
            $status_progress = 102;
        }
        $data_disposisi = array(
            'id_perusahaan' => $id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $admin->id_user,
            'catatan' => $this->input->post('catatan', TRUE),
            'status_progress' => $status_progress,
            'id_permohonan' => $this->session->userdata('id_permohonan')
        );

        $this->model->insert('disposisi', $data_disposisi);
        $this->model->update('biodata_perusahaan', array('status_progress' => 2), array('id_perusahaan' => $id_perusahaan));
        $this->model->update('permohonan', array('selesai' => 1), array('id_perusahaan' => $id_perusahaan));
        echo "<script>alert('Terima kasih telah melengkapi proses registrasi, data Anda segera akan kami proses')</script>";
        // echo "<script>window.history.back()</script>";
        $this->session->set_flashdata('message', 'Registrasi anda berhasil!');
        redirect(base_url('hal/dashboard'));
    }


    public function disposisi_dtlm_to_kasubdit($id_perusahaan)
    {

        $admin = $this->model->select('id_user', 'users', array('level' => 4, 'status' => 1));
        $data_disposisi = array(
            'id_perusahaan' => $id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $admin->id_user,
            'catatan' => $this->input->post('catatan', TRUE),
            'status_progress' => 6,
        );

        $this->model->insert('disposisi', $data_disposisi);
        $this->model->update('biodata_perusahaan', array('status_progress' => 2), array('id_perusahaan' => $id_perusahaan));
        echo "<script>alert('Terima kasih telah melengkapi proses registrasi, data Anda segera akan kami proses')</script>";

    }

    public function disposisi_kasubdit_to_kasie($id_perusahaan)
    {

        $admin = $this->model->select('id_user', 'users', array('level' => 5, 'status' => 1));
        $data_disposisi = array(
            'id_perusahaan' => $id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $admin->id_user,
            'catatan' => $this->input->post('catatan', TRUE),
            'status_progress' => 7,
        );

        $this->model->insert('disposisi', $data_disposisi);
        $this->model->update('biodata_perusahaan', array('status_progress' => 2), array('id_perusahaan' => $id_perusahaan));
        echo "<script>alert('Terima kasih telah melengkapi proses registrasi, data Anda segera akan kami proses')</script>";

    }

}
