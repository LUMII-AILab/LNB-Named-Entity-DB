<?php 
/**
 * Fails: authorize.php
 * Autors: Madara Paegle
 * Radīts: 2012.05.15.
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Klase Authorize
* Nolūks: lietotāja autorizēšana sistēmā
*/
class Authorize extends CI_Controller
{
	/*
	 * Klases konstruktors.
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	/*
	 * Funkcija izsauc ielogošanās formu un pārbauda, vai saņemtie lietotājvārda un paroles dati no lietotāja sakrīt ar vajadzīgo.
	 * Ja ir pareizi, uzstāda lietotāja sesijas datus, kuri tiek pāarbaudīti pie funkcijām, kas nodrošina datu rediģēšanu.
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
	 * Funkcija veic lietotāja ilogošanos no sistēmas - atstata lietotāja sesijas datus.
	*/
	public function logout()
	{
		$this->session->set_userdata("logged_in", FALSE);
		$this->session->unset_userdata("loged_in");
		
		$this->load->view('login_view');
		
	}
}
?>