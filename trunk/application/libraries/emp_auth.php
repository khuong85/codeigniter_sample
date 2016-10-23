<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Le  Phu
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

//---------------------------



/**
 * MY_session Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		Le  Phu
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class Emp_auth{
 	var $data;
 	protected  $ci;
 	/**
	 * MY_session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	function __construct() {
		//parent::__construct();
		$this -> ci = &get_instance();
	}

	/**
	 * Get status login
	 *
	 * @access  public
	 * @return  void
	 **/
	public function is_logged() {
		return (bool)$this -> ci -> session -> userdata('logged_in');
	}

	/**
	 * Get permission (Admin require)
	 *
	 * @access  public
	 * @return  void
	 **/
	public function is_admin() {
		$admin_group = '1';
		$user_group = $this -> ci -> session -> userdata('role');

		return $user_group == $admin_group;
	}
	/**
		 * Get permission (Admin require)
		 *
		 * @access  public
		 * @return
		 **/
		public function is_root() {
			$admin_root = '2';
			$user_group = $this -> ci -> session -> userdata('role');
			return $user_group == $admin_root;
		}
	/**
	 * This is function check del flag, if del_flag colunm in database = 0 is OK, else redirect to hompage.
	 * @access public
	 * @return boolean
	 **/
	public function check_flag() {
		$this->ci->load->database();
		$this->ci->load->model('User');
		$id = $this->ci->session->userdata('user_id');
			if($this->ci->User->check_flag($id)) {
				return true;
			} else {
				$this->ci->session->sess_destroy();
				if(isset($_COOKIE['MulodoVN'])) {
					delete_cookie('MulodoVN');
				}
				redirect('');
			}
		}

	
	

	/*
	* @author: Toan Le 
	* Get role for user
	* You can use this method to get permission for user
	*
	* @return boolean 2: your self, 1: Admin, 3: normal user
	* */
	function get_role() {
		//$this->CI = &get_instance();
		$is_login = $this->ci->session->userdata('logged_in');
		$role = $this->ci->session->userdata('role');
		
		if($is_login) {
			if($role == 1) {
				//admin
				return 1;
			} else if ($role == 0) {				
					if($this->ci->session->userdata('employee_id') == $this->ci->session->userdata('user_id')) {
						return 2;
						//you with your id (you can do some task with your profile)
					} else {
						//normal user: you see another profile
						return 3;
					}		
			} else if ($role == 2) {
				
				//super admin,  yourself
				if($this->ci->session->userdata('employee_id') == $this->ci->session->userdata('user_id')) {	
					return 4;
				} else {
					//super admin, not yourself
					return 5;
				}
			}
		} else {
			redirect("login");
		}
	}
	
	/**
	 * Get session
	 *
	 * @access  private
	 * @return  void
	 **/
	/*function getSession() {
	 return $this->all_userdata();
	 }*/

	/**
	 * Get permission ( Themeself require)
	 *
	 * @access public
	 * @return boolean TRUE: is themeself
	 */
	public function is_themeself($id = NULL) {
		//$emp_id = $this -> getSegment(3);
		$emp_id = $this -> ci -> uri -> segment(3);
		$emp_id = $emp_id ? $emp_id : $id;
		$user_id = $this -> ci -> session -> userdata('user_id');

		if ($emp_id == $user_id)
			return TRUE;
		else
			return FALSE;
	}

}

// END MY_session Class

/* End of file MY_session.php */
/* Location: ./application/libraries/MY_session.php */