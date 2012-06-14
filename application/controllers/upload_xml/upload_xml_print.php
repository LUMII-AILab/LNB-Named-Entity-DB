<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_xml_print extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_xml_model');
	}

	public function index()
	{
		$intStartTime = time();
		
		$file_name = 'marc21.xml';
		if (file_exists('uploads/'.$file_name))
		{
			$reader = new XMLReader();
			$reader->open('uploads/'.$file_name);
			$doc = new DOMDocument;
		
			while ($reader->read() && $reader->name !== 'record');
		
			while($reader->name === 'record')
			{
				$record = simplexml_import_dom($doc->importNode($reader->expand(), true));
		
				$bolIsObject = FALSE;
				$strDefinition = '';
				$strTime = '';
				$arrVariants1 = array();
				$arrVariants2 = array();
				foreach ($record->datafield as $datafield)
				{
					foreach ($datafield->attributes() as $v)
					{
						if ($v == '111')
						{
// 							echo '-------------------<br><br>';
// 							echo '<b>111</b><br>';
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $k => $v1)
								{
									if ($v1 != 'a' && $v1 != 'd' && $v1 != 'c' && $v1 != 'n')
									{
										echo $v1.' => '.$subfield.'<br>';
									}
								}
							}
							$bolIsObject = TRUE;
						}
						if ($v == '411')
						{
// 							echo '<b>411</b><br>';
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $k => $v1)
								{
									if ($v1 != 'a' && $v1 != 'd' && $v1 != 'c' && $v1 != 'n')
									{
										echo $v1.' => '.$subfield.'<br>';
									}
								}
							}
						}
						elseif ($v == '511' && $bolIsObject)
						{
// 							echo '<b>510</b><br>';
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $k => $v1)
								{
									if ($v1 != 'a' && $v1 != 'd' && $v1 != 'c' && $v1 != 'n')
									{
										echo $v1.' => '.$subfield.'<br>';
									}
								}
							}
						}
					}
				}
		
				$reader->next('record');
			}
		
			$reader->close();
			
			echo "FINISH:<br>$intStartTime - ".time();
		}
	}
	
	
	public function mergeFields($arrFields)
	{
		// arrResult['d'] definīciju izmanto tikai galneajam nosaukumam ar indexu *00
		
		$arrResult = array();
		if (isset($arrFields['a']))
		{
			// samaina vārdu un uzvārdu vietām, noņem komatu
			$arrParts = explode(",", $arrFields['a']);
			if (sizeof($arrParts) > 1 && $arrParts[1] != '')
			{
				$arrResult['a'] = trim($arrParts[1])." ".$arrParts[0];
			}
			else
			{
				$arrResult['a'] = trim($arrFields['a'], ", ");
			}
			
	
			if (isset($arrFields['q']))
			{
				// "a" vārdu un uzvārdu samaina vietām; aizvieto "a" saīsināto vārdu ar "q" pilno vārdu, noņemot iekavas
				$arrParts = explode(",", $arrFields['a']);
				if (sizeof($arrParts) > 1 && $arrParts[1] != '')
				{
					$arrResult['q'] = trim($arrFields['q'], '(), ')." ".$arrParts[0];
				}
			}
			
			if (isset($arrFields['d']))
			{
				// gadskaitļi definīcijai 
				$arrResult['d'] = $arrFields['d'];
			}
			
			if (isset($arrFields['b']))
			{
				if (substr($arrFields['b'], 0, 1) == '(')
				{
					// tāpat kā "q"
					$arrParts = explode(",", $arrFields['a']);
					if (sizeof($arrParts) > 1 && $arrParts[1] != '')
					{
						$arrResult['b'] = trim($arrFields['b'], '(), ')." ".$arrParts[0];
					}
				}
				elseif (substr($arrFields['b'], 0, 2) == '17' || substr($arrFields['b'], 0, 2) == '18' || substr($arrFields['b'], 0, 2) == '19')
				{
					// ja ir gadskaitlis neko nedara; definīciaji nepievieno, jo "b" pie *00 lauka nav kā gadskaitlis
				}
				else
				{
					// pievieno "a" un "q" ("b" nevar būt, jo ir šis variants) noformētajiem vārdiem beigās romiešu kārtas skaitli
					$arrResult['ab'] = $arrResult['a'].' '.trim($arrFields['b'], ', ');
					if (isset($arrResult['q']))
					{
						$arrResult['qb'] = $arrResult['q'].' '.trim($arrFields['b'], ', ');
					}
				}
			}
			
			if (isset($arrFields['c']) && !strstr($arrFields['c'], "von"))
			{
				// ja ir von, tad nekas labs nesanāks
				
				// uzruna, grāds priekšā liekams
				// pievieno "a", "ab", "q", "qb" un "b"
				$strUzruna = trim($arrFields['c'], ', ');
				
				// "Sir, Saint"variants ; " tēvs, franciskānis kapucīns,"
				if (strpos($strUzruna, ','))
				{
					$arrUzrunas = explode(",", $strUzruna);
					$arrUzrunas[1] = trim($arrUzrunas[1], ', ');
				}
				else 
				{
					$arrUzrunas[] = $strUzruna; 
				}
				
				foreach ($arrUzrunas as $strUzruna)
				{
					$arrResult['ac'][] = $strUzruna.' '.$arrResult['a'];
					
					if (isset($arrResult['ab']))
					{
						$arrResult['abc'][] = $strUzruna.' '.$arrResult['ab'];
					}
					
					if (isset($arrResult['q']))
					{
						$arrResult['qc'][] = $strUzruna.' '.$arrResult['q'];
					}
					
					if (isset($arrResult['qb']))
					{
						$arrResult['qbc'][] = $strUzruna.' '.$arrResult['qb'];
					}
					
					if (isset($arrResult['b']))
					{
						$arrResult['bc'][] = $strUzruna.' '.$arrResult['b'];
					}
				}
			}
		}
		
		return $arrResult;
	}
}