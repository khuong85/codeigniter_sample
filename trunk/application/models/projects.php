<?php
class Projects extends CI_Model {

  private $_table;

  public function __construct() {
    parent::__construct();
    $this->_table = 'projects';
  }

  public function listItem($number = 0, $offset = 0, $arrParam, $options = null) {
    if ($options['task'] == 'project-list') {
      $this->db->select("p.*, COUNT(pa.employee_id) as numPeople");

      if (isset($arrParam['keyword']) && $arrParam['keyword'] != '') {
        $this->db->like('name', $arrParam['keyword'], 'both');
        $this->session->set_flashdata('condition', $arrParam['keyword']);
      }
      $this->db->join('project_assignments as pa', 'pa.project_id = p.project_id', 'left')->group_by('p.project_id');
      $this->db->order_by("p.project_id", "desc");
      $query = $this->db->get('projects as p', $number, $offset);
      return $query->result_array();
    }
    if ($options['task'] == 'project-detail') {
      $this->db->select("p.*, COUNT(pa.employee_id) as numPeople");
      $this->db->join('project_assignments as pa', 'pa.project_id = p.project_id', 'left');
      $this->db->where('p.project_id = ' . $arrParam['project_id']);
      $query = $this->db->get('projects as p');
      
      return $query->row_array();
    }
    if ($options['task'] == 'project-info') {
      $this->db->select('*');
      $this->db->where('p.project_id = ' . $arrParam['project_id']);
      $query = $this->db->get('projects as p');
      return $query->row_array();
    }
  }

  public function countItem($arrParam) {
    if (isset($arrParam['keyword']) && $arrParam['keyword'] != '') {
      $this->db->like('name', $arrParam['keyword'], 'both');
    }
    return $this->db->count_all_results($this->_table);
  }

  public function deleteItem($arrParam, $options = null) {
    if ($options['task'] == 'project-delete') {
      $this->db->delete('project_assignments', array('project_id' => $arrParam['project_id']));
      return $this->db->delete($this->_table, array('project_id' => $arrParam['project_id']));
    }
  }

  public function saveItem($arrParam, $options = null) {

    if ($options['task'] == 'project-add') {
      $start_date = date('y-m-d', strtotime($arrParam['start_date']));
      $end_date = date('y-m-d', strtotime($arrParam['end_date']));
      $data['name'] = $arrParam['name'];
      $data['start_date'] = $start_date;
      $data['end_date'] = $end_date;
      $data['created'] = date('Y-m-d');
      return $this->db->insert($this->_table, $data);
    }

    if ($options['task'] == 'project-edit') {
      $start_date = date('y-m-d', strtotime($arrParam['project_start_date']));
      $end_date = date('y-m-d', strtotime($arrParam['project_end_date']));
      $data['name'] = $arrParam['project_name'];
      $data['start_date'] = $start_date;
      $data['end_date'] = $end_date;
      $data['modified'] = date('Y-m-d');
      $this->db->where('project_id', $arrParam['project_id']);
      return $this->db->update($this->_table, $data);
    }
  }

  /**
   * Get start date
   * @param int project id
   * @return Date object
   */
  public function getStartDate($project_id = NULL) {
    $this->db->select('start_date');
    $this->db->where('project_id =', $project_id);
    $query = $this->db->get('projects');
    return strtotime($query->row()->start_date);
  }

  /**
   * Get end date
   * @param int project id
   * @return Date object
   */
  public function getEndDate($project_id = NULL) {
    $this->db->select('end_date');
    $this->db->where('project_id =', $project_id);
    $query = $this->db->get('projects');
    return strtotime($query->row()->end_date);
  }

  /**
   * Get project name
   * @param int project id
   * @return string
   */
  public function getName( $project_id = NULL) {
    $this->db->select('name');
    $this->db->where('project_id =', $project_id);
    $query = $this->db->get('projects');
	return $query->row()->name;
  }

}
