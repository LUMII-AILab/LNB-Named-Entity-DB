<?php
/**
 * Fails: rdf_xml_model.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.12
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */

/*
 * Klase Rdf_xml_model
* Nolūks: modelis datu parādīšanai RDF/XML formā
*/
class Rdf_xml_model extends CI_Model
{
	/*
	 * Funkcija atgriež masīvu ar entītijām (līdz 1000) no datu bāzes, sākot ar entītiju, kuras ID ir padotais $intIDStart
	 */	
	public function getAllNames($intNameIDStart)
	{
		$strSQL = "SELECT * FROM `name` WHERE `ID` >= $intNameIDStart LIMIT 1000;";
		$arrNamedEntities = $this->db->query($strSQL)->result_array();
		return $arrNamedEntities;
	}
	
	/*
	 * Funkcija pārbauda, vai datu bāzē ir entītijas, kuru ID ir lielāks par padoto $intIDStart.
	 * Atgriež TRUE, ja ir, vai FALSE, ja nav.
	*/
	public function checkIsMoreNames($intNameIDStart)
	{
		$strSQL = "SELECT * FROM `name` WHERE `ID`  >= $intNameIDStart LIMIT 1;";
		$arrResult = $this->db->query($strSQL)->result_array();
		if(sizeof($arrResult) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * Funkcija atgriež masīvu ar objektiem (līdz 1000), sākot ar objektu, kura ID ir padotais $intIDStart
	*/
	public function getAllEntities($intEntityIDStart)
	{
		$strSQL = "SELECT * FROM `entity` WHERE `ID` >= $intEntityIDStart LIMIT 1000;";
		$arrEntities = $this->db->query($strSQL)->result_array();
		return $arrEntities;
	}

	/*
	 * Funkcija pārbauda, vai datu bāzē ir entītijas, kuru ID ir lielāks par padoto $intLastID.
	 * Atgriež TRUE, ja ir, vai FALSE, ja nav.
	*/
	public function checkIsMoreEntities($intEntityIDStart)
	{
		$strSQL = "SELECT * FROM `entity` WHERE `ID`  > $intEntityIDStart LIMIT 1;";
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
	 * Funkcija atgriež masīvu ar nosaukumiem, kuri satur padoto simbolu virkni $strName
	*/
	public function getNamesByName($strName)
	{
		$strSQL = "SELECT * FROM `name` WHERE MATCH(`name`) AGAINST('$strName');";
	
		$arrNames = $this->db->query($strSQL)->result_array();
	
		return $arrNames;
	}
	
	/*
	 * Funkcija atgriež objekta (entity), kura ID ir padotais parametrs $intID, definīciju
	*/
	public function getEntityDefinition($intEntityID)
	{
		$strSQL = "SELECT definition FROM entity WHERE ID = $intEntityID;";
		$arrResult = $this->db->query($strSQL)->result_array();
		if(sizeof($arrResult) > 0)
		{
			$strDefinition = $arrResult[0]['definition'];
			return $strDefinition;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * Funkcija atgriež masīvu ar objekta (entity), kura ID ir padotais $intID, nosaukumu nosaukumiem un ID
	*/
	public function getEntityNames($intEntityID)
	{
		$arrNames = array();
	
		$strSQL = "
		SELECT n.ID, n.name
		FROM name n, entityName en
		WHERE en.nameID = n.ID
		AND en.entityID = '". $intEntityID ."';";
	
		$arrNames = $this->db->query($strSQL)->result_array();
	
		return $arrNames;
	}
	
	/*
	 * Funkcija atgriež masīvu ar objekta (entity), kura ID ir padotais $intID, ontoloģiski saistītajiem objektiem
	 * (ID, definīcija, ontoloģiskās saites komentārs
	*/
	public function getEntityRelations($intEntityID)
	{
		$strSQL = "
		SELECT e.ID, e.definition, eo.comment
		FROM entityOntology eo, entity e
		WHERE eo.entity1ID = '$intEntityID'
		AND eo.entity2ID = e.ID
		UNION
		SELECT e.ID, e.definition, eo.comment
		FROM entityOntology eo, entity e
		WHERE eo.entity2ID = '$intEntityID'
		AND eo.entity1ID = e.ID;";
	
		$arrRelations = $this->db->query($strSQL)->result_array();
	
		return $arrRelations;
	}
	
	/*
	 * Funkcija atgriež nosaukuma, kura ID ir padotai $intID, nosaukumu
	*/
	public function getNameName($intNameID)
	{
		$strSQL = "SELECT name FROM name WHERE ID = $intNameID; ";
		$arrResult = $this->db->query($strSQL)->result_array();
		if(sizeof($arrResult) > 0)
		{
			$strName = $arrResult[0]['name'];
			return $strName;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * Funkcija atgriež masīvu ar objektiem (entity), kuriem piesaistīts nosaukums (name), kura ID ir padotais $intID
	*/
	public function getNameEntities($intNameID)
	{
		$strSQL = "
		SELECT  e.ID, e.definition
		FROM entityName en, entity e
		WHERE en.nameID = '$intNameID'
		AND en.entityID = e.ID; ";
	
		$arrEntities = $this->db->query($strSQL)->result_array();
	
		return $arrEntities;
	}
	
	/*
	 * Funkcija atgriež masīvu ar entītijas, kuras ID ir padotais $intID, dokumentu, kuros šīs entītijas nosaukums ir atrasts,
	 * norādi, nosaukumu, tipu, izdošanas laiku un nosaukuma sastopamību dokumentā
	*/
	public function getNameDocuments($intNameID, $intIDStart)
	{
		$strSQL = "
		SELECT d.reference, d.title, d.type, d.date, nd.occurrences
		FROM document d, nameDocument nd
		WHERE nd.nameID = '$intNameID'
		AND d.ID = nd.documentID
		LIMIT $intIDStart, 100;";
	
		$arrDocuments = $this->db->query($strSQL)->result_array();
	
		return $arrDocuments;
	}
	
	/*
	 * Funkcija pārbauda, vai datu bāzē ir dokumenti, kuros ir atrast entītijas, kuras ID ir padotais $intID, nosaukums un
	 * kuru ID ir lielāks par padoto $intDocumentIDStart.
	 * Atgriež TRUE, ja ir, vai FALSE, ja nav.
	*/
	public function checkIsMoreDocuments($intNameID, $intDocumentIDStart)
	{
		$strSQL = "SELECT * FROM nameDocument WHERE nameID = $intNameID AND documentID >= $intDocumentIDStart LIMIT 1;";
		$arrResult = $this->db->query($strSQL)->result_array();
		if(sizeof($arrResult) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>