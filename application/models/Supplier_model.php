<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
    public function get_all()
    {
        $result = $this->db->get_where('supplier', ['flag_del' => "0"]);
        return $result;
    }

    public function create($data)
    {
        $this->db->insert('supplier', $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function update($data, $id)
    {
        $this->db->update('supplier', $data, ['id' => $id]);
        return $this->db->affected_rows() > 0 ? true : false;
    }
}
