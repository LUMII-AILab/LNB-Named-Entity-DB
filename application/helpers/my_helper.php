<?php
/*
 * formatē "uzvards, vards":
 * izņem komatus, atstarpes,
 * samaina vietā vārdu ar uzvārdu.
 * atgriez "vards uzvards"
*/
function _f($string)
{
	$names = explode(",", $string);
	if(sizeof($names) > 1)
	{
		foreach($names as $name):
			$name = str_replace(",", "", $name);
			$name = trim($name);
		endforeach;
		if(strchr($names[1], "1") != FALSE)
		{
			$string = $names[0];
		}
		else 
		{
			$string = $names[1]." ".$names[0];
			$string = ltrim($string);
		}
	}
	return $string;
}

function _fn($string)
{
	$string = str_replace(',', '', $string);
	$string = str_replace('(', '', $string);
	$string = str_replace(')', '', $string);
	return $string;
}

function _merge_hf($heading, $fullname)
{
	$names = explode(",", $heading);
	if(sizeof($names) > 1)
	{
		foreach($names as $name):
			str_replace(",", "", $name);
			trim($name);
		endforeach;
		$heading = $fullname." ".$names[0];
	}
	return $heading;
}


function _vietv($string)
{
	$names = explode("(", $string);
	if(sizeof($names) > 1)
	{
		if(strchr($names[1], "Latvija") == FALSE && 
				strchr($names[1], "Rīga") == FALSE && 
				strchr($names[1], "Krievija") == FALSE &&
				strchr($names[1], "Vācija") == FALSE &&
				strchr($names[1], "novads") == FALSE &&
				strchr($names[1], "rajons") == FALSE &&
				strchr($names[1], "Amerikas") == FALSE &&
				strchr($names[1], "Igaunija") == FALSE &&
				strchr($names[1], "Lietuva") == FALSE &&
				strchr($names[1], "Spānija") == FALSE &&
				strchr($names[1], "Polija") == FALSE &&
				strchr($names[1], "Lielbritānija") == FALSE &&
				strchr($names[1], "Zviedrija") == FALSE &&
				strchr($names[1], "Itālija") == FALSE &&
				strchr($names[1], "Kanāda") == FALSE &&
				strchr($names[1], "Somija") == FALSE &&
				strchr($names[1], "Nīderlande") == FALSE &&
				strchr($names[1], "Dānija") == FALSE &&
				strchr($names[1], "Portugāle") == FALSE &&
				strchr($names[1], "Dānija") == FALSE &&
				strchr($names[1], "Izraēla") == FALSE &&
				strchr($names[1], "Singapūra") == FALSE &&
				strchr($names[1], "Serbija") == FALSE &&
				strchr($names[1], "Īrija") == FALSE &&
				strchr($names[1], "Šveice") == FALSE &&
				strchr($names[1], "Ķīna") == FALSE &&
				strchr($names[1], "Indija") == FALSE &&
				strchr($names[1], "Turcija") == FALSE &&
				strchr($names[1], "Austrālija") == FALSE &&
				strchr($names[1], "Austrija") == FALSE &&
				strchr($names[1], "Francija") == FALSE &&
				strchr($names[1], "Padomju") == FALSE &&
				strchr($names[1], "Peru") == FALSE &&
				strchr($names[1], "Ukraina") == FALSE &&
				strchr($names[1], "Čehija") == FALSE &&
				strchr($names[1], "Bulgārija") == FALSE &&
				strchr($names[1], "Grieķija") == FALSE &&
				strchr($names[1], "Čīle") == FALSE &&
				strchr($names[1], "Rumānija") == FALSE &&
				strchr($names[1], "Slovākija") == FALSE &&
				strchr($names[1], "Belģija") == FALSE &&
				strchr($names[1], "Beļģija") == FALSE &&
				strchr($names[1], "Beļgija") == FALSE &&
				strchr($names[1], "Polinēzija") == FALSE &&
				strchr($names[1], "Mjanma") == FALSE &&
				strchr($names[1], "Ungārija") == FALSE &&
				strchr($names[1], "Norvēģija") == FALSE &&
				strchr($names[1], "Horvātija") == FALSE &&
				strchr($names[1], "Slovēnija") == FALSE &&
				strchr($names[1], "Japāna") == FALSE &&
				strchr($names[1], "Afganistāna") == FALSE &&
				strchr($names[1], "Ēģipte") == FALSE &&
				strchr($names[1], "Kipra") == FALSE &&
				strchr($names[1], "Kazahstāna") == FALSE &&
				strchr($names[1], "Malaizija") == FALSE &&
				strchr($names[1], "Urugvaja") == FALSE &&
				strchr($names[1], "Pakistāna") == FALSE &&
				strchr($names[1], "Čehoslovākija") == FALSE &&
				strchr($names[1], "Kolumbija") == FALSE &&
				strchr($names[1], "Nepāla") == FALSE &&
				strchr($names[1], "Argentīna") == FALSE &&
				strchr($names[1], "Tadžikistāna") == FALSE &&
				strchr($names[1], "Meksika") == FALSE &&
				strchr($names[1], "Tanzānija") == FALSE &&
				strchr($names[1], "Baltkrievija") == FALSE &&
				strchr($names[1], "Russia") == FALSE &&
				strchr($names[1], "Indonēzija") == FALSE &&
				strchr($names[1], "Arābu") == FALSE &&
				strchr($names[1], "Tunisija") == FALSE &&
				strchr($names[1], "Armēnija") == FALSE &&
				strchr($names[1], "France") == FALSE &&
				strchr($names[1], "Gruzija") == FALSE &&
				strchr($names[1], "Canada") == FALSE &&
				strchr($names[1], "Indonēzija") == FALSE &&
				strchr($names[1], "Dienvidāfrikas") == FALSE &&
				strchr($names[1], "Trinidāda") == FALSE &&
				strchr($names[1], "Azerbaidžāna") == FALSE &&
				strchr($names[1], "Brazīlija") == FALSE &&
				strchr($names[1], "Anglija") == FALSE &&
				strchr($names[1], "Maroka") == FALSE &&
				strchr($names[1], "Āfrika") == FALSE &&
				strchr($names[1], "Lihtenšteina") == FALSE &&
				strchr($names[1], "Dienvidkoreja") == FALSE &&
				strchr($names[1], "Bauskas") == FALSE &&
				strchr($names[1], "Serbia") == FALSE &&
				strchr($names[1], "Skotija") == FALSE &&
				strchr($names[1], "England") == FALSE &&
				strchr($names[1], "China") == FALSE &&
				strchr($names[1], "Turkmenistāna") == FALSE &&
				strchr($names[1], "Germany") == FALSE &&
				strchr($names[1], "Uzbekistāna") == FALSE &&
				strchr($names[1], "Kanāriju") == FALSE &&
				strchr($names[1], "Irāka") == FALSE &&
				strchr($names[1], "Ķina") == FALSE &&strchr($names[1], "Mongolija") == FALSE &&
				strchr($names[1], "Īslande") == FALSE &&
				strchr($names[1], "Rukatunturi") == FALSE &&
				strchr($names[1], "Andora") == FALSE &&
				strchr($names[1], "Ekvadora") == FALSE &&
				strchr($names[1], "Kenija") == FALSE &&
				strchr($names[1], "Bosnija") == FALSE &&
				strchr($names[1], "Melnkalne") == FALSE &&
				strchr($names[1], "Taizeme") == FALSE &&
				strchr($names[1], "Greece") == FALSE &&
				strchr($names[1], "Irāna") == FALSE &&
				strchr($names[1], "Maldīvija") == FALSE &&
				strchr($names[1], "1") == FALSE &&
				strchr($names[1], "Okla") == FALSE &&
				strchr($names[1], "2") == FALSE &&
				strchr($names[1], "Pinang") == FALSE &&
				strchr($names[1], "Madeiras") == FALSE &&
				strchr($names[1], "Uzbekistan") == FALSE &&
				strchr($names[1], "Spain") == FALSE &&
				strchr($names[1], "Jeruzaleme") == FALSE &&
				strchr($names[1], "Alžīrija") == FALSE &&
				strchr($names[1], "Jaungvineja") == FALSE &&
				strchr($names[1], "Maldīvija") == FALSE &&
				strchr($names[1], "Koreja") == FALSE &&
				strchr($names[1], "Valkas") == FALSE &&
				strchr($names[1], "Hercegovina") == FALSE &&
				strchr($names[1], "Italia") == FALSE &&
				strchr($names[1], "Filipīnas") == FALSE &&
				strchr($names[1], "Moldova") == FALSE &&
				strchr($names[1], "Kirgizstāna") == FALSE &&
				strchr($names[1], "Kirgīzija") == FALSE &&
				strchr($names[1], "Brazavila") == FALSE &&
				strchr($names[1], "Venecuēla") == FALSE &&
				strchr($names[1], "Valmieras") == FALSE &&
				strchr($names[1], "Senegāla") == FALSE &&
				strchr($names[1], "Svazilenda") == FALSE &&
				strchr($names[1], "Malāvija") == FALSE &&
				strchr($names[1], "Mali") == FALSE &&
				strchr($names[1], "Jaunzēlande") == FALSE &&
				strchr($names[1], "Albānija") == FALSE &&
				strchr($names[1], "Armenia") == FALSE &&
				strchr($names[1], "Latvia") == FALSE &&
				strchr($names[1], "Turkey") == FALSE &&
				strchr($names[1], "Latvia") == FALSE &&
				strchr($names[1], "Taivāna") == FALSE &&
				strchr($names[1], "Surinama") == FALSE &&
				strchr($names[1], "Gvineja") == FALSE &&
				strchr($names[1], "Gajāna") == FALSE &&
				strchr($names[1], "Gviāna") == FALSE &&
				strchr($names[1], "Benina") == FALSE &&
				strchr($names[1], "Gabona") == FALSE &&
				strchr($names[1], "Lesoto") == FALSE &&
				strchr($names[1], "Eritreja") == FALSE &&
				strchr($names[1], "Kamerūna") == FALSE &&
				strchr($names[1], "Kongo") == FALSE &&
				strchr($names[1], "Piena") == FALSE &&
				strchr($names[1], "Guinea") == FALSE)
		{
// 			echo '! '.$names[0].'|'.$names[1].'<br />';
		}
		if(strchr($names[1], "1") != FALSE && strchr($names[1], "2") != FALSE)
		{
			unset($names[1]);
		}
		elseif (strchr($names[1], "South") != FALSE
				|| strchr($names[1], "Austrumu") != FALSE
				|| strchr($names[1], "East") != FALSE
				|| strchr($names[1], "Rietumu") != FALSE
				|| strchr($names[1], "West") != FALSE
				|| strchr($names[1], "paleokontinents") != FALSE
				|| strchr($names[1], "superkontinents") != FALSE
				|| strchr($names[1], "Supercontinent") != FALSE)
		{
			$names[0] = $names[1].' '.$names[0];
			unset($names[1]);
		}
		elseif (strchr($names[1], "D.C.") != FALSE
				|| strchr($names[1], "Kingdom") != FALSE
				|| strchr($names[1], "Duchy") != FALSE
				|| strchr($names[1], "Demokrātiska") != FALSE
				|| strchr($names[1], "Democratic") != FALSE
				|| strchr($names[1], "Leopoldvila") != FALSE
				|| strchr($names[1], "Raiskuma") != FALSE)
		{
			$names[0] = $names[0].' '.$names[1];
			unset($names[1]);
		}
		elseif (strchr($names[1], "Extinct") != FALSE
				|| strchr($names[1], "Ehtinct") != FALSE
				|| strchr($names[1], "izzudusi") != FALSE 
				|| strchr($names[1], "federācija") != FALSE
				|| strchr($names[1], "Federation") != FALSE
				|| strchr($names[1], "Republic") != FALSE
				|| strchr($names[1], "Republika") != FALSE
				|| strchr($names[1], "hercogiste") != FALSE
				|| strchr($names[1], "planēta") != FALSE
				|| strchr($names[1], "Planet") != FALSE
				|| strchr($names[1], "Republics") != FALSE
				|| $names[1] == ''
				|| strchr($names[1], "astronomija") != FALSE
				|| strchr($names[1], "Astronomy") != FALSE
				|| strchr($names[1], "LAtvija") != FALSE)
		{
			unset($names[1]);
		}
		elseif (strchr($names[1], "Novēģija") != FALSE)
		{
			$names[1] = 'Norvēģija';
		}
		else 
		{
			$names2 = explode(",", $names[1]);
			if(sizeof($names2) > 1)
			{
				$names[1] = $names2[0];
				if(strchr($names2[1], "1") == FALSE || strchr($names2[1], "2") == FALSE)
				{
					$names[2] = $names2[1];
				}
			}
			$names2 = explode(":", $names[1]);
			if(sizeof($names2) > 1)
			{
				$names[1] = $names2[0];
// 				if(strchr($names2[1], "1") == FALSE || strchr($names2[1], "2") == FALSE)
// 				{
// 					$names[2] = $names2[1];
// 				}
// 				echo '!! '.$names[1].' : '.$names[2].'<br />';
			}
		}
		
		return $names;
	}
		return FALSE;
}

function _vietv2($string)
{
	$names = explode(" un ", $string);
	if(sizeof($names) > 1)
	{
		echo '!UN!'. $names[0] . ' un '. $names[1] .'<br />';
		$names[0] = trim($names[0]);
		$names[1] = trim($names[1]);
		
		if($names[0] == 'Bosnija' || $names[0] == 'Trinidāda')
		{
			echo '-!UN!'. $names[0] . ' un '. $names[1] .'<br />';
			return FALSE;
		}
		
		return $names;
	}
	else 
	{
		return FALSE;
	}
}