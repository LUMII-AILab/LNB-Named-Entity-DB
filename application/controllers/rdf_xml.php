<?php 
/**
 * Fails: rdf_xml.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.20
 * Pēdējās izmaiņas: 2012.05.23.
 * 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Klase Rdf_xml
 * Nolūks: kontrolieris datu ieguvei RDF/XML formātā
*/
class Rdf_xml extends CI_Controller 
{
	/*
	 * Klases konstruktors.
	*/	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('rdf_xml_model'); // ielādē modeli	
		$this->load->library('session');
	}
	
	/*
	 * Funkcija ielādē lietotāja saskarnes skatu datu pieprasīšanai RDF/XML formātā.
	*/
	public function index()
	{
		$arrOutputData['strPageTitle'] = "RDF/XML";
		$this->load->view('rdf_xml_input_view', $arrOutputData);
	}
	
	/*
	 * Funkcija izveido sarakstu ar datu bāzē reģistrētajiem īpašvārdiem RDF/XML formātā. 
	 * Īpašvārdi tiek atgriezti porcijās pa 1000 nosaukumiem, ja ir vairāk nosaukumu, tad tiek pievienota URI norāde uz nākamo nosaukumu porciju.
	*/
	public function showAllNames()
	{
		$strXML = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:ne="http://lnb.ailab.lv/ne#">';
		
		$strBasePath = 'http://'. $_SERVER['SERVER_NAME'];
		
		if ($this->input->get('start', TRUE)) // get parametrs 'start' norāda identifikatoru īpašvārdam, no kura jāsāk rādīt informāciju
		{
			$intIDStart = (int)$this->input->get('start', TRUE);
		}
		else
		{
			$intIDStart = 1;
		}
		
		$arrNames = $this->rdf_xml_model->getAllNames($intIDStart); // izsauc modeļa funkciju, kas atgriež informāciju par īpašvārdiem no datu bāzes 
		foreach ($arrNames as $arrName) // pievieno info par katru īpašvārdu faila saturam
		{
			$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/name/'. $arrName['ID'] .'" ne:name="'. htmlspecialchars($arrName['name']) .'"/>';			
			$intLastID = $arrName['ID'];
		}
		
		if (isset($intLastID))
		{
			$bolIsMoreNames = $this->rdf_xml_model->checkIsMoreNames($intLastID); // izsauc modeļa funkciju, kas noskaidro, vai datu bāzē ir vēl īpašvārdi
			if ($bolIsMoreNames) // faila saturam pievieno URI norādi uz nākamo īpašvārdu porciju
			{
				$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/index" ne:nextpage="'. $strBasePath .'/namedEntityDB/index?start='. ($intLastID+1) .'"/>';
			}
		}
		
		$strXML .= "</rdf:RDF>";
		
		$this->load->view('rdf_xml_view', array('strXML' => $strXML)); // RDF/XML formāta skata ielāde
	}
	
	
	/* 
	 * Funkcija izveido sarakstu ar datu bāzē reģistrētajiem objektiem RDF/XML formātā.
	 * Objekti tiek atgriezti porcijās pa 1000 objektiem, ja ir vairāk objekti, tad tiek pievienota URI norāde uz nākamo objektu porciju.
	*/
	public function showAllEntities()
	{
		$strXML = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:ne="http://lnb.ailab.lv/ne#">';
	
		$strBasePath = 'http://'. $_SERVER['SERVER_NAME'];
	
		if ($this->input->get('start', TRUE)) // get parametrs 'start' norāda identifikatoru objektam, no kura jāsāk rādīt informāciju
		{
			$intIDStart = (int)$this->input->get('start', TRUE);
		}
		else
		{
			$intIDStart = 1;
		}
	
		$arrEntities = $this->rdf_xml_model->getAllEntities($intIDStart); // izsauc modeļa funkciju, kas atgriež informāciju par objektiem no datu bāzes 
		foreach ($arrEntities as $arrEntity) // pievieno info par katru objektu faila saturam
		{
			$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/entity/'. $arrEntity['ID'] .'" ne:definition="'. htmlspecialchars($arrEntity['definition']) .'"/>';
			$intLastID = $arrEntity['ID'];
		}
		
		if (isset($intLastID)) 
		{
			$bolIsMoreEnties = $this->rdf_xml_model->checkIsMoreEntities($intLastID); // izsauc modeļa funkciju, kas noskaidro, vai datu bāzē ir vēl objekti
			if ($bolIsMoreEnties) // faila saturam pievieno URI norādi uz nākamo objektu porciju
			{
				$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/index" ne:nextpage="'. $strBasePath .'/namedEntityDB/entities?start='. ($intLastID+1) .'"/>';
			}
		}
	
		$strXML .= "</rdf:RDF>";
	
		$this->load->view('rdf_xml_view', array('strXML' => $strXML)); // RDF/XML formāta skata ielāde
	}
	
	/*
	 * Funkcija izveido sarakstu ar atrastajiem īpašvārdiem RDF/XML formātā.
	 * 
	 * Parametri:
	 * 	 strName - meklējamais īpašvārs
	 */
	public function showSearchedNames($strName ='')
	{
		if ($this->input->post('name', TRUE)) // gadījumā, ja meklējums tiek pieprasīts caur lietotāja saskarnes RDF/XML skata formu
		{
			$strName = $this->input->post('name', TRUE);
		}
		
		$strXML = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:ne="http://lnb.ailab.lv/ne#">';
		
		$strBasePath = 'http://'.$_SERVER['SERVER_NAME'];
		
		$arrNames = $this->rdf_xml_model->getNamesByName($strName); // izsauc modeļa funkciju, kas meklē īpašvārdus un atgriež informāciju par atrastajiem īpašvārdiem no datu bāzes 
		foreach ($arrNames as $arrName) // pievieno info par katru īpašvārdu faila saturam
		{
			$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/name/'. $arrName['ID'] .'" ne:name="'. htmlspecialchars($arrName['name']) .'"/>';
		}

		$strXML .= "</rdf:RDF>";
		
		$this->load->view('rdf_xml_view', array('strXML' => $strXML)); // RDF/XML formāta skata ielāde
	}

	
	/* 
	 * Funkcija pēc padotā identifikatora ($intID) izveido objekta (entity) informāciju:
	 * -	objekta definīcija, 
	 * -	objekta nosaukumi, 
	 * -	ontoloģiski saistīti objekti
	 * RDF/XML formā.
	*/
	public function showEntityData($intID = 0)
	{
		if ($this->input->post('id', TRUE))
		{
			$intID = $this->input->post('id', TRUE);
		}
		
		$strXML = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:ne="http://lnb.ailab.lv/ne#">';
		
		$intID = (int)$intID;
		
 		$strBasePath = 'http://'. $_SERVER['SERVER_NAME'];
 		
 		$strDefinition = $this->rdf_xml_model->getEntityDefinition($intID);
 		
		if ($strDefinition !== FALSE)
		{
			$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/entity/'. $intID .'"> <ne:definition>'. htmlspecialchars($strDefinition) .'</ne:definition>';

			$arrNames = $this->rdf_xml_model->getEntityNames($intID);
 			if (sizeof($arrNames) > 0)
 			{
 				$strXML .= '<ne:names rdf:parseType="Collection">';
				foreach ($arrNames as $arrName)
				{
					$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/name/'. $arrName['ID'] .'" ne:name="'. htmlspecialchars($arrName['name']) .'" />';
				}
	  			$strXML .= '</ne:names>';
 			}
			
			$arrRelations = $this->rdf_xml_model->getEntityRelations($intID);
			if (sizeof($arrRelations) > 0)
			{
				$strXML .= '<ne:relations rdf:parseType="Collection">';
				foreach ($arrRelations as $arrRelation)
				{
					$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/entity/'. $arrRelation['ID'] .'" ne:definition="'. htmlspecialchars($arrRelation['definition']) .'" ne:relation="'. htmlspecialchars($arrRelation['comment']) .'" />';
				}
				$strXML .= "</ne:relations>";
			}
			
			$strXML .= '</rdf:Description>';
		}
		
		$strXML .= '</rdf:RDF>';
 		
		$this->load->view('rdf_xml_view', array('strXML' => $strXML));
	}
	
	
	/* Funkcija pēc padotā identifikatora ($intID) izvada noaukuma (name) informāciju:
	 * -	nosaukuma nosaukums, 
	 * -	nosaukuma objekti,
	 * -	dokumenti, kuros nosaukums atrasts (dokumenti tiek atgriezti porcijās pa 100 dokumentiem, 
	 * ja ir vairāk dokumentu, tad tiek pievienota URI norāde uz nākamo dokumentu porciju)
	 * RDF/XML formā.
	*/
	public function showNameData($intID = 0)
	{
		if ($this->input->post('id', TRUE))
		{
			$intID = $this->input->post('id', TRUE);
		}
		
		$intID = (int)$intID;
		
		$strXML = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:ne="http://lnb.ailab.lv/ne#" xmlns:corpus="http://lnb.ailab.lv/corpus#">';
	
		if ($this->input->get('start', TRUE))
		{
			$intIDStart = (int)$this->input->get('start', TRUE);
		}
		else
		{
			$intIDStart = 1;
		}
	
		$strBasePath = 'http://'. $_SERVER['SERVER_NAME'];
	
		$strName = $this->rdf_xml_model->getNameName($intID);
	
		if ($strName !== FALSE)
		{
			$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/name/'. $intID .'"> <ne:name>'. htmlspecialchars($strName) .'</ne:name>';
	
			$arrEntities = $this->rdf_xml_model->getNameEntities($intID);
			$strXML .= '<ne:entities rdf:parseType="Collection">';
			foreach ($arrEntities as $arrEntity)
			{
				$strXML .= '<rdf:Description rdf:about="'. $strBasePath .'/namedEntityDB/entity/'. $arrEntity['ID'] .'" ne:definition="'. htmlspecialchars($arrEntity['definition']) .'" />';
			}
			$strXML .= '</ne:entities>';

			$arrDocuments = $this->rdf_xml_model->getNameDocuments($intID, $intIDStart);
			if (sizeof($arrDocuments) > 0)
			{
				$strXML .= '<ne:documents rdf:parseType="Collection">';
				foreach ($arrDocuments as $arrDocument)
				{
					$strXML .= '<rdf:Description rdf:about="http://kautkas.lnb.lv/'. $arrDocument['reference'] .'" corpus:title="'. $arrDocument['title'] .'" corpus:type="'. $arrDocument['type'] .'" corpus:dateissued="'. $arrDocument['date'] .'" corpus:occurrences="'. $arrDocument['occurrences'] .'" />';
				}
				$strXML .= '</ne:documents>';
			}
	
			$intIDStart += 100;
			$bolIsMoreDocuments = $this->rdf_xml_model->checkIsMoreDocuments($intID, $intIDStart);
			if ($bolIsMoreDocuments)
			{
				$strXML .= '<ne:nextpage>'. $strBasePath .'/namedEntityDB/name/'. $intID .'?start='. $intIDStart .'</ne:nextpage>';
			}
			$strXML .= '</rdf:Description>';
		}
	
		$strXML .= '</rdf:RDF>';
	
		$this->load->view('rdf_xml_view', array('strXML' => $strXML));
	}
}

?>