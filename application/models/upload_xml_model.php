<?php
/**
 * Fails: upload_xml_model.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.12
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */

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
			// atrod nosaukuma objekta ID (pieņem, ka katram nosaukumam ir viens objekts. ņem pirmo objektu)
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
/*************************************************************************/
	
	public function addObjectToNameByID($intID, $intObjID, $strObjComment)
	{
		// pārbauda, vai jau nav sasaistīti
		$strSQL = "SELECT * FROM named_entity_object WHERE named_entity_ID = $intID AND object_ID = $intObjID;";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return FALSE;
		}
		else
		{
			$strSQL = "INSERT INTO named_entity_object (named_entity_ID, object_ID, comment, input_from) VALUES ('$intID', '$intObjID', '$strObjComment', 'ui');";
			$this->db->query($strSQL);
			return TRUE;
		}
	}
	
	public function addNewObjectToName($intID, $strDefinition, $intCategory, $strObjComment)
	{
		// pārbauda, vai jau nav objekts ar tādu definīciju
		$strSQL = "SELECT ID FROM object WHERE definition = '$strDefinition';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intObjID = $arrResult[0]['ID'];
			// pārbauda, vai jau sasaistīti
			$strSQL = "SELECT * FROM named_entity_object WHERE named_entity_ID = $intID AND object_ID = $intObjID;";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno jaunu objektu
			$strSQL = "INSERT INTO object (definition, category_ID, input_from) VALUES ('$strDefinition', '$intCategory', 'ui');";
			$this->db->query($strSQL);
			$intObjID = $this->db->insert_id();
		}
	
		// savieno objektu ar nosaukumu
		$strSQL = "INSERT INTO named_entity_object (named_entity_ID, object_ID, comment, input_from) VALUES ('$intID', '$intObjID', '$strObjComment', 'ui');";
		$this->db->query($strSQL);
		return TRUE;
	}
	
	public function addDocumentToName($intID, $intDocOcc, $strDocTitle, $strDocAuthor, $strDocDate, $strDocRef)
	{
		// pārbauda, vai jau nav dokuments ar šādu nosaukumu, autoru, datumu un norādi
		$strSQL = "SELECT ID FROM document WHERE reference = '$strDocRef';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intDocID = $arrResult[0]['ID'];
			// pārbauda, vai jau sasaistīti
			$strSQL = "SELECT * FROM named_entity_document WHERE named_entity_ID = '$intID' AND document_ID = '$intDocID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno jaunu dokumentu
			$strSQL = "INSERT INTO document (title, author, date, reference, input_from) VALUES ('$strDocTitle', '$strDocAuthor', '$strDocDate', '$strDocRef', 'ui');";
			$this->db->query($strSQL);
			$intDocID = $this->db->insert_id();
		}
	
		// savieno dokumentu ar nosaukumu
		$strSQL = "INSERT INTO named_entity_document (named_entity_ID, document_ID, frequency, input_from) VALUES ('$intID', '$intDocID', '$intDocOcc', 'ui');";
		$this->db->query($strSQL);
		return TRUE;
	}
	
	public function addAlternativeNameToObject($intID, $strAltName, $strAltComment)
	{
		//vai tāds nosaukums jau ir DB
		$strSQL = "SELECT ID FROM named_entity WHERE name = '$strAltName';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intAltID = $arrResult[0]['ID'];
			// vai ir sasaistīts ar objektu
			$strSQL = "SELECT ID FROM named_entity_object WHERE named_entity_ID = '$intAltID' AND object_ID = '$intID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno DB nosaukumu
			$strSQL = "INSERT INTO named_entity (name, input_from) VALUES ('$strAltName', 'ui');";
			$this->db->query($strSQL);
			$intAltID = $this->db->insert_id();
		}
	
		// pievieno objektam
		$strSQL = "INSERT INTO named_entity_object (named_entity_ID, object_ID, named_entity_type_ID, comment, input_from) VALUES ('$intAltID', $intID, 2, '$strAltComment', 'ui');";
		$this->db->query($strSQL);
	
		return TRUE;
	}
	
	public function addOntObjectToObjectByID($intID, $intOntID, $strOntComment)
	{
		// pārbauda, vai jau nav sasaistīti
		$strSQL = "SELECT * FROM object_ontology WHERE (object1_ID = $intID AND object2_ID = $intOntID) OR (object1_ID = $intOntID AND object2_ID = $intID);";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			return FALSE;
		}
		else
		{
			$strSQL = "INSERT INTO object_ontology (object1_ID, object2_ID, comment, input_from) VALUES ('$intID', '$intOntID', '$strOntComment', 'ui');";
			$this->db->query($strSQL);
			return TRUE;
		}
	}
	
	public function addNewOntObjectToObject($intID, $strDefinition, $intCategory, $strComment)
	{
		// pārbauda, vai jau nav objekts ar tādu definīciju
		$strSQL = "SELECT ID FROM object WHERE definition = '$strDefinition';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intOntID = $arrResult[0]['ID'];
			// pārbauda, vai jau sasaistīti
			$strSQL = "SELECT * FROM object_ontology WHERE (object1_ID = $intID AND object2_ID = $intOntID) OR (object1_ID = $intOntID AND object2_ID = $intID);";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno jaunu objektu
			$strSQL = "INSERT INTO object (definition, category_ID, input_from) VALUES ('$strDefinition', '$intCategory', 'ui');";
			$this->db->query($strSQL);
			$intOntID = $this->db->insert_id();
		}
	
		// savieno objektus
		$strSQL = "INSERT INTO object_ontology (object1_ID, object2_ID, comment, input_from) VALUES ('$intID', '$intOntID', '$strComment', 'ui');";
		$this->db->query($strSQL);
		return TRUE;
	}
	
	public function addResourceToObject($intID, $strResName, $strResRef)
	{
		// pārbauda, vai jau nav resurss ar šādu nosaukumu un norādi
		$strSQL = "SELECT ID FROM resource WHERE name = '$strResName' AND reference = '$strResRef';";
		$arrResult = $this->db->query($strSQL)->result_array();
		if (sizeof($arrResult) > 0)
		{
			$intResID = $arrResult[0]['ID'];
			// pārbauda, vai jau sasaistīti
			$strSQL = "SELECT * FROM object_resource WHERE object_ID = $intID AND resource_ID = '$intResID';";
			$arrResult = $this->db->query($strSQL)->result_array();
			if (sizeof($arrResult) > 0)
			{
				return FALSE;
			}
		}
		else
		{
			// pievieno jaunu resursu
			$strSQL = "INSERT INTO resource (name, reference, input_from) VALUES ('$strResName', '$strResRef', 'ui');";
			$this->db->query($strSQL);
			$intResID = $this->db->insert_id();
		}
	
		// savieno ar objekt objektus
		$strSQL = "INSERT INTO object_resource (object_ID, resource_ID, input_from) VALUES ('$intID', '$intResID', 'ui');";
		$this->db->query($strSQL);
		return TRUE;
	}
	
	public function getObjectData($intID, $intOffsetAlt, $intOffsetOnt)
	{
		/*** START object data ***/
		$arrData['intID'] = $intID;
		$strSQL = "
		SELECT o.definition, c.name AS category
		FROM object o, category c
		WHERE o.ID = $intID
		AND c.ID = o.category_ID; ";
		$arrResult = $this->db->query($strSQL)->result_array();
		$arrData['strDefinition'] = $arrResult[0]['definition'];
		$arrData['strCategory'] = $arrResult[0]['category'];
		/*** END object data ***/
	
	
		/*** START names ***/
		$strSQL = "
		SELECT ne.ID, ne.name, neo.comment
		FROM named_entity ne, named_entity_object neo
		WHERE neo.object_ID = $intID
		AND ne.ID = neo.named_entity_ID
		LIMIT $intOffsetAlt, 10; ";
		$arrData['arrNamedEntities'] = $this->db->query($strSQL)->result_array();
		/*** END names ***/
	
	
		/*** START ontology objects ***/
		$strSQL = "
		SELECT o.ID, o.definition, c.name AS category, olt.name AS link_type, oo.comment
		FROM object_ontology oo, ontology_link_type olt, object o, category c
		WHERE oo.object1_ID = '$intID'
		AND oo.object2_ID = o.ID
		AND oo.ontology_link_type_ID = olt.ID
		AND o.category_ID = c.ID
		UNION
		SELECT o.ID, o.definition, c.name AS category, olt.name AS link_type, oo.comment
		FROM object_ontology oo, ontology_link_type olt, object o, category c
		WHERE oo.object2_ID = '$intID'
		AND oo.object1_ID = o.ID
		AND oo.ontology_link_type_ID = olt.ID
		AND o.category_ID = c.ID
		LIMIT $intOffsetOnt, 10;";
		$arrData['arrOntologies'] = $this->db->query($strSQL)->result_array();
		/*** END ontology objects ***/
	
	
		/*** START documents ***/
		if (sizeof($arrData['arrNamedEntities']) > 1)
		{
		$strSQL = "
		SELECT ne.ID, ne.name, d.title, d.reference, ned.frequency, d.date
		FROM named_entity ne, named_entity_document ned, document d
			WHERE (";
			foreach ($arrData['arrNamedEntities'] as $arrName)
			{
			$strSQL .= "ne.ID = '". $arrName['ID'] ."'
			OR ";
		}
		$strSQL = trim($strSQL, "OR ");
		$strSQL .= ")
		AND ned.named_entity_ID = ne.ID
		AND ned.document_ID = d.ID;";
		$arrData['arrDocuments'] = $this->db->query($strSQL)->result_array();
	}
				/*** END documents ***/
	
				/*** START resources ***/
				$strSQL = "
				SELECT r.name, r.reference
				FROM object_resource obr, resource r
				WHERE obr.object_ID = '$intID'
				AND obr.resource_ID = r.ID;";
		$arrData['arrResources'] = $this->db->query($strSQL)->result_array();
	/*** END resources ***/
	
	return $arrData;
	}
	
}

?>