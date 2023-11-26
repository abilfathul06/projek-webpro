<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian_model extends CI_Model
{
    public function get_all()
    {
        $this->db->select('a.*, b.nama AS admin')
            ->from('pembelian a')
            ->join('admin b', 'a.admin_id = b.id');
        return $this->db->get();
    }

    public function get_obat($pembelian_id)
    {
        $this->db->select('b.kode, b.nama_obat, b.harga_beli, a.jumlah, a.id')
            ->from('detail_pembelian a')
            ->join('obat b', 'a.kode_obat = b.kode')
            ->where('pembelian_id', $pembelian_id);
        return $this->db->get();
    }

    public function create($data)
    {
        $this->db->insert('pembelian', $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function create_detail($data)
    {
        $this->db->insert_batch('detail_pembelian', $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function getByKode($kode)
    {
        return $this->db->get_where('pembelian', ['kode' => $kode])->row();
    }

    public function update($data, $kode)
    {
        $this->db->update('pembelian', $data, ['kode' => $kode]);
        return $this->db->affected_rows() > 0 ? true : false;
    }
}
