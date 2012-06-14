<?php
/**
 * Fails: search.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.10
 * Pēdējās izmaiņas: 2012.05.23.
 * 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Klase Search
 * Nolūks: kontrolieris nosaukuma meklēšanas funkcijas izpildei
*/
class Search extends CI_Controller
{
	/*
	 * Klases konstruktors.
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url'); // ielādē url palīgu - redirect() funkcijas izmantošanai
		$this->load->model('search_model');	// ielādē modeli
		$this->load->library('session'); // ielādē sesijas bibliotēku, lai ērti izmantotu sesijas datus
	}
	
	/*
	 * Klases noklusējuma funkcija
	*/
	public function index()
	{
		$this->showSearchResults();
	}
	
	/*
	 * Funkcija saņem datus: meklējamais nosaukums un tā kategorija. Apstrādā tos, noskaidro rezultātu kārtošanas
	 * parametrus, izsauc meklēšanas funkciju un izsauc meklēšanas rezultātu skatu.
	*/
	public function showSearchResults()
	{
		/*** start": nosaukums ***/
		if ($this->input->post('key_word', TRUE))
		{
			$strName = $this->input->post('key_word', TRUE);
		}
		else // ja nav padota nosaukuma vērtība, pieņem, ka jāmeklē visi noteiktās kategorijas nosaukumi
		{
			$strName = '';
		}
		/*** end: nosaukums ***/
		
		
		/*** start: rezultāatu kārtošana ***/
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
		
		
		/*** start: kategorijas ***/
		$arrCategories = array();
		
		if ($this->input->post('category', TRUE))
		{
			$arrCategoriesPost = $this->input->post('category', TRUE);
			foreach ($arrCategoriesPost as $intCategoryID)
			{
				/* atrod apakškategoriju ID vērtības, kuras būs jāmeklē datu bāzē */
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
		}
		else // pēc noklusējuma meklē visos
		{
			$arrCategoriesPost = array(0);
		}
		/*** end: kategorijas ***/
		
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
		
		/* uzglabā skatā izmantojamos datus masīvā */
		$arrResult =$this->search_model->getEntityNameByNameAndCategory($strName, $arrCategories, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage); // izsauc meklēšanas funkciju
		
		$arrOutputData['arrEntityNames'] = $arrResult['arrEntityNames'];
		$arrOutputData['intRowsCount'] = $arrResult['intRowsCount'];
		$arrOutputData['intRowCountPerPage'] = $intRowCountPerPage;
		$arrOutputData['intPageNum'] = $intPageNum;
		$arrOutputData['strKeyWord'] = $strName;
		$arrOutputData['arrCategoriesPost'] = $arrCategoriesPost;
		$arrOutputData['strOrderBy'] = $strOrderBy;
		$arrOutputData['strOrderMode'] = $strOrderMode;

		$this->load->view('search_view', $arrOutputData); // izsauc skatu
	}
}

?>