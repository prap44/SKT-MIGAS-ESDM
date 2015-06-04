<?php
/**
 * Created by PhpStorm.
 * User: deptech
 * Date: 12/17/2014
 * Time: 2:31 PM
 */


class Pengumuman extends CI_Controller{

    public function detail($id_pengumuman){
        $detail = $this->model->select('*', 'pengumuman', array('id_pengumuman' => $id_pengumuman));
        $result = $this->model->selects('*', 'pengumuman');

        $data = array(
            'penulis' => $detail->penulis,
            'judul' => $detail->judul,
            'isi' => $detail->isi,
            'tanggal_terbit' => $detail->tanggal_terbit,
            'results' => $result
        );
        $this->load->view('umum/pengumuman_detail', $data);
    }
}