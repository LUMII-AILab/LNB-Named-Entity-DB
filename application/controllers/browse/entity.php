<?php
/**
 * Fails: entity.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.12
 * Pēdējās izmaiņas: 2012.05.23.
 * 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Klase Entity
 * Nolūks: kontrolieris objekta informācijas meklēšanas, parādīšanas, labošanas, dzēšamas funkciju izpildei
*/
class Entity extends CI_Controller
{
	/* Klases konstruktors */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('session_helper');
		$this->load->model('entity_model');	
		$this->load->library('session');
	}
	
	/*
	 * Funkcija nodrošina objekta sķirkļa parādīšanu un novirzi uz šķirklī izsaukto formu funkcijām
	 * 
	 * Parametri:
	 * 	$intEntityID - objekta identifikators, 
	 * 	$arrOutputData - skata informācijas masīvs
	 * 
	 */
	public function showEntityData($intEntityID, $arrOutputData=array())
	{	
		if (!isset($intEntityID) || !is_numeric($intEntityID) || !ctype_digit($intEntityID) || !$this->entity_model->checkIfIsEntity($intEntityID)) // pārbauda padotā ID vērtību
		{
			header ("Location: /namedEntityDB/browse/");
		}
		else 
		{	
			if ($this->input->post('delete_entity')) // objekta dzēšana
			{
				$this->deleteEntity($intEntityID);
			}
			elseif ($this->input->post('save_entity')) // objekta pamatinfo labošana
			{
				$this->editEntity($intEntityID);
			}
			else
			{				
				if ($this->input->post('delete_name')) // objekta nosaukuma dzēšana
				{
					$this->deleteEntityName($intEntityID);
				}
				elseif ($this->input->post('delete_ontology')) // objekta ontoloģiska objekta dzēšana
				{
					$this->deleteEntityOntology($intEntityID);
				}
				elseif ($this->input->post('delete_resource')) // objekta resursa dzēšana
				{
					$this->deleteEntityResource($intEntityID);
				}
				
				elseif ($this->input->post('edit_entity')) // objekta pamatinfo labošanas skats
				{
					$arrOutputData['bolEditEntity'] = TRUE;
					$arrOutputData['arrCategories'] =  $this->entity_model->getAllCategories();
				}
				elseif ($this->input->post('edit_name')) // objekta nosaukuma labošanas skats
				{
					$arrOutputData['intEditName'] = $this->input->post('name_id');
				}
				elseif ($this->input->post('edit_ontology')) // objekta ontoloģijas labošanas skats
				{
					$arrOutputData['intEditOntology'] = $this->input->post('ontology_id');
				}
				elseif ($this->input->post('edit_resource')) // objekta resursa labošanas skats
				{
					$arrOutputData['intEditResource'] = $this->input->post('resource_id');
				}
				
				elseif ($this->input->post('save_name')) // objekta nosaukuma labošana
				{
					$this->editEntityName($intEntityID);
				}
				elseif ($this->input->post('save_ontology')) // objekta ontoloģiska objekta labošana
				{
					$this->editEntityOntology($intEntityID);
				}
				elseif ($this->input->post('save_resource')) // objekta resursa labošana
				{
					$this->editEntityResource($intEntityID);
				}
				
				elseif ($this->input->post('add_name')) // objekta nosaukuma pievienošana
				{
					$arrOutputData = $this->addEntityName($intEntityID);
				}
				elseif ($this->input->post('add_ontology')) // objekta ontoloģiska objekta pievienošana
				{
					$arrOutputData = $this->addEntityOntology($intEntityID);
				}
				elseif ($this->input->post('add_resource')) // objekta resursa pievienošana
				{
					$arrOutputData = $this->addEntityResource($intEntityID);
				}
				
				
				$arrOutputData = array_merge($arrOutputData, $this->entity_model->getEntityData($intEntityID));
				
				$this->load->view('entity_view', $arrOutputData); // ielādē objekta šķirkli
			}
		}
	}
		
	/* Funkcija nodrošina jauna objekta pievienošanu */
	public function addNewEntity()
	{
		if (checkSession($this))
		{
			if (!$this->input->post('definition', TRUE)) // ja nav padoti formas ievaddati, ielādē jauna objekta pievienošanas formas skatu
			{
				$arrOutputData['arrCategories'] = $this->entity_model->getAllCategories(); // izsauc funkciju visu kategoriju iegūšanai, ko rādīt formās izvēlnes laukā
				$this->load->view('add_new_entity_view', $arrOutputData);
			}
			else
			{
				$strDefinition = $this->input->post('definition', TRUE);
				$strTime = $this->input->post('time', TRUE);
				$intCategory = $this->input->post('category', TRUE);
				$strName = $this->input->post('name', TRUE);
				$intEntityID = $this->entity_model->insertEntity($strDefinition, $strTime, $intCategory);
				$arrOutputData = $this->addEntityName($intEntityID);
				$this->showEntityData($intEntityID, $arrOutputData); // novirza uz jaunizveidotā vai atrastā objekta šķirkli
			}
		}
	}
	
	/* Funkcija nodrošina iespēju labot objekta pamatinformāciju */
	public function editEntity($intEntityID)
	{
		if (checkSession($this))
		{
			$strDefinition = $this->input->post('definition');
			$strTime = $this->input->post('time');
			$intCategory = $this->input->post('category');
			$intNewEntityID = $this->entity_model->updateEntity($intEntityID, $strDefinition, $strTime, $intCategory);
			unset($_POST['save_entity']);
			$this->showEntityData($intNewEntityID); // novirza uz jaunizveidotā vai atrastā objekta šķirkli
		}
	}
	
	/* Funkcija nodrošina iespēju labot objekta un nosaukuma saites informāciju */
	public function editEntityName($intEntityID)
	{
		if (checkSession($this))
		{
			$intNameID = $this->input->post('name_id');
			$strComment = $this->input->post('name_comment');
			$strTimeFrom = $this->input->post('time_from');
			$strTimeTo = $this->input->post('time_to');
			$this->entity_model->updateEntityName($intEntityID, $intNameID, $strComment, $strTimeFrom, $strTimeTo);
		}
	}
	
	/* Funkcija nodrošina iespēju labot objektu ontoloģiskās saites informāciju */
	public function editEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$intOntEntityID = $this->input->post('ontology_id');
			$strComment = $this->input->post('ontology_comment', TRUE);
			$this->entity_model->updateEntityOntology($intEntityID, $intOntEntityID, $strComment);
		}
	}
	
	/* Funkcija nodrošina iespēju labot objektu ontoloģiskās saites informāciju */
	public function editEntityResource($intEntityID)
	{
		if (checkSession($this))
		{
			$intResourceID = $this->input->post('resource_id');
			$strComment = $this->input->post('resource_comment', TRUE);
			$this->entity_model->updateEntityResource($intEntityID, $intResourceID, $strComment);
		}
	}
	
	
	/* Funkcija nodrošina iespēju dzēst objektu */
	public function deleteEntity($intEntityID)
	{
		if (checkSession($this))
		{
			$this->entity_model->deleteEntity($intEntityID);
			$this->load->view('deleted_view', array('object' => 'entity'));
		}
	}
	
	/* Funkcija nodrošina iespēju dzēst objekta un nosaukuma saites informāciju */
	public function deleteEntityName($intEntityID)
	{
		if (checkSession($this))
		{
			$intNameID = $this->input->post('name_id');
			$this->entity_model->deleteEntityName($intEntityID, $intNameID);
		}
	}
	
	/* Funkcija nodrošina iespēju dzēst objektu ontoloģiskās saites informāciju */
	public function deleteEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$intOntEntityID = $this->input->post('ontology_id');
			$this->entity_model->deleteEntityOntology($intEntityID, $intOntEntityID);
		}
	}
	
	/* Funkcija nodrošina iespēju dzēst objekta un resursa saites informāciju */
	public function deleteEntityResource($intEntityID)
	{
		if (checkSession($this))
		{
			$intResourceID = $this->input->post('resource_id');
			$this->entity_model->deleteEntityResource($intEntityID, $intResourceID);
		}
	}
	
	
	
	/* Funkcija nodrošina iespēju pievienot objektam nosaukumu */
	public function addEntityName($intEntityID)
	{
		if (checkSession($this))
		{	
			$arrOutputData['strName'] = $this->input->post('name', TRUE);
			$arrOutputData['strNameComment'] = $this->input->post('name_comment', TRUE);
			$arrOutputData['strNameTimeFrom'] = $this->input->post('name_time_from', TRUE);
			$arrOutputData['strNameTimeTo'] = $this->input->post('name_time_to', TRUE);
			
			$intNameID = $this->entity_model->insertName($arrOutputData['strName']);
				
			$result = $this->entity_model->insertEntityName($intEntityID, $intNameID, $arrOutputData['strNameComment'], $arrOutputData['strNameTimeFrom'], $arrOutputData['strNameTimeTo']);
			if (!$result)
			{
				$arrOutputData['strNameError'] = "Nosaukums un objekts jau ir sasaistīti.";
			}
			
			return $arrOutputData;
		}
	}
	
	/* Funkcija nodrošina iespēju pievienot objektam ontoloģisku objektu */
	public function addEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$arrOutputData['intOntologyID'] = $this->input->post('ontology_id', TRUE);
			$arrOutputData['strOntologyComment'] = $this->input->post('ontology_comment', TRUE);
			
			if (!is_numeric($arrOutputData['intOntologyID']) || !ctype_digit($arrOutputData['intOntologyID']) || !$this->entity_model->checkIfIsEntity($arrOutputData['intOntologyID'])) // pārbauda padotā ID vērtību
			{
				$arrOutputData['strOntologyError'] = "Ievadiet derīgu objekta ID!";
			}
			else
			{
				$result = $this->entity_model->insertEntityOntology($intEntityID, $arrOutputData['intOntologyID'], $arrOutputData['strOntologyComment']);
				if (!$result)
				{
					$arrOutputData['strOntologyError'] = "Objekti jau ir sasaistīti.";
				}
			}
			
			return $arrOutputData;
		}	
	}
	
	/* Funkcija nodrošina iespēju pievienot objektam resursu */
	public function addEntityResource($intEntityID)
	{
		if (checkSession($this))
		{
			$arrOutputData['strResourceName'] = $this->input->post('resource_name', TRUE);
			$arrOutputData['strResourceRef'] = $this->input->post('resource_ref', TRUE);
			$arrOutputData['strResourceComment'] = $this->input->post('resource_comment', TRUE);

			$intResourceID = $this->entity_model->insertResource($arrOutputData['strResourceName'], $arrOutputData['strResourceRef']);
			$result = $this->entity_model->insertEntityResource($intEntityID, $intResourceID, $arrOutputData['strResourceComment']);
			if (!$result)
			{
				$arrOutputData['strResourceError'] = "Objekts un resurss jau ir sasaistiti.";
			}
			
			return $arrOutputData;
		}
	}
}
?>