<?php
class User extends CI_Model{
	// beginning of docblock template area
    /**
     * @access private
     * @var string 
     */
	private $_table = "employees"; //Table name of employees in database
	
	/**
     * @access private
     * @var string 
     */
	private $_login_Stutus='login_status'; //Table name of login_status in Database.
	
	//
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @param $email, $password
     * @access public
     * @var string 
     * @return array
     */
	public function check_login($email = '', $password = '') {
		$this->db->where('email', $email);
		$this->db->where('password', $password);
		$this->db->where('del_flag', 0);
		return $this->db->count_all_results($this->_table);
	}
	
	//
	public function get_id($email) {
		$this->db->select("employee_id");
        $this->db->where("email",$email);
        $query=$this->db->get($this->_table);
        if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$value = (int)$row->employee_id;
			} return $value;
		} return false;
	}
	
	//
	public function check_flag($id) {
		$this->db->select('del_flag');
		$this->db->where('employee_id',$id);
		$query =  $this->db->get($this->_table);
		if($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				$value = (int)$row->del_flag;
		    }
		    if($value == 0)
		    return true;
		} return false;
	}
	
	//
	public function add_user($email = '', $pass = '') {
		$arr = array(
		'email'                 => $email,
		'password'           =>$pass,
		'created'             =>date('Y-m-d',now()));
		$this->db->insert($this->_table, $arr);
		return true;
	}
	
	//
		public function update_user($id,$data) {
			$this->db->where('employee_id',$id);
			$this->db->update($this->_table, $data);
			return true;
		}
	
	//
	public function get_user_by_id($id) {
		$this->db->select('*');
		$this->db->where('employee_id',$id);
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0) {
		    $row = $query->row_array();    	
			return $row;
		} return false;
	}
	
	//
	public function get_info_user($email) {
		$this->db->select('*');
		$this->db->where('email',$email);
		$query = $this->db->get($this->_table);
		if($query->num_rows() > 0) {
		    $row = $query->row_array();    	
			return $row;
		} return false;
	}
	
	//
	public function forgot_password($id, $data) {
		$this->db->where('employee_id',$id);
		return $this->db->update($this->_table, $data);
	}
	
	//
	public function check_signature($employee_id= '') {
			if(!empty($employee_id)) {
				$this->db->select('employee_id');
		        $this->db->where('employee_id',$employee_id);
		        $query=$this->db->get($this->_login_Stutus);
		        if($query->num_rows() > 0) {
		        	foreach ($query->result() as $row) {
					    $value = (int)$row->employee_id;
					} return $value;
		        }
			} return false;
		}
	
	//
	public function update_signature($data,$id) {
		$this->db->where('employee_id',$id);
		return $this->db->update($this->_login_Stutus,$data);
	}
	
	//
	public function create_signature($data) {
		if($this->db->insert('login_status', $data)) return true;
		return false;
	}
	
	//
	public function get_id_by_sig($sig) {
		$this->db->select('employee_id');
		$this->db->where('signature',$sig);
		$result = $this->db->get($this->_login_Stutus);
		if($result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				$value = (int)$row->employee_id;
			} return $value;
		} return false;
	}
}
?>