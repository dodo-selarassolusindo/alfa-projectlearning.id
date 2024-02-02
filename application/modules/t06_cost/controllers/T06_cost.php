<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T06_cost extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T06_cost_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('ssp');
    }

    public function index()
    {
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucwords(str_replace('_', ' ', substr('t06_cost', 4)));
        $data['_view'] = 't06_cost/t06_cost_list';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T06_cost_model->json();
    }

    public function json2()
    {
        echo $this->T06_cost_model->json2();
    }

    public function read($id)
    {
        $row = $this->T06_cost_model->get_by_id($id);
        if ($row) {
            $data = array(
                'judul_form' => 'Detail Data',
                'id' => $row->id,
                'nama' => $row->nama,
                'akun' => $row->akun,
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucwords(str_replace('_', ' ', substr('t06_cost', 4)));
            $data['_view'] = 't06_cost/t06_cost_read';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t06_cost'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('t06_cost/create_action'),
            'id' => set_value('id'),
            'nama' => set_value('nama'),
            'akun' => set_value('akun'),
        );
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucwords(str_replace('_', ' ', substr('t06_cost', 4)));
        $data['_view'] = 't06_cost/t06_cost_form';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama' => $this->input->post('nama',TRUE),
                'akun' => $this->input->post('akun',TRUE),
            );

            $this->T06_cost_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t06_cost'));
        }
    }

    public function update($id)
    {
        $row = $this->T06_cost_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t06_cost/update_action'),
                'id' => set_value('id', $row->id),
                'nama' => set_value('nama', $row->nama),
                'akun' => set_value('akun', $row->akun),
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucwords(str_replace('_', ' ', substr('t06_cost', 4)));
            $data['_view'] = 't06_cost/t06_cost_form';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t06_cost'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'nama' => $this->input->post('nama',TRUE),
                'akun' => $this->input->post('akun',TRUE),
            );

            $this->T06_cost_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t06_cost'));
        }
    }

    public function delete($id)
    {
        $row = $this->T06_cost_model->get_by_id($id);

        if ($row) {
            $this->T06_cost_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t06_cost'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t06_cost'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('akun', 'akun', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t06_cost.xls";
        $judul = "t06_cost";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Nama");
        xlsWriteLabel($tablehead, $kolomhead++, "Akun");
        foreach ($this->T06_cost_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama);
            xlsWriteNumber($tablebody, $kolombody++, $data->akun);
            $tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

}

/* End of file T06_cost.php */
/* Location: ./application/controllers/T06_cost.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-02-02 10:09:18 */
/* http://harviacode.com */