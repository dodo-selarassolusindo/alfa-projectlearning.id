<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T30_job_order extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T30_job_order_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('ssp');
        $this->load->model('t01_customer/T01_customer_model');
        $this->load->model('t02_shipper/T02_shipper_model');
        $this->load->model('t00_lokasi/T00_lokasi_model');
        $this->load->model('t04_armada/T04_armada_model');
        $this->load->model('t31_job_order_detail/T31_job_order_detail_model');
    }

    public function index()
    {
        $data['_sub_judul'] = 'Transaksi';
        $data['_judul'] = ucwords(str_replace('_', ' ', substr('t30_job_order', 4)));
        $data['_view'] = 't30_job_order/t30_job_order_list';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function get_new_nomor_()
    {
        // echo 'JU'.$_POST['tgl']; //exit;
        echo get_new_nomor('t30_job_order', 'JO', $_POST['tanggal']);
        // pre($_POST['tanggal']); exit;
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->T30_job_order_model->json();
    }

    public function json2()
    {
        echo $this->T30_job_order_model->json2();
    }

    public function read($id)
    {
        $row = $this->T30_job_order_model->get_by_id($id);
        if ($row) {
            $data = array(
                'judul_form' => 'Detail Data',
                'id' => $row->id,
                'tanggal_job_order' => date_to_dmy($row->tanggal_job_order),
                'nomor' => $row->nomor,
                'customer' => $this->T01_customer_model->get_by_id($row->customer)->nama,
                'shipper' => $this->T02_shipper_model->get_by_id($row->shipper)->nama,
                'tanggal_muat' => date_to_dmy($row->tanggal_muat),
                'lokasi' => $this->T00_lokasi_model->get_by_id($row->lokasi)->nama,
                'all_job_order_detail' => $this->T31_job_order_detail_model->get_all_by_job_order($id),
            );
            $data['_sub_judul'] = 'Transaksi';
            $data['_judul'] = ucwords(str_replace('_', ' ', substr('t30_job_order', 4)));
            $data['_view'] = 't30_job_order/t30_job_order_read';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t30_job_order'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('t30_job_order/create_action'),
            'id' => set_value('id'),
            'tanggal_job_order' => set_value('tanggal_job_order'),
            'nomor' => set_value('nomor', get_new_nomor('t30_job_order', 'JO', date('d-m-Y'))),
            'customer' => set_value('customer'),
            'shipper' => set_value('shipper'),
            'tanggal_muat' => set_value('tanggal_muat'),
            'lokasi' => set_value('lokasi'),
            'all_customer' => $this->T01_customer_model->get_all(),
            'all_shipper' => $this->T02_shipper_model->get_all(),
            'all_lokasi' => $this->T00_lokasi_model->get_all(),
            'all_armada' => $this->T04_armada_model->get_all(),
        );
        $data['_sub_judul'] = 'Transaksi';
        $data['_judul'] = ucwords(str_replace('_', ' ', substr('t30_job_order', 4)));
        $data['_view'] = 't30_job_order/t30_job_order_form';
        $this->load->view('welcome/welcome_message', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tanggal_job_order' => date_to_ymd($this->input->post('tanggal_job_order',TRUE)),
                'nomor' => $this->input->post('nomor',TRUE),
                'customer' => $this->input->post('customer',TRUE),
                'shipper' => $this->input->post('shipper',TRUE),
                'tanggal_muat' => date_to_ymd($this->input->post('tanggal_muat',TRUE)),
                'lokasi' => $this->input->post('lokasi',TRUE),
            );
            // insert table master job order
            $this->T30_job_order_model->insert($data);

            // id table master job order
            $job_order = $this->db->insert_id();

            // insert table detail job order
            $data = $this->input->post();
            foreach ($data['armada'] as $key => $item) {
                $detail = [
                    'job_order' => $job_order,
                    'armada' => $item,
                    'nomor_container' => $data['nomor_container'][$key],
                ];
                $this->T31_job_order_detail_model->insert($detail);
            }

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t30_job_order'));
        }
    }

    public function update($id)
    {
        $row = $this->T30_job_order_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('t30_job_order/update_action'),
                'id' => set_value('id', $row->id),
                'tanggal_job_order' => set_value('tanggal_job_order', $row->tanggal_job_order),
                'nomor' => set_value('nomor', $row->nomor),
                'customer' => set_value('customer', $row->customer),
                'shipper' => set_value('shipper', $row->shipper),
                'tanggal_muat' => set_value('tanggal_muat', $row->tanggal_muat),
                'lokasi' => set_value('lokasi', $row->lokasi),
                'all_customer' => $this->T01_customer_model->get_all(),
                'all_shipper' => $this->T02_shipper_model->get_all(),
                'all_lokasi' => $this->T00_lokasi_model->get_all(),
                'all_armada' => $this->T04_armada_model->get_all(),
                'all_job_order_detail' => $this->T31_job_order_detail_model->get_all_by_job_order($id),
            );
            $data['_sub_judul'] = 'Transaksi';
            $data['_judul'] = ucwords(str_replace('_', ' ', substr('t30_job_order', 4)));
            $data['_view'] = 't30_job_order/t30_job_order_form';
            $this->load->view('welcome/welcome_message', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t30_job_order'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'tanggal_job_order' => date_to_ymd($this->input->post('tanggal_job_order',TRUE)),
                'nomor' => $this->input->post('nomor',TRUE),
                'customer' => $this->input->post('customer',TRUE),
                'shipper' => $this->input->post('shipper',TRUE),
                'tanggal_muat' => date_to_ymd($this->input->post('tanggal_muat',TRUE)),
                'lokasi' => $this->input->post('lokasi',TRUE),
            );
            // update table master job order
            $this->T30_job_order_model->update($this->input->post('id', TRUE), $data);

            // id table master job order
            $job_order = $this->input->post('id', TRUE);

            // hapus table detail job order berdasarkan job order
            $this->T31_job_order_detail_model->delete_by_job_order($job_order);

            // insert table detail job order
            $data = $this->input->post();
            foreach ($data['armada'] as $key => $item) {
                $detail = [
                    'job_order' => $job_order,
                    'armada' => $item,
                    'nomor_container' => $data['nomor_container'][$key],
                ];
                $this->T31_job_order_detail_model->insert($detail);
            }

            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t30_job_order'));
        }
    }

    public function delete($id)
    {
        $row = $this->T30_job_order_model->get_by_id($id);

        if ($row) {

            // hapus table master job order
            $this->T30_job_order_model->delete($id);

            // hapus table detail job order
            $this->T31_job_order_detail_model->delete_by_job_order($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t30_job_order'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t30_job_order'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tanggal_job_order', 'tanggal job order', 'trim|required');
        $this->form_validation->set_rules('nomor', 'nomor', 'trim|required');
        $this->form_validation->set_rules('customer', 'customer', 'trim|required');
        $this->form_validation->set_rules('shipper', 'shipper', 'trim|required');
        $this->form_validation->set_rules('tanggal_muat', 'tanggal muat', 'trim|required');
        $this->form_validation->set_rules('lokasi', 'lokasi', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t30_job_order.xls";
        $judul = "t30_job_order";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Job Order");
        xlsWriteLabel($tablehead, $kolomhead++, "Nomor");
        xlsWriteLabel($tablehead, $kolomhead++, "Customer");
        xlsWriteLabel($tablehead, $kolomhead++, "Shipper");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Muat");
        xlsWriteLabel($tablehead, $kolomhead++, "Lokasi");
        foreach ($this->T30_job_order_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_job_order);
            xlsWriteLabel($tablebody, $kolombody++, $data->nomor);
            xlsWriteNumber($tablebody, $kolombody++, $data->customer);
            xlsWriteNumber($tablebody, $kolombody++, $data->shipper);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_muat);
            xlsWriteNumber($tablebody, $kolombody++, $data->lokasi);
            $tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

}

/* End of file T30_job_order.php */
/* Location: ./application/controllers/T30_job_order.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-02-02 04:29:09 */
/* http://harviacode.com */
