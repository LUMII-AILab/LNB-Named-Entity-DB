<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * KLASE
 * Nosaukums: Entity
 * Funkcija: Kontrolieris objekta informācijas meklēšanas, parādīšanas, labošanas, dzēšanas funkcijām
*/
class Entity extends CI_Controller
{
	/* 
	 * FUNKCIJA
	 * 
	 * Klases konstruktors 
	*/
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
	 * FUNKCIJA
	 * 
	 * Nosaukums: showEntityData
	 * Funkcija: Objekta sķirkļa parādīšana un šķirklī rediģēšanas funkciju izsaukšana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			$arrOutputData - skata informācijas masīvs
	 * 			(POST) Izsauc rediģēšanas funkcijas:
	 * 				delete_entity
	 * 				save_entity
	 * 				delete_name
	 * 				delete_ontology
	 * 				delete_resource
	 * 				edit_entity
	 * 				edit_name, name_id
	 * 				edit_ontology, ontology_id
	 * 				edit_resource, resource_id
	 * 				save_name
	 * 				save_ontology
	 * 				save_resource
	 * 				add_name
	 * 				add_ontology
	 * 				add_resource		
	 */
	public function showEntityData($intEntityID, $arrOutputData=array())
	{
		// Pārbauda, vai padotais objekta ID ir derīgs
		if (!isset($intEntityID) || !is_numeric($intEntityID) || !ctype_digit($intEntityID) || !$this->entity_model->checkIfIsEntity(intval($intEntityID))) // pārbauda padotā ID vērtību
		{
			header ("Location: /namedEntityDB/browse/");
		}
		else 
		{
			if ($this->input->post('delete_entity'))
			{
				// Objekta dzēšana
				$this->deleteEntity($intEntityID);
			}
			elseif ($this->input->post('save_entity'))
			{
				// Objekta pamatinformācijas labošana
				$this->editEntity($intEntityID);
			}
			else
			{
				// Objekta apskatīšana vai rediģēšana
				
				
				// Objekta rediģēšanas funkciju izsaukšana
				if ($this->input->post('delete_name'))
				{
					// Objekta nosaukuma dzēšana
					$this->deleteEntityName($intEntityID);
				}
				elseif ($this->input->post('delete_ontology'))
				{
					// Objekta ontoloģiska objekta dzēšana
					$this->deleteEntityOntology($intEntityID);
				}
				elseif ($this->input->post('delete_resource'))
				{
					// Objekta resursa dzēšana
					$this->deleteEntityResource($intEntityID);
				}
				
				elseif ($this->input->post('edit_entity'))
				{
					// Objekta pamatinfo labošanas skats
					$arrOutputData['bolEditEntity'] = TRUE;
					$arrOutputData['arrCategories'] =  $this->entity_model->getAllCategories();
				}
				elseif ($this->input->post('edit_name'))
				{
					// Objekta nosaukuma labošanas skats
					$arrOutputData['intEditName'] = $this->input->post('name_id');
				}
				elseif ($this->input->post('edit_ontology'))
				{
					// Objekta ontoloģijas labošanas skats
					$arrOutputData['intEditOntology'] = $this->input->post('ontology_id');
				}
				elseif ($this->input->post('edit_resource'))
				{
					// Objekta resursa labošanas skats
					$arrOutputData['intEditResource'] = $this->input->post('resource_id');
				}
				
				elseif ($this->input->post('save_name'))
				{
					// Objekta nosaukuma labošana
					$this->editEntityName($intEntityID);
				}
				elseif ($this->input->post('save_ontology'))
				{
					// Objekta ontoloģiska objekta labošana
					$this->editEntityOntology($intEntityID);
				}
				elseif ($this->input->post('save_resource'))
				{
					// Objekta resursa labošana
					$this->editEntityResource($intEntityID);
				}
				
				elseif ($this->input->post('add_name'))
				{
					// Objekta nosaukuma pievienošana
					$arrOutputData = $this->addEntityName($intEntityID);
				}
				elseif ($this->input->post('add_ontology'))
				{
					// Objekta ontoloģiska objekta pievienošana
					$arrOutputData = $this->addEntityOntology($intEntityID);
				}
				elseif ($this->input->post('add_resource'))
				{
					// Objekta resursa pievienošana
					$arrOutputData = $this->addEntityResource($intEntityID);
				}
				
				
				// Iegūst objekta informāciju
				$arrOutputData = array_merge($arrOutputData, $this->entity_model->getEntityData($intEntityID));
				
				// Izveido objekta šķirkļa skatu
				$this->load->view('entity_view', $arrOutputData);
			}
		}
	}
		
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: addNewEntity
	 * Funkcija: Jauna objekta pievienošana
	 * Parametri:
	 * 			(POST)
	 *  			definition - objekta definīcija
	 *  			time - objekta laiks
	 * 				category - objekta kategorija
	 *  			name - objekta nosaukums
	*/
	public function addNewEntity()
	{
		if (checkSession($this))
		{
			// Ja nav padoti formas ievaddati, ielādē jauna objekta pievienošanas formas skatu
			if (!$this->input->post('definition', TRUE))
			{
				// Iegūst kategorijas
				$arrOutputData['arrCategories'] = $this->entity_model->getAllCategories();
				
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
				
				// Novirza uz jaunizveidotā vai atrastā objekta šķirkli
				$this->showEntityData($intEntityID, $arrOutputData);
			}
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: editEntity
	 * Funkcija: Objekta pamatinformācijas labošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				definition - jaunā definīcija
	 * 				time - jaunais laiks
	 * 				category - jaunā kategorija
	*/
	public function editEntity($intEntityID)
	{
		if (checkSession($this))
		{
			$strDefinition = $this->input->post('definition');
			$strTime = $this->input->post('time');
			$intCategory = $this->input->post('category');
			
			$intNewEntityID = $this->entity_model->updateEntity($intEntityID, $strDefinition, $strTime, $intCategory);
			
			unset($_POST['save_entity']);
			// Novirza uz jaunizveidotā vai atrastā objekta šķirkli
			$this->showEntityData($intNewEntityID);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: editEntityName
	 * Funkcija: Objekta un nosaukuma saites informācijas labošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				name_id - nosaukuma identifikators
	 * 				name_comment - objekta un nosaukuma saites komentārs
	 * 				time_from - nosaukuma lietošanas laiks no
	 * 				time_to - nosaukuma lietošanas laiks līdz
	*/
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
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: editEntityOntology
	 * Funkcija: Objektu ontoloģiskās saites informācijas labošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				ontology_id - ontoloģisiki saistītā objekta identifikators
	 * 				ontology_comment - objektu ontoloģijas komentārs
	*/
	public function editEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$intOntEntityID = $this->input->post('ontology_id');
			$strComment = $this->input->post('ontology_comment', TRUE);
			
			$this->entity_model->updateEntityOntology($intEntityID, $intOntEntityID, $strComment);
		}
	}
	
	/*
	 * FUNKCIJA
	 * 
	 * Nosaukums: editEntityResource
	 * Funkcija: Objekta resursa informācijas labošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				resource_id - objekta resursa identifikators
	 * 				resource_comment - objekta resursa komentārs
	*/
	public function editEntityResource($intEntityID)
	{
		if (checkSession($this))
		{
			$intResourceID = $this->input->post('resource_id');
			$strComment = $this->input->post('resource_comment', TRUE);
			
			$this->entity_model->updateEntityResource($intEntityID, $intResourceID, $strComment);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteEntity
	 * Funkcija: Objekta dzēšana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	*/
	public function deleteEntity($intEntityID)
	{
		if (checkSession($this))
		{
			$this->entity_model->deleteEntity($intEntityID);
			
			$this->load->view('deleted_view', array('object' => 'entity'));
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteEntityName
	 * Funkcija: Objekta un nosaukuma saites dzēšana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				name_id - nosaukuma identifikators
	*/
	public function deleteEntityName($intEntityID)
	{
		if (checkSession($this))
		{
			$intNameID = $this->input->post('name_id');
			
			$this->entity_model->deleteEntityName($intEntityID, $intNameID);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteEntityOntology
	 * Funkcija: Objektu ontoloģiskās saites dzēšana 
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				ontology_id - ontoloģiski saistītā objekta identifikators
	*/
	public function deleteEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$intOntEntityID = $this->input->post('ontology_id');
			
			$this->entity_model->deleteEntityOntology($intEntityID, $intOntEntityID);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteEntityResource
	 * Funkcija: Objekta un resursa saites dzēšana 
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				resource_id - resursa identifikators
	*/
	public function deleteEntityResource($intEntityID)
	{
		if (checkSession($this))
		{
			$intResourceID = $this->input->post('resource_id');
			
			$this->entity_model->deleteEntityResource($intEntityID, $intResourceID);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: addEntityName
	 * Funkcija: Objekta nosaukuma pievienošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				name - nosaukums
	 * 				name_comment - objekta un nosaukuma saites komentārs
	 * 				name_time_from - nosaukuma lietošanas laiks no
	 * 				name_time_to - nosaukuma lietošanas laiks līdz
	*/
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
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: addEntityOntology
	 * Funkcija: Ontoloģiski saistīta objekta saites pievienošana
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				ontology_id - ontoloģiski saistītā objekta identifikators
	 * 				ontology_comment - objektu ontoloģijas komentārs
	*/
	public function addEntityOntology($intEntityID)
	{
		if (checkSession($this))
		{
			$arrOutputData['intOntologyID'] = $this->input->post('ontology_id', TRUE);
			$arrOutputData['strOntologyComment'] = $this->input->post('ontology_comment', TRUE);
			
			// Pārbauda padotā ID vērtību
			if (!is_numeric($arrOutputData['intOntologyID']) || !ctype_digit($arrOutputData['intOntologyID']) || !$this->entity_model->checkIfIsEntity($arrOutputData['intOntologyID']))
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
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: addEntityResource
	 * Funkcija: Resursa pievienošana objektam
	 * Parametri:
	 * 			$intEntityID - objekta identifikators
	 * 			(POST)
	 * 				resource_name - resursa nosaukums
	 * 				resource_ref - norāde uz resursu
	 * 				resource_comment - resursa komentārs
	*/
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