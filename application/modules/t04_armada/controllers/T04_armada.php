<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T04_armada extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T04_armada_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('ssp');
    }

    public function index()
    {
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t04_armada', 4));
        $data['_view'] = 't04_armada/t04_armada_list';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T04_armada_model->json();
    }

    public function json2()
    {
        echo $this->T04_armada_model->json2();
    }

    public function read($id)
    {
        $row = $this->T04_armada_model->get_by_id($id);
        if ($row) {
            $data = array(
                'judul_form' => 'Detail Data',
                'id' => $row->id,
                'merk' => $row->merk,
                'tipe' => $row->tipe,
                'tahun_pembuatan' => $row->tahun_pembuatan,
                'harga_beli' => $row->harga_beli,
                'nomor_polisi' => $row->nomor_polisi,
                'nomor_rangka' => $row->nomor_rangka,
                'nomor_mesin' => $row->nomor_mesin,
                'tanggal_pembelian' => $row->tanggal_pembelian,
                'tanggal_jatuh_tempo_pajak' => $row->tanggal_jatuh_tempo_pajak,
                'tanggal_jatuh_tempo_kir' => $row->tanggal_jatuh_tempo_kir,
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t04_armada', 4));
            $data['_view'] = 't04_armada/t04_armada_read';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t04_armada'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('t04_armada/create_action'),
            'id' => set_value('id'),
            'merk' => set_value('merk'),
            'tipe' => set_value('tipe'),
            'tahun_pembuatan' => set_value('tahun_pembuatan'),
            'harga_beli' => set_value('harga_beli'),
            'nomor_polisi' => set_value('nomor_polisi'),
            'nomor_rangka' => set_value('nomor_rangka'),
            'nomor_mesin' => set_value('nomor_mesin'),
            'tanggal_pembelian' => set_value('tanggal_pembelian'),
            'tanggal_jatuh_tempo_pajak' => set_value('tanggal_jatuh_tempo_pajak'),
            'tanggal_jatuh_tempo_kir' => set_value('tanggal_jatuh_tempo_kir'),
        );
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t04_armada', 4));
        $data['_view'] = 't04_armada/t04_armada_form';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'merk' => $this->input->post('merk',TRUE),
                'tipe' => $this->input->post('tipe',TRUE),
                'tahun_pembuatan' => $this->input->post('tahun_pembuatan',TRUE),
                'harga_beli' => $this->input->post('harga_beli',TRUE),
                'nomor_polisi' => $this->input->post('nomor_polisi',TRUE),
                'nomor_rangka' => $this->input->post('nomor_rangka',TRUE),
                'nomor_mesin' => $this->input->post('nomor_mesin',TRUE),
                'tanggal_pembelian' => $this->input->post('tanggal_pembelian',TRUE),
                'tanggal_jatuh_tempo_pajak' => $this->input->post('tanggal_jatuh_tempo_pajak',TRUE),
                'tanggal_jatuh_tempo_kir' => $this->input->post('tanggal_jatuh_tempo_kir',TRUE),
            );

            $this->T04_armada_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t04_armada'));
        }
    }

    public function update($id)
    {
        $row = $this->T04_armada_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t04_armada/update_action'),
                'id' => set_value('id', $row->id),
                'merk' => set_value('merk', $row->merk),
                'tipe' => set_value('tipe', $row->tipe),
                'tahun_pembuatan' => set_value('tahun_pembuatan', $row->tahun_pembuatan),
                'harga_beli' => set_value('harga_beli', $row->harga_beli),
                'nomor_polisi' => set_value('nomor_polisi', $row->nomor_polisi),
                'nomor_rangka' => set_value('nomor_rangka', $row->nomor_rangka),
                'nomor_mesin' => set_value('nomor_mesin', $row->nomor_mesin),
                'tanggal_pembelian' => set_value('tanggal_pembelian', $row->tanggal_pembelian),
                'tanggal_jatuh_tempo_pajak' => set_value('tanggal_jatuh_tempo_pajak', $row->tanggal_jatuh_tempo_pajak),
                'tanggal_jatuh_tempo_kir' => set_value('tanggal_jatuh_tempo_kir', $row->tanggal_jatuh_tempo_kir),
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t04_armada', 4));
            $data['_view'] = 't04_armada/t04_armada_form';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t04_armada'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'merk' => $this->input->post('merk',TRUE),
                'tipe' => $this->input->post('tipe',TRUE),
                'tahun_pembuatan' => $this->input->post('tahun_pembuatan',TRUE),
                'harga_beli' => $this->input->post('harga_beli',TRUE),
                'nomor_polisi' => $this->input->post('nomor_polisi',TRUE),
                'nomor_rangka' => $this->input->post('nomor_rangka',TRUE),
                'nomor_mesin' => $this->input->post('nomor_mesin',TRUE),
                'tanggal_pembelian' => $this->input->post('tanggal_pembelian',TRUE),
                'tanggal_jatuh_tempo_pajak' => $this->input->post('tanggal_jatuh_tempo_pajak',TRUE),
                'tanggal_jatuh_tempo_kir' => $this->input->post('tanggal_jatuh_tempo_kir',TRUE),
            );

            $this->T04_armada_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t04_armada'));
        }
    }

    public function delete($id)
    {
        $row = $this->T04_armada_model->get_by_id($id);

        if ($row) {
            $this->T04_armada_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t04_armada'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t04_armada'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('merk', 'merk', 'trim|required');
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|required');
        $this->form_validation->set_rules('tahun_pembuatan', 'tahun pembuatan', 'trim|required');
        $this->form_validation->set_rules('harga_beli', 'harga beli', 'trim|required|numeric');
        $this->form_validation->set_rules('nomor_polisi', 'nomor polisi', 'trim|required');
        $this->form_validation->set_rules('nomor_rangka', 'nomor rangka', 'trim|required');
        $this->form_validation->set_rules('nomor_mesin', 'nomor mesin', 'trim|required');
        $this->form_validation->set_rules('tanggal_pembelian', 'tanggal pembelian', 'trim|required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_pajak', 'tanggal jatuh tempo pajak', 'trim|required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_kir', 'tanggal jatuh tempo kir', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t04_armada.xls";
        $judul = "t04_armada";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");
        xlsBOF();
        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Merk");
        xlsWriteLabel($tablehead, $kolomhead++, "Tipe");
        xlsWriteLabel($tablehead, $kolomhead++, "Tahun Pembuatan");
        xlsWriteLabel($tablehead, $kolomhead++, "Harga Beli");
        xlsWriteLabel($tablehead, $kolomhead++, "Nomor Polisi");
        xlsWriteLabel($tablehead, $kolomhead++, "Nomor Rangka");
        xlsWriteLabel($tablehead, $kolomhead++, "Nomor Mesin");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Pembelian");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Jatuh Tempo Pajak");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Jatuh Tempo Kir");
        foreach ($this->T04_armada_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->merk);
            xlsWriteLabel($tablebody, $kolombody++, $data->tipe);
            xlsWriteLabel($tablebody, $kolombody++, $data->tahun_pembuatan);
            xlsWriteNumber($tablebody, $kolombody++, $data->harga_beli);
            xlsWriteLabel($tablebody, $kolombody++, $data->nomor_polisi);
            xlsWriteLabel($tablebody, $kolombody++, $data->nomor_rangka);
            xlsWriteLabel($tablebody, $kolombody++, $data->nomor_mesin);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_pembelian);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_jatuh_tempo_pajak);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_jatuh_tempo_kir);
            $tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

}

/* End of file T04_armada.php */
/* Location: ./application/controllers/T04_armada.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-02-02 04:00:59 */
/* http://harviacode.com */