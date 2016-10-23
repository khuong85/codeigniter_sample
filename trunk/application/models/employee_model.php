<?php
/**
 * This file contains functions used for showing user's info
 *
 * @package application
 * @subpackage controllers
 * @author Le Toan <le.toan@mulodo>
 * @name employee_model.php
 *
 */
class Employee_Model extends CI_Model {
	private $tableName;
	private $user_emp_id;
	protected $_gallery_path = "";
	protected $_gallery_url = "";

	public function __construct() {
		parent::__construct();
		$this -> load -> helper('path');
		$this -> tableName = 'employees';
		$this -> _gallery_path = set_realpath('./common/uploads');
		$this -> _gallery_url = base_url('common/uploads');
		$this -> load -> database();

		$this -> user_emp_id = $this -> session -> userdata('user_id');
	}

	/**
	 * Get info about profile like: name, origin, phone, address for employee
	 * @param int $id that is used to identify employee
	 * @return array contain info about employee
	 */
	public function get_profile($id) {
		$this -> db -> select('employee_id, name, photo, origin, date_of_birth, address, phone, hire_date, left_date, title, email, skill, self-introduction, role');
		$this -> db -> from('employees');
		$this -> db -> where('employee_id', $id);
		$results = $this -> db -> get();

		if ($results -> num_rows() > 0) {
			$row = array($results -> row_array());
			$temp = $this -> convern_employee_dateFormat($row);
			return $temp[0];
		} else {
			return NULL;
		}
	}

	/**
	 * Delele employee's profile by employee_id
	 * @param int $id that is used to identify employee
	 * @return boolean if deleting is successful then return true, otherwise return false value
	 */
	public function delete_profile($id) {
		$this -> db -> where('employee_id', $id);
		$data_updated = array('del_flag' => 1);
		$this -> db -> update('employees', $data_updated);
		return ($this -> db -> affected_rows() > 0);
	}

	/**
	 * Get the number of project assignment by employee_id
	 * @param int $employee_id that is used to identify user
	 * @return int the number of project assignment for user
	 */
	public function get_count_all_assignments($employee_id) {

		$value_condition = array($employee_id);
		$query = 'SELECT count(s.assignment_id) as number 
				FROM project_assignments s 
				where s.employee_id=?';

		$result = $this -> db -> query($query, $value_condition);
		$num_rows = $result -> row() -> number;
		return $num_rows;
	}

	/**
	 * Delete an project assignment by assignment_id
	 * @param int $id_delete that is used to identify for assignment
	 * @return boolean if deleting is successful then return true, otherwise return false value
	 */
	public function delete_project_assignment($id_delete) {
		$this -> db -> where('assignment_id', $id_delete);
		$this -> db -> delete('project_assignments');
		return ($this -> db -> affected_rows() > 0);
	}

	/**
	 * Get list project assignments by employee_id with limiting number_rows
	 * @param int $employee_id that is used to identify employee
	 * @param int $limit the number of rows which are used to show
	 * @param int $offset the offset for getting list project assignments
	 * @return array the array that containt info about limiting project assignments list
	 */
	public function get_list_project_assignments_limit($employee_id, $limit, $offset) {

		$value_condition = array($employee_id);
		$query = "
				Select p.name, s.assignment_id as assignment_id, s.start_date,
				(select count(*) from project_assignments c where c.project_id=p.project_id ) as SL
				From projects p, project_assignments s
				Where p.project_id=s.project_id and s.employee_id= ?
				Order by s.assignment_id desc
				Limit $offset, $limit";

		$result = $this -> db -> query($query, $value_condition);
		$temp = $result -> result_array();
		//change date format
		foreach ($temp as $key => $val) {
			$temp[$key]['start_date'] = $this->convern_dateFormat($val['start_date'], TRUE);
		}
		return $temp;
	}

	/**
	 * Get list evaluations by employee_id with limiting number_rows
	 * @param int $employee_id that is used to identify employee
	 * @param int $limit the number of rows which are used to show
	 * @param int $offset the offset for getting list evaluations
	 * @return array the array that containt info about limiting evaluations list
	 */
	public function get_list_evaluations_limit($employee_id, $limit, $offset) {
		$value_condition = array($employee_id);
		$query = "Select  evaluate_id, 
				  		  evaluated_date, detail, 
						  (select name from rank ra where ra.rank_id = eva.rank) as rank_name
					From evaluations eva Where employee_id=?
					Order by evaluate_id desc
					Limit  $offset, $limit";

		$result = $this -> db -> query($query, $value_condition);
		$temp = $result -> result_array();
		//change date format
		foreach ($temp as $key => $val) {
			$temp[$key]['evaluated_date'] = $this->convern_dateFormat($val['evaluated_date'], TRUE);
		}
		return $temp;
	}

	/**
	 * Get the employee of evaluations by employee_id
	 * @param int $employee_id that is used to identify employee
	 * @return int the number of project assignments for employee
	 */
	public function get_count_all_evaluations($employee_id) {
		$value_condition = array($employee_id);
		$query = "select count(*) as num from evaluations where employee_id = ?";
		$result = $this -> db -> query($query, $value_condition);
		$num_rows = $result -> row() -> num;
		return $num_rows;
	}

