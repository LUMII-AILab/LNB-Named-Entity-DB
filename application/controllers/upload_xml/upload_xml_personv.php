<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_xml_personv extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_xml_model');
	}

	public function index()
	{
		$intStartTime = time();
		
		$file_name = 'marc21_2.xml';
		if (file_exists('uploads/'.$file_name))
		{
			$reader = new XMLReader();
			$reader->open('uploads/'.$file_name);
			$doc = new DOMDocument;
		
			
			while ($reader->read() && $reader->name !== 'record');
		
			while ($reader->name === 'record')
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
						if ($v == '100')
						{
							$arrFields = array();
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a') // Personal name
									{
										$arrFields['a'] = $subfield;
									}
									elseif ($v1 == 'b') // Numeration
									{
										$arrFields['b'] = $subfield;
									}
									elseif ($v1 == 'c') // Titles and other words associated with a name
									{
										$arrFields['c'] = $subfield;
									}
									elseif ($v1 == 'd') // Dates associated with a name
									{
										$arrFields['d'] = $subfield;
									}
									elseif ($v1 == 'q') // Fuller form of name
									{
										$arrFields['q'] = $subfield;
									}
								}
							}
		
							$arrResult = $this->mergeFields($arrFields);
							if (sizeof($arrResult) > 0)
							{
								$bolIsObject = TRUE;
							}
							
							foreach ($arrResult['names'] as $strName)
							{
								$arrVariants1[] = $strName;
								$strDefinition = $strName;
							}
		
							if (isset($arrResult['date']))
							{
								$strTime = $arrResult['date'];
							}
						}
						elseif ($v == '400')
						{
							$arrFields = array();
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a')
									{
										$arrFields['a'] = $subfield;
									}
									elseif ($v1 == 'q')
									{
										$arrFields['q'] = $subfield;
									}
									elseif ($v1 == 'd')
									{
										$arrFields['d'] = $subfield;
									}
									elseif ($v1 == 'c')
									{
										$arrFields['c'] = $subfield;
									}
									elseif ($v1 == 'b')
									{
										$arrFields['b'] = $subfield;
									}
								}
							}
		
							$arrResult = $this->mergeFields($arrFields);
		
							foreach ($arrResult['names'] as $strName)
							{
								$arrVariants1[] = $strName;
							}
						}
						elseif ($v == '500')
						{
							$arrFields = array();
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a')
									{
										$arrFields['a'] = $subfield;
									}
									elseif ($v1 == 'q')
									{
										$arrFields['q'] = $subfield;
									}
									elseif ($v1 == 'd')
									{
										$arrFields['d'] = $subfield;
									}
									elseif ($v1 == 'c')
									{
										$arrFields['c'] = $subfield;
									}
									elseif ($v1 == 'b')
									{
										$arrFields['b'] = $subfield;
									}
								}
							}
		
							$arrResult = $this->mergeFields($arrFields);
									
							foreach ($arrResult['names'] as $strName)
							{
								$arrVariants2[] = $strName;
							}
						}
					}
				}
		
				if ($bolIsObject)
				{
// 					echo '<b>'.$strDefinition.'</b><br>';
					$intObjectID = $this->upload_xml_model->insertObject($strDefinition, $strTime, 1);
// 					echo 'OBJEKTS:<br>';
					
					foreach($arrVariants1 as $strName)
					{
// 						echo $strName.'<br>';
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
					}
		
// 					echo "<br>";
					foreach($arrVariants2 as $strName)
					{
// 						echo $strName.'<br>';
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
					}
// 					echo '----------<br>';
// 					die();
				}
		
				$reader->next('record');
			}
		
			$reader->close();
			
			echo "FINISH:<br>$intStartTime - ".time();
		}
	}
	
	
	public function mergeFields($arrFields)
	{
		/*
		 * 'a' // Personal name
		 * 'b' // Numeration
		 * 'c' // Titles and other words associated with a name
		 * 'd' // Dates associated with a name
		 * 'q' // Fuller form of name
		 */
		
		// arrResult['d'] definīciju izmanto tikai galvenajam nosaukumam ar indexu *00
				
		$strUzruna = '';
		if (isset($arrFields['c']) && !strstr($arrFields['c'], "von"))
		{
			$strUzruna = trim($arrFields['c'], ', ');
		}
		
		$strVardsUzvards = '';
		$strVardsUzvards_q = '';
		$strVardsUzvards_b = '';
		if (isset($arrFields['a']))
		{	
			// samaina vārdu un uzvārdu vietām, noņem komatu
			$arrParts = explode(",", $arrFields['a']);
			if (sizeof($arrParts) > 1 && $arrParts[1] != '')
			{
				$strVardsUzvards = trim($arrParts[1], ', ')." ".$arrParts[0];
				
				if (isset($arrFields['q']))
				{
					// "a" saīsināto vārdu aizvieto ar "q" pilno vārdu, noņemot iekavas
					$strVardsUzvards_q = trim($arrFields['q'], '(), ')." ".$arrParts[0];
				}
				
				if (isset($arrFields['b']) && substr($arrFields['b'], 0, 1) == '(')
				{
					// tāpat kā "q"
					$strVardsUzvards_b = trim($arrFields['b'], '(), ')." ".$arrParts[0];
				}
			}
			else
			{
				$strVardsUzvards = trim($arrFields['a'], ", ");
			}
		}
		
		$strGadi = '';
		if (isset($arrFields['d']))
		{
			// gadskaitļi definīcijai
			$strGadi = $arrFields['d'];
		}
		
		
		$strNumurs = '';
		if (isset($arrFields['b']) && substr($arrFields['b'], 0, 1) != '(' && substr($arrFields['b'], 0, 2) != '17' && substr($arrFields['b'], 0, 2) != '18' && substr($arrFields['b'], 0, 2) != '19')
		{
			$strNumurs = trim($arrFields['b'], ', ');
		}
		
		$arrResult = array();
		$arrResult['names'][] = trim($strUzruna .' '. $strVardsUzvards . ' ' . $strNumurs);
		if ($strVardsUzvards_b != '') $arrResult['names'][] = trim($strUzruna .' '. $strVardsUzvards_b . ' ' . $strNumurs);
		if ($strVardsUzvards_q != '') $arrResult['names'][] = trim($strUzruna .' '. $strVardsUzvards_q . ' ' . $strNumurs);
		$arrResult['date'] = $strGadi;
		
		return $arrResult;
	}
}