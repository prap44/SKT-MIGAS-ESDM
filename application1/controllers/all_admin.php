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
            redirect('umum/logout');
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
        $c->unset_delete();
        $c->unset_edit();
        $c->unset_print();
        $c->unset_export();
        $c->field_type('id_user', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('alamat', 'text');
        $c->required_fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website');
        $c->fields('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'alamat', 'kota', 'provinsi', 'website', 'deskripsi_perusahaan');
        $c->columns('nama_perusahaan', 'direktur_utama', 'contact_person', 'email', 'kota', 'provinsi');
		$c->set_relation('kota', 'ref_kota', 'kota');
		$c->set_relation('provinsi', 'ref_provinsi', 'provinsi');
        $output = $c->render();
        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 8 OR $level == 2) {
                $this->load->view('level' . $level . '/list_member', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.2 FUNGSI DETAIL_PERUSAHAAN


    /*Pusing muyeng-muyeng*/
    public function detail_evaluasi($param, $id_permohonan)
    {
        $evaluator = $this->model->selects('*', 'rekapitulasi', array('penilai_level' => 6, 'id_permohonan' => $id_permohonan));
        $konsep_skt = $this->model->select('*', 'temp_skt', array('id_permohonan' => $id_permohonan));      
        $dok_pendukung = $this->model->select('*', 'dokumen_pendukung_pjit', array('id_permohonan' => $id_permohonan));		

        $data = array(
            'evaluator' => $evaluator,
            'konsep_skt' => $konsep_skt,
            'param' => $this->uri->segment(3),
			'dok_pendukung' => $dok_pendukung
        );
		
        $this->load->view('semua/view_rekap', $data);
    }

	public function save_doc_kasie($param, $id_permohonan){
		$this->db->update('temp_skt', array('konten_konsep' => $this->input->post('content_template', TRUE)), array('id_permohonan' => $id_permohonan));
		redirect('all_admin/detail_evaluasi/pengajuan_skp/'.$id_permohonan);
	}
	
	public function daftar_dokumen_siap_terbit(){
        $c = new grocery_crud();
        $c->set_table('temp_skt');
        $c->unset_operations();
		$c->where('status_disetujui', 1);
		$c->columns('id_permohonan', 'no_skt_sementara', 'tanggal_disetujui');
		$c->display_as('id_permohonan', 'Perusahaan | Bidang & Sub Bidang Usaha');
		$c->display_as('no_skt_sementara', 'No SKT');
		$c->display_as('tanggal_disetujui', 'Disetujui');
		$c->callback_column('id_permohonan', function ($value, $row){
				$permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $value));
				$biodata = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
				$ref_bidang = $this->model->select('*', 'ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
				$ref_sub_bidang = $this->model->select('*', 'ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang));
				return $biodata->nama_perusahaan.' | '.$ref_bidang->bidang_usaha.', '.$ref_sub_bidang->sub_bidang.'&nbsp;&nbsp;&nbsp;';
        });	
		$c->add_action('Prosess', 'sR', base_url('all_admin/proses_dokumen/add') . '/');
		
        $output = $c->render();

        $this->logs();
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } else {
            redirect('umum/logout');
        }
		
	
	}
	
	
	
    public function proses_dokumen(){
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');
        $temp_skt = $this->model->select('*', 'temp_skt', array('id_temp_skt' => $this->uri->segment(4)));
		$masa_berlaku = explode('-', $temp_skt->tanggal_disetujui);
        $c = new grocery_crud();
        $c->set_table('dt_dokumen_skt');
        $c->fields('file_dokumen', 'id_permohonan', 'no_dokumen', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'id_perusahaan', 'print_dokumen');
        $c->required_fields('file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/file_skt');
		
		$c->display_as('file_dokumen', 'Upload Dokumen');
		$c->display_as('print_dokumen', 'Print Dokumen');
		$c->callback_after_insert(array($this, 'update_dokumen_skt'));
		//$c->callback_field('file_dokumen', array($this, 'callback_file_dokumen'));
		$c->callback_field('print_dokumen', array($this, 'callback_print_dokumen'));
        
        $c->field_type('id_permohonan', 'hidden', $temp_skt->id_permohonan);
        $c->field_type('no_dokumen', 'hidden', $temp_skt->no_skt_sementara);
        $c->field_type('mulai_masa_berlaku', 'hidden', implode('-', $masa_berlaku));
		$masa_berlaku[0] = $masa_berlaku[0]+3;
        $c->field_type('akhir_masa_berlaku', 'hidden', implode('-', $masa_berlaku));
		
        $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $temp_skt->id_permohonan));
        if($permohonan != NULL){
            $id_per = $permohonan->id_perusahaan;
        }else {
            $id_per = '';
        }
		
        $c->field_type('id_perusahaan', 'hidden', $id_per);

        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
			$id_permo = $this->model->select('*', 'dt_dokumen_skt', array('id_dokumen' => $this->uri->segment(4)));
			$temp_skt = $this->model->select('*', 'temp_skt', array('id_permohonan' => $id_permo->id_permohonan));
			$permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id_permo->id_permohonan));
			$biodata_perusahaan = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
			$this->db->update('temp_skt', array('status_disetujui' => 2), array('id_temp_skt' => $temp_skt->id_temp_skt));
			$subject = 'Pengajuan SKT Disetujui';
			$message = 'Selamat, pengajuan SKT online Anda telah disetujui dan dapat Anda unduh melalui link di bawah ini:<br/>';
			$message .= base_url().'assets/uploads/file_skt/'.$id_permo->file_dokumen;
			$message .= '<br/><br/>Terima Kasih.<br/><br/>Hormat Kami,<br/><br/>Direktorat Jenderal Minyak dan Gas Bumi';
			$message .= '<br/><br/><b>*Mohon untuk tidak membalas email ini.</b>';
			$send_email = $this->send_mail($biodata_perusahaan->email, $subject, $message);
            redirect('all_admin/detail_perusahaan/print_dokumen/'.$id_permo->id_permohonan);
        }

        $level_user = $this->session->userdata('level');
        $this->load->view('level7/cetak_dokumen', $output);

        // $c = new grocery_crud();
        // $c->set_table('ref_template_skt');
        // $output = $c->render();
        // $this->load->view('level2/konsep_dokumen_skt', $output);

    }
	
	public function update_dokumen_skt($post_array, $primary_key){
		$data = array(
			'id_perusahaan' => $post_array['id_perusahaan'],
			'file_dokumen' => $post_array['file_dokumen'] ,			
			'no_dokumen' => $post_array['no_dokumen'],
			'id_permohonan' => $post_array['id_permohonan'],
			'status' => 1,
			'mulai_masa_berlaku' => $post_array['mulai_masa_berlaku'],
			'akhir_masa_berlaku' => $post_array['akhir_masa_berlaku']
			
		);
		$this->db->insert('dokumen_skt', $data);
        return TRUE;
	}
	
	public function callback_print_dokumen($value='', $primary_key = ''){
		$temp = $this->model->select('*', 'temp_skt', array('id_temp_skt' => $this->uri->segment(4)));
		return '<span style="color:red; font-size:12px">*) Print dokumen terlebih dahulu untuk kemudian di upload kan</span><br/><br/><textarea name="content_template" id="content_template" class="ckeditor">'.$temp->konten_konsep.'</textarea>';
	}
	
