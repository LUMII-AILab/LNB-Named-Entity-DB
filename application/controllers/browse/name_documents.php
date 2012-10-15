<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * KLASE
 * 
 * Nosaukums: Name_documents
 * Funkcija: Kontrolieris datu apstrādei ar nosaukumu dokumentiem
*/
class Name_documents extends CI_Controller
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
		$this->load->model('name_document_model');	
		$this->load->library('session');
	}
	
	/*
	 * FUNKCIJA
	 * 
	 * Nosaukums: showNameDocumentData
	 * Funkcija: Nosaukuma dokumentu sķirkļa parādīšanu un šķirkla rediģēšanas funkciju izsaukšanu
	 * Parametri:
	 *			$intNameID - nosaukuma identifikators
	 * 			(POST) Izsauc rediģēšanas funkcijas:
	 * 				delete_name
	 * 				edit_name
	 * 				save_name
	 * 				delete_document
	 * 				edit_document, document_id
	 * 				save_document
	 * 				add_document
	 * 			(POST)
	 * 				pageNum - lapas numurs
	 * 				row_count_per_page - dokumentu ierakstu skaits vienā lapā
	 * 				order_by - ierakstu kārtošanas kritērijs
	 * 				order_mode - ierakstu kārtošanas kārtība
	*/
	public function showNameDocumentData($intNameID)
	{
		// Pārbauda, vai padotais nosaukuma ID ir derīgs
		if (!isset($intNameID) || !is_numeric($intNameID) || !ctype_digit($intNameID) || !$this->name_document_model->checkIfIsName($intNameID))
		{
			header ("Location: /namedEntityDB/browse/");
		}
		else
		{
			if ($this->input->post('delete_name'))
			{
				// Nosaukuma dzēšana
				$this->deleteName($intNameID);
			}
			else
			{
				// Nosaukuma dokumentu saraksta apskatīšana vai rediģēšana
				$arrOutputData = array();
				
				
				// Nosaukuma vai nosaukuma dokumenta rediģēšanas funkciju izsaukšana
				if ($this->input->post('edit_name'))
				{
					// Nosaukuma labošanas skats
					$arrOutputData['bolEditName'] = TRUE;
				}
				elseif ($this->input->post('save_name'))
				{
					// Nosaukuma pamatinformācijas labošana
					$this->editName($intNameID);
				}
				elseif ($this->input->post('delete_document'))
				{
					// Nosaukuma dokumenta dzēšana
					$this->deleteNameDocument($intNameID);
				}
				elseif ($this->input->post('edit_document'))
				{
					// Nosaukuma dokumenta labošanas skats
					$arrOutputData['intEditDocument'] = $this->input->post('document_id');
				}
				elseif ($this->input->post('save_document'))
				{
					// Nosaukuma dokumenta labošana
					$this->editNameDocument($intNameID);
				}
				elseif ($this->input->post('add_document'))
				{
					// Nosaukuma dokumenta pievienošana
					$arrOutputData = $this->addNameDocument($intNameID);
				}

				
				// Lapas numurs
				if ($this->input->post('pageNum', TRUE))
				{
					$intPageNum = (int)$this->input->post('pageNum', TRUE);
				}
				else
				{
					// Pēc noklusējuma tiek rādīta 1.lapa
					$intPageNum = 1;
				}
				$arrOutputData['intPageNum'] = $intPageNum;
				
				
				// Rindu skaits vienā lapā
				$arrRowCounts = array(10, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100);
				if (in_array($this->input->get('row_count_per_page', TRUE), $arrRowCounts))
				{
					$intRowCountPerPage = (int)$this->input->get('row_count_per_page', TRUE);
				}
				else
				{
					$intRowCountPerPage = 40;
				}
				$arrOutputData['intRowCountPerPage'] = $intRowCountPerPage;
				
	
				// Rezultātu kārtošanas kritērijs: sastopamība, dokumenta nosaukums, datums vai tips
				if ($this->input->post('order_by', true))
				{
					$strOrderBy = $this->input->post('order_by', true);
				}
				else
				{
					// Pēc noklusējuma tiek kārtots pēc sastopamības
					$strOrderBy = 'occ';
				}
				$arrOutputData['strOrderBy'] = $strOrderBy;
				
				
				// Rezultātu kārtošanas kārtība: augoši vai dilstoši
				if ($this->input->post('order_mode', true))
				{
					$strOrderMode = $arrOutputData['strOrderMode'] = $this->input->post('order_mode', true);
				}
				else 
				{
					// Pēc noklusējuma tiek kārtots dilstoši
					$strOrderMode = 'desc';
				}
				$arrOutputData['strOrderMode'] = $strOrderMode;
				
				
				// Iegūst nosaukuma dokumentus
				$arrResult = $this->name_document_model->getNameDocumentData($intNameID, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage);
				$arrOutputData['arrDocuments'] = $arrResult['arrDocuments'];
				$arrOutputData['intRowsCount'] = $arrResult['intRowsCount'];
				
				// Iegūst nosaukumu
				$arrNameData = $this->name_document_model->getNameData($intNameID);
				$arrOutputData['arrNameData'] = $arrNameData;
				
				// Iegūst nosaukuma objektus
				$arrNameEntities = $this->name_document_model->getNameEntities($intNameID);
				$arrOutputData['arrNameEntities'] = $arrNameEntities;
				
				
				// Izveido nosaukuma dokumentu šķirkļa skatu
				$this->load->view('name_document_view', $arrOutputData);
			}
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: editName
	 * Funkcija: Nosaukuma pamatinformāciju labošana
	 * Parametri: 
	 * 			$intNameID - nosaukuma identifikators
	 * 			(POST)
	 * 				name - jaunais nosaukums
	*/
	public function editName($intNameID)
	{
		if (checkSession($this))
		{
			$strName = $this->input->post('name');
			$intNewEntityID = $this->name_document_model->updateName($intNameID, $strName);
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: editNameDocument
	 * Funkcija: Nosaukuma&dokumenta saites un dokumenta informācijas labošana
	 * Parametri: 
	 * 			$intNameID - nosaukuma identifikators
	 * 			(POST)
	 * 				occurrences - nosaukuma sastopamība dokumentā
	 * 				document_id - dokumenta identifikators
	 * 				title - dokumenta nosaukums
	 * 				reference - norāde uz dokumentu
	 * 				author - dokumenta autors
	 * 				date - dokumenta izdošanas datums
	 * 				type - dokumenta tips
	*/
	public function editNameDocument($intNameID)
	{
		if (checkSession($this))
		{
			$intOccurrences = $this->input->post('occurrences');
			$intDocumentID = $this->input->post('document_id');
			$strTitle = $this->input->post('title');
			$strReference = $this->input->post('reference');
			$strAuthor = $this->input->post('author');
			$strDate = $this->input->post('date');
			$strType = $this->input->post('type');
			
			// Pārbauda sastopamības vērtību
			if (!is_numeric($arrOutputData['intOccurrences']) || !ctype_digit($arrOutputData['intOccurrences']) || $arrOutputData['intOccurrences'] < 0)
			{
				$arrOutputData['strDocumentError'] = "Norādiet derīgu nosaukuma sastopamības vērtību!";
			}
			else
			{
				$this->name_document_model->updateDocument($intDocumentID, $strTitle, $strReference, $strAuthor, $strDate, $strType);
				$this->name_document_model->updateNameDocument($intNameID, $intDocumentID, $intOccurrences);
			}
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: addNameDocument
	 * Funkcija: Dokumentu pievienošana nosaukumam
	 * Parametri: 
	 * 			$intNameID - nosaukuma identifikators
	 * 			(POST)
	 * 				occurrences - nosaukuma sastopamība dokumentā
	 * 				title - dokumenta nosaukums
	 * 				reference - norāde uz dokumentu
	 * 				author - dokumenta autors
	 * 				date - dokumenta izdošanas datums
	 * 				type - dokumenta tips
	*/
	public function addNameDocument($intNameID)
	{
		if (checkSession($this))
		{
			$arrOutputData['intOccurrences'] = $this->input->post('occurrences');
			$arrOutputData['strTitle'] = $this->input->post('title', TRUE);
			$arrOutputData['strReference'] = $this->input->post('reference', TRUE);
			$arrOutputData['strAuthor'] = $this->input->post('author', TRUE);
			$arrOutputData['strDate'] = $this->input->post('date', TRUE);
			$arrOutputData['strType'] = $this->input->post('type', TRUE);
			
			// Pārbauda sastopamības vērtību
			if (!is_numeric($arrOutputData['intOccurrences']) || !ctype_digit($arrOutputData['intOccurrences']) || $arrOutputData['intOccurrences'] < 0)
			{
				$arrOutputData['strDocumentError'] = "Norādiet derīgu nosaukuma sastopamības vērtību!";
			}
			else
			{
				$intDocumentID = $this->name_document_model->insertDocument($arrOutputData['strTitle'], $arrOutputData['strAuthor'], $arrOutputData['strDate'], $arrOutputData['strType'], $arrOutputData['strReference']);
				
				$result = $this->name_document_model->insertNameDocument($intNameID, $intDocumentID, $arrOutputData['intOccurrences']);
				if (!$result)
				{
					$arrOutputData['strDocumentError'] = 'Dokuments un nosaukums jau ir sasaistīti.';
				}
			}
			return $arrOutputData;
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteName
	 * Funkcija: Nosaukumu dzēšana
	 * Parametri: 
	 * 			$intNameID - nosaukuma identifikators					
	*/
	public function deleteName($intNameID)
	{
		if (checkSession($this))
		{
			$this->name_document_model->deleteName($intNameID);
			$this->load->view('deleted_view', array('object' => 'name'));
		}
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: deleteNameDocument
	 * Funkcija: Nosaukuma un dokumenta saites dzēšana
	 * Parametri: 
	 * 			$intNameID - nosaukuma identifikators
	 * 			(POST)
	 * 				document_id - dokumenta identifikators
	*/
	public function deleteNameDocument($intNameID)
	{
		$intDocID = $this->input->post('document_id', TRUE);
		$this->name_document_model->deleteNameDocument($intDocID, $intNameID);
	}
}
?>