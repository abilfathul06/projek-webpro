<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends MY_Controller
{

    private $array_obat = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pembelian_model');
    }

    public function index()
    {
        $pembelian = $this->Pembelian_model->get_all()->result();
        foreach ($pembelian as $key => $value) {
            $pembelian[$key]->obat = $this->Pembelian_model->get_obat($value->id)->result();
        }
        $data['pembelian'] = $pembelian;
        // print_r($data['pembelian']);
        // die;
        $this->layout->set_title('Data Pembelian');
        return $this->layout->load('template', 'pembelian/index', $data);
    }

    public function tambah()
    {
        $this->load->model('Obat_model');
        $data['obat'] = $this->Obat_model->get_all();
        $this->layout->set_title('Tambah pembelian');
        return $this->layout->load('template', 'pembelian/tambah', $data);
    }

    public function store()
    {
        $this->load->model('Obat_model');
        $this->form_validation->set_rules('data_obat', 'Obat', 'callback__data_obat_check');
        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => false,
                'message' => 'form error',
                'error' => validation_errors('<div class="alert alert-danger">', '</div>'),
            ];
            echo json_encode($response);
            return;
        }
        $data_pembelian = [
            'tgl' => date('Y-m-d h:i:s'),
            'admin_id' => $this->session->userdata('user_id'),
        ];

        $tambah = $this->Pembelian_model->create($data_pembelian);
        $pembelian_id = $this->db->insert_id();

        $detail_pembelian = [];
        foreach ($this->array_obat as $key => $ob) {
            $detail_pembelian[$key] = [
                'pembelian_id' => $pembelian_id,
                'kode_obat' => $ob->kode,
                'jumlah' => $ob->jumlah,
            ];
            $obat = $this->db->get_where('obat', ['kode' => $ob->kode])->row();
            $jumlah_baru = $obat->stok + $ob->jumlah;
            $data_obat = [
                'stok' => $jumlah_baru
            ];
            $this->Obat_model->update($data_obat, $ob->kode);
        }
        // print_r($detail_transaksi);
        // die;

        $this->Pembelian_model->create_detail($detail_pembelian);
        $msg = $tambah ? 'Berhasil ditambah' : 'Gagal ditambah';
        $response = [
            'status' => true,
            'message' => $msg,
        ];
        echo json_encode($response);
        return;
    }

    public function _data_obat_check($value)
    {
        $this->array_obat = json_decode($value);
        if (empty($this->array_obat)) {
            $this->form_validation->set_message('_data_obat_check', 'The {field} can not be empty');
            return false;
        }
        foreach ($this->array_obat as $ob) {
            $obat = $this->db->get_where('obat', ['kode' => $ob->kode])->row();
            if (!$obat) {
                $this->form_validation->set_message('_data_obat_check', 'Data {field} tidak ditemukan');
                return false;
            }
        }
        return true;
    }

    public function hapus($id = null)
    {
        if (!$id) return show_404();
        $this->db->delete('pembelian', ['id' => $id]);
        $this->session->set_flashdata('info', 'Berhasil dihapus');
        redirect('pembelian');
    }
}
