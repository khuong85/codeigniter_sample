<?php

class Project_assignments extends CI_Model {

  private $_table;
  private $employee_id;

  public function __construct() {
    parent::__construct();
    $this->_table = 'project_assignments';
  }

  public function listItem($number = 0, $offset = 0, $arrParam, $options = null) {
    if ($options['task'] == 'project-assginment-list') {
      $this->db->select('pa.* , e.name, e.photo');
      $this->db->join('employees as e', 'pa.employee_id = e.employee_id', 'left')->where('pa.project_id = ' . $arrParam['project_id']);
      $query = $this->db->get('project_assignments as pa');
      return $query->result_array();
    }

    if ($options['task'] == 'project-not-assigment-list') {
      $this->db->select('e.name, e.skill, e.employee_id');
      $this->db->join('employees as e', 'pa.employee_id = e.employee_id', 'left')->where('pa.project_id != ' . $arrParam['project_id']);
      $query = $this->db->get('project_assignments as pa');
      return $query->result_array();
    }
  }

  public function deleteItem($arrParam, $options = null) {
    if ($options['task'] == 'project-assignment-delete') {
      return $this->db->delete('project_assignments', array('assignment_id' => $arrParam['assignment_id']));
    }
  }

  public function saveItem($arrParam, $options = array()) {
    if ($options['task'] == 'project-assignment-add') {
      $typeUser = $arrParam['typeUser'];
      $start_date = date('y-m-d', strtotime($arrParam[$typeUser . '_start_date']));
      $end_date = date('y-m-d', strtotime($arrParam[$typeUser . '_end_date']));
      $data['project_id'] = $arrParam['project_id'];
      $data['start_date'] = $start_date;
      $data['end_date'] = $end_date;
      $data['created'] = date('Y-m-d');
      $result = false;
      if (isset($arrParam['employeeId'])) {
        foreach ($arrParam['employeeId'] as $key => $value) {
          if (is_numeric($value) == false) {
            $result = false;
          } else {
            $data['employee_id'] = $value;
            $result = $this->db->insert($this->_table, $data);
          }
          if (!$result) {
            break;
          }
        }
      }
      return $result;
    }
  }

  /**
   * @author: Pham An
   */

  public function set_employee_id($emp_id = 0) {
    $this->employee_id = $emp_id;
  }

  /**
   * Use for project assignment panel.
   * Create sample data in projects table.
   */
  public function sample_project_data() {
    $this->load->helper(array('string', 'date'));
    for ($i = 0; $i < 10; $i++) {
      $this->db->insert('projects', array('name' => random_string('alpha', 8), 'start_date' => mdate('%Y-%m-%d', time()), 'end_date' => mdate('%Y-%m-%d', time()),));
    }
  }

  /**
   * Use for project assignment panel.
   * Get project in DB, and support pagination.
   * @param string $str
   * @param int $number
   * @param int $offset
   */
  public function get_projects($str = NULL, $number = 5, $offset = 0) {
    $offset = $offset != '' ? $offset : 0;

    $query = "select p.project_id, p.name, p.start_date, p.end_date, (select count(*) from project_assignments c where c.project_id=p.project_id) as num_people 
    FROM projects p 
    WHERE 
    	p.name LIKE '%{$str}%' 
    	AND p.project_id NOT IN (SELECT a.project_id 
    							FROM  project_assignments a 
    							WHERE a.employee_id={$this->employee_id})
    ORDER BY p.project_id DESC 
    LIMIT {$offset}, {$number};";
    $rs = $this->db->query($query);
    $temp = $this->convern_project_dateFormat($rs->result_array());
    return $temp;
  }

  /**
   * Use for project assignment panel.
   * Count num row which search string.
   * @param string $str
   */
  public function search_count($str = NULL) {
    $query = "select count(p.project_id) num_field from projects p where p.name LIKE '%{$str}%' and p.project_id NOT IN (sELECT a.project_id FROM  project_assignments a WHERE a.employee_id={$this
        ->employee_id});";
    $rs = $this->db->query($query);

    return $rs->row()->num_field;
  }

  /**
   * Use for project assignment panel.
   * Assign project to database.
   * @param int $emp_id
   * @param int $pro_id
   * @param date $start_date
   * @param date $end_date
   */
  public function assignment($projects = NULL, $s_date = NULL, $e_date = NULL) {
    foreach ($projects as $p_id) {
      $temp = array('employee_id' => $this->employee_id, 'project_id' => $p_id, 'start_date' => $this->convern_dateFormat($s_date, FALSE),
          'end_date' => $this->convern_dateFormat($e_date, FALSE));
      $this->db->set($temp);
      $this->db->insert('project_assignments');
    }
  }

  protected function convern_project_dateFormat($projects, $currentFormatIsMySQL = TRUE) {
    foreach ($projects as $key => $p) {
      if (isset($p['start_date']) && isset($p['end_date'])) {
        $p['start_date'] = $this->convern_dateFormat($p['start_date'], $currentFormatIsMySQL);
        $p['end_date'] = $this->convern_dateFormat($p['end_date'], $currentFormatIsMySQL);
        $projects[$key] = $p;
      }
    }
    return $projects;
  }

  protected function convern_dateFormat($val, $currentFormatIsMySQL = TRUE) {
    if ($currentFormatIsMySQL)
      return ($val == '0000-00-00' || $val == '') ? NULL : date('d-m-Y', strtotime($val));
    else
      return ($val == '00-00-0000' || $val == '') ? NULL : date('Y-m-d', strtotime($val));
  }
}
