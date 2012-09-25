<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * KLASE
 * 
 * Nosaukums: Search
 * Funkcija: kontrolieris nosaukuma meklēšanas funkcijas izpildei
*/
class Search extends CI_Controller
{
	/*
	 * FUNKCIJA
	 * 
	 * Klases konstruktors
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url'); // ielādē url palīgu - redirect() funkcijas izmantošanai
		$this->load->model('search_model');	// ielādē modeli
		$this->load->library('session'); // ielādē sesijas bibliotēku, lai ērti izmantotu sesijas datus
	}
	
	/*
	 * FUNKCIJA
	 * 
	 * Nosaukums: index (Klases noklusējuma funkcija)
	 * Funkcija: novirza uz funkciju showSearchResults
	*/
	public function index()
	{
		$this->showSearchResults();
	}
	
	/*
	 * FUNKCIJA
	 * 
	 * Nosaukums: showSearchResults
	 * Funkcija: Nosaukuma meklēšana
	 * Parametri:
	 * 			(POST)
	 * 				key_word - meklējamais atslēgas vārds
	 * 				
	 * 				
	*/
	public function showSearchResults()
	{
		// Meklējamais atslēgas vārds
		if ($this->input->get('key_word', TRUE))
		{
			$strName = addslashes($this->input->get('key_word', TRUE));
		}
		else
		{
			// Ja nav padota nosaukuma vērtība, pieņem, ka jāmeklē visi noteiktās kategorijas nosaukumi
			$strName = '';
		}
		$arrOutputData['strKeyWord'] = $strName;
		
		
		// Rezultātu kārtošanas kritērijs
		if ($this->input->get('order_by', true))
		{
			$strOrderByGet = $this->input->get('order_by', true);
			if ($strOrderByGet == 'name' || $strOrderByGet == 'occ' || $strOrderByGet == 'def' || $strOrderByGet == 'time' || $strOrderByGet == 'category')
			{
				$strOrderBy = $strOrderByGet;
			}
		}
		else
		{
			$strOrderBy = 'occ';
		}
		$arrOutputData['strOrderBy'] = $strOrderBy;
		
		
		// Rezultātu kārtošanas kārtība
		if ($this->input->get('order_mode', true) == 'ASC')
		{
			$strOrderMode = 'ASC';
		}
		else
		{
			$strOrderMode = 'DESC';
		}
		$arrOutputData['strOrderMode'] = $strOrderMode;
		
		
		// Kategorijas
		$arrCategories = array();
		if ($this->input->get('category', TRUE) && is_numeric($this->input->get('category', TRUE)))
		{
			$intCategoryID = $this->input->get('category', TRUE);
			
			// atrod apakškategoriju ID vērtības, kuras būs jāmeklē datu bāzē
			if ($intCategoryID == 1) // persona
			{
				$arrCategories = array_merge($arrCategories, array(1, 2, 3));
			}
			elseif ($intCategoryID == 2) // vieta
			{
				$arrCategories = array_merge($arrCategories, array(4, 5, 6, 7));
			}
			elseif ($intCategoryID == 3) // organizācija
			{
				$arrCategories = array_merge($arrCategories, array(8, 9, 10, 11, 12, 13));
			}
			elseif ($intCategoryID == 4) // iestāde
			{
				$arrCategories = array_merge($arrCategories, array(14));
			}
			elseif ($intCategoryID == 5) // notikums
			{
				$arrCategories = array_merge($arrCategories, array(15));
			}
			elseif ($intCategoryID == 6) // produkts
			{
				$arrCategories = array_merge($arrCategories, array(16, 17, 18, 19, 20));
			}
			elseif ($intCategoryID == 7) // laiks
			{
				$arrCategories = array_merge($arrCategories, array(21, 22));
			}
			elseif ($intCategoryID == 8) // citi
			{
				$arrCategories = array_merge($arrCategories, array(23));
			}
		}
		else // pēc noklusējuma meklē visos
		{
			$intCategoryID = 0;
		}
		$arrOutputData['intCategoryID'] = $intCategoryID;
		
		
		// Lapas numurs
		if ($this->input->get('pageNum', TRUE) && is_numeric($this->input->get('pageNum', TRUE)) && $this->input->get('pageNum', TRUE) > 0)
		{
			$intPageNum = intval($this->input->get('pageNum', TRUE));
		}
		else // pēc noklusējuma 1. lapa
		{
			$intPageNum = 1;
		}
		$arrOutputData['intPageNum'] = $intPageNum;

		
		// Ierakstu skaits vienā lapā
		if ($this->input->get('row_count_per_page', TRUE) == 20 || $this->input->get('row_count_per_page', TRUE) == 40)
		{
			$intRowCountPerPage = (int)$this->input->get('row_count_per_page', TRUE);
		}
		else
		{
			$intRowCountPerPage = 40;
		}
		$arrOutputData['intRowCountPerPage'] = $intRowCountPerPage;
		
		
		// Izsauc meklēšanas funkciju datu bāzē
		$arrResult = $this->search_model->getEntityNameByNameAndCategory($strName, $arrCategories, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage);
		
		$arrOutputData['arrEntityNames'] = $arrResult['arrEntityNames'];
		$arrOutputData['intRowsCount'] = $arrResult['intRowsCount'];

		// Izveido meklēšanas rezultātu skatu
		$this->load->view('search_view', $arrOutputData);
	}
}
?>