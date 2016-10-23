<?php

/**
 * This file contains functions used for showing user's info
 *
 * @package application
 * @subpackage controllers
 * @author Le Toan <le.toan@mulodo>, Pham An
 * @name employee.php
 *
 */

class Employee extends CI_Controller {
	private $paging_config;
	private $upload_path;

	/**
	 * Constructor for Employee controller
	 */
	public function __construct() {
		parent::__construct();
		$this -> emp_auth -> get_role();
		$this -> construct();
		$this -> load -> model('employee_model');
		$this -> load -> library('pagination');
	}

	public function index() {
		$this -> management_panel();
	}

	/**
	 * View more detail about employee
	 * @param int $id is used to identify for employee
	 * @return void
	 */
	public function employee_detail($id = 1) {
		$this -> session -> set_userdata('last_url', 'employee/employee_detail/' . $id);
		$data['title'] = 'Mulodo VN Employee Management';
		$this -> session -> set_userdata('employee_id', $id);
		$data['isAccess'] = $this -> emp_auth -> get_role();
		$offset = 0;

		//get profile from user
		$user_info = $this -> employee_model -> get_profile($id);
		$num_rows_assignments = $this -> employee_model -> get_count_all_assignments($id);

		//for pagination list project assignments
		$config['base_url'] = base_url('/employee/get_list_project_assignments');
		$config['total_rows'] = $num_rows_assignments;
		$config['per_page'] = COUNT_PER_PAGE;
		$config['uri_segment'] = 4;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['full_tag_open'] = "<div class='pagination pagination-left'>";
		$config['full_tag_close'] = "</div>";

		$pagination_1 = new CI_Pagination();

		$pagination_1 -> initialize($config);

		//set list assignments, evaluations, user_info, items and create_links to pass through view
		$data['list_assignments'] = $this -> employee_model -> get_list_project_assignments_limit($id, COUNT_PER_PAGE, $offset);
		$data['create_links_project_assignments'] = $pagination_1 -> create_links();
		$data['user_info'] = $user_info;

		//for pagination list evaluations
		$num_rows_evalutions = $this -> employee_model -> get_count_all_evaluations($id);
		$pagination_2 = new CI_Pagination();
		$config['base_url'] = base_url('/employee/get_list_evaluations');
		$config['total_rows'] = $num_rows_evalutions;
		$config['per_page'] = COUNT_PER_PAGE;
		$config['uri_segment'] = 4;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['full_tag_open'] = "<div class='pagination pagination-left'>";
		$config['full_tag_close'] = "</div>";

		$pagination_2 -> initialize($config);
		//set list evaluation
		$data['evaluations'] = $this -> employee_model -> get_list_evaluations_limit($id, COUNT_PER_PAGE, $offset);
		$data['create_links_evaluations'] = $pagination_2 -> create_links();
		//$this->load->view('employee/employee_view_index', $data);

		$this -> template -> current_view = ('employee/employee_view_index');
		$this -> template -> set($data);
		$this -> template -> render();
	}

	/**
	 * Get limited project assignments list
	 * @param int $offset is used to specify offset for pagination
	 * @return void
	 */
	public function get_list_project_assignments($offset = 0) {
		//get list project assignments from user
		$id = $this -> session -> userdata('employee_id');

		$num_rows_assignments = $this -> employee_model -> get_count_all_assignments($id);
		//for pagination
		$config['base_url'] = base_url('/employee/get_list_project_assignments');

		$config['total_rows'] = $num_rows_assignments;
		$config['per_page'] = COUNT_PER_PAGE;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['full_tag_open'] = "<div class='pagination pagination-left'>";
		$config['full_tag_close'] = "</div>";

		$this -> pagination -> initialize($config);

		$data['list_assignments'] = $this -> employee_model -> get_list_project_assignments_limit($id, COUNT_PER_PAGE, $offset);
		$data['create_links_project_assignments'] = $this -> pagination -> create_links();
		$data['isAccess'] = $this -> emp_auth -> get_role();
		$this -> load -> view('employee/project_assignment_view', $data);
	}

