<?php 
/**
 * Fails: name_documents.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.20
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Klase Name_documents
* Nolūks: kontrolieris datu apstrādei par nosaukumu nosaukuma dokumentiem
*/
class Name_documents extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('session_helper');
		$this->load->model('name_document_model');	
		$this->load->library('session');
	}
	
	public function showNameDocumentData($intNameID)
	{
		if (!isset($intNameID) || !is_numeric($intNameID) || !ctype_digit($intNameID) || !$this->name_document_model->checkIfIsName($intNameID)) // pārbauda padotā ID vērtību
		{
			header ("Location: /namedEntityDB/browse/");
		}
		else
		{
			if ($this->input->post('delete_name')) // nosaukuma dzēšana
			{
				$this->deleteName($intNameID);
			}
			else
			{
				$arrOutputData = array();
				
				if ($this->input->post('edit_name')) // nosaukuma labošanas skats
				{
					$arrOutputData['bolEditName'] = TRUE;
				}
				elseif ($this->input->post('save_name')) // nosaukuma pamatinfo labošana
				{
					$this->editName($intNameID);
				}
				elseif ($this->input->post('delete_document')) // nosaukuma dokumenta dzēšana
				{
					$this->deleteNameDocument($intNameID);
				}
				elseif ($this->input->post('edit_document')) // nosaukuma dokumenta labošanas skats
				{
					$arrOutputData['intEditDocument'] = $this->input->post('document_id');
				}
				elseif ($this->input->post('save_document')) // nosaukuma dokumenta labošana
				{
					$this->editNameDocument($intNameID);
				}
				elseif ($this->input->post('add_document')) // nosaukuma dokumenta pievienošana
				{
					$arrOutputData = $this->addNameDocument($intNameID);
				}

				
				/*** start: lapas numurs ***/
				if ($this->input->post('pageNum', TRUE))
				{
					$intPageNum = (int)$this->input->post('pageNum', TRUE);
				}
				else // pēc noklusējuma 1
				{
					$intPageNum = 1;
				}
				/*** end: lapas numurs ***/
				
				
				if ($this->input->post('row_count_per_page', TRUE))
				{
					$intRowCountPerPage = (int)$this->input->post('row_count_per_page', TRUE);
				}
				else
				{
					$intRowCountPerPage = 40;
				}
				
	
				/*** start: rezultātu kārtošana ***/
				$strOrderBy = 'occ';
				if ($this->input->post('order_by', true))
				{
					$strOrderBy = $arrOutputData['strOrderBy'] = $this->input->post('order_by', true);
				}
				
				$strOrderMode = 'desc';
				if ($this->input->post('order_mode', true))
				{
					$strOrderMode = $arrOutputData['strOrderMode'] = $this->input->post('order_mode', true);
				}
				/*** end: rezultātu kārtošana ***/
				
				$arrResult = $this->name_document_model->getNameDocumentData($intNameID, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage); // iegūst dokumentu sarakstu
				
				$arrOutputData['arrDocuments'] = $arrResult['arrDocuments'];
				$arrOutputData['intRowsCount'] = $arrResult['intRowsCount'];
				$arrOutputData['arrNameData'] = $this->name_document_model->getNameData($intNameID); // iegūst nosaukumu
				$arrOutputData['arrNameEntities'] = $this->name_document_model->getNameEntities($intNameID); // iegūst nosaukuma objektus
				$arrOutputData['intRowCountPerPage'] = $intRowCountPerPage;
				$arrOutputData['intPageNum'] = $intPageNum;
				$arrOutputData['strOrderBy'] = $strOrderBy;
				$arrOutputData['strOrderMode'] = $strOrderMode;
				
				
				$this->load->view('name_document_view', $arrOutputData);
			}
		}
	}
	
	/* Funkcija nodrošina iespēju labot nosaukuma pamatinformāciju */
	public function editName($intNameID)
	{
		if (checkSession($this))
		{
			$strName = $this->input->post('name');
			$intNewEntityID = $this->name_document_model->updateName($intNameID, $strName);
		}
	}
	
	/* Funkcija nodrošina iespēju labot nosaukuma un dokumenta saites un dokumenta informāciju */
	public function editNameDocument($intNameID)
	{
		if (checkSession($this))
		{
			$intOccurrences = $this->input->post('occurrences');
			if (!is_numeric($intOccurrences) || !ctype_digit($intOccurrences) || $intOccurrences < 0)
			{
				$arrOutputData['strDocumentError'] = "Norādiet derīgu nosaukuma sastopamības vērtību!";
			}
			else
			{
				$intDocumentID = $this->input->post('document_id');
				$strTitle = $this->input->post('title');
				$strReference = $this->input->post('reference');
				$strAuthor = $this->input->post('author');
				$strDate = $this->input->post('date');
				$strType = $this->input->post('type');
				$this->name_document_model->updateDocument($intDocumentID, $strTitle, $strReference, $strAuthor, $strDate, $strType);
				$this->name_document_model->updateNameDocument($intNameID, $intDocumentID, $intOccurrences);
			}
		}
	}
	
	/* Funkcija nodrošina iespēju pievienot nosaukumam dokumentu */
	public function addNameDocument($intNameID)
	{	
		if (checkSession($this))
		{
			$arrOutputData['intOccurrences'] = $this->input->post('occurrences');
			$arrOutputData['strTitle'] = $this->input->post('title', TRUE);
			$arrOutputData['strAuthor'] = $this->input->post('author', TRUE);
			$arrOutputData['strDate'] = $this->input->post('date', TRUE);
			$arrOutputData['strType'] = $this->input->post('type', TRUE);
			$arrOutputData['strReference'] = $this->input->post('reference', TRUE);
			
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
	
	/* Funkcija nodrošina iespēju dzēst nosaukumu */
	public function deleteName($intNameID)
	{
		if (checkSession($this))
		{
			$this->name_document_model->deleteName($intNameID);
			$this->load->view('deleted_view', array('object' => 'name'));
		}
	}
	
	/* Funkcija nodrošina iespēju dzēst nosaukuma dokumentu */
	public function deleteNameDocument($intNameID)
	{
		$intDocID = $this->input->post('document_id', TRUE);
		$this->name_document_model->deleteNameDocument($intDocID, $intNameID);
	}
}

?>