	/**
	 * Get ranks list which are used to evaluate for employee
	 * @return array the array that containt info about ranks list
	 */
	public function get_list_ranks() {
		$query = "select rank_id, name from rank";
		$result = $this -> db -> query($query);
		return $result -> result_array();
	}

	/**
	 * Check exist an evaluation with date for employee
	 * @param int $employee_id that is used to identify employee
	 * @param int $evaluated_date the date when employee is evaluated
	 * @return boolean  true if exist, otherwise is false value
	 */
	public function is_exist_evaluatedate_member($employee_id, $evaluated_date) {
		$array_condition = array("employee_id" => $employee_id, "evaluated_date" => $evaluated_date);
		$this -> db -> where($array_condition);
		$query = $this -> db -> get('evaluations');
		if ($query -> num_rows() > 0)
			return true;
		else
			return false;
	}

	/**
	 * Add evaluation for employee
	 * @param int $employee_id that is used to identify employee
	 * @param int $evaluated_date the date when employee is evaluated
	 * @param int  $rank the rank_id
	 * @param string $detail the more info about evaluation for employee
	 * @return boolean true if success, otherwise is false
	 */
	public function insert_evaluation_member($employee_id, $evaluated_date, $rank, $detail) {
		if ($this -> is_exist_evaluatedate_member($employee_id, $evaluated_date)) {
			//exist the same evaluation for member
			return false;
		} else {
			//not exist so able to insert
			$created = date('Y-m-d');
			$data = array('employee_id' => $employee_id, 'evaluated_date' => $this->convern_dateFormat($evaluated_date, FALSE), 'rank' => $rank, 'detail' => $detail, 'created' => $created);

			$this -> db -> insert('evaluations', $data);
			return ($this -> db -> affected_rows() > 0);
		}

	}

	/**
	 * Get more info about evaluation
	 * @param int $evaluate_id that is used to identify an evaluation for employee
	 * @return array the array contain more info about evaluation
	 */
	public function get_detail_evaluation($evaluate_id) {
		$value_condition = array($evaluate_id);
		$query = "select detail from evaluations where evaluate_id = ?";
		$result = $this -> db -> query($query, $value_condition);
		return $result -> row_array();
	}

	/**
	 * Delete an evalution for employee
	 * @param int $evaluate_id that is used to identify an evaluation for employee
	 * @return boolean the true if success, otherwise is false
	 */
	public function delete_evaluation($evaluate_id) {
		$this -> db -> where('evaluate_id', $evaluate_id);
		$this -> db -> delete('evaluations');
		return ($this -> db -> affected_rows() > 0);
	}

	/**
	 * Edit an evaluation for employee
	 * @param int $employee_id that is used to identify employee
	 * @param int $evaluated_date the date when employee is evaluated
	 * @param int  $rank the rank id
	 * @param string $detail the more info about evaluation for employee
	 * @return boolean true if success, otherwise is false
	 */
	public function edit_evaluation($evaluate_id, $evaluated_date, $rank, $detail) {
		$data = array('evaluated_date' => $this->convern_dateFormat($evaluated_date, FALSE), 'rank' => $rank, 'detail' => $detail);
		$this -> db -> where('evaluate_id', $evaluate_id);
		$this -> db -> update('evaluations', $data);
		return ($this -> db -> affected_rows() > 0);
	}

	/*
	 * =======================================================
	 * @author pham.an
	 */

	/**
	 * Use by employee management layout.
	 * @param int $number
	 * @param int $offset
	 */
	public function paging_items($number = 5, $offset = 0) {
		$this -> db -> where('del_flag = ', '0');
		$this -> db -> where('employee_id <> ', $this -> user_emp_id);
		$query = $this -> db -> get($this -> tableName, $number, $offset);
		return $this -> convern_employee_dateFormat($query -> result_array());
	}

	/**
	 * Search employee with employee_name. Use by employee management layout.
	 * @param string $str
	 * @param int $number
	 * @param int $offset
	 */
	public function search_items($str = NULL, $number = 5, $offset = 0) {
		$this -> session -> set_flashdata('search_string', $str);
		$this -> db -> where('del_flag = ', '0');
		$this -> db -> where('employee_id <> ', $this -> user_emp_id);
		$this -> db -> like('name', $str);

		$query = $this -> db -> get($this -> tableName, $number, $offset);
		return $this -> convern_employee_dateFormat($query -> result_array());
	}

	protected function convern_employee_dateFormat($vals, $currentFormatIsMySQL = TRUE) {
		foreach ($vals as $key => $val) {
			if (isset($val['hire_date']))
				$val['hire_date'] = $this -> convern_dateFormat($val['hire_date'], $currentFormatIsMySQL);
			if (isset($val['left_date'])) 
				$val['left_date'] = $this -> convern_dateFormat($val['left_date'], $currentFormatIsMySQL);
			if (isset($val['date_of_birth']))
				$val['date_of_birth'] = $this -> convern_dateFormat($val['date_of_birth'], $currentFormatIsMySQL);
			$vals[$key] = $val;
		}
		return $vals;
	}

