<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @name: register.php
 * @author Le Phu
 * @package: register controller
 *
 */


class Register extends CI_Controller {
	var $_data;
	
	//Constructor
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session', 'form_validation', 'email'));
		$this->load->helper('date','url');
		$this->load->database();
		$this->load->model("User");
		$this->_data['message'] = $this->session->flashdata('message');
		$this->_data['error'] = $this->session->flashdata('error');
	}
	
	public function index() {
		
		if(isset($_COOKIE['MulodoVN'])) { 
			$cookie_value = $_COOKIE['MulodoVN'];
			$id_cookie = $this->User->get_id_by_sig($cookie_value);
			$del_flag = $this->User->check_flag($id_cookie);
			if(isset($del_flag) == true) {
				$info = $this->User->get_user_by_id($id_cookie);
				$name = (!empty($info['name']))?$info['name']:'Unname';
				$session_data = array('user_id'=> (int)$info['employee_id'],'name'=>$name,'email'=> $info['email'],'role'=>$info['role'],'logged_in' => true);
				$this->session->set_userdata($session_data);
				$log_session = $this->session->userdata('logged_in');
				if(!empty($log_session)) {
				redirect('employee/management_panel', 'refresh');
				}
			}
		}else {
			$log_session = $this->session->userdata('logged_in');
			$log_id = $this->session->userdata('user_id');
			$del_flag = $this->User->check_flag($log_id);
			if(isset($del_flag) == true) {
				if(!empty($log_session)) {
					redirect('employee/management_panel', 'refresh');
				} else {
					redirect('register/newuser');
				}
			}
		}
	}
	
	//Register new
	public function newuser() {
			$this->_data['title'] = 'Mulodo VN Employee Management';
			$this->_data['heading'] = 'Register new user';
		
			// invalid or initial request
			$email = $this->input->post('username', true);
			$ext_email = explode('@',$email);
			//$trueEmail = $ext_email[1];
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Email', "trim['Please enter email here']|min_length[6]|max_length[100]|required|valid_email['Sorry, there is no match for that email address']|strtolower");
			if($this->input->post('back')) {
				redirect('login','refresh');
			}
			if ($this->form_validation->run() == false) {
					// invalid or initial request
					$this->load->view('auth/register_view',$this->_data);
				} else if($this->input->post('cancel')){ 
					redirect('login');
				} else if(empty($email)) {
					$this->session->set_flashdata('error', 'Email not null, Please enter email here.');
					redirect('register/newuser');
				} else if($ext_email[1] != 'mulodo.com') {
					$this->session->set_flashdata('error', 'Please enter the email with extension @mulodo.com.');
					redirect('register/newuser');
				} else {
					if($this->check_user($email)) {
						if($this->signup($email)){
							$this->session->set_flashdata('message', 'Register successfull, please check your email to see login link.');
							redirect('register/newuser');
						} else {
							$this->session->set_flashdata('error', 'Registration failed. Please contact admin!');
							redirect('register/newuser');
						}
					} else {
						$this->session->set_flashdata('error', 'Email already existed, please go to Login or Forgot password.');
						redirect('register/newuser');
					}
				}
		}
		
	//check user existi?
	private function check_user($email='') {
		if($this->User->get_id($email) == 0) {
			return true;
		} return false;
	}
	
//Create new member
	private function signup($email) {
		$salt = 'MulodoVN';
		$pass = $email.time();
		$password_md = md5($pass);
		$password = sha1($password_md.$salt);
		//$add_result = $this->User->add_user($email, $password);
		
		//Send email
		$this->email->from('le.phu@mulodo.com', 'Mulodo Employees Management');
		$this->email->to($email); 
		$this->email->subject('Register - Mulodo Employees Management');
		$content  = 'Hello! <br/>';
		$content .= 'Congratulations. You have registered successfully.<br/>';
		$content .= 'Please user the following information to access your account:<br/>';
		$content .= 'ID: '.$email.' <br/>';
		$content .= 'Password: '.$pass.' <br/>';
		$content .= 'URL : http://www.staff.mulodo.com <br/>';
		$content .= 'Best regards, <br/>';
		$content .= 'Mulodo Manager Team <br/>';
		$this->email->message($content);
		
		if($this->email->send() && $this->User->add_user($email, $password)) {
				return true;
			} return false;
	}
	
	//Forgot password
	public function forgot_password() {
			$this->_data['title'] = 'Mulodo VN Employee Management';
			$this->_data['heading'] = 'Forgot your password';
			
			// invalid or initial request
			$email = $this->input->post('username', true);
			$ext_email = explode('@',$email);
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Email', "trim|required|valid_email|strtolower");
			if($this->input->post('back')) {
				redirect('login','refresh');
			}
			if ($this->form_validation->run() == false) {
					// invalid or initial request
					$this->load->view('auth/forgot_password_view',$this->_data);
				} else if($this->input->post('cancel')){ 
					redirect('');
				} else if($ext_email[1] != 'mulodo.com') {
					$this->session->set_flashdata('error', 'Please enter the email with extension @mulodo.com.');
					redirect('register/newuser');
				} else {
					$id = $this->User->get_id($email);
					if(isset($id) && $id > 0) {
						$salt = "MulodoVN";
						$pass = $email.time();
						$password_md = md5($pass);
						$password = sha1($password_md.$salt);
						$data = array('password'=>$password);
						//$forgot_password = $this->User->forgot_password($id, $data);
						
						//Send mail
						$this->email->from('le.phu@mulodo.com', 'Mulodo Employees Management');
						$this->email->to($email); 
						$this->email->subject('Forgot password - Mulodo Employees Management');
						$content  = 'Hello! <br/>';
						$content .= 'Your password has been reset successfully.<br/>';
						$content .= 'Please use new password to access your account: <br/>';
						$content .= 'ID: '.$email.' <br/>';
						$content .= 'Password: '.$pass.' <br/>';
						$content .= 'URL : http://www.staff.mulodo.com <br/>';
						$content .= 'Best regards, <br/>';
						$content .= 'Mulodo Manager Team <br/>';
						$this->email->message($content);
							
						if($this->email->send() && $this->User->forgot_password($id, $data)){
							$this->session->set_flashdata('message', 'New password has been sent to your email.');
							redirect('register/forgot_password');
							} else {
								$this->session->set_flashdata('error', 'Can\'t sent email to your email.');
							}
						} else {
							$this->session->set_flashdata('error', 'Email does not exist in database.');
							redirect('register/forgot_password');
						}
				}
	}
}