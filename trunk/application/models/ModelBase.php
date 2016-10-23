<?php
class ModelBase extends CI_Model
{
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function sample_project_data()
	{
        $this->load->helper(array('string', 'date'));
		for($i=0; $i<10; $i++)
		{
			$this->db->insert(
				'projects', 
				array( 
					'name'=>random_string('alpha', 8),
					'start_date'=>mdate('%Y-%m-%d', time()),
					'end_date'=>mdate('%Y-%m-%d', time()),
				)); 
		}
	}
	
	public function get_projects($str=NULL, $number=5, $offset=0)
	{
		$employee_id = 1;
		
		$offset = $offset!=''?$offset:0;
		
		$query = "select p.project_id, p.name, p.start_date, p.end_date, (select count(*) from project_assignments c where c.project_id=p.project_id) as num_people from projects p where p.name LIKE '%{$str}%' and p.project_id NOT IN (sELECT a.project_id FROM  project_assignments a WHERE a.employee_id={$employee_id}) LIMIT {$offset}, {$number};";
		$rs = $this->db->query($query);
		
		return $rs->result_array();
	}
	
	public function search_count($str=NULL)
	{
		$employee_id = 1;
		
		$query = "select count(p.project_id) num_field from projects p where p.name LIKE '%{$str}%' and p.project_id NOT IN (sELECT a.project_id FROM  project_assignments a WHERE a.employee_id={$employee_id});";
		$rs = $this->db->query($query);
		
		return $rs->row()->num_field;
	}
}