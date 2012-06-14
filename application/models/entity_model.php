<?php
/**
 * Fails: entity_model.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.12
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */

/*
* Klase Entity_model
* Nolūks: modelis objekta informācijas meklēšanas, parādīšanas, labošanas, dzēšamas funkciju izpildei
*/
class Entity_model extends CI_Model
{	
	/*
	 * Funkcija atrod un atgriež informāciju par objektu un tā saitēm
	 * 
	 * Parametrs: $intEntityID - objekta identifikators
	 */
	public function getEntityData($intEntityID)
	{
		/* start: objekta dati */
		$strSQL = "SELECT e.ID, e.definition, e.time, c.name AS category, c.ID AS categoryID FROM entity e, category c WHERE e.ID = '$intEntityID' AND c.ID = e.categoryID; ";
		$arrResult = $this->db->query($strSQL)->result_array();
		$arrData = $arrResult[0];
		/* end: objekta dati */
	
		/* start: objekta nosaukumi */
		$strSQL = "SELECT n.ID, n.name, en.comment, en.timeFrom, en.timeTo, (SELECT SUM(nd.occurrences) FROM nameDocument nd WHERE nd.nameID = n.ID) as totalOccurrences
					FROM name n, entityName en 
					WHERE en.entityID = $intEntityID AND n.ID = en.nameID 
					ORDER BY totalOccurrences DESC, name ASC; ";
		$arrData['arrNames'] = $this->db->query($strSQL)->result_array();
		/* end: objekta nosaukumi */
	
		/* start: objekta saistītie objekti */
		$strSQL = "SELECT e.ID, e.definition, eo.comment FROM entityOntology eo, entity e WHERE eo.entity1ID = '$intEntityID' AND eo.entity2ID = e.ID
					UNION
					SELECT e.ID, e.definition, eo.comment FROM entityOntology eo, entity e WHERE eo.entity2ID = '$intEntityID' AND eo.entity1ID = e.ID
					ORDER BY definition ASC;";
		$arrData['arrOntologies'] = $this->db->query($strSQL)->result_array();
		/* end: objekta saistītie objekti */	
	
		/* start: objekta resursi */
		$strSQL = "SELECT r.ID, r.name, r.reference, er.comment FROM entityResource er, resource r WHERE er.entityID = '$intEntityID' AND er.resourceID = r.ID ORDER BY name ASC;";
		$arrData['arrResources'] = $this->db->query($strSQL)->result_array();
		/* start: objekta resursi */
	
		return $arrData;
	}
	
	/*
	 * Funkcija atgriež masīvu ar visu kategoriju ID un nosaukumiem.
	 */
	public function getAllCategories()
	{
		$strSQL = "SELECT * FROM category;";
		$arrResult = $this->db->query($strSQL)->result_array();
		return $arrResult;
	}
	
