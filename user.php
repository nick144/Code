<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('template');
        $this->load->library("excel");
        $this->load->model('admin_model', 'admin_model');
       
        date_default_timezone_set('Asia/Calcutta');

    }

	public function index()
	{
		/*if (!$this->checklogin()) {
            redirect('admin/login', 'refresh');
        } else {*/

            $admin_user = $this->db->query("Select * from admin_user")->result_array();
            if(isset($_POST['export'])){
                $this->excel->setActiveSheetIndex(0);
                $fileName = rand().'-export.xls';
                $this->excel->stream($fileName, $admin_user);
            }

            $view = 'user_list';

            $data['admin_user'] = $this->db->query("Select * from admin_user")->result();

            $this->template->load_admin_template($view, $data);

        /*}*/
		
	}


    function create_user(){
        /*if(!$this->checklogin()){
            redirect('admin/login');
        }
        else{*/
            if (!empty($_POST['user_name']) && !empty($_POST['password'])) {
                $data = array(
                    'username' => $_POST['user_name'] ,
                    'password' => md5($_POST['password']) ,
                    'role' => $_POST['admin_role']
                 );

                 $this->db->insert('admin_user', $data); 
            }
            redirect('admin/user');
        /*}*/
    }


    function edit_user($id){
        if(!$this->checklogin()){
            redirect('admin/login');
        }
        else{
            if (!empty($_POST['user_name']) && !empty($_POST['password'])) {

                $data = array(
                    'username' => $_POST['user_name'] ,
                    'password' => md5($_POST['password']) ,
                    'role' => $_POST['admin_role']
                 );

                $this->db->update('admin_user', $data, array('id' => $id));
            }


            $view = 'edit_user';

            $data['admin_user'] = $this->admin_model->getUserById($id);

            $this->template->load_admin_template($view, $data);
        }
    }


    function delete_user($id){
        if(!$this->checklogin()){
            redirect('admin/login');
        }
        else{
            $this->db->where('id', $id);
            $this->db->delete('admin_user');
            redirect('admin/user');
        }
    }
    


	function checklogin() {
        $temp = $this->session->userdata('admin_id');
        if ($temp != '') {
            return TRUE;
        }
        else
            return FALSE;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */