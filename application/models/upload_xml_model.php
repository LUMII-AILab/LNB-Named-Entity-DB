<?php
/*
 * Klase Upload_xml_model
* Nolūks: modelis datu ievadai datu bāzē no xml dokumentiem
*/
class Upload_xml_model extends CI_Model
{
	public function insertObject($strDefinition, $strTime, $intCategory, $bolMarc=TRUE)
	{
		if ($bolMarc)
		{
			$strInput = 'MARC21 XML';
		}
		else 
		{
			$strInput = 'ielu XML';
		}
		
		if (strstr($strDefinition, "'")) $strDefinition = str_replace("'", "''", $strDefinition);
		
		// pārbauda, vai jau nav objekts ar tādu definīciju un laiku
		$strSQL = "SELECT ID FROM entity WHERE definition = '$strDefinition' AND time = '$strTime' AND categoryID = '$intCategory';";
// 		echo $strSQL.'<br/>';
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return $arrResult[0]['ID'];
		}
		else
		{
			// pievieno jaunu objektu
			$strSQL = "INSERT INTO entity (definition, time, categoryID, infoSource) VALUES ('$strDefinition', '$strTime', '$intCategory', '$strInput');";
			$this->db->query($strSQL);
			return $this->db->insert_id();
		}
	}
	
	public function addNameToObject($intObjectID, $strName, $intType)
	{
		if (strstr($strName, "'")) $strName = str_replace("'", "''", $strName);
		
		//vai tāds nosaukums jau ir DB
		$strSQL = "SELECT ID FROM name WHERE name = '$strName';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intNameID = $arrResult[0]['ID'];
			// vai ir sasaistīts ar objektu
			$strSQL = "SELECT * FROM entityName WHERE nameID = '$intNameID' AND entityID = '$intObjectID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno DB nosaukumu
			$strSQL = "INSERT INTO name (name, infoSource) VALUES ('$strName', 'MARC21 XML');";
			$this->db->query($strSQL);
			$intNameID = $this->db->insert_id();
		}
		
		// pievieno objektam
		$strSQL = "INSERT INTO entityName (nameID, entityID, infoSource) VALUES ('$intNameID', '$intObjectID', 'MARC21 XML');";
		$this->db->query($strSQL);
		
		return TRUE;	
	}
	
	public function addObjectToObject($intObjectID, $strOntObjName, $strOntComment)
	{
		// atrod nosaukuma ID
		$strSQL = "SELECT ID FROM name WHERE name = '$strOntObjName';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intNameID = $arrResult[0]['ID'];
			// atrod nosaukuma objekta ID (pieņem, ka katram nosaukumam ir viens objekts. Ņem pirmo objektu)
			$strSQL = "SELECT entityID FROM entityName WHERE nameID = '$intNameID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				$intOntObjID = $arrResult[0]['entityID'];
				// pārbauda, vai objekti jau nav sasaistīti
				$strSQL = "SELECT * FROM entityOntology WHERE (entity1ID = '$intObjectID' AND entity2ID = '$intOntObjID') OR (entity1ID = '$intOntObjID' AND entity2ID = '$intObjectID');";
				$arrResult = $this->db->query($strSQL)->result_array();
				if (sizeof($arrResult) > 0)
				{
					return FALSE;
				}
				else 
				{
					// sasaista objektus 
					$strSQL = "INSERT INTO entityOntology (entity1ID, entity2ID, comment, infoSource) VALUES ('$intObjectID', '$intOntObjID', '$strOntComment', 'MARC21 XML');";
					$this->db->query($strSQL);
					return TRUE;
				}
			}
			else 
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function addNameToObjectIelas($intObjectID, $strName, $strComment, $intType)
	{
		if (strstr($strName, "'")) $strName = str_replace("'", "''", $strName);
	
		//vai tāds nosaukums jau ir DB
		$strSQL = "SELECT ID FROM name WHERE name = '$strName';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intNameID = $arrResult[0]['ID'];
			// vai ir sasaistīts ar objektu
			$strSQL = "SELECT * FROM entityName WHERE nameID = '$intNameID' AND entityID = '$intObjectID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno DB nosaukumu
			$strSQL = "INSERT INTO name (name, infoSource) VALUES ('$strName', 'ielu XML');";
			$this->db->query($strSQL);
			$intNameID = $this->db->insert_id();
		}
	
		// pievieno objektam
		$strSQL = "INSERT INTO entityName (nameID, entityID, comment, infoSource) VALUES ('$intNameID', '$intObjectID', '$strComment', 'ielu XML');";
		$this->db->query($strSQL);
	
		return TRUE;
	}
}

?>