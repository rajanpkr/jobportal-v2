<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/tags_model');
        $this->load->model('admin/category_model');
        $this->load->library('form_validation');
        if(!$this->helper_model->validate_admin_session()){
          redirect(base_url() . 'admin');
        }

    }

    public function index() {
        $this->cms_tags();
    }


    function cms_tags() {
        $config['base_url'] = site_url(ADMIN_PATH . '/tags/page');
        $data['main'] = 'admin/tags/list';
        $query = $this->db->get('tbl_tags');
        $config['total_rows'] = $query->num_rows();

        $config['per_page'] = '300';
        $offset = $this->uri->segment(4, 0);
        $config['uri_segment'] = '4';
        $this->pagination->initialize($config);

        $data['tags'] = $this->tags_model->tags_list($config['per_page'], $offset);
        $data['links'] = $this->pagination->create_links();
        $data['title'] = 'Tags';

        $this->load->view('admin/admin', $data);
    }

    function add() {
        $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|is_unique[tbl_tags.name]');
        $this->form_validation->set_rules('status', 'Status', 'required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $data['main'] = 'admin/tags/add';
            $data['title'] = 'Add Tags';
            $this->load->view('admin/admin', $data);
        } else {
            $this->tags_model->add_tags();
            $this->session->set_userdata( 'flash_msg_type', "success" );
            $this->session->set_flashdata('flash_msg', 'Tags Added Successfully');
            redirect(ADMIN_PATH . '/tags', 'refresh');
        }
    }

    function edit($id) {
        $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|callback__matches_other_tags['.$id.']');
        $this->form_validation->set_rules('category_id', 'Category', 'required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'required|xss_clean');

        $data['category_info'] = $this->category_model->category_list_all();

        if ($this->form_validation->run() == FALSE) {
            $data['info'] = $this->tags_model->get_tags($id);
            $data['main'] = 'admin/tags/edit';
            $data['title'] = 'Edit Tags';
            $this->load->view('admin/admin', $data);
        } else {
            $this->tags_model->update_tags($id);
            $this->session->set_userdata( 'flash_msg_type', "success" );
            $this->session->set_flashdata('flash_msg', 'Tags Updated Successfully');
            redirect(ADMIN_PATH . '/tags', 'refresh');
        }
    }

    function delete_tags($id) {
         if($this->tags_model->delete_tags($id)) {
            echo json_encode(array(
                    'response' => TRUE,
                    'message' => 'Tags successfully deleted'
                ));
        } else {
            echo json_encode(array(
                    'response' => FALSE,
                    'message' => 'You cannot delete this tags until there are user tags under it.'
                ));
        }
    }

    public function change_status($id) {
        $options = array('id' => $id);
        $query = $this->db->get_where('tbl_tags', $options, 1);
        $det=$query->row_array();
                
        if ($det['status'] === '1') {
            $status = '0';
            $txt="Inactive";
        } elseif ($det['status'] === '0') {
            $status = '1';
            $txt="Active";
        }

        $data = array('status' => $status);
        $this->db->where('id', $id);
        $this->db->update('tbl_tags', $data);
        echo $txt;
    }


    function _matches_other_tags($tag_name='', $id='') {
        if($this->tags_model->find_matching_tag($tag_name, $id)) {
            $this->form_validation->set_message('_matches_other_tags', 'The tag name already exists. Please provide a different tag name.');
            return false;
        } else {
            return true;
        }
    }

}
