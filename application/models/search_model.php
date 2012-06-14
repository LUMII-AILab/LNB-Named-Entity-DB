<?php
/**
 * Fails: search_model.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.10
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */

/*
 * Klase Search_model
* Nolūks: modelis nosaukuma meklēšanas funkcijas izpildei
*/
class Search_model extends CI_Model
{
	/*
	 * Funkcija meklē datu bāzē nosaukumu pēc nosaukuma un kategorijas, kārtojot pēc nosacījumiem, un atgriež 50 ierakstus vienas meklēšanas rezultātu lapas parādīšanai.
	 * 
	 * Parametri:
	 * 
	 * 	$strName - meklējamais nosaukums, 
	 * 	$arrCategories - masīvs ar meklējamā nosaukuma objekta kategorijām, 
	 * 	$intPageNum - rādāmās lapas numurs,
	 *  $strOrderBy - kārtojamās kolonnas atslēgvārds,
	 * 	$strOrderMode - kārtošana režīms - augoši vai dilstoši.
	 * 
	 */
	public function getEntityNameByNameAndCategory($strName, $arrCategories, $intPageNum, $strOrderBy, $strOrderMode, $intRowCountPerPage)
	{
		$intOffset = ($intPageNum - 1) * $intRowCountPerPage; // nosaka, no kuras rindas sākot, jāatgriež rezultāta rindas
	
		$strSQL = "SELECT SQL_CALC_FOUND_ROWS n.ID, n.name, e.definition, c.name AS category, e.ID AS entityID, e.time, 
					(SELECT SUM(nd.occurrences) FROM nameDocument nd WHERE nd.nameID = n.ID) as totalOccurrences
					FROM name n, entityName en, entity e, category c WHERE ";
		
		if ($strName != '') // ja padots tukšs nosaukums, meklē visus norādīto kategoriju nosaukumus
		{
			$strSQL .= "MATCH(n.name) AGAINST(?) AND "; 
		}
		
 		$strSQL .= "n.ID = en.nameID AND en.entityID = e.ID AND c.ID = e.categoryID ";
 		
		if (sizeof($arrCategories) > 0) // ja ir noteiktas meklējāmā nosaukuma objekta kategorijas, pievieno tās sql vaicājuma
		{
			$strSQL .= "AND (";
			foreach ($arrCategories as $intCategory)
			{
				$strSQL .= "e.categoryID = ". $intCategory ." OR ";
			}
			$strSQL = trim($strSQL, "OR ");
			$strSQL .= ") ";
		}
		
		/* pieraksta vaicājumam kārtošanas nosacījums*/
		$strSQL .= "ORDER BY ";
		if ($strOrderBy == 'def')
		{
			$strSQL .= 'definition ';
		}
		elseif ($strOrderBy == 'time')
		{
			$strSQL .= 'time ';
		}
		elseif ($strOrderBy == 'category')
		{
			$strSQL .= 'category ';
		}
		elseif ($strOrderBy == 'name')
		{
			$strSQL .= 'name ';
		}
		else // pēc noklusējuma kārto pēc sastopamības
		{
			$strSQL .= 'totalOccurrences ';
		}
		
		if ($strOrderMode == 'ASC')
		{
			$strSQL .= 'ASC ';
		}
		else // pēc noklusējuma kārto dilstošā secībā
		{
			$strSQL .= 'DESC ';
		}
		
		$strSQL .= "LIMIT $intOffset, $intRowCountPerPage;"; // nosaka atgriežamo nosaukuma rindu limitu 50
			
		$arrResult['arrEntityNames'] = $this->db->query($strSQL, $strName)->result_array(); // izpilda datu bāzes vaicājumu
		
		$strSQL = "SELECT FOUND_ROWS();";
		$result = $this->db->query($strSQL)->result_array(); // izpilda datu bāzes vaicājumu		
		$intRowsCount = $result[0]['FOUND_ROWS()'];
		
		$arrResult['intRowsCount'] = $intRowsCount;
		
		return $arrResult; // atgriež datu bāzes vaicājuma rezultātu
	}	
}
?>