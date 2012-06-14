<?php
/*
 * Pārbauda, vai lietotājs ir autentificējies
 */
function checkSession($current_this)
{
	if ($current_this->session->userdata("logged_in")) // pārbauda, vai ietotājs ir autorizējis
	{
		return TRUE;
	}
	else // gadījumā, ja lietotāja sesija ir beigusies vai ja tiek veikta nesankcionēta pieeja sistēmai
	{
		header ("Location: /namedEntityDB/login/");
	}
}
?>