<?php

/*
 * Klase Name_document_model
* Nolūks: modelis nosaukuma un nosaukuma dokumentu informācijas meklēšanas, parādīšanas, labošanas, dzēšamas funkciju izpildei
*/
class Name_document_model extends CI_Model
{
	public function getNameData($intNameID)
	{
		$intNameID = intval($intNameID);
		
		$strSQL = "SELECT ID, name FROM name WHERE ID = $intNameID; ";
		$arrResult = $this->db->query($strSQL)->result_array();
		return $arrResult[0];
	}
	
	public function getNameDocumentData($intNameID, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage)
	{
		$intNameID = intval($intNameID);
		$intPageNum = intval($intPageNum);
		$strOrderBy = addslashes($strOrderBy);
		$strOrderMode = addslashes($strOrderMode);
		$intRowCountPerPage = intval($intRowCountPerPage);
		
		$intOffset = ($intPageNum - 1) * $intRowCountPerPage; // nosaka, no kuras rindas sākot, jāatgriež rezultāta rindas
		
		$strSQL = "SELECT SQL_CALC_FOUND_ROWS d.ID, nd.occurrences, d.title, d.author, d.date, d.type, d.reference 
					FROM nameDocument nd, document d 
					WHERE nd.nameID = '$intNameID' 
					AND nd.documentID = d.ID 
					ORDER BY ";

		if ($strOrderBy == 'title') $strSQL .= 'title ';
		elseif ($strOrderBy == 'author') $strSQL .= 'author ';
		elseif ($strOrderBy == 'date') $strSQL .= 'date ';
		elseif ($strOrderBy == 'type') $strSQL .= 'type ';
		else $strSQL .= 'occurrences ';
		
		if ($strOrderMode == 'ASC') $strSQL .= 'ASC ';
		else $strSQL .= 'DESC ';
		
		$strSQL .= "LIMIT $intOffset, $intRowCountPerPage;";
		
		$arrResult['arrDocuments'] = $this->db->query($strSQL)->result_array();	
		
		$strSQL = "SELECT FOUND_ROWS();";
		$result = $this->db->query($strSQL)->result_array(); // izpilda datu bāzes vaicājumu
		$intRowsCount = $result[0]['FOUND_ROWS()'];
		
		$arrResult['intRowsCount'] = $intRowsCount;
		
		return $arrResult;
	}

	public function getNameEntities($intNameID)
	{
		$intNameID = intval($intNameID);
		
		$strSQL = "SELECT e.ID, e.definition, e.time, c.name AS category, en.comment 
					FROM entityName en, entity e, category c
					WHERE en.nameID = $intNameID 
					AND en.entityID = e.ID 
					AND e.categoryID = c.ID 
					ORDER BY e.definition;";
		return $this->db->query($strSQL)->result_array();
	}
	
	public function checkIfIsName($intNameID)
	{
		$intNameID = intval($intNameID);
		
		$strSQL = "SELECT ID FROM name WHERE ID = '$intNameID';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function updateName($intNameID, $strName)
	{
		$intNameID = intval($intNameID);
		$strName = addslashes($strName);
		
		$strSQL = "UPDATE name 
					SET name = '". htmlspecialchars($strName) ."', infoSource = 'UI' 
					WHERE ID = '$intNameID';";
		$this->db->query($strSQL);
	}
	
	public function	updateDocument($intDocumentID, $strTitle, $strReference, $strAuthor, $strDate, $strType)
	{
		$intDocumentID = intval($intDocumentID);
		$strTitle = addslashes($strTitle);
		$strReference = addslashes($strReference);
		$strAuthor = addslashes($strAuthor);
		$strDate = addslashes($strDate);
		$strType = addslashes($strType);
		
		$strSQL = "UPDATE document 
					SET title = '". htmlspecialchars($strTitle) ."', author = '". htmlspecialchars($strAuthor) ."', date = '". htmlspecialchars($strDate) ."', type = '". htmlspecialchars($strType) ."', reference = '". htmlspecialchars($strReference) ."', infoSource = 'UI' 
					WHERE ID = '$intDocumentID';";
		$this->db->query($strSQL);
	}
	
	public function updateNameDocument($intNameID, $intDocumentID, $intOccurrences)
	{
		$intNameID = intval($intNameID);
		$intDocumentID = intval($intDocumentID);
		$intOccurrences = intval($intOccurrences);
		
		$strSQL = "UPDATE nameDocument 
					SET occurrences = '$intOccurrences', infoSource = 'UI' 
					WHERE documentID = '$intDocumentID' 
					AND nameID = '$intNameID';";
		$this->db->query($strSQL);
		
		// update name totalOccurrences
		$strSQL = "UPDATE name
					SET totalOccurrences = (SELECT SUM(occurrences) FROM namedocument WHERE nameID = '$intNameID')
					WHERE ID = '$intNameID';";
		$this->db->query($strSQL);
	}
	
	public function insertDocument($strTitle, $strAuthor, $strDate, $strType, $strReference)
	{
		$strTitle = addslashes($strTitle);
		$strAuthor = addslashes($strAuthor);
		$strDate = addslashes($strDate);
		$strType = addslashes($strType);
		$strReference = addslashes($strReference);
		
		$strSQL = "SELECT ID FROM document WHERE reference = '$strReference';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return $arrResult[0]['ID'];
		}
		else
		{
			$strSQL = "INSERT INTO document (title, author, date, type, reference, infoSource) VALUES ('". htmlspecialchars($strTitle) ."', '". htmlspecialchars($strAuthor) ."', '". htmlspecialchars($strDate) ."', '". htmlspecialchars($strType) ."', '". htmlspecialchars($strReference) ."', 'UI');";
			$this->db->query($strSQL);
			return $this->db->insert_id();
		}
	}
	
	public function insertNameDocument($intNameID, $intDocumentID, $intOccurrences)
	{
		$intNameID = intval($intNameID);
		$intDocumentID = intval($intDocumentID);
		$intOccurrences = intval($intOccurrences);
		
		$strSQL = "SELECT * FROM nameDocument WHERE nameID = '$intNameID' AND documentID = '$intDocumentID';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) == 0)
		{
			$strSQL = "INSERT INTO nameDocument 
						(nameID, documentID, occurrences, infoSource) VALUES 
						('$intNameID', '$intDocumentID', '$intOccurrences', 'UI');";
			$this->db->query($strSQL);
			
			// update name totalOccurrences
			$strSQL = "UPDATE name
						SET totalOccurrences = (SELECT SUM(occurrences) FROM namedocument WHERE nameID = '$intNameID')
						WHERE ID = '$intNameID';";
			$this->db->query($strSQL);
			
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}
	
	public function deleteName($intNameID)
	{
		$intNameID = intval($intNameID);
		
		$strSQL = "DELETE FROM name WHERE ID = '$intNameID';";
		$this->db->query($strSQL);
	}
	
	public function deleteNameDocument($intDocumentID, $intNameID)
	{
		$intDocumentID = intval($intDocumentID);
		$intNameID = intval($intNameID);
		
		$strSQL = "DELETE FROM nameDocument 
					WHERE documentID = '$intDocumentID' 
					AND nameID = '$intNameID';";
		$this->db->query($strSQL);
		
		
		// update name totalOccurrences
		$strSQL = "UPDATE name
					SET totalOccurrences = (SELECT SUM(occurrences) FROM namedocument WHERE nameID = '$intNameID')
					WHERE ID = '$intNameID';";
		$this->db->query($strSQL);
	}
}
?>