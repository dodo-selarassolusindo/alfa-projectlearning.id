<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T31_job_order_detail extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T31_job_order_detail_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('ssp');
    }

    public function index()
    {
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t31_job_order_detail', 4));
        $data['_view'] = 't31_job_order_detail/t31_job_order_detail_list';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T31_job_order_detail_model->json();
    }

    public function json2()
    {
        echo $this->T31_job_order_detail_model->json2();
    }

    public function read($id)
    {
        $row = $this->T31_job_order_detail_model->get_by_id($id);
        if ($row) {
            $data = array(
                'judul_form' => 'Detail Data',
                'id' => $row->id,
                'job_order' => $row->job_order,
                'armada' => $row->armada,
                'nomor_container' => $row->nomor_container,
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t31_job_order_detail', 4));
            $data['_view'] = 't31_job_order_detail/t31_job_order_detail_read';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t31_job_order_detail'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('t31_job_order_detail/create_action'),
            'id' => set_value('id'),
            'job_order' => set_value('job_order'),
            'armada' => set_value('armada'),
            'nomor_container' => set_value('nomor_container'),
        );
        $data['_sub_judul'] = 'Master';
        $data['_judul'] = ucfirst(substr('t31_job_order_detail', 4));
        $data['_view'] = 't31_job_order_detail/t31_job_order_detail_form';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'job_order' => $this->input->post('job_order',TRUE),
                'armada' => $this->input->post('armada',TRUE),
                'nomor_container' => $this->input->post('nomor_container',TRUE),
            );

            $this->T31_job_order_detail_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t31_job_order_detail'));
        }
    }

    public function update($id)
    {
        $row = $this->T31_job_order_detail_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t31_job_order_detail/update_action'),
                'id' => set_value('id', $row->id),
                'job_order' => set_value('job_order', $row->job_order),
                'armada' => set_value('armada', $row->armada),
                'nomor_container' => set_value('nomor_container', $row->nomor_container),
            );
            $data['_sub_judul'] = 'Master';
            $data['_judul'] = ucfirst(substr('t31_job_order_detail', 4));
            $data['_view'] = 't31_job_order_detail/t31_job_order_detail_form';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t31_job_order_detail'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'job_order' => $this->input->post('job_order',TRUE),
                'armada' => $this->input->post('armada',TRUE),
                'nomor_container' => $this->input->post('nomor_container',TRUE),
            );

            $this->T31_job_order_detail_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t31_job_order_detail'));
        }
    }

    public function delete($id)
    {
        $row = $this->T31_job_order_detail_model->get_by_id($id);

        if ($row) {
            $this->T31_job_order_detail_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t31_job_order_detail'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t31_job_order_detail'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('job_order', 'job order', 'trim|required');
        $this->form_validation->set_rules('armada', 'armada', 'trim|required');
        $this->form_validation->set_rules('nomor_container', 'nomor container', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t31_job_order_detail.xls";
        $judul = "t31_job_order_detail";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Job Order");
        xlsWriteLabel($tablehead, $kolomhead++, "Armada");
        xlsWriteLabel($tablehead, $kolomhead++, "Nomor Container");
        foreach ($this->T31_job_order_detail_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteNumber($tablebody, $kolombody++, $data->job_order);
            xlsWriteNumber($tablebody, $kolombody++, $data->armada);
            xlsWriteLabel($tablebody, $kolombody++, $data->nomor_container);
            $tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

}

/* End of file T31_job_order_detail.php */
/* Location: ./application/controllers/T31_job_order_detail.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-02-02 04:29:15 */
/* http://harviacode.com */