<?php
class ReportModel extends Model {


    function __construct()
    {
        // Call the Model constructor
        parent::Model();
    }
 
    function report($userId, $id, $table, $tableId, $type)
    {
		$this->db->where($tableId, $id);
		$results = $this->db->get($table);
		$db = $results->row();
        $votes = $db->$type;
        $votes++;
        $data = array(
                 $type => $votes,
              );
  
        $this->db->where($tableId, $id);
        $this->db->update($table, $data); 

		$reportTable = array(
                    'user_id' => $userId,
                    $tableId => $id,
                    'type' => $type
                    );      
        
        $this->db->insert('reports', $reportTable);
	}
	
 	function verifyNoReport($id, $userId, $tableId)
	{
		$this->db->where('user_id', $userId);
		$this->db->where($tableId, $id);


		$results = $this->db->get('reports');
		if($results->num_rows() > 0) {
			return $results;
		} else {
			return false;
		}      
    }    
	
	function submitBug($summary, $text)
	{
		$user = $this->session->userdata('DX_username');
		$insert['created_by'] = $user;
		$insert['summary'] = $summary;
		$insert['description'] = $text;
		$this->db->insert('bug_reports', $insert);
	
	}
} 
    
    
?>
