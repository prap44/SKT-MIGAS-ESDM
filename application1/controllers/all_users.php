<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class All_users extends CI_Controller
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
                                $this->session->set_flashdata('message', 'Password berhasil diubah!');
                                redirect('all_users/pengaturan');
                            }
                        } else {
                            $this->session->set_flashdata('new_password', '<div class="text-error">Password tidak sama!</div>');
                            redirect('all_users/pengaturan');
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Anda memasukkan password baru yang sama dengan password lama Anda. \nPassword tidak diubah!');
                        redirect('all_users/pengaturan');
                    }
                } else {
                    $this->session->set_flashdata('current_password', '<div class="text-error">Password salah!</div>');
                    redirect('all_users/pengaturan');
                }
            }
        } elseif ($this->session->userdata('id_user') == NULL) {
            redirect('all_users/dashboard');
        }
        $this->logs();
    }
	
	

//***************************************************************************************************************************
// 1.2 FUNGSI DASHBOARD

    public function dashboard()
    {
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $status = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan')), array('id_disposisi', 'desc'));
                $daftar_skt = $this->model->selects('*', 'dokumen_skt', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'status' => 1));
                $sl_permohonan = selects('*', 'permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'selesai' => 1));

                $data = array(
                    'daftar_skt' => $daftar_skt,
                    'status_progress' => '',
                    'data_permohonan' => $sl_permohonan,
                    );
                if ($status) {
                    if ($status->status_progress == 3 || $status->status_progress == 10) {
                        $notif = '<h3>*Anda mendapatkan catatan revisi dari Admin! &nbsp <a href="' . base_url('all_admin/detail_perusahaan/pengajuan_skt/' . $status->id_permohonan) . '"><button class="btn btn-sm btn-primary detail" type="button">Lihat Catatan</button></a></h3>';
                        $this->load->view('level1/dashboard', array('notif' => $notif, 'daftar_skt' => $daftar_skt, 'data_permohonan' => $sl_permohonan));
                    }else {
                        // $notif = 'Anda mendapatkan catatan revisi dari Admin! &nbsp <a href="'.base_url('all_admin/detail_perusahaan/pengajuan_skt/'.$this->session->userdata('id_perusahaan')) .'"><button class="btn btn-sm btn-primary detail" type="button">Lihat Catatan</button></a>';
                        $this->load->view('level1/dashboard',$data);
                    }
                } else {
                    $this->load->view('level' . $level . '/dashboard',$data);
                }
            } else {
				$output = $this->total_pengajuan_admin();
				$data = array('daftar_tugas' => $output);
                $this->load->view('level' . $level . '/dashboard',$data);
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }
	

	public function total_pengajuan_admin(){
		$level = $this->session->userdata('level');
		if ($level == 2) {
			$where = 2;
			$where_revisi = 3;
            $where_naik = 13;
		} elseif ($level == 3) {
			$where = 4;
			$where_revisi = NULL;
            $where_naik = 12;
		} elseif ($level == 4) {
			$where = 6;
			$where_revisi = NULL;
            $where_naik = 11;
		} elseif ($level == 5) {
			$where = 7;
			$where_revisi = 10;
            $where_naik = 9;
		} elseif ($level == 6) { 
			$where = 8;
			$where_revisi = 102;
            $where_naik = 8;
		} elseif ($level == 8) { 
			$where = NULL;
			$where_revisi = NULL;
            $where_naik = NULL;
		}

		$data = selects('*', 'permohonan', array('selesai' => 1));
		
		$skt_masuk = 0;
		if($data != NULL){ 
			foreach ($data as  $permohonan){							
				if ($permohonan->jenis_permohonan == 'SKT Baru' || $permohonan->jenis_permohonan == 'Perpanjangan SKT') {
					$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
					if($select_last_disposisi_by_id_permohonan != NULL){
						if ($select_last_disposisi_by_id_permohonan->status_progress == $where && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
								$skt_masuk++;
						} 
					}
				}
			}
		}
		
		$skt_naik = 0;
		if($data != NULL){ 
			foreach ($data as  $permohonan){							
				if ($permohonan->jenis_permohonan == 'SKT Baru' || $permohonan->jenis_permohonan == 'Perpanjangan SKT') {
					$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
					if($select_last_disposisi_by_id_permohonan != NULL){
						if ($select_last_disposisi_by_id_permohonan->status_progress == $where_naik && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
								$skt_naik++;
						} 
					}
				}
			}
		}
		
		$skp_masuk = 0;
		if($data != NULL){ 
			foreach ($data as  $permohonan){							
				if ($permohonan->jenis_permohonan == 'SK Penunjukkan Baru' || $permohonan->jenis_permohonan == 'Perpanjangan SK Penunjukkan') {
					$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
					if($select_last_disposisi_by_id_permohonan != NULL){
						if ($select_last_disposisi_by_id_permohonan->status_progress == $where && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
								$skp_masuk++;
						} 
					}
				}
			}
		}
		
		$skp_naik = 0;
		if($data != NULL){ 
			foreach ($data as  $permohonan){							
				if ($permohonan->jenis_permohonan == 'SK Penunjukkan Baru' || $permohonan->jenis_permohonan == 'Perpanjangan SK Penunjukkan') {
					$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
					if($select_last_disposisi_by_id_permohonan != NULL){
						if ($select_last_disposisi_by_id_permohonan->status_progress == $where_naik && $select_last_disposisi_by_id_permohonan->user_tujuan == $this->session->userdata('id_user')) {
								$skp_naik++;
						} 
					}
				}
			}
		}

		$revisi= 0;
		if($data != NULL && $where_revisi != NULL){
			foreach ($data as  $permohonan){
				$select_last_disposisi_by_id_permohonan = select('*', 'disposisi', array('id_permohonan' => $permohonan->id_permohonan), array('id_disposisi', 'DESC'));
				if($select_last_disposisi_by_id_permohonan != NULL){
					if($level != 8){
						if ($select_last_disposisi_by_id_permohonan->status_progress == $where_revisi) {
								 $revisi++;
						} 
					}else{
						if ($select_last_disposisi_by_id_permohonan->status_progress == 3 || $select_last_disposisi_by_id_permohonan->status_progress == 10 || $select_last_disposisi_by_id_permohonan->status_progress == 102) {
								 $revisi++;
						} 
					}
				}
			}
		}
		
		$register = $this->model->selects('*', 'registrasi');
		$all_skt = count(selects('*', 'permohonan', array('selesai' => 1, 'jenis_permohonan' => 'SKT Baru'), array('jenis_permohonan' => 'Perpanjangan SKT')));
		$all_skp = count(selects('*', 'permohonan', array('selesai' => 1, 'jenis_permohonan' => 'SK Penunjukkan Baru'), array('jenis_permohonan' => 'Perpanjangan SK Penunjukkan')));
		$total_register = count($register);
			$output = '<div class="tablesection" style="width:250px;font-family:sans-serif;font-size:14px;text-shadow:#666;margin:0px 20px 0px 20px!important;border: #fff 2px solid;border-radius: 3px 30px;box-shadow: 7px 4px 13px #888888;padding:20px"><b>Jumlah Pengajuan ('.date('d M Y').') </b><hr style="margin:7px 0px 7px 0px;width:230px; border-bottom:1px dashed #888"/>
			<table>';
		if($level == 2 || $level == 8){
			$output .= '<tr><td style="padding-right:15px">Register Baru</td><td style="padding-right:15px">:</td><td>'.$total_register.'</td><tr>';
		}
		if($level != 8){
			$output .= '<tr><td style="padding-right:15px">Pengajuan SKT</td><td style="padding-right:15px">:</td><td>'.$skt_masuk.'</td><tr>
			<tr><td style="padding-right:15px">Pengajuan SK Penunjukkan</td><td style="padding-right:15px">:</td><td>'.$skp_masuk.'</td><tr>';
		}else{
			$output .= '<tr><td style="padding-right:15px">Pengajuan SKT</td><td style="padding-right:15px">:</td><td>'.$all_skt.'</td><tr>
			<tr><td style="padding-right:15px">Pengajuan SK Penunjukkan</td><td style="padding-right:15px">:</td><td>'.$all_skp.'</td><tr>';
		}
		if($level != 2 && $level != 8){
			$output .= '<tr><td style="padding-right:15px">Pengajuan SKT Disetujui</td><td style="padding-right:15px">:</td><td>'.$skt_naik.'</td><tr>
			<tr><td style="padding-right:15px">Pengajuan SK Penunjukkan Disetujui</td><td style="padding-right:15px">:</td><td>'.$skp_naik.'</td><tr>';
		}
		if($level != 3 && $level != 4){
			$output .= '<tr><td style="padding-right:15px">Pengajuan Sedang Direvisi</td><td>:</td><td>'.$revisi.'</td><tr>';
		}		
			$output .= '</table></div>';

		return $output;
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
	
	
}