	/*
	 * Funkcija pievieno datu bāzei jaunu objektu, vispirms pārbaudot, vai objekts ar tādiem datiem jau ir datu bāzē.
	 * 
	 * Parametri:
	 * 	$strDefinition - definīcija, 
	 * 	$strTime - objektam raksturīgais laiks, 
	 * 	$intCategory - kategorija
	 */
	public function insertEntity($strDefinition, $strTime, $intCategory)
	{
		// pārbauda, vai jau ir objekts ar tādiem datiem
		$strSQL = "SELECT ID FROM entity WHERE definition = '$strDefinition' AND time = '$strTime' AND categoryID = '$intCategory';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return $arrResult[0]['ID']; // atgriež atrastā objekta ID
		}
		else
		{
			// pievieno jaunu objektu
			$strSQL = "INSERT INTO entity (definition, time, categoryID, infoSource) VALUES ('". htmlspecialchars($strDefinition) ."', '". htmlspecialchars($strTime) ."', '$intCategory', 'ui');";
			$this->db->query($strSQL);
			return $this->db->insert_id(); // atgriež jaunizveidotā objekta ID
		}
	}
	
	/*
	 * Funkcija pārbauda, vai eksistē objekts ar padoto ID
	 *
	 * Parametrs: $intEntityID - objekta ID
	 *
	 */
	public function checkIfIsEntity($intEntityID)
	{
		$strSQL = "SELECT ID FROM entity WHERE ID = '$intEntityID';";
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
	
	/*
	 * Funkcija nodrošina objekta info labošanu, vispirms pārbaudot, vai šāds objekts jau ir. Ja ir - objektus apvieno vienā objektā.
	 * 
	 * Parametri:
	 * 	$intEntityID - objekta identifikators, 
	 *  $strDefinition - objekta definīcija, 
	 *  $strTime - objektam raksturīgais laiks, 
	 *  $intCategory - kategorijas identifikators
	 *  
	 */
	public function updateEntity($intEntityID, $strDefinition, $strTime, $intCategory)
	{
		// pārbauda, vai jau nav cits objekts ar šādiem datiem
		$strSQL = "SELECT ID FROM entity WHERE ID != '$intEntityID' AND definition = '$strDefinition' AND time = '$strTime' AND categoryID = '$intCategory';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0) // ja ir, piesaista labotā objekta info atrastajam objektam
		{
			$intNewEntityID = $arrResult[0]['ID'];
			
			$strSQL = "UPDATE entityname SET entityID = '$intNewEntityID' WHERE entityID = '$intEntityID';"; // pārnes objekta-nosaukuma saites
			$this->db->query($strSQL);
			
			$strSQL = "UPDATE entityresource SET entityID = '$intNewEntityID' WHERE entityID = '$intEntityID';"; // pārnes objekta-resursa saites
			$this->db->query($strSQL);

			$strSQL = "UPDATE entityontology SET entity1ID = '$intNewEntityID' WHERE entity1ID = '$intEntityID';"; // pārnes objekta-objekta saites
			$this->db->query($strSQL);
			$strSQL = "UPDATE entityontology SET entity2ID = '$intNewEntityID' WHERE entity2ID = '$intEntityID';";
			$this->db->query($strSQL);
			
			$strSQL = "DELETE FROM entity WHERE ID = '$intEntityID';"; // izdzēš laboto objektu
			$this->db->query($strSQL);
			
			return $intNewEntityID; // atgriež apvienotā objekta ID
		}
		else // ja nav, labo objekta info
		{
			$strSQL = "UPDATE entity SET definition = '". htmlspecialchars($strDefinition) ."', time = '". htmlspecialchars($strTime) ."', categoryID = '$intCategory' WHERE ID = '$intEntityID';";
			$this->db->query($strSQL);
			
			return $intEntityID; // atgriež labojamā objekta ID
		}
	}
	
	/*
	 * Funkcija nodrošina objekta un tā saišu ar visiem datu bāzes vienumiem dzēšanu.
	 * 
	 * Parametrs:
	 * 	$intEntityID - objekta identifikators
	 * 
	 */
	public function deleteEntity($intEntityID)
	{
		$strSQL = "DELETE FROM entityName WHERE entityID = '$intEntityID';"; // idzēš objekta-nosaukuma saites
		$this->db->query($strSQL);
		
		$strSQL = "DELETE FROM entityOntology WHERE entity1ID = '$intEntityID' OR  entity2ID = '$intEntityID';"; // idzēš objekta-objekta saites
		$this->db->query($strSQL);
		
		$strSQL = "DELETE FROM entityResource WHERE entityID = '$intEntityID';"; // idzēš objekta-resursa saites
		$this->db->query($strSQL);
		
		$strSQL = "DELETE FROM entity WHERE ID = '$intEntityID';"; // izdzēš objektu
		$this->db->query($strSQL);
	}
	
	public function insertName($strName)
	{
		// pārbauda, vai ir jau datu bāzē
		$strSQL = "SELECT ID FROM name WHERE name = '$strName';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return $arrResult[0]['ID'];
		}
		else 
		{
			// pievieno jaunu nosaukumu
			$strSQL = "INSERT INTO name (name, infoSource) VALUES ('". htmlspecialchars($strName) ."', 'ui');";
			$this->db->query($strSQL);
			return $this->db->insert_id();
		}
	}
	
	public function insertEntityName($intEntityID, $intAltName, $strAltComment, $strTimeFrom, $strTimeTo)
	{
		// vai ir sasaistīts ar objektu
		$strSQL = "SELECT nameID FROM entityName WHERE nameID = '$intAltName' AND entityID = '$intEntityID';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return FALSE;
		}
		else
		{
			// sasaista ar objektu
			$strSQL = "INSERT INTO entityName (nameID, entityID, comment, timeFrom, timeTo, infoSource) VALUES ('". $intAltName ."', $intEntityID, '". htmlspecialchars($strAltComment) ."', '". htmlspecialchars($strTimeFrom) ."', '". htmlspecialchars($strTimeTo) ."', 'ui');";
			$this->db->query($strSQL);
			return TRUE;
		}
	}
	
	
	public function insertEntityResource($intEntityID, $intResID, $strComment)
	{
		// vai ir sasaistīts ar objektu
		$strSQL = "SELECT entityID FROM entityResource WHERE resourceID = '$intResID' AND entityID = '$intEntityID';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return FALSE;
		}
		else
		{
			// sasaista ar objektu
			$strSQL = "INSERT INTO entityResource (resourceID, entityID, comment, infoSource) VALUES ('$intResID', $intEntityID, '". htmlspecialchars($strComment) ."', 'ui');";
			$this->db->query($strSQL);
			return TRUE;
		}
	}
	
	public function insertResource($strName, $strRef)
	{
		// pārbauda, vai ir jau datu bāzē
		$strSQL = "SELECT ID FROM resource WHERE name = '$strName' AND reference = '$strRef';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return $arrResult[0]['ID'];
		}
		else 
		{
			// pievieno jaunu nosaukumu
			$strSQL = "INSERT INTO resource (name, reference, infoSource) VALUES ('". htmlspecialchars($strName) ."', '". htmlspecialchars($strRef) ."', 'ui');";
			$this->db->query($strSQL);
			return $this->db->insert_id();
		}
	}
	
	public function deleteEntityName($intEntityID, $intNameID)
	{
		$strSQL = "DELETE FROM entityName WHERE entityID = '$intEntityID' AND nameID = '$intNameID';";
		$this->db->query($strSQL);
	}
	
	public function updateEntityName($intEntityID, $intNameID, $strComment, $strTimeFrom, $strTimeTo)
	{
		$strSQL = "UPDATE entityName SET comment = '". htmlspecialchars($strComment) ."', timeFrom = '". htmlspecialchars($strTimeFrom) ."', timeTo = '". htmlspecialchars($strTimeTo) ."' WHERE entityID = '$intEntityID' AND nameID = '$intNameID';";
		$this->db->query($strSQL);
	}
	
	public function insertEntityOntology($intEntityID, $intOntID, $strOntComment)
	{
		// pārbauda, vai jau nav sasaistīti
		$strSQL = "SELECT * FROM entityOntology WHERE (entity1ID = $intEntityID AND entity2ID = $intOntID) OR (entity1ID = $intOntID AND entity2ID = $intEntityID);";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return FALSE;
		}
		else
		{
			$strSQL = "INSERT INTO entityOntology (entity1ID, entity2ID, comment, infoSource) VALUES ('$intEntityID', '$intOntID', '". htmlspecialchars($strOntComment) ."', 'ui');";
			$this->db->query($strSQL);
			return TRUE;
		}
	}
	
	public function deleteEntityOntology($intEntityID, $intOntEntityID)
	{
		$strSQL = "DELETE FROM entityOntology WHERE (entity1ID = '$intEntityID' AND entity2ID = '$intOntEntityID') OR (entity1ID = '$intOntEntityID' AND entity2ID = '$intEntityID') ;";
		$this->db->query($strSQL);	
	}
	
	public function updateEntityOntology($intEntityID, $intOntEntityID, $strComment)
	{
		$strSQL = "UPDATE entityOntology SET comment = '". htmlspecialchars($strComment) ."' WHERE (entity1ID = '$intEntityID' AND entity2ID = '$intOntEntityID') OR (entity1ID = '$intOntEntityID' AND entity2ID = '$intEntityID') ;";
		$this->db->query($strSQL);
	}
	
	public function deleteEntityResource($intEntityID, $intResID)
	{
		$strSQL = "DELETE FROM entityResource WHERE entityID = '$intEntityID' AND resourceID = '$intResID';";
		$this->db->query($strSQL);
	}
	
	public function updateEntityResource($intEntityID, $intResID, $strComment)
	{
		$strSQL = "UPDATE entityResource SET comment = '". htmlspecialchars($strComment) ."' WHERE entityID = '$intEntityID' AND resourceID = '$intResID';";
		$this->db->query($strSQL);
	}
}

?>