<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Name_total_occurrences extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{		
		$query = "SELECT ID FROM name;";
		$arrResult = $this->db->query($query)->result_array();
		$arrNameIDs = array();
		foreach ($arrResult as $arrName)
		{
			$arrNameIDs[] = $arrName['ID'];
		}
		unset($arrResult);

		foreach ($arrNameIDs as $intNameID)
		{
			$query = "UPDATE name 
						SET totalOccurrences = 
							(SELECT SUM(occurrences) 
								FROM namedocument 
								WHERE nameID = '$intNameID') 
						WHERE ID = '$intNameID';";
			$this->db->query($query);
		}
		
		echo 'END';
		
	}
}

?>