	protected function convern_dateFormat($val, $currentFormatIsMySQL = TRUE) {
		if ($currentFormatIsMySQL)
			return ($val == '0000-00-00' || $val == '') ? NULL : date('d-m-Y', strtotime($val));
		else
			return ($val == '00-00-0000' || $val == '') ? NULL : date('Y-m-d', strtotime($val));
	}

	/**
	 * Count all employee in database. Use by employee management layout( pagination)
	 */
	public function count_all() {
		$this -> db -> from($this -> tableName);
		$this -> db -> where('del_flag = ', '0');
		$this -> db -> where('employee_id <> ', $this -> user_emp_id);
		$rs = $this -> db -> count_all_results();
		return $rs;
	}

	/**
	 * Count all result of search, with search string.
	 * @param string $str search string
	 */
	public function search_count($str = NULL) {
		$this -> db -> like('name', $str);
		$this -> db -> from($this -> tableName);
		$this -> db -> where('del_flag = ', '0');
		$this -> db -> where('employee_id <> ', $this -> user_emp_id);
		$rs = $this -> db -> count_all_results();
		return $rs;
	}

	/**
	 * Delete employee with employee_id
	 * @param int $emp_id
	 */
	public function delete_employee($emp_id) {
		$this -> db -> where('employee_id', $emp_id);
		$this -> db -> delete($this -> tableName);
	}

	/**
	 * Update employee with employee ID and some data.
	 * @param array $data
	 */
	public function update_profile($data) {
		//change dateformat
		$temp = $this -> convern_employee_dateFormat(array($data), FALSE);
		$data = $temp[0];
		$this -> db -> where('employee_id', $data['employee_id']);
		$this -> db -> update($this -> tableName, $data);
	}

	/**
	 * Load employee profile
	 * @param int $emp_id
	 */
	public function load_profile($emp_id) {
		$this -> db -> where('employee_id', $emp_id);
		$query = $this -> db -> get($this -> tableName);

		if ($query -> num_rows() > 0) {
			$row = array($query -> row_array());
			$temp = $this -> convern_employee_dateFormat($row);
			return $temp[0];
		} else {
			return NULL;
		}
	}

	/**
	 * Upload image.
	 * @param int $emp_id
	 */
	public function do_upload() {
		$config = array('upload_path' => $this -> _gallery_path, 'allowed_types' => 'gif|jpg|jpeg|png', 'max_size' => '2000');

		$this -> load -> library("upload", $config);

		if (!$this -> upload -> do_upload("photo")) {
			$error = array($this -> upload -> display_errors());
			return array('error' => $error[0]);
		} else {
			$image_data = $this -> upload -> data();

			return array('image_name' => $image_data['raw_name'] . $image_data['file_ext']);
		}
		$config = array("source_image" => $image_data['full_path'], "new_image" => $this -> _gallery_path . "/thumbs", "maintain_ration" => true, "width" => '150', "height" => "100");
		$this -> load -> library("image_lib", $config);
		$this -> image_lib -> resize();
	}

	/**
	 * Count all employee in database
	 * @param array $arrParam
	 * @author: Trieu Phan (phan.trieu@mulodo.com)
	 */
	public function countItem($arrParam) {
		$arrEmployeeId = explode(',', $arrParam['strEmployeeId']);
		$this -> db -> where_not_in('employee_id', $arrEmployeeId);
		if ($arrParam['keyword_employee'] != '') {
			$this -> db -> like('name', $arrParam['keyword_employee'], 'both');
		}
		$this -> db -> where('del_flag', 0);
		return $this -> db -> count_all_results($this -> tableName);
	}

	/**
	 * Load all employee in database
	 * @param array $arrParam
	 * @author: Trieu Phan (phan.trieu@mulodo.com)
	 */
	public function listItem($number = 0, $offset = 0, $arrParam, $options = null) {
		if ($options['task'] == 'employee-list') {
			$arrEmployeeId = explode(',', $arrParam['strEmployeeId']);
			$this -> db -> select('e.*');
			if ($arrParam['keyword_employee'] != '') {
				$this -> db -> like('name', $arrParam['keyword_employee'], 'both');
				$this->session->set_flashdata('condition_employee', $arrParam['keyword_employee']);
			}
			$this -> db -> where_not_in('e.employee_id', $arrEmployeeId);
			$this -> db -> where('del_flag', 0);
			$query = $this -> db -> get('employees as e', $number, $offset);
			return $query -> result_array();
		}
	}

	public function getEvaluationByEmployeeId($employeeId) {
		$query = "select * from evaluations where employee_id = $employeeId and evaluated_date = (select max(evaluated_date) from evaluations where employee_id = $employeeId)";
		$result = $this -> db -> query($query, array());
		return $result -> row_array();
	}

}
?>