	/**
	 * Get limited evaluations list
	 * @param int $offset is used to specify offset for pagination
	 * @return void
	 */
	public function get_list_evaluations($offset = 0) {
		$id = $this -> session -> userdata('employee_id');
		$num_rows_evaluations = $this -> employee_model -> get_count_all_evaluations($id);

		//for pagination
		$config['base_url'] = base_url('/employee/get_list_evaluations');
		$config['total_rows'] = $num_rows_evaluations;
		$config['per_page'] = COUNT_PER_PAGE;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['full_tag_open'] = "<div class='pagination pagination-left'>";
		$config['full_tag_close'] = "</div>";

		$this -> pagination -> initialize($config);

		$data['evaluations'] = $this -> employee_model -> get_list_evaluations_limit($id, COUNT_PER_PAGE, $offset);
		$data['create_links_evaluations'] = $this -> pagination -> create_links();
		$data['isAccess'] = $this -> emp_auth -> get_role();
		echo $this -> load -> view('employee/evaluation_user_view', $data, true);
	}

	/**
	 * Delete profile by employee id
	 * @param int $id_delete is used to identify for employee that is deleted
	 * @return boolean if deleting employee is successful then return true, otherwise return false value
	 */
	public function delete_profile($id_delete) {
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			$is_success = $this -> employee_model -> delete_profile($id_delete);
			if ($is_success) {
				redirect('employee/management_panel');
			} else {
				echo FALSE;
			}
		}
	}

	/**
	 * Delete project assignment by id
	 * @param int $id_delete is used to identify project assignment that is deleted
	 * @return if deleting project assignment is successful then return the remain list, otherwise return false value
	 */
	public function delete_project_assignment($id_delete) {
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			$is_success = $this -> employee_model -> delete_project_assignment($id_delete);
			if ($is_success === true) {
				return $this -> get_list_project_assignments();
			} else {
				echo $is_success;
			}
		}
	}

	/**
	 * Get list rank to show for dropdown list
	 * @return string json string that show ranks list
	 */
	public function get_list_ranks() {
		echo json_encode($this -> employee_model -> get_list_ranks());
	}

	/**
	 * Insert an evaluation for member
	 * @return if inserting an avaluation is successful then return the new evaluations list, otherwise return false value
	 */
	public function insert_evaluation_member() {
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			$id = $this -> session -> userdata('employee_id');
			$rank_id = $this -> input -> post("rank_id");
			$date_evaluation = $this -> input -> post("date_evaluation");
			$detail = $this -> input -> post("detail");

			$success = $this -> employee_model -> insert_evaluation_member($id, $date_evaluation, $rank_id, $detail);
			if ($success === true) {
				//redirect("employee/myprofile", false);
				$this -> get_list_evaluations();
			} else {
				echo $success;
			}
		}
	}

	/**
	 * Get detail about employee's evaluation
	 * @return string info about evaluate rank for employee
	 */
	public function get_detail_evaluation() {
		$evaluate_id = $this -> input -> get('evaluate_id');
		$detail_evaluation = $this -> employee_model -> get_detail_evaluation($evaluate_id);

		echo $detail_evaluation['detail'];
	}

	/**
	 * Delete an evaluation for employee by evaluate_id
	 * @param int $evaluate_id is used to specify for an evaluation with employee that is deleted
	 * @return if deleting an evaluation is successful then return the remain evaluations list, otherwise return false value
	 */
	public function delete_evaluation($evaluate_id) {
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			//$evaluate_id = $this->input->get('evaluate_id');
			$is_success = $this -> employee_model -> delete_evaluation($evaluate_id);

			if ($is_success === true) {
				//redirect("employee/myprofile", false);
				//$this->get_list_evaluations();
				echo $is_success;
			} else {
				//echo "There are some problem with your operation so it's failed";
				echo $is_success;
			}
		}
	}

	/**
	 * Edit an evaluation for employee by evaluate_id
	 * @return string the new evaluations list after updating info for an evaluation
	 */
	public function edit_evaluation() {
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			$evaluate_id = $this -> input -> post('evaluate_id');
			$rank = $this -> input -> post('rank');
			$evaluated_date = $this -> input -> post('evaluated_date');
			$detail = $this -> input -> post('detail');

			$is_success = $this -> employee_model -> edit_evaluation($evaluate_id, $evaluated_date, $rank, $detail);
			return $this -> get_list_evaluations();
		}
	}

	/*
	 * =======================================================
	 * @author pham.an
	 */

	private function construct() {
		// paging config
		$this -> load -> library(array('form_validation'));
		$this -> load -> helper(array('string', 'path'));

		$this -> paging_config['per_page'] = COUNT_PER_PAGE;
		$this -> paging_config['uri_segment'] = 4;
		$this -> upload_path = set_realpath('./common/uploads');

		$this -> load -> model('project_assignments');
		$this -> load -> model('projects');
		$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');
	}

	/**
	 * Show Employee Management.
	 */
	public function management_panel() {
		$this -> session -> set_userdata('last_url', 'employee/management_panel/');
		$data['title'] = 'Mulodo VN Employee Management';
		$data['heading'] = 'Employee Management';

		$config["base_url"] = base_url() . "employee/management_panel";
		$config["total_rows"] = $this -> employee_model -> count_all();
		$config["per_page"] = COUNT_PER_PAGE;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config["uri_segment"] = 3;

		$this -> pagination -> initialize($config);

		$page = $this -> uri -> segment(3, 0);
		$data["vals"] = $this -> employee_model -> paging_items($config["per_page"], $page);

		$data['pagitation'] = $this -> pagination -> create_links();
		$this -> template -> current_view = ('employee/management');
		$this -> template -> set($data);
		$this -> template -> render();
	}

	/**
	 * Search employee. Show list result.
	 * Send search string with $_POST val.
	 */
	public function search_employee() {
		$this -> session -> set_userdata('last_url', 'management_panel/');
		$data['title'] = 'Mulodo VN Employee Management';
		$data['heading'] = 'Employee Management';

		$temp = $this -> input -> post('search_string');
		$flash_str = $this -> session -> flashdata('search_string');
		$str = !empty($temp) ? $temp : $flash_str;

		$new_str_flag = $this -> input -> post('new_str');
		if ($new_str_flag == 1)
			$str = $temp;

		if (empty($str))
			redirect('employee/management_panel');

		$config["base_url"] = base_url() . "employee/search_employee/";
		$search_count = $this -> employee_model -> search_count($str);

		if ($search_count > 0) {
			$config["total_rows"] = $search_count;
			$config["per_page"] = COUNT_PER_PAGE;
			$config["uri_segment"] = 3;
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Previous';
			$config['full_tag_open'] = "<div class='pagination pagination-left'>";
			$config['full_tag_close'] = "</div>";

			$this -> pagination -> initialize($config);

			$page = $this -> uri -> segment(3, 0);
			$data["vals"] = $this -> employee_model -> search_items($str, $config["per_page"], $page);
			$data['pagitation'] = $this -> pagination -> create_links();
			$data['search_string'] = $str;
		} else {
			$data['not_found'] = 1;
			$data['search_string'] = $str;
		}
		$this -> template -> current_view = ('employee/management');
		$this -> template -> set($data);
		$this -> template -> render();
	}

	/**
	 * Show layout Update employee profile.
	 * @param int $emp_id
	 */
	public function update_employee($emp_id = NULL, $error = NULL) {
		if ($emp_id) :
			$data['title'] = 'Mulodo VN Employee Management';
			$data['heading'] = 'Update Employee';
			$data['permission'] = FALSE;
			$data['error_message'] = $this -> session -> userdata('error_message');
			$data['success_flag'] = FALSE;
			if (!empty($data['error_message']))
				$this -> session -> unset_userdata('error_message');

			if ($this -> input -> post('cancel')) {
				redirect('employee/management_panel', 'refresh');
			}

			if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_themeself($emp_id) || $this -> emp_auth -> is_root()) {
				$data['profile'] = $this -> employee_model -> load_profile($emp_id);
				$data['profile']['is-admin'] = ($data['profile']['role'] == 1) ? TRUE : FALSE;
				$data['employee_id'] = $emp_id;
				$data['permission'] = TRUE;
				$data['success_flag'] = $this -> session -> userdata('success_flag');
				if ($data['success_flag'])
					$this -> session -> set_userdata('success_flag', FALSE);
				$error = $this -> session -> userdata('error_message');
				if (!empty($error['type'])) {
					$data['error_message'] = $error;
					$this -> session -> unset_userdata('error_message');
				}
			} else {
			}
			$this -> template -> current_view = ('employee/update');
			$this -> template -> set($data);
			$this -> template -> render();
		else :
			redirect('employee/management_panel');
		endif;
	}

	/**
	 * Use for update employee profile.
	 * Get data in POST val, and save with employee_model/update_profile.
	 */
	public function save_profile() {
		$data = $this -> input -> post();
		if ($this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root() || $this -> emp_auth -> is_themeself($data['employee_id'])) {
			$email = $this -> input -> post('email');
			$hire_date = $this -> input -> post('hire_date');
			$left_date = $this -> input -> post('left_date');
			$date_of_birth = $this -> input -> post('date_of_birth');
			$pass = $this -> input -> post('password');
			$temp = $this -> input -> post('title');

			$json_param = json_encode(array('hire_date' => $hire_date, 'left_date' => $left_date));

			$this -> form_validation -> set_rules('phone', 'Phone', 'integer|max_length[13]');
			$this -> form_validation -> set_rules('date_of_birth', 'Date of birth', 'callback_check_date');
			$this -> form_validation -> set_rules('hire_date', 'Hire Date', 'callback_check_date');
			$this -> form_validation -> set_rules('left_date', ' Left Date', "callback_check_date|callback_check_leftDate[{$json_param}]");
			$this -> form_validation -> set_rules('photo', 'Photo', 'max_length[254]');

			$this -> form_validation -> set_rules('name', 'Name', 'max_length[30]|callback_check_NonEmpty');
			$this -> form_validation -> set_rules('origin', 'Origin', 'max_length[50]|callback_check_NonEmpty|callback_check_alphaSpace');
			$this -> form_validation -> set_rules('address', 'Address', 'max_length[254]|callback_check_NonEmpty');
			$this -> form_validation -> set_rules('title', 'Title', 'max_length[100]|callback_check_NonEmpty');
			$this -> form_validation -> set_rules('skill', 'Skill', 'max_length[500]|callback_check_NonEmpty|my_regex');
			$this -> form_validation -> set_rules('self-introduction', 'Self-Introduction', 'max_length[500]|callback_check_NonEmpty');

			if ($this -> emp_auth -> is_themeself($data['employee_id']) && !empty($pass)) {
				$this -> form_validation -> set_rules('password', 'Password', 'trim|min_length[6]|max_length[32]|xss_clean');
			}

			//validating
			if ($this -> form_validation -> run() == FALSE) {
				$error['type'] = 'field';
				$error['message'] = validation_errors();
				$this -> session -> set_userdata('error_message', $error);
				$this -> update_employee($data['employee_id']);
			} else {//success
				//change password
				if ($this -> emp_auth -> is_themeself($data['employee_id']) && !empty($pass)) {
					$salt = 'MulodoVN';
					$pass = $email . time();
					$password_md = md5(trim($data['password']));
					// trim pass
					$data['password'] = sha1($password_md . $salt);
				} else
					unset($data['password']);

				// email do not change
				unset($data['email']);

				// change permission with check admin role
				if ($this -> emp_auth -> is_root() && isset($data['is-admin'])) {
					if ($data['is-admin'] == 1) {
						$data['role'] = 1;
					} else
						$data['role'] = 0;
				}

				unset($data['is-admin']);
				unset($data['del_flag']);
				foreach ($data as $key => $val)
					if (empty($val))
						$data[$key] = NULL;
				$labels = array('name', 'origin', 'address', 'title', 'skill', 'self-introduction');
				foreach ($labels as $key) {
					$data[$key] = trim($data[$key]);
				}
				// change name in status bar.
				if ($this -> emp_auth -> is_themeself($data['employee_id']))
					$this -> session -> set_userdata('name', $data['name']);

				$this -> employee_model -> update_profile($data);
				$this -> session -> set_userdata('success_flag', TRUE);
				redirect('employee/update_employee/' . $data['employee_id']);
			}
		} else {
			redirect('employee/management_panel/');
		}
	}

	/**
	 * Do upload action.
	 * Only upload image, max size: 200x300
	 */
	function do_upload($emp_id = NULL) {
		if (isset($emp_id)) :
			if ($this -> emp_auth -> is_themeself($emp_id) || $this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
				$this -> load -> helper('file');
				$temp = $this -> employee_model -> do_upload();
				if (!empty($temp['error'])) {
					$error['type'] = 'image';
					$error['message'] = $temp['error'];
					$this -> session -> set_userdata('error_message', $error);
				} else {
					$image_name = $temp['image_name'];

					$config['image_library'] = 'gd2';
					$config['allowed_types'] = 'gif|jpg|jpeg';
					$config['source_image'] = './common/uploads/' . $image_name;
					$config["new_image"] = './common/uploads/thumb_' . $image_name;
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 200;
					$config['height'] = 200;

					$this -> load -> library('image_lib', $config);

					$this -> image_lib -> resize();
					delete_files($config['source_image']);
					$new_image_name = '/thumb_' . $image_name;
					// save to DB.
					$this -> employee_model -> update_profile(array('employee_id' => $emp_id, 'photo' => $new_image_name));
				}
				$data['permission'] = TRUE;
			}
			redirect('employee/update_employee/' . $emp_id);
		else :
			redirect('employee/management_panel');
		endif;
	}

	/**
	 * For Assign project panel
	 */

	/**
	 * Show Assign project panel for employee.
	 */
	public function assign_project($employee_id = NUL) {
		$data['title'] = 'Mulodo VN Employee Management';
		$data['heading'] = 'Update Employee';
		$this -> employee_id = $employee_id;
		$this -> project_assignments -> set_employee_id($this -> employee_id);

		$data['employee_id'] = $this -> employee_id;
		$this -> template -> current_view = ('employee/assign_project');
		$data['permission'] = FALSE;

		if ($this -> emp_auth -> is_themeself($employee_id) || $this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			$data['arr_project'] = $this -> project_assignments -> get_projects(NULL, $this -> paging_config['per_page'], 0);

			$this -> paging_config['base_url'] = base_url('employee/search/' . $this -> employee_id);
			$this -> paging_config['total_rows'] = $this -> project_assignments -> search_count(NULL);
			$this -> paging_config['full_tag_open'] = '<div class="pagination pagination-left paging_links">';
			$this -> paging_config['full_tag_close'] = '</div>';
			$this -> paging_config['next_link'] = 'Next';
			$this -> paging_config['prev_link'] = 'Previous';

			$this -> pagination -> initialize($this -> paging_config);
			$data['pagination'] = $this -> pagination -> create_links();
			$data['permission'] = TRUE;
			$data['success_flag'] = $this -> session -> userdata('success_flag');
			if ($data['success_flag'] == TRUE)
				$this -> session -> set_userdata('success_flag', FALSE);
			$error = $this -> session -> userdata('error_message');
			if (!empty($error['type'])) {
				$data['error_message'] = $error;
				$this -> session -> unset_userdata('error_message');
			}
		}
		$this -> template -> set($data);
		$this -> template -> render();
	}

	/**
	 * Saving on Project_assignment
	 */
	public function save_assign() {
		$this -> employee_id = $this -> input -> post('employee_id');
		if ($this -> emp_auth -> is_themeself($this -> employee_id) || $this -> emp_auth -> is_admin() || $this -> emp_auth -> is_root()) {
			if (empty($this -> employee_id))
				redirect('employee/management_panel');
			$this -> project_assignments -> set_employee_id($this -> employee_id);
			$vals = $this -> input -> post(NULL, TRUE);
			$start_date = $this -> input -> post('start_date');
			$end_date = $this -> input -> post('end_date');
			$projects = isset($vals['project_id']) ? $vals['project_id'] : array();
			$date_param = json_encode(array($start_date, $end_date));

			$this -> form_validation -> set_error_delimiters('<div class="error">', '</div>');

			$this -> form_validation -> set_rules('project_id', 'Project ID', "callback_check_array|callback_check_projectAssign[{$date_param}]");
			$this -> form_validation -> set_rules('employee_id', 'Employee ID', 'required|integer');
			$this -> form_validation -> set_rules('start_date', 'Start date', 'required');
			$this -> form_validation -> set_rules('end_date', 'End date', "callback_check_assignDate[{$date_param}]");

			//require duplicate data
			if ($this -> form_validation -> run() != FALSE) {
				$this -> project_assignments -> assignment($projects, $start_date, $end_date);
				$this -> session -> set_userdata('success_flag', TRUE);
			} else {
				$error['type'] = 'field';
				$error['message'] = validation_errors();
				$this -> session -> set_userdata('error_message', $error);
			}
			$this -> assign_project($this -> employee_id);
		} else
			redirect('employee/');
	}

	/**
	 * Use for AJAX pagination in popup Project Assignment.
	 */
	public function search($employee_id = NULL) {
		$this -> employee_id = $employee_id;
		$this -> project_assignments -> set_employee_id($this -> employee_id);
		$str = $this -> input -> post('search_str');
		$str = $str == 'Search by name' ? NULL : $str;

		$data['arr_project'] = $this -> project_assignments -> get_projects($str, $this -> paging_config['per_page'], $this -> uri -> segment(4));
		if (count($data['arr_project']) > 0) {
			$this -> paging_config['base_url'] = base_url('employee/search/' . $this -> employee_id);
			$this -> paging_config['total_rows'] = $this -> project_assignments -> search_count($str);
			$this -> paging_config['full_tag_open'] = '<div class="pagination pagination-left paging_links">';
			$this -> paging_config['full_tag_close'] = '</div>';
			$this -> paging_config['next_link'] = 'Next';
			$this -> paging_config['prev_link'] = 'Previous';

			$this -> pagination -> initialize($this -> paging_config);
			$data['pagination'] = $this -> pagination -> create_links();
			$this -> load -> view('employee/popup_content', $data);
		} else {
			echo '<h4>Search not found.</h4>';
		}
	}

	/**
	 * Check array as int array.
	 * @param array $array
	 */
	public function check_array($arr_project) {
		// check null array
		$count = count($arr_project);
		if ($count <= 0) {
			$this -> form_validation -> set_message('check_array', 'Please select some project.');
			return FALSE;
		}
		// check num array
		for ($i = 0; $i < $count; $i++) {
			if (!is_numeric($arr_project[$i])) {
				$this -> form_validation -> set_message('check_array', 'Some thing wrong with %s, please contact to Admin.');
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Check date.
	 * @param string $d Format as yy-dd-mm
	 */
	public function check_date($d = NULL) {
		// var_dump($d);
		$this -> form_validation -> set_message('check_date', 'This %s is not date time format(dd-mm-yyyy).');
		if (!empty($d)) {// missing empty val
			$arr = explode('-', $d);
			if (count($arr) == 3) {
				if (!(empty($arr['0']) && empty($arr['1']) && empty($arr['2']))) {
					if (checkdate($arr[1], $arr[0], $arr[2]))//mm-dd-yy
						return TRUE;
				}
			}
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Check start date less than start date of project
	 * @param string Date of assignment
	 * @param json_string List project id
	 */
	public function check_startDate($ass_start_date = NULL, $json_param = NULL) {
		//$this -> form_validation -> set_message('check_startDate', 'Need check start date less than start date of project.');
		//return false;
		if ($ass_start_date && $json_param) {
			$ass_date = strtotime($ass_start_date);
			$param = json_decode($json_param, TRUE);
			$projects = $param[0];

			foreach ($projects as $project_id) {
				$s_date = $this -> projects -> getStartDate($project_id);
				$e_date = $this -> projects -> getEndDate($project_id);
				if ($ass_date < $s_date || $e_date > $ass_date) {
					$this -> form_validation -> set_message('check_startDate', "Need select start date between start-end date of project.");
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**
	 * Check start end more than end date of project
	 * @param string Date of assignment
	 * @param json_string List project id
	 */
	public function check_endDate($ass_end_date = NULL, $json_param = NULL) {

		if ($ass_end_date && $json_param) {
			$param = json_decode($json_param, TRUE);
			$ass_start_date = strtotime($param[1]);
			$ass_end_date = strtotime($ass_end_date);
			$projects = $param[0];

			foreach ($projects as $project_id) {
				$s_date = $this -> projects -> getStartDate($project_id);
				$e_date = $this -> projects -> getEndDate($project_id);
				$project_name = $this -> projects -> getName($project_id);

				if (!($s_date < $ass_end_date && $ass_end_date < $e_date)) {
					if ($ass_start_date > $ass_end_date)
						$this -> form_validation -> set_message('check_endDate', "Need select end date more than start date.");
					else
						$this -> form_validation -> set_message('check_endDate', 'Need check assign END DATE between start/end date project "' . $project_name . '".');
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function check_leftDate($d = NULL, $json_str = NULL) {
		// var_dump($json_str);
		$this -> form_validation -> set_message('check_leftDate', "Need select left date more than hire date.");
		if (isset($json_str)) {
			$param = json_decode($json_str, TRUE);
			$hire_date = strtotime($param['hire_date']);
			$left_date = strtotime($param['left_date']);
			if ($hire_date && $left_date)
				if ($hire_date > $left_date)
					return FALSE;
		}
		return TRUE;
	}

	public function check_projectAssign($projects = NULL, $param_date = NULL) {
		$dates = json_decode($param_date, TRUE);
		if (count($projects) > 0 && count($dates) == 2) {
			$ass_startDate = strtotime($dates[0]);
			$ass_endDate = strtotime($dates[1]);
			if (empty($ass_startDate) && empty($ass_endDate))
				return TRUE;
			// dont check if startDate/endDate as NULL.

			foreach ($projects as $project_id) {
				$project_startDate = $this -> projects -> getStartDate($project_id);
				$project_endDate = $this -> projects -> getEndDate($project_id);
				$project_name = $this -> projects -> getName($project_id);

				// check ass_startDate bettwen project_startDate/project_endDate
				if (!($project_startDate <= $ass_startDate && $ass_startDate <= $project_endDate)) {
					$this -> form_validation -> set_message('check_projectAssign', 'Need check assign START DATE between start/end date of project "' . $project_name . '".');
					return FALSE;
				}
				// check ass_endDate bettwen project_startDate/project_endDate
				if (!empty($ass_endDate))
					if (!($project_startDate <= $ass_endDate && $ass_endDate <= $project_endDate)) {
						$this -> form_validation -> set_message('check_projectAssign', 'Need check assign END DATE between start/end date project "' . $project_name . '".');
						return FALSE;
					}
			}
		}
		return TRUE;
	}

	public function check_assignDate($d, $param_date) {
		$dates = json_decode($param_date, TRUE);
		if (count($dates) == 2) {
			$s_date = strtotime($dates[0]);
			$e_date = strtotime($dates[1]);
			if ($s_date > $e_date) {
				$this -> form_validation -> set_message('check_assignDate', 'Need check End date more than Start date.');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function check_NonEmpty($str) {
		if (!empty($str)) {
			$this -> form_validation -> set_message('check_NonEmpty', 'Please input a valid "%s".');
			$temp = trim($str);
			$length = strlen($temp);
			if ($length <= 0)
				return FALSE;
		}
		return TRUE;
	}

	public function check_alphaSpace($str) {
		if (!empty($str)) {
			$arr = explode(' ', $str);
			foreach ($arr as $key => $value) {
				$this -> form_validation -> set_message('check_alphaSpace', "Please input a valid '%s'.");
				if ($this -> form_validation -> numeric($value))
					return FALSE;
			}
		}
		return TRUE;
	}

}
?>