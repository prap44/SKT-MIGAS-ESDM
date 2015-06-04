<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_skp extends CI_Controller
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
                $this->load->view('level1/skp_tabel');
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
	}

