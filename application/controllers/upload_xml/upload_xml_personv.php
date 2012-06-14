<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
						if ($v == '100')
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
							if (sizeof($arrResult) > 0)
							{
								$bolIsObject = TRUE;
							}
		
							if (isset($arrResult['a']))
							{
								$arrVariants1[] = $arrResult['a'];
								$strDefinition = $arrResult['a'];
							}
							if (isset($arrResult['q']))
							{
								$arrVariants1[] = $arrResult['q'];
								$strDefinition = $arrResult['q'];
							}
							if (isset($arrResult['d']))
							{
								$strTime = $arrResult['d'];
							}
							if (isset($arrResult['b']))
							{
								$arrVariants1[] = $arrResult['b'];
								$strDefinition = $arrResult['b'];
							}
							if (isset($arrResult['ab']))
							{
								$arrVariants1[] = $arrResult['ab'];
								$strDefinition = $arrResult['ab'];
							}
							if (isset($arrResult['qb']))
							{
								$arrVariants1[] = $arrResult['qb'];
								$strDefinition = $arrResult['qb'];
							}
							if (isset($arrResult['ac']))
							{
								foreach ($arrResult['ac'] as $strResult)
								{
									$arrVariants1[] = $strResult;
									$strDefinition = $strResult;
								}
							}
							if (isset($arrResult['abc']))
							{
								foreach ($arrResult['abc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
									$strDefinition = $strResult;
								}
							}
							if (isset($arrResult['qc']))
							{
								foreach ($arrResult['qc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
									$strDefinition = $strResult;
								}
							}
							if (isset($arrResult['qbc']))
							{
								foreach ($arrResult['qbc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
									$strDefinition = $strResult;
								}
							}
							if (isset($arrResult['bc']))
							{
								foreach ($arrResult['bc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
									$strDefinition = $strResult;
								}
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
		
							if (isset($arrResult['a']))
							{
								$arrVariants1[] = $arrResult['a'];
							}
							if (isset($arrResult['q']))
							{
								$arrVariants1[] = $arrResult['q'];
							}
							if (isset($arrResult['b']))
							{
								$arrVariants1[] = $arrResult['b'];
							}
							if (isset($arrResult['ab']))
							{
								$arrVariants1[] = $arrResult['ab'];
							}
							if (isset($arrResult['qb']))
							{
								$arrVariants1[] = $arrResult['qb'];
							}
							if (isset($arrResult['ac']))
							{
								foreach ($arrResult['ac'] as $strResult)
								{
									$arrVariants1[] = $strResult;
								}
							}
							if (isset($arrResult['abc']))
							{
								foreach ($arrResult['abc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
								}
							}
							if (isset($arrResult['qc']))
							{
								foreach ($arrResult['qc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
								}
							}
							if (isset($arrResult['qbc']))
							{
								foreach ($arrResult['qbc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
								}
							}
							if (isset($arrResult['bc']))
							{
								foreach ($arrResult['bc'] as $strResult)
								{
									$arrVariants1[] = $strResult;
								}
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
		
							if (isset($arrResult['a']))
							{
								$arrVariants2[] = $arrResult['a'];
							}
							if (isset($arrResult['q']))
							{
								$arrVariants2[] = $arrResult['q'];
							}
							if (isset($arrResult['b']))
							{
								$arrVariants2[] = $arrResult['b'];
							}
							if (isset($arrResult['ab']))
							{
								$arrVariants2[] = $arrResult['ab'];
							}
							if (isset($arrResult['qb']))
							{
								$arrVariants2[] = $arrResult['qb'];
							}
							if (isset($arrResult['ac']))
							{
								foreach ($arrResult['ac'] as $strResult)
								{
									$arrVariants2[] = $strResult;
								}
							}
							if (isset($arrResult['abc']))
							{
								foreach ($arrResult['abc'] as $strResult)
								{
									$arrVariants2[] = $strResult;
								}
							}
							if (isset($arrResult['qc']))
							{
								foreach ($arrResult['qc'] as $strResult)
								{
									$arrVariants2[] = $strResult;
								}
							}
							if (isset($arrResult['qbc']))
							{
								foreach ($arrResult['qbc'] as $strResult)
								{
									$arrVariants2[] = $strResult;
								}
							}
							if (isset($arrResult['bc']))
							{
								foreach ($arrResult['bc'] as $strResult)
								{
									$arrVariants2[] = $strResult;
								}
							}
						}
					}
				}
		
				if ($bolIsObject)
				{
					$intObjectID = $this->upload_xml_model->insertObject($strDefinition, $strTime, 1);
					
					foreach($arrVariants1 as $strName)
					{
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
					}
		
					foreach($arrVariants2 as $strName)
					{
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
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