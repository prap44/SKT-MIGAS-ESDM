<?php
/**
 * Created by PhpStorm.
 * User: deptech
 * Date: 12/17/2014
 * Time: 4:22 PM
 */

class Perpanjangan extends CI_Controller
{
    public function detail_perusahaan($id_perusahaan)
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
//            'param' => $param,
            'sop' => $sop,
            'csr' => $csr,
        );
        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/perpanjangan', $detail_data);
            } else {
                $this->load->view('level1/perpanjangan', $detail_data);
            }
        } else {
            redirect('hal/logout');
        }

    }

}