//***************************************************************************************************************************
// 3.4 FUNGSI SEND_MAIL

    public function send_mail($to, $subject, $message)
    {
        $config = Array(
			/* 'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'ycared@gmail.com', // change it to yours
            'smtp_pass' => '', // change it to yours */
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
        $this->email->from('no-reply@migas.esdm.go.id', 'Ditjen MIGAS'); // change it to yours
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
	
	public function print_dokumen(){
		$config['upload_path'] = './assets/uploads/file_skt';
		$config['file_name'] = '';
		$config['allowed_types'] = 'pdf';
        $config['max_size'] = '10000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
			$data['error'] = $this->upload->display_errors();
			echo 'gagal';
        } else {
            $datax = array('upload_data' => $this->upload->data());
            $data = array(
                'file_dokumen' => $datax['upload_data']['file_name']
           );
		   $insert = insert('dokumen_skt', $data);
		   $insert = insert('dt_dokumen_skt', $data);
		   if($insert){
				echo 'Berhasil!';
		   }
		}
	}
	
	public function tu_print_laporan(){
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');
		$c = new grocery_crud();
        $c->set_table('pelaporan_periodik');
        $c->fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan');
        $c->set_field_upload('file_pelaporan_periodik', 'assets/uploads/file_pelaporan_periodik');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/detail_perusahaan")));
		$c->unset_delete();
		$c->unset_edit();
		$c->unset_read();
		$c->unset_export();
		$c->unset_print();
		
        $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $this->session->userdata('temporary')));
		
		$c->display_as('file_pelaporan_periodik', 'Upload Laporan');
		
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('temporary'));
        $c->field_type('semester', 'hidden', 'Laporan ke 0');
        $c->field_type('id_perusahaan', 'hidden', $permohonan->id_perusahaan);
		
		$output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->detail_perusahaan($output);
        } else {
            return $output;
        }
	}
	
    public function set_temporary(){
		$aksi = $this->session->set_userdata('temporary', '');
	}
	
	public function detail_perusahaan($param, $id_permohonan){
		//echo 'ya'.$this->session->userdata('temporary');
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
			'permohonan' => $id_permohonan,
        );
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
				if($param == 'laporan_periodik'){
					$this->load->view('level1/view_laporan_berkala', $detail_data);
				}elseif($param == 'print'){
					$this->load->view('level1/print', $detail_data);
				}else{
					$this->load->view('level1/view_detail', $detail_data);
				}
            } else {
                if($level == 6){
					if($param == 'revisi_skt' || $param == 'revisi_skp'){
						//$this->load->view('semua/view_detail_revisi', $detail_data);
						$this->load->view('level'.$level.'/view_detail', $detail_data);
					}elseif($param == 'laporan_berkala_evaluator'){
						$this->load->view('semua/view_laporan_berkala', $detail_data);
					}else{
						$this->load->view('level6/view_detail', $detail_data);
					}
				}elseif($level == 2 && $param == 'revisi_skt' || $level == 2 && $param == 'revisi_skp'){
					//$this->load->view('semua/view_detail_revisi', $detail_data);
					$this->load->view('level'.$level.'/view_detail', $detail_data);
				}elseif($level == 7){
					$this->config->load('grocery_crud');
					$this->config->set_item('grocery_crud_dialog_forms', true);
					$this->config->set_item('grocery_crud_default_per_page', 10);
					$this->session->set_userdata('temporary', $id_permohonan);
					$output1 = $this->tu_print_laporan();
					$js_files =  $output1->js_files;
					$css_files =  $output1->css_files;
					$output = $output1->output;
					 $this->load->view('level7/view_detail_print', (object)array(
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
						'permohonan' => $id_permohonan,
						'js_files' => $js_files,
						'css_files' => $css_files,
						'output' => $output
					));
				
				}elseif($level == 5 && $param == 'laporan_berkala_kasie'){
					$this->load->view('semua/view_laporan_berkala', $detail_data);
				}else{
					$this->load->view('level'.$level.'/view_detail', $detail_data);
				}
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
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms', true);
		$this->config->set_item('grocery_crud_default_per_page', 10);
        $c->set_table('registrasi');
        $c->unset_add();
        $c->unset_edit();
        // $c->unset_print();
        // $c->unset_export();
        $c->unset_delete();

        // unset
        $c->unset_columns('contact_person', 'kota', 'provinsi', 'web', 'website', 'siup', 'file_surat_pernyataan');

        $c->columns('nama_perusahaan', 'direktur_utama', 'website', 'npwp', 'file_surat_ket_domisili', 'siup', 'file_siup', 'file_surat_pernyataan', 'surat_ket_domisili', 'tanggal_member');
        $c->set_rules('email', 'Email', 'required|valid_email');
        $c->unset_fields('tanggal_member', 'status', 'pengajuan');
        $c->add_action('Setuju', base_url('assets/images/success.png'), base_url('admin/regis_setuju/') . '/');
        $c->add_action('Tolak', base_url('assets/images/close.png'), base_url('admin/regis_tolak/') . '/');
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

    public function histori_disposisi($param, $id_permohonan){
       $c = new grocery_crud();
        $c->set_table('disposisi');
        $c->unset_add();
        $c->unset_edit();
        $c->unset_print();
        $c->unset_export();
        $c->unset_delete();
        $c->columns('user_asal', 'user_tujuan', 'tanggal_masuk', 'catatan_user_asal');
        $c->fields('user_asal', 'user_tujuan', 'tanggal_masuk', 'catatan_user_asal');

		$c->display_as('user_asal', 'Dari');
		$c->display_as('user_tujuan', 'Untuk');
		$c->display_as('catatan_user_asal', 'Catatan');
		
		$permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id_permohonan));
		$user = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
		$c->where('id_permohonan', $id_permohonan);
		//$c->where('user_asal !=', $user->id_user);
        //$c->set_relation('user_asal', 'users', 'nama_lengkap');
        //$c->set_relation('user_tujuan', 'users', 'nama_lengkap');
		$c->callback_column('user_asal', function ($value, $row){
				$user = $this->model->select('*', 'users', array('id_user' => $value));
				if($user->level == 1){
					$biodata = $this->model->select('*', 'biodata_perusahaan', array('id_user' => $value));
					return $biodata->nama_perusahaan;
				}else{
					return $user->nama_lengkap;
				}
        });	
		$c->callback_column('user_tujuan', function ($value, $row){
				$user = $this->model->select('*', 'users', array('id_user' => $value));
				if($user->level == 1){
					$biodata = $this->model->select('*', 'biodata_perusahaan', array('id_user' => $value));
					return $biodata->nama_perusahaan;
				}else{
					return $user->nama_lengkap;
				}
        });	
        $output = $c->render();
        $this->logs();

		$this->logs();
        $level = $this->session->userdata('level');
        if ($level != 1) {
            $this->load->view('semua/view_histori', $output);
        } else {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 3.5 FUNGSI REKAPITULASI

    public function rekapitulasi($id_perusahaan, $bahan_penilaian)
    {
        $this->load->library('form_validation');
		
		$this->form_validation->set_rules('catatan_penilaian', 'catatan_penilaian', 'required');
		
        if ($this->form_validation->run() == FALSE) {
            redirect(base_url('all_admin/detail_perusahaan/'.$this->input->post('link', TRUE).'/' .  $this->input->post('id_permohonan', TRUE)));
            // echo "<string>alert('Lengkapi Data!')</script>";
            echo "<script>window.history.back()</script>";
        } else {
            $data = array(
                'id_perusahaan' => $id_perusahaan,
                'penilai' => $this->session->userdata('id_user'),
                'penilai_level' => $this->session->userdata('level'),
                'id_permohonan' => $this->input->post('id_permohonan', TRUE),
                'bahan_penilaian' => urldecode($bahan_penilaian),
                'catatan_penilaian' => $this->input->post('catatan_penilaian', TRUE),
            );
			$catatan = $this->model->select('*', 'rekapitulasi', array('id_perusahaan' => $id_perusahaan, 'bahan_penilaian' => urldecode($bahan_penilaian), 'id_permohonan' => $this->input->post('id_permohonan', TRUE), 'penilai' => $this->session->userdata('id_user')));
			if($catatan){
				$this->db->update('rekapitulasi', $data, array('id_perusahaan' => $id_perusahaan, 'bahan_penilaian' => urldecode($bahan_penilaian), 'id_permohonan' => $this->input->post('id_permohonan', TRUE), 'penilai' => $this->session->userdata('id_user')));			
			}else{
				$this->db->insert('rekapitulasi', $data);			
			}
            if ($this->db->affected_rows() > 0) {
                 // echo "<script>alert('Berhasil disimpan')</script>"; 
                $this->session->set_flashdata('message', 'Berhasil disimpan');
                // echo "<script>window.history.back()</script>";
                redirect('all_admin/detail_perusahaan/'.$this->input->post('link', TRUE).'/' .  $this->input->post('id_permohonan', TRUE));
            }
        }
    }


//***************************************************************************************************************************
// 3.8 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN)

    function daftar_pengajuan_skt(){ 
       $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
       view('semua/view_daftar_skt', array('data' => $select_permohonan));
    }

//***************************************************************************************************************************
// 3.6 FUNGSI DAFTAR_PENGAJUAN_SKP_BARU	(Anak Multigrid FUNGSI DAFTAR_PENGAJUAN_SKP)

    function daftar_pengajuan_skp(){ 
       $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
       view('semua/view_daftar_skp', array('data' => $select_permohonan));
    }


//***************************************************************************************************************************
// 3.12 FUNGSI DAFTAR_PENGAJUAN_SKT_ADMIN_NAIK	(Induk FUNGSI DAFTAR_PENGAJUAN_SKT_BARU_ADMIN_NAIK & FUNGSI DAFTAR_PENGAJUAN_SKT_PERPANJANGAN_ADMIN_NAIK)

    function daftar_pengajuan_skt_admin_naik(){
        $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('semua/view_daftar_skt_disetujui', array('data' => $select_permohonan));
    }    
	
	function daftar_pengajuan_skp_admin_naik(){
        $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('semua/view_daftar_skp_disetujui', array('data' => $select_permohonan));
    }

    /*end pusing muyeng-munyeng*/
//***************************************************************************************************************************
// 3.13 FUNGSI PENGAJUAN_SKT_DITERIMA

/*     public function pengajuan_revisi_diterima(){
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();
        // echo $this->uri->segment(4);
        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)), array('id_disposisi', 'desc'));
        // echo $last_disposisi->id_perusahaan;
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
			$c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));

        $levels = $this->session->userdata('level');
        $c->display_as('id_perusahaan', 'nama perusahaan');

        //lagi di level 2 didisposisi ke level 4

        if ($levels == 5) {
            $level = 6;
            $c->field_type('status_progress', 'hidden', '102');
        } elseif ($levels == 6) {
            $level = 5;
            $c->field_type('status_progress', 'hidden', '9');
        }  elseif ($levels == 2) {
            $level = 3;
            $c->field_type('status_progress', 'hidden', '4');
        } 

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
                redirect(base_url('all_admin/daftar_revisi_evaluator'));
            } elseif ($level_user == 5) {
                redirect(base_url('all_admin/daftar_revisi_kasie'));
            } elseif ($level_user == 2) {
                redirect(base_url('all_admin/daftar_revisi_admin'));
            }
        }
    
        if ($level_user != NULL) {
            $this->load->view('level' . $level_user . '/view_list', $output);
        } elseif ($level_user == NULL) {
            redirect('umum/logout');
        }
    } */
	
    public function pengajuan_skt_diterima(){
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();
        // echo $this->uri->segment(4);
        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)), array('id_disposisi', 'desc'));
        // echo $last_disposisi->id_perusahaan;
        
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));

        $levels = $this->session->userdata('level');
        $c->display_as('id_perusahaan', 'nama perusahaan');
        $c->display_as('catatan_user_asal', 'Disposisi');


        $c->required_fields('user_tujuan', 'id_perusahaan');
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

        $c->set_relation('user_tujuan', 'users', 'nama_lengkap', array('level' => $level));
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan');
        // $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima'));

        // $c->required_fields('user_tujuan', 'id_perusahaan');
        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        $level_user = $this->session->userdata('level');
        if ($state == 'success') {
            
            $lv4 = select('*', 'permohonan', array('id_permohonan' => $this->uri->segment(4)));


            

            if ($level_user == 6) {
                redirect('all_admin/konsep_dokumen_skt/add/' . $last_disposisi->id_perusahaan);
            } elseif ($lv4->jenis_permohonan == 'Perpanjangan SK Penunjukkan') {
                redirect('all_admin/konsep_dokumen_skp/add/' . $last_disposisi->id_perusahaan);
            } else {
                redirect(base_url('all_admin/daftar_pengajuan_skt'));
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

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)), array('id_disposisi', 'desc'));

        $level = $this->session->userdata('level');

        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));
        // /$c->field_type('catatan', 'text');

        $c->display_as('catatan_user_asal', 'Disposisi');
        $c->display_as('id_perusahaan', 'nama perusahaan');
		$c->required_fields('user_tujuan', 'id_perusahaan');
        //lagi di level 2 didisposisi ke level 4

        if ($level == 6) {
            $c->field_type('status_progress', 'hidden', '9');
            $tujuan = 5;
        } elseif ($level == 5) {
            $c->field_type('status_progress', 'hidden', '11');
            $tujuan = 4;
        } elseif ($level == 4) {
            $c->field_type('status_progress', 'hidden', '12');
            $tujuan = 3;
        } elseif ($level == 3) {
            $c->field_type('status_progress', 'hidden', '13');
            $tujuan = 7;
        }

        
        $c->set_relation('user_tujuan', 'users', 'nama_lengkap', array('level' => $tujuan));
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan');


        // if ($level == 3) {
        //     $c->callback_after_insert(array($this, 'update_permohonan_dokumen_skt'));
        // }
        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima_naik'));


        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
           // $level = $this->session->userdata('level');
            if ($level == 6) {
                redirect('all_admin/daftar_pengajuan_skt');
            }elseif ($level == 5) {
				redirect(site_url('all_admin/daftar_pengajuan_skt_admin_naik'));
			} elseif ($level == 7) {
                $dokumenskt = select('*', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));
                $id_dokumenskt = select('*', 'dokumen_skt', array('id_permohonan' => $dokumenskt->id_permohonan));

                redirect('all_admin/no_dokumen_skt/edit/' . $id_dokumenskt->id_dokumen);
            } else {
                redirect(base_url('all_admin/daftar_pengajuan_skt_admin_naik'));    
            }
        }

       // $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }


        public function konsep_dokumen_skp($da = NULL, $id = NULL){
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');
        $dap = $this->model->select('*', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));
        $c = new grocery_crud();
        $c->set_table('dokumen_pendukung_pjit');
        $c->fields('berita_acara_presentasi', 'berita_acara_visitasi', 'id_perusahaan', 'id_permohonan');
        $c->required_fields('berita_acara_presentasi', 'berita_acara_visitasi');
        $c->set_field_upload('berita_acara_presentasi', 'assets/uploads/file_skp');
        $c->set_field_upload('berita_acara_visitasi', 'assets/uploads/file_skp');

        
        
        $c->field_type('id_permohonan', 'hidden', $dap->id_permohonan);
       
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
            redirect(site_url('all_admin/daftar_pengajuan_skp'));
        }elseif ($state == 'list') {
            redirect(site_url('all_users/dashboard'));
        }

        $level_user = $this->session->userdata('level');
        $this->load->view('level' . $level_user . '/view_list', $output);

    }

    public function no_dokumen_skt($da = NULL, $id = NULL)
    {
        // echo $da;

        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');

        $id_per = $this->model->select('id_perusahaan', 'disposisi', array('id_disposisi' => $id));
        $c = new grocery_crud();
        $c->set_table('dokumen_skt');
        // $c->unset_fields('no_dokumen', 'deskripsi', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'id_permohonan', 'status');
        $c->unset_fields('status', 'id_permohonan', 'file1', 'file2');
        $c->set_field_upload('file_dokumen','assets/uploads/file_skt');

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

        $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('semua/view_daftar_revisi', array('data' => $select_permohonan));


    }

         public function daftar_revisi_kasie()
    {
                $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('semua/view_daftar_revisi', array('data' => $select_permohonan));
    }
	
    public function daftar_revisi_evaluator(){
        $select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
        view('semua/view_daftar_revisi', array('data' => $select_permohonan));
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
	
	/* 	public function update_pengajuan_skt_diterima($post_array, $primary_key)
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
    } */


    public function update_pengajuan_skt_diterima_naik($post_array, $primary_key){

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

        //$disposisi = $this->model->select('*', 'disposisi', array('id_disposisi' => $post_array['id_parent']));
        
        $data = array(
            'id_permohonan' => $post_array['id_permohonan']
            // 'tanggal_disetujui' => date('Y-m-d H:i:s')
        );
       // $this->db->update('disposisi', $data, array('id_perusahaan' => $post_array['id_perusahaan']));

        if ($level == 3) {		
			$dok_skt = selects('*', 'dt_dokumen_skt');
			$last_id = 0;
			foreach($dok_skt as $key => $dok){
				$last_skt_id = explode('/', $dok->no_dokumen);
				if($last_skt_id[3] == date('Y')){
					$last_id = $last_skt_id[0]+1;
				}
			}
			
			/* Get Last SKT ID */
			if($last_id <= 99){
				if($last_id <= 9){
					$last_id = '00'.$last_id;
				}else{
					$last_id = '0'.$last_id;
				}
			}
			
			/* Change SKT ID in Temp_skt to The New One */
			$data_print = $this->model->select('*', 'temp_skt', array('id_permohonan' => $post_array['id_permohonan']));
			
			$ex_dp = explode('/', $data_print->no_skt_sementara);
			$ex_dp[0] = $last_id;
			$im_dp = implode('/', $ex_dp);
			
			/* Change SKT ID */
			$after_update1 = str_replace($data_print->no_skt_sementara,$im_dp, $data_print->konten_konsep);
			
			/* Get Bidang And Sub Bidang Usaha SKT to Generate a Serial Number */
			$permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $post_array['id_permohonan']));
			if($permohonan->sub_bidang <= 9){
				$permohonan->sub_bidang = '0'.$permohonan->sub_bidang;
			}
			
			/* Generate SKT Serial Number */
			$serial = 'DMT'.date('y').''.$permohonan->sub_bidang.''.date('m').''.$permohonan->bidang_usaha.''.date('d').''.$last_id;
			
			/* Object to Change */
			$objek = '[(serial_number)]';
			
			/* Change SKT Content and Apply the Serial Number */
			$after_update2 = str_replace($objek, $serial, $after_update1);
			
			/* Update Permohonan and Temp_skt Tables to Initialize Direktur Permission of Final SKT Document */
            $this->db->update('permohonan', array('selesai' => 2), array('id_permohonan' => $post_array['id_permohonan']));
            $this->db->update('temp_skt', array('status_disetujui' => 1, 'tanggal_disetujui' => date('Y-m-d'), 'no_skt_sementara' => $im_dp, 'konten_konsep' => $after_update2), array('id_permohonan' => $post_array['id_permohonan']));
			
			/* Serial Number to Insert */
			$sn = array(
				'serial_num' => $this->encrypt->encode($serial),
				'no_skt' => $im_dp,
				'id_permohonan' => $post_array['id_permohonan'],
				'id_perusahaan' => $permohonan->id_perusahaan
				);
			
			/* Checking Serial Number for This SKT */
			$recs = $this->model->select('*', 'verifikasi', array('id_permohonan' => $post_array['id_permohonan']));
			if($recs == NULL){
				$this->db->insert('verifikasi', $sn);
			}
        }

        return TRUE;
    }

	
	public function daftar_semua_pengajuan_skt($value=''){
		$select_permohonan = selects('*', 'permohonan', array('selesai' => 1));
		view('test/test', array('data' => $select_permohonan));
	}
	
	    public function pengajuan_skp_diterima(){
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');
        //$c->unset_delete();
        $c->unset_edit();
        // echo $this->uri->segment(4);
        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)), array('id_disposisi', 'desc'));
        // echo $last_disposisi->id_perusahaan;
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));

        $levels = $this->session->userdata('level');
        $c->display_as('id_perusahaan', 'nama perusahaan');
        $c->display_as('catatan_user_asal', 'Disposisi');

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

        // $l4 = $this->model->selects('*', 'users', array('level' => $level));
		$c->set_relation('user_tujuan', 'users', 'nama_lengkap', array('level' => $level));
        // foreach ($l4 as $key => $l4o) {
        //     # code...
        //     $ListUser = array($l4o->id_user => $l4o->nama_lengkap);
        // }

        // $c->field_type('user_tujuan', 'dropdown', $ListUser);
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));
        $c->unset_fields('tanggal_masuk', 'tanggal_selesai', 'nilai', 'catatan');
        
        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        $level_user = $this->session->userdata('level');
        if ($state == 'success') {
            if ($level_user == 6) {
                redirect('all_admin/konsep_dokumen_skp/add/' . $last_disposisi->id_perusahaan);
            } else {
                redirect(base_url('all_admin/daftar_pengajuan_skp'));
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

    public function pengajuan_skp_diterima_naik()
    {
        // merubah status_user menjadi dokumen lengkap

        $c = new grocery_crud();
        $c->set_table('disposisi');

        //$c->unset_delete();
        $c->unset_edit();

        $last_disposisi = $this->model->select('*', 'disposisi', array('id_permohonan' => $this->uri->segment(4)), array('id_disposisi', 'desc'));

        $level = $this->session->userdata('level');
        $c->fields('user_asal', 'id_parent', 'status_progress', 'id_perusahaan', 'user_tujuan', 'id_permohonan', 'catatan_user_asal');

        $c->field_type('id_permohonan', 'hidden', $this->uri->segment(4));
        $c->field_type('user_asal', 'hidden', $this->session->userdata('id_user'));
        $c->field_type('id_parent', 'hidden', $last_disposisi->id_disposisi);
        // /$c->field_type('catatan', 'text');
        $c->required_fields('user_tujuan', 'id_perusahaan');

        $c->display_as('catatan_user_asal', 'Catatan');
        $c->display_as('id_perusahaan', 'nama perusahaan');

        //lagi di level 2 didisposisi ke level 4

        if ($level == 6) {
            $c->field_type('status_progress', 'hidden', '9');
            $tujuan = 5;
        } elseif ($level == 5) {
            $c->field_type('status_progress', 'hidden', '11');
            $tujuan = 4;
        } elseif ($level == 4) {
            $c->field_type('status_progress', 'hidden', '12');
            $tujuan = 3;
        } elseif ($level == 3) {
            $c->field_type('status_progress', 'hidden', '13');
            $tujuan = 7;
        }

        $c->set_relation('user_tujuan', 'users', 'nama_lengkap', array('level' => $tujuan));
        $c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan', array('id_perusahaan' => $last_disposisi->id_perusahaan));

        $c->callback_after_insert(array($this, 'update_pengajuan_skt_diterima_naik'));


        $output = $c->render();
        $state = $c->getState();
        $state_info = $c->getStateInfo();

        if ($state == 'success') {
            $level = $this->session->userdata('level');
            if ($level == 6) {
				$dok_pendukung = $this->model->select('*', 'dokumen_pendukung_pjit', array('id_permohonan' => ''));
                redirect('all_admin/konsep_dokumen_skp/add/' . $this->uri->segment(4));
            } elseif ($level == 5){
				redirect(site_url('all_admin/daftar_pengajuan_skp_admin_naik'));
			}elseif ($level == 7) {
                $dokumenskt = select('*', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));
                $id_dokumenskt = select('*', 'dokumen_skt', array('id_permohonan' => $dokumenskt->id_permohonan));

                redirect('all_admin/no_dokumen_skp/edit/' . $id_dokumenskt->id_dokumen);
            } else {
                redirect(base_url('all_admin/daftar_pengajuan_skp_admin_naik'));    
            }
        }

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            $this->load->view('level' . $level . '/view_list', $output);
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }


    // public function konsep_dokumen_skp($da = NULL, $id = NULL){
    //     $this->load->config('grocery_crud');
    //     $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');
    //     $dap = $this->model->select('*', 'disposisi', array('id_disposisi' => $this->uri->segment(4)));
    //     $c = new grocery_crud();
    //     $c->set_table('dokumen_skt');
    //     $c->unset_fields('no_dokumen', 'deskripsi', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'status');
    //     $c->set_field_upload('file_dokumen', 'assets/uploads/file_skt');
        
    //     $c->field_type('id_permohonan', 'hidden', $dap->id_permohonan);
       
    //     if($dap != NULL){
    //         $dap = $dap->id_perusahaan;
    //     }else {
    //         $dap = '';
    //     }
    //     $c->field_type('id_perusahaan', 'hidden', $dap);

    //     $output = $c->render();
    //     $state = $c->getState();
    //     $state_info = $c->getStateInfo();

    //     if ($state == 'success') {
    //         redirect(site_url('all_admin/daftar_pengajuan_skp'));
    //     }

    //     $level_user = $this->session->userdata('level');
    //     $this->load->view('level' . $level_user . '/view_list', $output);

    // }

    public function no_dokumen_skp($da = NULL, $id = NULL)
    {
        // echo $da;

        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf|doc|docx');

        $id_per = $this->model->select('id_perusahaan', 'disposisi', array('id_disposisi' => $id));
        $c = new grocery_crud();
        $c->set_table('dokumen_skt');
        // $c->unset_fields('no_dokumen', 'deskripsi', 'mulai_masa_berlaku', 'akhir_masa_berlaku', 'id_permohonan', 'status');
        $c->unset_fields('status', 'id_permohonan');
        $c->set_field_upload('file_dokumen','assets/uploads/file_skt');
        
		$c->set_field_upload('file1','assets/uploads/file_skt');
		$c->set_field_upload('file2','assets/uploads/file_skt');

		$c->display_as('file1', 'Berita Acara');
		$c->display_as('file2', 'Berita Acara Visitasi');
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
            redirect(site_url('all_admin/daftar_pengajuan_skp_admin_naik'));
        }

        $level_user = $this->session->userdata('level');
        $this->load->view('level' . $level_user . '/view_list', $output);

    }
	
	public function kotak_pesan(){		
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level != 1) {
				redirect('all_admin/kotak_masuk');
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
	}
	
	public function kotak_masuk(){
        $level = $this->session->userdata('level');
		
        $c = new grocery_crud();
        $c->set_table('disposisi');
		$c->unset_operations();
		$c->where('user_tujuan', $this->session->userdata('id_user'));
		$c->where('catatan_user_asal !=', 'Pengajuan telah direvisi');
		$c->where('catatan_user_asal !=', 'Revisi pengajuan');
		$c->where('catatan_user_asal !=', 'Pegajuan masuk');
		$c->where('catatan_user_asal !=', '');
		$c->columns('user_asal', 'id_perusahaan', 'tanggal_masuk', 'catatan_user_asal');
		
		$c->set_relation('user_asal', 'users', 'nama_lengkap');
		$c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan');
		
		$c->display_as('user_asal', 'Dari');
		$c->display_as('id_perusahaan', 'Pengajuan Dari');
		$c->display_as('catatan_user_asal', 'Pesan');
		
        $output = $c->render();
        if ($level != NULL) {
            if ($level != 1) {
				$this->load->view('semua/kotak_pesan', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
		
	}
	
	public function kotak_keluar(){
        $level = $this->session->userdata('level');
		
        $c = new grocery_crud();
        $c->set_table('disposisi');
		$c->unset_operations();
		$c->where('user_asal', $this->session->userdata('id_user'));
		$c->where('catatan_user_asal !=', '');
		$c->columns('user_tujuan', 'id_perusahaan', 'tanggal_masuk', 'catatan_user_asal');
		
		$c->set_relation('user_tujuan', 'users', 'nama_lengkap');
		$c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan');
		
		$c->display_as('user_tujuan', 'Untuk');
		$c->display_as('id_perusahaan', 'Pengajuan Dari');
		$c->display_as('catatan_user_asal', 'Pesan');
		
        $output = $c->render();
        if ($level != NULL) {
            if ($level != 1) {
				$this->load->view('semua/kotak_pesan', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
		
	}
	
	public function konsep_dokumen_skt($link, $id_permohonan){
        $level = $this->session->userdata('level');
		$dt = $this->pilih_template_skt($id_permohonan);
        if ($level != NULL) {
            if ($level == 6) {
				$this->load->view('level6/konsep_dokumen', $dt);
            } else {
                redirect('all_users/dashboard');
            }
        } else {
            redirect('umum/logout');
        }
	}
	
	public function simpan_konsep($back_link = NULL, $id_permohonan = ''){
	
        $level = $this->session->userdata('level');
		
		$data = array(
			'id_permohonan' => $id_permohonan,
			'konten_konsep' => $this->input->post('content_template', FALSE),
			'no_skt_sementara' => $this->input->post('no_skt_sementara', FALSE)
			);
		$ada = $this->model->select('*', 'temp_skt', array('id_permohonan' => $id_permohonan));
		if($level == 6){
			if($ada){
				$this->db->update('temp_skt', $data, array('id_permohonan' => $id_permohonan));
			}else{
				$this->db->insert('temp_skt', $data);
			}			
			redirect('all_admin/'.$back_link.'_diterima_naik/add/'.$id_permohonan);
		}else{
			if($ada){
				$this->db->update('temp_skt', $data, array('id_permohonan' => $id_permohonan));
			}else{
				$this->db->insert('temp_skt', $data);
			}			
            redirect('all_users/daftar_'.$back_link.'admin_naik');
		}
		
	}

    public function pilih_template_skt($id_permohonan){		
		setlocale(LC_ALL, 'IND');
		
		$template_skt = $this->model->selects('*', 'ref_template_skt', array('id_template_skt' => 1));
		$dok_skt = selects('*', 'dt_dokumen_skt');
		$permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $id_permohonan));
		$biodata_perusahaan = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $permohonan->id_perusahaan));
		$bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
		$sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang));
		
		$last_id = 0;
		if($dok_skt != NULL){
		foreach($dok_skt as $key => $dok){
			$last_skt_id = explode('/', $dok->no_dokumen);
			if($last_skt_id[3] == date('Y')){
				$last_id = $last_skt_id[0]+1;
			}
		}
		}
		
		if($last_id <= 99){
			if($last_id <= 9){
				$last_id = '00'.$last_id;
			}else{
				$last_id = '0'.$last_id;
			}
		}
		
		if($template_skt != NULL){
			$template_skt_obj = '';
			foreach ($template_skt as $key => $template) {
				$template_skt_obj = $template->head;
				$template_skt_obj .= $last_id.'/SKT-'.$permohonan->bidang_usaha.'/DMT/'.date('Y');
				$template_skt_obj .= $template->body1;
				$template_skt_obj .= $biodata_perusahaan->nama_perusahaan;
				$template_skt_obj .= $template->body2;
				$template_skt_obj .= $biodata_perusahaan->alamat;
				$template_skt_obj .= $template->body3;
				$template_skt_obj .= '<b>'.$bidang_usaha->bidang_usaha.'</b> subbidang <b>'.$sub_bidang->sub_bidang.'</b> dengan bagian subbidang:</p>';
				$template_skt_obj .= '<table align="center" border="1" bordercolor="#000" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">';
				
				if($permohonan->bagian_sub_bidang != NULL){
					$bagiansubbidang = explode(',', $permohonan->bagian_sub_bidang);
					foreach ($bagiansubbidang as $key => $bsb) {
						$bagian_sub_bidang = select('*','ref_bagian_sub_bidang', array('id_bagian_sub_bidang' => $bsb));
						if($permohonan->sub_bagian_sub_bidang != NULL){
							$template_skt_obj .= '<tr><td>'.$bagian_sub_bidang->bagian_sub_bidang.'</td><td>';
							$subbagiansubbidang = explode(',', $permohonan->sub_bagian_sub_bidang);
							foreach($subbagiansubbidang as $key => $sbsb){
								$id_sbsb = explode('.', $sbsb);
								//$sub_bagian_sub_bidang = select('*','ref_sub_bagian_sub_bidang', array('id_sub_bagian_sub_bidang' => $id_sbsb[0]));
								if($id_sbsb[0] == $bsb){
									//$temp2[$key] = $sub_bagian_sub_bidang->sub_bagian_sub_bidang;
									//$hsil[$key] = $id_sbsb[1][$key];
									$template_skt_obj .= $id_sbsb[1].', ';
								}
							}
							$template_skt_obj .= '</td></tr>';
						}else{
							$temp[$key] = $bagian_sub_bidang->bagian_sub_bidang;
							$template_skt_obj .= '<tr><td>'.implode(', ', $temp).'<td></tr>';
						}
					}
				}
					
				$template_skt_obj .= '';
				$template_skt_obj .= '</table>';
				$template_skt_obj .= $template->body4;
				$template_skt_obj .= $biodata_perusahaan->nama_perusahaan;
				$template_skt_obj .= $template->body5;
				$template_skt_obj .= strftime('%d %B %Y');
				$template_skt_obj .= $template->foot;
			}
		}else{
			$template_skt_obj = '';
		}

		$output = array('konsep_skt' => $template_skt_obj, 'no_skt_sementara' => $last_id.'/SKT-'.$permohonan->bidang_usaha.'/DMT/'.date('Y'));
		
        return $output;
        //$json['menu'] = '';
        // echo json_encode($json);
    }

    public function laporan_berkala_evaluator(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms', true);
		$this->config->set_item('grocery_crud_default_per_page', 10);
		$this->load->config('grocery_crud');
		$c = new grocery_crud();
        $c->set_table('pelaporan_periodik');
		$c->where('id_evaluator', $this->session->userdata('id_user'));
		$c->where('catatan_evaluator', NULL);
		$c->where('status_laporan', 1);
		$c->or_where('status_laporan', 2);
        $c->columns('id_perusahaan', 'no_skt', 'bidang_usaha', 'semester', 'file_pelaporan_periodik', 'aksi');
		$c->field_type('id_permohonan', 'hidden');		
		
		$c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan');
        $c->fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan', 'id_kasie', 'id_evaluator','catatan_evaluator');
        $c->required_fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan', 'id_kasie', 'catatan_evaluator');
		$c->edit_fields('skrip','catatan_evaluator');
		
        $c->set_field_upload('file_pelaporan_periodik', 'assets/uploads/file_pelaporan_periodik');
		$c->unset_delete();
		$c->unset_add();
		$c->unset_export();
		$c->unset_print();
		$c->unset_mytools();		
		
		$c->field_type('catatan_evaluator', 'text');
		$c->callback_field('skrip', function ($value, $row){
			 $isi = '<script>
			 $("#skrip_display_as_box").remove();
			 $("#catatan_evaluator_display_as_box").remove();
			 $("div.crud-form div.mDiv div.ftitle").remove();
			$("#save-and-go-back-button").attr("value", "Simpan");
			$("#form-button-save").remove();
			$("#cancel-button").remove();
			
			 $(".pDiv").css("border", "none");
			 $(".form-div").css("border", "none");
			 $("#skrip_input_box").css("width", "100%");
			 $("#field-catatan_evaluator").hide();
			 $("#id_evaluator_display_as_box").css({"width":"auto","margin-right":"7px"});
			 $(".ui-dialog-content").dialog( "option", "height", 480 );
			 $(".ui-dialog-content").dialog( "option", "width", 750 );
				</script><div style="width:100%;text-align: center"><h4>&laquo; Buat Catatan Evaluasi &raquo;</h4></div><hr style="margin-top:10px!important;margin-bottom:0px!important"/>';
			
			return $isi;
        });
		
		$c->callback_column('aksi', function ($value, $row){
			 return '<div style="text-align:center; margin: 0px !important"><a class="link-pilih edit-anchor edit_button" title="Buat point evaluasi" href="'.base_url().'all_admin/laporan_berkala_evaluator/edit/'.$row->id_pelaporan_periodik.'">Evaluasi</a> | <a class="link-pilih" title="Lihat detail laporan" href="'.base_url().'all_admin/detail_perusahaan/laporan_berkala_evaluator/'.$row->id_permohonan.'">Lihat detail </a>';
        });
		
		$c->callback_column('no_skt', function ($value, $row){
			 $dok_skt = $this->model->select('*', 'dokumen_skt', array('id_permohonan' => $row->id_permohonan));
			return $dok_skt->no_dokumen;
        });
		
		$c->callback_column('bidang_usaha', function ($value, $row){
			 $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $row->id_permohonan));
			 $bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
			 $sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang));	
			 return $bidang_usaha->bidang_usaha.' /<br/>'.$sub_bidang->sub_bidang;
        }); 
        
		
		$c->display_as('id_perusahaan', 'Perusahaan');
		$c->display_as('bidang_usaha', 'Bidang & Sub Bidang');
		$c->display_as('no_skt', 'No SKT');
		$c->display_as('file_pelaporan_periodik', 'File Laporan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/laporan_berkala_evaluator")));
		
		$c->set_lang_string('update_success_message',
		 'Data Anda berhasil disimpan.
		 <script type="text/javascript">
		  window.location = "'.site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__)).'";
		 </script>
		 <div style="display:none">
		 '
		);
		
		$output = $c->render();
        $this->logs();
		
		if ($c->getState() == 'read') {
            redirect('all_admin/laporan_berkala_evaluator');
        }else{
			$this->load->view('level6/laporan_berkala_evaluator',  $output);
		}
	}
	
    public function laporan_berkala_kasie(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms', true);
		$this->config->set_item('grocery_crud_default_per_page', 10);
		$this->load->config('grocery_crud');
		$c = new grocery_crud();
        $c->set_table('pelaporan_periodik');
		$c->where('id_kasie', $this->session->userdata('id_user'));
		$c->where('status_laporan', 1);
		$c->or_where('id_kasie', NULL);
		$c->or_where('status_laporan', 2);
        $c->columns('id_perusahaan', 'no_skt', 'bidang_usaha', 'semester', 'file_pelaporan_periodik', 'catatan_evaluator', 'status_laporan', 'aksi');
		$c->field_type('id_permohonan', 'hidden');
        $c->field_type('id_kasie', 'hidden', $this->session->userdata('id_user'));
		
		$c->set_relation('id_perusahaan', 'biodata_perusahaan', 'nama_perusahaan');
		$c->set_relation('id_evaluator', 'users', 'nama_lengkap', array('level' => 6));
        $c->fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan', 'id_kasie', 'id_evaluator');
        $c->required_fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan', 'id_kasie', 'id_evaluator');
		$c->edit_fields('id_evaluator','id_kasie', 'skrip');
		
        $c->set_field_upload('file_pelaporan_periodik', 'assets/uploads/file_pelaporan_periodik');
		$c->unset_delete();
		//$c->unset_edit();
		//$c->unset_read();
		$c->unset_add();
		$c->unset_export();
		$c->unset_print();
		
		$c->callback_field('skrip', function ($value, $row){
			 return '<script>
			 $("#skrip_display_as_box").remove();
			 
			 $("div.crud-form div.mDiv div.ftitle").remove();
			 $(".ptogtitle").remove();
			 $(".pDiv").css("border", "none");
			 $(".form-div").css("border", "none");
			 $(".ui-dialog-content").dialog( "option", "height", 250 );
			 $(".ui-dialog-content").dialog( "option", "width", 650 );
			 $("#save-and-go-back-button").attr("value", "Submit");
			$("#form-button-save").remove();
			$("#cancel-button").remove();
			 </script>';
        });
		
		$c->unset_mytools();
		$c->callback_column('aksi', function ($value, $row){
			 return '<div style="text-align:center; margin: 0px !important"><a class="link-pilih  edit-anchor edit_button" title="Lihat Catatan Evaluator" href="'.base_url().'all_admin/lihat_catatan_evaluator/read/'.$row->id_pelaporan_periodik.'">Detail Evaluasi</a> | <a class="link-pilih edit-anchor edit_button" title="Tugaskan evaluator" href="'.base_url().'all_admin/laporan_berkala_kasie/edit/'.$row->id_pelaporan_periodik.'">Tugaskan</a> | <a class="link-pilih" title="Lihat detail laporan" href="'.base_url().'all_admin/detail_perusahaan/laporan_berkala_kasie/'.$row->id_permohonan.'">Lihat detail </a><hr style="margin: 6px 0px 6px 0px; border: 0; border-top: 1px solid #BDC3C7;"/>
			 <a class="link-pilih" title="Revisi laporan" href="'.base_url().'all_admin/aksi_kasie/revisi/'.$row->id_pelaporan_periodik.'">Revisi</a> | <a class="link-pilih" title="Terima Laporan" href="'.base_url().'all_admin/aksi_kasie/terima/'.$row->id_pelaporan_periodik.'">Terima</a> | <a class="link-pilih" title="Tolak Laporan" href="'.base_url().'all_admin/aksi_kasie/tolak/'.$row->id_pelaporan_periodik.'">Tolak</a></div>';
        });
		
		$c->callback_column('status_laporan', function ($value, $row){
			 if($value == 1 && $row->id_evaluator != NULL && $row->catatan_evaluator == NULL){
				return '<span style="background-color:#F7CA18; color:#fff; border-radius:3px; padding: 0px 2px 0px 2px;">Belum Dievaluasi</span>';
			 }elseif($value == 1 && $row->id_evaluator != NULL && $row->catatan_evaluator != NULL){
				return '<span style="background-color:#3498DB; color:#fff; border-radius:3px; padding: 0px 2px 0px 2px;">Sudah Dievaluasi</span>';
			 }elseif($value == 2){
				return '<span style="background-color:#6C7A89; color:#fff; border-radius:3px; padding: 0px 2px 0px 2px;">Direvisi</span>';
			 }
        });
		$c->unset_columns('action', 'pilihan');
		$c->callback_column('no_skt', function ($value, $row){
			 $dok_skt = $this->model->select('*', 'dokumen_skt', array('id_permohonan' => $row->id_permohonan));
			 return $dok_skt->no_dokumen;
        });
		
		$c->callback_column('bidang_usaha', function ($value, $row){
			 $permohonan = $this->model->select('*', 'permohonan', array('id_permohonan' => $row->id_permohonan));
			 $bidang_usaha = select('*','ref_bidang_usaha', array('id_bidang_usaha' => $permohonan->bidang_usaha));
			 $sub_bidang = select('*','ref_sub_bidang', array('id_sub_bidang' => $permohonan->sub_bidang));	
			 return $bidang_usaha->bidang_usaha.' /<br/>'.$sub_bidang->sub_bidang;
        }); 
        
		
		$c->display_as('id_perusahaan', 'Perusahaan');
		$c->display_as('bidang_usaha', 'Bidang & Sub Bidang');
		$c->display_as('no_skt', 'No SKT');
		$c->display_as('file_pelaporan_periodik', 'File Laporan');
		$c->display_as('id_evaluator', 'Nama Evaluator');
		$c->display_as('catatan_evaluator', 'Hasil Evaluasi');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/laporan_berkala_kasie")));
		
		$c->set_lang_string('update_success_message',
		 'Data Anda berhasil disimpan.
		 <script type="text/javascript">
		  window.location = "'.site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__)).'";
		 </script>
		 <div style="display:none">
		 '
		);
		
		$output = $c->render();
        $this->logs();
		if ($c->getState() != 'read') {
            $c->fields('file_pelaporan_periodik', 'id_permohonan', 'semester', 'id_perusahaan');
        }
		
		if ($c->getState() != 'edit' && $c->getState() != 'list') {
            redirect('all_admin/laporan_berkala_kasie');
        }else{
			$this->load->view('level5/laporan_berkala_kasie',  $output);
		}
	}
	
    public function lihat_catatan_evaluator(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms', true);
		$this->config->set_item('grocery_crud_default_per_page', 10);
		$this->load->config('grocery_crud');
		$c = new grocery_crud();
        $c->set_table('pelaporan_periodik');
		$c->columns('catatan_evaluator', 'id_evaluator');
		$c->set_relation('id_evaluator', 'users', 'nama_lengkap');
		$c->fields('id_evaluator', 'catatan_evaluator');
		$c->display_as('id_evaluator', 'Nama Evaluator');
        //$c->field_type('id_evaluator', 'hidden');
		$c->callback_field('catatan_evaluator', function ($value, $row){
			 $isi = '<script>
			 $("#catatan_evaluator_display_as_box").remove();
			 $("div.crud-form div.mDiv div.ftitle").remove();
			 $("#cancel-button").remove();
			 $(".pDiv").css("border", "none");
			 $(".form-div").css("border", "none");
			 $("#id_evaluator_display_as_box").css({"width":"auto","margin-right":"7px"});
			 $(".ui-dialog-content").dialog( "option", "height", 430 );
			 $(".ui-dialog-content").dialog( "option", "width", 650 );';
			if($value != NULL){
				$isi .= 'CKEDITOR.replace( "content_template",
						{
							removePlugins: "toolbar",
							height : 200,
							width : 550,
							tabSpaces : 4,
							readOnly : true
						});</script><hr style="margin-top:0px!important"/>
						<textarea id="content_template" class="ckeditor">'.$value.'</textarea>';
			}else{
				$isi .= '</script><hr style="margin-top:0px!important"/><span><b>Tidak ada catatan</b></span>';
			}
			
			return $isi;
        });
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/laporan_berkala_evaluator")));
		$output = $c->render();
		if ($c->getState() != 'read') {
            redirect('all_admin/laporan_berkala_kasie');
        }else{
			$this->laporan_berkala_kasie($output);
		}
	}
	
    public function aksi_kasie($aksi='', $id)
    {
        switch ($aksi) {
            case 'revisi':
                $aksi = 2;
                break;   
            case 'terima':
                $aksi = 3;
                break;
            case 'ditolak':
                $aksi = 4;
                break;
        }
    $up_lap_berkala = array(
            'id_kasie' => $this->session->userdata('id_user'),
            'tgl_kasie' => date('Y-m-d H:i:s'),
            'status_laporan' => $aksi,
            );
        $update = update('pelaporan_periodik', $up_lap_berkala, array('id_pelaporan_periodik' => $id));

        if ($update) {
            //$this->session->set_flashdata('message', 'Berhasil');
            redirect('all_admin/laporan_berkala_kasie');
        }else {
            //$this->session->set_flashdata('message', 'Gagal');
            redirect('all_admin/laporan_berkala_kasie');
        }
       
    }
	
	public function cek_status_admin(){  
		$post = $this->input->get(NULL, TRUE);
		$config = array();
		$config["base_url"] = base_url() . "all_admin/cek_status_admin?keyword=".$post['keyword'].'&jenis_skt='.$post['jenis_skt'];
		$config["per_page"] = 10;
		if(isset($post['per_page'])){
		$page = ($post['per_page']) ? $post['per_page'] : 0;	
		}else{
		$page = 0;	
		}
		$config['page_query_string']=true;			
		
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
					
			}elseif($post['jenis_skt'] == 'nama') {
				$data["response"] = $this->model->search_like('v_dokumen_skt', 'nama_perusahaan', $post['keyword'], array('status' => 1), array($config["per_page"], $page));
				$data["total"] = $this->model->select_like('v_dokumen_skt', 'nama_perusahaan', $post['keyword'], array('status' => 1));
				
			}else{
				$data["response"] = $this->model->search_like('v_dokumen_skt', 'no_dokumen', $post['keyword'], array('status' => 1), array($config["per_page"], $page));
				$data["total"] = $this->model->select_like('v_dokumen_skt', 'no_dokumen', $post['keyword'], array('status' => 1));
			}
			
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
		$data["psn"] = '<table class="table table-bordered table-striped"><thead><th>No Dokumen</th><th>Nama Perusahaan</th><th>Mulai Berlaku</th><th>Akhir Berlaku</th><th>Dokumen</th></thead><tbody><tr><td colspan="5">Data tidak ditemukan!</td></tr></table>';  
		
		$level = $this->session->userdata('level');
		if($level != NULL){
			$this->load->view('semua/cekstatus_admin', $data);
		}else{
			redirect('all_users/dashboard');
		}
        
    }
	
}