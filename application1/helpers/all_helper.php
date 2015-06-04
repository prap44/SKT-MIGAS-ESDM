<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function select($select = '', $table = '', $where = '', $order_by = '')
{
    $ci =& get_instance();
    $ci->load->database();

    if ($select != '') {
        $ci->db->select($select);
    }

    if ($table != '') {
        $ci->db->from($table);
    }

    if ($where != '') {
        $ci->db->where($where);
    }

    if ($order_by != '') {
        $ci->db->order_by($order_by[0], $order_by[1]);
    }

    $data = $ci->db->get();
    if ($data->num_rows() == 0) {
        return '';
    } else {
        return $data->row();
    }
}

function selects($select = '', $table = '', $where = '', $or_where = '', $order_by = '', $limit = '')
{
    $ci =& get_instance();
    $ci->load->database();

    if ($select != ''){
        $ci->db->select($select);
    }

    if ($table != ''){
        $ci->db->from($table);
    }

    if ($where != '') {
        $ci->db->where($where);
    }
    if ($or_where != '') {
        $ci->db->or_where($or_where);
    }
    // bukan nilai tidak pke =>
    if ($order_by != ''){
        $ci->db->order_by($order_by[0], $order_by[1]);
    }
    //$ci->db->order_by("title", "desc");
    if ($limit != ''){
        $ci->db->limit($limit[0], $limit[1]); // array(2, 7)  dibacanya setelah bari ke 7 (8,9) ambi 2 row
    }

    $data = $ci->db->get();

    if ($data->num_rows() == 0) {
        return '';
    } else {
        return $data->result();
    }
}

function counting($select = '', $table = '', $where = '')
{
    $ci =& get_instance();
    $ci->load->database();
    
    if ($select != ''){
        $ci->db->select($select);
    }

    if ($table != ''){
        $ci->db->from($table);
    }

    if ($where != '') {
        $ci->db->where($where);
    }

    $data = $ci->db->get();
    if ($data->num_rows() == 0) {
        return 0;
    } else {
        return $data->num_rows();
    }
}

function like($table = '', $field = '', $keyword = '', $where = '')
{
    
    $ci =& get_instance();
    $ci->load->database();

    if ($table != '' && $keyword != '') {
        $ci->db->like($field, $keyword);
    }

    if ($where != ''){
        $ci->db->where($where);
    }

    $data = $ci->db->get();
    if ($data->num_rows() == 0) {
        return 0;
    } else {
        return $data->result();
    }
}


function update($table, $data, $id_field)
{
    
    $ci =& get_instance();
    $ci->load->database();

    $ci->db->update($table, $data, $id_field);
    if ($ci->db->affected_rows() == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function delete($table, $id_field)
{
    /*
      |menghapus byk data ke banyak table
      |$tables = array('table1', 'table2', 'table3');
      |$ci->db->where('id', '5');
      |$ci->db->delete($tables);
     */
    
    $ci =& get_instance();
    $ci->load->database();

    $ci->db->delete($table, $id_field);
    if ($ci->db->affected_rows() == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function insert($table, $data)
{
    
    $ci =& get_instance();
    $ci->load->database();

    $ci->db->insert($table, $data);
    if ($ci->db->affected_rows() == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function last_inserted_id()
{
    $ci =& get_instance();
    $ci->load->database();

    $last_inserted_id = $ci->db->insert_id();
    return $last_inserted_id;
}

function assets($file_name){
    $ci =& get_instance();
    $ci->load->helper('url');

    $path_to_assets = base_url('assets/'.$file_name);
    return $path_to_assets;
}

function view($view_file, $data=''){
    $ci =& get_instance();
    $view = $ci->load->view($view_file, $data);
    return $view;
}

function current_controller($param=''){
    $ci =& get_instance();
    $ci->load->helper('url');
    $class = $ci->router->fetch_class();
    return site_url($class.'/'.$param);
}

function controller_method(){
    $ci =& get_instance();
    $ci->load->helper('url');
    $class = $ci->router->fetch_class();
    $method = $ci->router->fetch_method();
    return site_url($class.'/'.$method);
}