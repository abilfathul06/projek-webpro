<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function get_all_count()
    {
        $obat = $this->db->get('obat')->num_rows();
        $transaksi = $this->db->get('transaksi')->num_rows();
        $supplier = $this->db->get('supplier')->num_rows();
        $count = [
            'obat' => $obat,
            'transaksi' => $transaksi,
            'supplier' => $supplier,
        ];
        return $count;
    }
}
