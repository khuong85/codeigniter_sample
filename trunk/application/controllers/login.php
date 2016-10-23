<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @name: login.php
 * @author Le Phu
 * @package: login controller
 *
 */


class Login extends CI_Controller {
	public $_data;
	//Constructor
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'form_validation', 'email'));
		$this->load->helper(array('date','url','cookie'));
		$this->load->database();
		$this->load->model("User");
		$this->_data['message'] = $this->session->flashdata('message');
		$this->_data['title'] = 'Mulodo VN Employee Management';
		$this->_data['heading'] = 'Login';
	}
	 
	// callback function with multiple parameters
	function _valid_login($str='') {
		//list($username, $pass) = explode(',', $params);
		$username = $this->input->post('username', true);
		$pass =  trim($this->input->post('password', true)," ");
		$salt = 'MulodoVN';
		$password_md = md5($pass);
		$password = sha1($password_md.$salt);
		
		if(empty($username) && empty($pass)) {
			$this->form_validation->set_message('_valid_login', 'The Username and Password fields is required.');
		} else if(empty($username)) {
			$this->form_validation->set_message('_valid_login', 'The Username field is required.');
		} else if(empty($pass)) {
			$this->form_validation->set_message('_valid_login', 'The Password field is required.');
		}else {
			if($this->User->check_login($username, $password) > 0) {
				return true;
			} else {
				$this->form_validation->set_message('_valid_login', 'Invalid email or password, please try again!');
			}
		}
		return false;
	}
	
	//Default function, it's the first 
	public function index() {
		if(isset($_COOKIE['MulodoVN'])) { 
			$cookie_value = $_COOKIE['MulodoVN'];
			$id_cookie = $this->User->get_id_by_sig($cookie_value);
			$del_flag = $this->User->check_flag($id_cookie);
			if(isset($del_flag) == true) {
				$info = $this->User->get_user_by_id($id_cookie);
				$name = (!empty($row['name']))?$row['name']:'Unname';
				$session_data = array('user_id'=> (int)$info['employee_id'],'name'=>$name,'email'=> $info['email'],'role' => $info['role'],'logged_in' => true);
				$this->session->set_userdata($session_data);
				$log_session = $this->session->userdata('logged_in');
				if(!empty($log_session)) {
					redirect('employee/management_panel', 'refresh');
				}
			} else {
				$this->logout();
			}
		} else {
			$log_session = $this->session->userdata('logged_in');
			$log_id = $this->session->userdata('user_id');
			$del_flag = $this->User->check_flag($log_id);
			if(isset($del_flag) == true) {
				if(!empty($log_session)) {
					redirect('employee/management_panel', 'refresh');
				} else {
					//$this->load->view('auth/login',$data);
					$username = $this->input->post('username', true);
					$password = $this->input->post('password', true);
					$remember = $this->input->post('remember', true);
					//$params = "{$username},{$password}";
					$this->load->library('form_validation'); //|callback__foo[bar]
					if(isset($username) && isset($password)) {
							if(empty($username) && empty($password)) {
								$this->form_validation->set_rules('data[username]', 'Username', "max_length[50]['The email address field can not exceed 50 characters in length']|required|valid_email|strtolower|xss_clean");
								$this->form_validation->set_rules('data[password]', 'Password', "min_length[6]|max_length[50]|required|strtolower|xss_clean");
							} else {
								$this->form_validation->set_rules('data[login]', 'Login', "strtolower|xss_clean|callback__valid_login");
							}
						}
					if ($this->form_validation->run() == false) {
						// invalid or initial request
						$this->load->view('auth/login',$this->_data);
					} else {
						//ob_start();
						$this->load->model('User');
						$row = $this->User->get_info_user($username);
						$name = (!empty($row['name']))?$row['name']:'Unname';
						$session_data = array('user_id'=>(int)$row['employee_id'],'name'=>$name,'email'=> "$username",'role'=>$row['role'],'logged_in' => true);
						$this->session->set_userdata($session_data);
						if($remember) {
							$this->login_status($row['employee_id'], $username);
						}//ob_end_flush();ob_end_clean();
						redirect('employee/management_panel');
					}
				}
			} else {
				$this->logout();
			}
		}
	}
	
	//Check status login 
	private function login_status($id, $username) {
		$sig = md5($username.time());
		$today = date("Y-m-d H:i:s");
		$expire_day = date("Y-m-d H:i:s",strtotime ("+7 days"));
		$data = array("employee_id" =>$id,"signature"=>$sig,"expire_date"=>$expire_day,"created"=>$today);
		$update_data = array("signature"=>$sig,"expire_date"=>$expire_day);
		$employee_id = $this->User->check_signature($id);
		 if(isset($employee_id) &&  $employee_id > 0) {
				if($this->User->update_signature($update_data,$id)) {
					$this->create_cookie($sig,$expire_day);
				}
		} else {
				if($this->User->create_signature($data)) {
					$this->create_cookie($sig,$expire_day);
				}
		} 
		//redirect('employee/management_panel');
	}
	
	//check user existi?
	private function check_user($email='') {
		$this->load->model("User");
		if($this->User->get_id($email)) {
			return true;
		} return false;
	}
	
	//Logout system
	public function logout() {
		$this->session->sess_destroy();
		if(isset($_COOKIE['MulodoVN'])) {
			$this->destroy_cookie();
		}
		redirect('login');
	}
	
	//Create new cookie
	 function create_cookie($sig,$expire_day) {
		set_cookie("MulodoVN", $sig, strtotime($expire_day));
	}
	
	//Remove cookie
	function destroy_cookie() {
		delete_cookie('MulodoVN');
	}
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>