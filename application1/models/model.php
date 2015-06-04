<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model extends CI_Model {

    /**
      | Description		: Model active record for CodeIgniter
      | Author			: Reza Mukti
      | Email			: ycared@gmail.com
      | Date			: 6 Juli 2014 :: 16.51 WIB
     */
#ISUD :: Insert | Select| Update| Delete
#
#
#-----------------------------------------------Insert----------------------------------------------------#
    function getData($keyword) {
        return $data = $this->db->from('petani')->like('nama', $keyword)->get()->result();
    }

    function insert($table, $data) {
        $this->db->insert($table, $data);
        return TRUE;
    }

#-----------------------------------------------Select----------------------------------------------------#

    function selects($select = FALSE, $table = FALSE, $where = array(), $or_where = array(), $order_by = array(), $limit = array()) {
        //$this->db->select('title, content, date');
        if ($select != FALSE)
            $this->db->select($select);

        if ($table != FALSE)
            $this->db->from($table);

        if (count($where) != 0) {
            $this->db->where($where);
        }
        if (count($or_where) != 0) {
            $this->db->or_where($or_where);
        }
        // bukan nilai tidak pke =>
        if (count($order_by) != 0)
            $this->db->order_by($order_by[0], $order_by[1]);
        //$this->db->order_by("title", "desc");
        if (count($limit) != 0)
            $this->db->limit($limit[0], $limit[1]); // array(2, 7)  dibacanya setelah bari ke 7 (8,9) ambi 2 row

        $data= $this->db->get();

        if ($data->num_rows() == 0) {
            return NULL;
        } else {
            return $data->result();
        }
    }
    function getCountSelects($select = FALSE, $table = FALSE, $where = array()){
        if ($select != FALSE)
            $this->db->select($select);

        if ($table != FALSE)
            $this->db->from($table);

        if (count($where) != 0) {
            $this->db->where($where);
        }
       
        $query = $this->db->get();
        return $query->num_rows();
    }
    function selects_array($select = FALSE, $table = FALSE, $where = array(), $or_where = array(), $order_by = array(), $limit = array()) {
        //$this->db->select('title, content, date');
        if ($select != FALSE)
            $this->db->select($select);

        if ($table != FALSE)
            $this->db->from($table);

        if (count($where) != 0) {
            $this->db->where($where);
        }
        if (count($or_where) != 0) {
            $this->db->or_where($or_where);
        }
        // bukan nilai tidak pke =>
        if (count($order_by) != 0)
            $this->db->order_by($order_by[0], $order_by[1]);
        //$this->db->order_by("title", "desc");
        if (count($limit) != 0)
            $this->db->limit($limit[0], $limit[1]); // array(2, 7)  dibacanya setelah bari ke 7 (8,9) ambi 2 row

        return $this->db->get()->result_array();
    }

    function select($select = FALSE, $table = FALSE, $where = array(), $order_by= array()) {
        //$this->db->select('title, content, date');
        if ($select != FALSE)
            $this->db->select($select);

        if ($table != FALSE)
            $this->db->from($table);

        if (count($where) != 0)
            $this->db->where($where);

        if (count($order_by) != 0)
            $this->db->order_by($order_by[0], $order_by[1]);

        $data = $this->db->get();
        if ($data->num_rows() == 0) {
            return NULL;
        } else {
            return $data->row();
        }
    }

    function select_max_min_avg_sum($table, $max = FALSE, $min = FALSE, $avg = FALSE, $sum = FALSE) {

        if ($max)
            $this->db->select_max($max);

        if ($min)
            $this->db->select_min($min);

        if ($avg)
            $this->db->select_avg($avg);

        if ($sum)
            $this->db->select_sum($sum);

        return $this->db->get($table)->result();
    }

    function select_like($table, $field = FALSE, $keyword = FALSE, $where = array()) {
        $this->db->like($field, $keyword);

        if (count($where) != 0)
            $this->db->where($where);

        return $this->db->get($table)->result();
    }

    function count_table($table) {
        return $this->db->count_all($table);
    }

#-----------------------------------------------Update----------------------------------------------------#

    function update($table, $data, $id_field = array()) {
        $this->db->update($table, $data, $id_field);
        return TRUE;
    }

#-----------------------------------------------Delete----------------------------------------------------#

    function delete($table, $id_field) {
        /*
          |menghapus byk data ke banyak table
          |$tables = array('table1', 'table2', 'table3');
          |$this->db->where('id', '5');
          |$this->db->delete($tables);
         */
        $this->db->delete($table, $id_field);
        return TRUE;
    }
    
    //get one record with lastest entry
    function get_one($iso_1 = 'en', $tbl, $id) {
        $data = $this->db
                ->where('id_status', 1)
                ->where('is_delete', 0)
                ->where('id_site',1)
                ->where('LCASE(iso_1)', strtolower($iso_1))
                ->order_by($id, 'desc')
                ->limit(1)
                ->get($tbl)
                ->row();

        return $data;
    }



        function get_two($iso_1 = 'en', $tbl, $id_tbl, $id_site) {
        $data = $this->db
                ->where('id_status', 1)
                ->where('is_delete', 0)
                ->where('id_site',$id_site)
                ->where('LCASE(iso_1)', strtolower($iso_1))
                ->order_by($id_tbl, 'desc')
                ->limit(2)
                ->get($tbl)
                ->result();

        return $data;
    }




    function getList($iso_1 = 'en', $tbl, $id, $limit) {
        $data = $this->db
            ->where('id_status', 1)
            ->where('is_delete', 0)
            //->where('id_site',$id_site)
            ->where('LCASE(iso_1)', strtolower($iso_1))
            ->order_by($id, 'desc')
            ->limit($limit)
            ->get($tbl)
            ->result();

        return $data;
    }
    function download() {
        $data = $this->db
                //->where('id_status', 1)
                ->where('is_delete', 0)
                //->where('id_site',$id_site)
                //->where('LCASE(iso_1)', strtolower($iso_1))
                ->order_by('id_download', 'desc')
                ->limit(1)
                ->get('ddi_download')
                ->row();

        return $data;
    }
	
	function search_like($table, $field = FALSE, $keyword = FALSE, $where = array(), $limit = array()) {
        $this->db->like($field, $keyword);
        $this->db->limit($limit[0], $limit[1]);

        if (count($where) != 0)
            $this->db->where($where);

        return $this->db->get($table)->result();
    }
#-----------------------------------------------Pagination----------------------------------------------------#	
	public function record_count($table) {
        return $this->db->count_all($table);
    }
 
    public function my_pagination($limit, $start, $table, $order) {
        $this->db->limit($limit, $start);
        $this->db->order_by($order, 'DESC');
        $query = $this->db->get($table);
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
}