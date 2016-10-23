<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Project extends CI_Controller {

  public $_data;
  private $_message = array();

  public function __construct() {
    parent::__construct();
    $this->load->helper('url');
    $this->load->Model("Projects");
    $this->load->Model("Project_assignments");
    $this->load->Model("Employee_Model");
    $this->load->library('emp_auth');

    if (!$this->emp_auth->is_logged()) {
      redirect('login', 'refresh');
    }
    $this->_data['role'] = "";
    if ($this->emp_auth->is_admin() != true && $this->emp_auth->is_root() != true) {
      $this->_data['role'] = 'style="display: none"';
    }

    $this->_data['role_assign'] = '';
    if ($this->emp_auth->is_admin() || $this->emp_auth->is_root()) {
      $this->_data['role_assign'] = 'style="display: none"';
    }

    $this->_data['title'] = 'Mulodo VN Employee Management';
    $this->_data['heading'] = 'Mulodo VN Employee Management';
  }

  public function index() {
    $arrParam = $this->input->post();
    $this->_data['keyword'] = '';
    $total_segment = $this->uri->total_segments();

    if ($arrParam['keyword']) {
      $this->_data['keyword'] = trim($arrParam['keyword']);
    } else {
      $this->_data['keyword'] = $this->session->flashdata('condition');
    }
    if (isset($arrParam['new_str']) && $arrParam['new_str'] == 1) {
      $this->_data['keyword'] = trim($arrParam['keyword']);
    }
    $config['base_url'] = base_url('/project/index/');
    $config['total_rows'] = $this->Projects->countItem($this->_data);
    $config['per_page'] = COUNT_PER_PAGE;
    $config['full_tag_open'] = "<div id='pagination'>";
    $config['full_tag_close'] = "</div>";
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $config['uri_segment'] = $total_segment;
    $this->pagination->initialize($config);
    $this->_data['message'] = $this->session->flashdata('message');
    $this->_data['Items'] = $this->Projects->listItem($config['per_page'], $this->uri->segment(3), $this->_data, array('task' => 'project-list'));
    $count = count($this->_data['Items']);
    if ($count <= 0) {
      $this->_data['NotFound'] = 0;
    }
    $this->template->current_view = ('project/index');
    $this->template->set($this->_data);
    $this->template->render();
    //$this->load->view("project/index", $this->_data);
  }

  public function delete() {
    if ($this->emp_auth->is_admin() || $this->emp_auth->is_root()) {
      if ($this->uri->segment(2) == 'delete') {
        $this->_data['project_id'] = $this->uri->segment(3);
        $delete = $this->Projects->deleteItem($this->_data, array('task' => 'project-delete'));
        if ($delete) {
          $this->session->set_flashdata('message', 'Delete successfully.');
        }
      }
    }
    redirect('project');
  }

  public function add() {
    $arrParam = $this->input->post();
    if ($arrParam) {
      $this->Projects->saveItem($arrParam, array('task' => 'project-add'));
      $this->session->set_flashdata('message', 'Add new project successfully.');
    }
    redirect('project');
  }

  public function listProject() {
    redirect('project');
  }

  public function edit() {
    $arrParam = $this->input->post();
    if ($arrParam) {
      $this->Projects->saveItem($arrParam, array('task' => 'project-edit'));
      $this->session->set_flashdata('message', 'Update project successfully.');
    }
    redirect('project');
  }

  public function detail() {
    if ($this->uri->segment(2) == 'detail') {
      if ($this->uri->segment(3) != false && is_numeric($this->uri->segment(3))) {
        $this->_data['project_id'] = $this->uri->segment(3);
        $this->_data['project'] = $this->Projects->listItem(0, 0, $this->_data, array('task' => 'project-detail'));
        if ($this->_data['project']['project_id'] == '') {
          redirect('project');
        }
        $this->_data['project_assignment'] = $this->Project_assignments->listItem(0, 0, $this->_data, array('task' => 'project-assginment-list'));
        $this->_data['message'] = $this->session->flashdata('message');
        $this->template->current_view = ('project/detail');
        $this->template->set($this->_data);
        $this->template->render();
      } else {
        redirect('project');
      }
    }
  }

  public function deleteProjectAssignment() {
    if ($this->uri->segment(2) == 'deleteProjectAssignment') {
      $project_id = $this->uri->segment(3);
      $this->_data['assignment_id'] = $this->uri->segment(4);
      $delete = $this->Project_assignments->deleteItem($this->_data, array('task' => 'project-assignment-delete'));
      if ($delete) {
        $this->session->set_flashdata('message', 'Delete successfully.');
      }
    }
    redirect('project/detail/' . $project_id);
  }

  public function listEmployee() {
    $arrParam = $this->input->post();
    $this->_data['keyword_employee'] = '';
    if (isset($arrParam['keyword_employee'])) {
      $this->_data['keyword_employee'] = trim($arrParam['keyword_employee']);
    } else {
      $this->_data['keyword_employee'] = $this->session->flashdata('condition_employee');
    }
    if (isset($arrParam['new_str_employee']) && $arrParam['new_str_employee'] == 1) {
      $this->_data['keyword_employee'] = trim($arrParam['keyword_employee']);
    }

    if (isset($arrParam['project_id'])) {
      $this->session->set_userdata(array('project_id' => $arrParam['project_id']));
    }
    if (isset($arrParam['strEmployeeId'])) {
      $this->session->set_userdata(array('strEmployeeId' => $arrParam['strEmployeeId']));
    }
    $this->_data['strEmployeeId'] = $this->session->userdata('strEmployeeId');
    //$this->_data['keyword_employee'] = $this->session->userdata('keyword_employee');
    $this->_data['project_id'] = $this->session->userdata('project_id');

    $config['base_url'] = base_url('/project/listEmployee/');
    $config['total_rows'] = $this->Employee_Model->countItem($this->_data);
    $config['per_page'] = COUNT_PER_PAGE;
    $config['full_tag_open'] = "<div id='pagination'>";
    $config['full_tag_close'] = "</div>";
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $this->pagination->initialize($config);
    $this->_data['project'] = $this->Projects->listItem(0, 0, $this->_data, array('task' => 'project-info'));

    $this->_data['message'] = $this->session->flashdata('message');
    $this->_data['Items'] = $this->Employee_Model->listItem($config['per_page'], $this->uri->segment(3), $this->_data, array('task' => 'employee-list'));

    foreach ($this->_data['Items'] as $employee_key => $employee_value) {
      $evaluation = $this->Employee_Model->getEvaluationByEmployeeId($employee_value['employee_id']);
      if ($evaluation != null) {
        $this->_data['Items'][$employee_key]['rank'] = $evaluation['rank'];
      } else {
        $this->_data['Items'][$employee_key]['rank'] = '';
      }
    }
    $this->_data['rank'] = $this->Employee_Model->get_list_ranks();
    $this->template->current_view = ('project/assign_employee');
    $this->template->set($this->_data);
    $this->template->render();
  }

  public function assignEmployee() {
    $arrParam = $this->input->post();
    $result = false;
    if ($arrParam['typeUser'] == 'assign' && $this->emp_auth->is_themeself($arrParam['employeeId'][0])) {
      if (isset($arrParam)) {
        $result = $this->Project_assignments->saveItem($arrParam, array('task' => 'project-assignment-add'));
        if ($result) {
          $this->session->set_flashdata('message', 'Assign employee successfully.');
          redirect('project/detail/' . $arrParam['project_id']);
        } else {
          redirect('project');
        }
      }
    } else {
      redirect('project');
    }
  }
}
