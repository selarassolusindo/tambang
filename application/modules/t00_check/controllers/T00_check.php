<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class T00_check extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('T00_check_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 't00_check?q=' . urlencode($q);
            $config['first_url'] = base_url() . 't00_check?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 't00_check';
            $config['first_url'] = base_url() . 't00_check';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->T00_check_model->total_rows($q);
        $t00_check = $this->T00_check_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            't00_check_data' => $t00_check,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->load->view('t00_check/t00_check_list', $data);
        $data['_view'] = 't00_check/t00_check_list';
        $data['_caption'] = 'Check';
        $this->load->view('_00_dashboard/_00_dashboard_view', $data);
    }

    public function read($id)
    {
        $row = $this->T00_check_model->get_by_id($id);
        if ($row) {
            $data = array(
				'idcheck' => $row->idcheck,
				'idtruck' => $row->idtruck,
				'created_at' => $row->created_at,
				'updated_at' => $row->updated_at,
			);
            $this->load->view('t00_check/t00_check_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t00_check'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('t00_check/create_action'),
			'idcheck' => set_value('idcheck'),
			'idtruck' => set_value('idtruck'),
			'created_at' => set_value('created_at'),
			'updated_at' => set_value('updated_at'),
		);
        // $this->load->view('t00_check/t00_check_form', $data);
        $data['_view'] = 't00_check/t00_check_form';
        $data['_caption'] = 'Check';
        $this->load->view('_00_dashboard/_00_dashboard_view', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
				'idtruck' => $this->input->post('idtruck',TRUE),
				'created_at' => $this->input->post('created_at',TRUE),
				'updated_at' => $this->input->post('updated_at',TRUE),
			);
            $this->T00_check_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('t00_check'));
        }
    }

    public function update($id)
    {
        $row = $this->T00_check_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Simpan',
                'action' => site_url('t00_check/update_action'),
				'idcheck' => set_value('idcheck', $row->idcheck),
				'idtruck' => set_value('idtruck', $row->idtruck),
				'created_at' => set_value('created_at', $row->created_at),
				'updated_at' => set_value('updated_at', $row->updated_at),
			);
            // $this->load->view('t00_check/t00_check_form', $data);
            $data['_view'] = 't00_check/t00_check_form';
            $data['_caption'] = 'Check';
            $this->load->view('_00_dashboard/_00_dashboard_view', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t00_check'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('idcheck', TRUE));
        } else {
            $data = array(
				'idtruck' => $this->input->post('idtruck',TRUE),
				'created_at' => $this->input->post('created_at',TRUE),
				'updated_at' => $this->input->post('updated_at',TRUE),
			);
            $this->T00_check_model->update($this->input->post('idcheck', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('t00_check'));
        }
    }

    public function delete($id)
    {
        $row = $this->T00_check_model->get_by_id($id);

        if ($row) {
            $this->T00_check_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('t00_check'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('t00_check'));
        }
    }

    public function _rules()
    {
		$this->form_validation->set_rules('idtruck', 'idtruck', 'trim|required');
		$this->form_validation->set_rules('created_at', 'created at', 'trim|required');
		$this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');
		$this->form_validation->set_rules('idcheck', 'idcheck', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "t00_check.xls";
        $judul = "t00_check";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Idtruck");
		xlsWriteLabel($tablehead, $kolomhead++, "Created At");
		xlsWriteLabel($tablehead, $kolomhead++, "Updated At");
		foreach ($this->T00_check_model->get_all() as $data) {
            $kolombody = 0;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteNumber($tablebody, $kolombody++, $data->idtruck);
			xlsWriteLabel($tablebody, $kolombody++, $data->created_at);
			xlsWriteLabel($tablebody, $kolombody++, $data->updated_at);
			$tablebody++;
            $nourut++;
        }
        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=t00_check.doc");
        $data = array(
            't00_check_data' => $this->T00_check_model->get_all(),
            'start' => 0
        );
        $this->load->view('t00_check/t00_check_doc',$data);
    }

    public function create_action2($idtruck)
    {
        // $this->_rules();

        // if ($this->form_validation->run() == FALSE) {
            // $this->create();
        // } else {
            $data = array(
				'idtruck' => $idtruck,
				// 'created_at' => $this->input->post('created_at',TRUE),
				// 'updated_at' => $this->input->post('updated_at',TRUE),
			);
            $this->T00_check_model->insert($data);
            // $this->session->set_flashdata('message', 'Create Record Success');
            // redirect(site_url('t00_check'));
        // }
    }

}

/* End of file T00_check.php */
/* Location: ./application/controllers/T00_check.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-05-05 20:49:14 */
/* http://harviacode.com */
