<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class pencarian extends CI_Controller {

    public function cari_skt(){  

         $no_skt = $this->input->post('no_skt', TRUE);
         $nama_perusahaan = $this->input->post('nama_perusahaan', TRUE);
			
			$data_perusahaan = $this->model->selects('*', 'biodata_perusahaan', array('nama_perusahaan' => $nama_perusahaan));

			$dok_skt = NULL;
			if($no_skt != NULL){
				$dok_skt = $this->model->select_like('dokumen_skt', 'no_dokumen', $no_skt);
			}elseif($nama_perusahaan != NULL){
				$dok_skt = $this->model->select_like('dokumen_skt', 'no_dokumen', $nama_perusahaan);
			}elseif($no_skt== NULL){
				$dok_skt= NULL;
			}

			 if($dok_skt != NULL){
			 	$dok_skt = $dok_skt;
			 }else{
			 	$dok_skt= NULL;
			 }
			
			$output = array('response' => $dok_skt, 'msg'=> 'Data tidak ditemukan!');
			
			$this->load->view('umum/cekstatus', $output);
        
    }
}