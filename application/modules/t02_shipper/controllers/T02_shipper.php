<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T02_shipper extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T02_shipper_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('ssp');
        $this->load->model('t00_lokasi/T00_lokasi_model');
    }

    public function index()
    {
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t02_shipper', 4));
        $data['_view'] = 't02_shipper/t02_shipper_list';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function get_lokasi_()
    {
        echo $this->T00_lokasi_model->get_by_id($this->T02_shipper_model->get_by_id($_POST['id'])->kota)->id;
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T02_shipper_model->json();
    }

    public function json2()
    {
        echo $this->T02_shipper_model->json2();
    }

    public function read($id)
    {
        $row = $this->T02_shipper_model->get_by_id($id);
        if ($row) {
            $data = array(
                'judul_form' => 'Detail Data',
                'id' => $row->id,
                'kode' => $row->kode,
                'nama' => $row->nama,
                'alamat' => $row->alamat,
                'kota' => $this->T00_lokasi_model->get_by_id($row->kota)->nama,
                'contact_person' => $row->contact_person,
                'telepon' => $row->telepon,
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t02_shipper', 4));
            $data['_view'] = 't02_shipper/t02_shipper_read';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t02_shipper'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('t02_shipper/create_action'),
            'id' => set_value('id'),
            'kode' => set_value('kode'),
            'nama' => set_value('nama'),
            'alamat' => set_value('alamat'),
            'kota' => set_value('kota'),
            'contact_person' => set_value('contact_person'),
            'telepon' => set_value('telepon'),
            'lokasi' => $this->T00_lokasi_model->get_all(),
        );
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t02_shipper', 4));
        $data['_view'] = 't02_shipper/t02_shipper_form';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'kode' => $this->input->post('kode',TRUE),
                'nama' => $this->input->post('nama',TRUE),
                'alamat' => $this->input->post('alamat',TRUE),
                'kota' => $this->input->post('kota',TRUE),
                'contact_person' => $this->input->post('contact_person',TRUE),
                'telepon' => $this->input->post('telepon',TRUE),
            );

            $this->T02_shipper_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t02_shipper'));
        }
    }

    public function update($id)
    {
        $row = $this->T02_shipper_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t02_shipper/update_action'),
                'id' => set_value('id', $row->id),
                'kode' => set_value('kode', $row->kode),
                'nama' => set_value('nama', $row->nama),
                'alamat' => set_value('alamat', $row->alamat),
                'kota' => set_value('kota', $row->kota),
                'contact_person' => set_value('contact_person', $row->contact_person),
                'telepon' => set_value('telepon', $row->telepon),
                'lokasi' => $this->T00_lokasi_model->get_all(),
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t02_shipper', 4));
            $data['_view'] = 't02_shipper/t02_shipper_form';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t02_shipper'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'kode' => $this->input->post('kode',TRUE),
                'nama' => $this->input->post('nama',TRUE),
                'alamat' => $this->input->post('alamat',TRUE),
                'kota' => $this->input->post('kota',TRUE),
                'contact_person' => $this->input->post('contact_person',TRUE),
                'telepon' => $this->input->post('telepon',TRUE),
            );

            $this->T02_shipper_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t02_shipper'));
        }
    }

    public function delete($id)
    {
        $row = $this->T02_shipper_model->get_by_id($id);

        if ($row) {
            $this->T02_shipper_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t02_shipper'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t02_shipper'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
        $this->form_validation->set_rules('kota', 'kota', 'trim|required');
        $this->form_validation->set_rules('contact_person', 'contact person', 'trim|required');
        $this->form_validation->set_rules('telepon', 'telepon', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t02_shipper.xls";
        $judul = "t02_shipper";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama");
        xlsWriteLabel($tablehead, $kolomhead++, "Alamat");
        xlsWriteLabel($tablehead, $kolomhead++, "Kota");
        xlsWriteLabel($tablehead, $kolomhead++, "Contact Person");
        xlsWriteLabel($tablehead, $kolomhead++, "Telepon");
        foreach ($this->T02_shipper_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama);
            xlsWriteLabel($tablebody, $kolombody++, $data->alamat);
            xlsWriteNumber($tablebody, $kolombody++, $data->kota);
            xlsWriteLabel($tablebody, $kolombody++, $data->contact_person);
            xlsWriteLabel($tablebody, $kolombody++, $data->telepon);
            $tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

}

/* End of file T02_shipper.php */
/* Location: ./application/controllers/T02_shipper.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-02-01 16:03:47 */
/* http://harviacode.com */
