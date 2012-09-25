<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * KLASE
 * Nosaukums: Authorize
 * Funkcija: lietotāja autorizēšana sistēmā (Lietotājvārds: user, Parole: p)
*/
class Authorize extends CI_Controller
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
		$this->load->library('session');
	}
	
	/*
	 * FUNKCIJA
	 * 
	 * Nosaukums: login
	 * Funkcija: izsauc autorizācijas formu un pārbauda, vai saņemtie lietotājvārda un paroles dati no lietotāja sakrīt ar vajadzīgo.
	 * 			Ja ir pareizi, uzstāda lietotāja sesijas datus, kuri tiek pārbaudīti pie funkcijām, kas nodrošina datu rediģēšanu.
	 * Parametri:
	 * 			(POST)
	 * 				user - lietotājvārds
	 * 				passw - parole
	*/
	public function login()
	{
		$arrOutputData = array();
		
		// pārbauda, vai ir izsaukums no formas
		if (($strUser = $this->input->post('user', TRUE)) && ($strPassw = $this->input->post('passw', TRUE)))
		{
			// pārbauda, vai ievadīts pareizs lietotājvārds un parole
			if ($strUser == 'user' && $strPassw == 'p')
			{				
				$this->session->set_userdata("logged_in", TRUE);
				redirect('../namedEntityDB/browse');
				return;
			}
		}
		$arrOutputData['strUser'] = $this->input->post('user', TRUE);
		$this->load->view('login_view', $arrOutputData);
	}
	
	/* 
	 * FUNKCIJA
	 * 
	 * Nosaukums: logout
	 * Funkcija: veic lietotāja izlogošanos no sistēmas - atstata lietotāja sesijas datus. 
	 * Parametri:
	*/
	public function logout()
	{
		$this->session->set_userdata("logged_in", FALSE);
		$this->session->unset_userdata("loged_in");
		
		$this->load->view('login_view');	
	}
}
?>