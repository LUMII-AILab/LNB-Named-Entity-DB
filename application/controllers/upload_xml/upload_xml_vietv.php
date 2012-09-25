<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_xml_vietv extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_xml_model');
	}

	public function index()
	{
		echo '<pre>';
		$i = 0;
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
				$arrVariants = array();
				$arrOntologies = array();
				foreach ($record->datafield as $datafield)
				{
					foreach ($datafield->attributes() as $v)
					{
						if ($v == '151')
						{
							$bolNotAlt = FALSE;
							
							$arrFields = array();
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a')
									{
										$arrFields['a'] = $subfield;
									}
									elseif ($v1 == 'x' || $v1 == 'y' || $v1 == 'v' || $v1 == 'z')
									{
										$bolNotAlt = TRUE;
									}
								}
							}
		
							if (!$bolNotAlt) 
							{
								$arrResult = $this->mergeFields($arrFields);
														
								if (sizeof($arrResult) > 0)
								{
									++$i;
									$bolIsObject = TRUE;
								
									if (isset($arrResult['a']))
									{
										$arrVariants[] = $arrResult['a'];
										$strDefinition = $arrResult['a'];
									}
									if (isset($arrResult['aOnt']))
									{
										$arrOntologies[] = $arrResult['aOnt'];
									}
								}
							}
						}
						elseif ($v == '451' && $bolIsObject)
						{
							$bolNotAlt = FALSE;
							$arrFields = array();
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a' || $v1 == 'D')
									{
										$arrFields['a'] = $subfield;
									}
									elseif ($v1 == 'x' || $v1 == 'y' || $v1 == 'v' || $v1 == 'z')
									{
										$bolNotAlt = TRUE;
									}
								}
							}
		
							if (!$bolNotAlt)
							{
								$arrResult = $this->mergeFields($arrFields);
								
								if (isset($arrResult['a']))
								{
									$arrVariants[] = $arrResult['a'];
								}
								if (isset($arrResult['aOnt']))
								{
									$arrOntologies[] = $arrResult['aOnt'];
								}
							}
						}
						elseif ($v == '551' && $bolIsObject)
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
									elseif ($v1 == 'w')
									{
										$arrFields['w'] = $subfield;
									}
								}
							}
		
							$arrResult = $this->mergeFields($arrFields);
							
							if (isset($arrResult['a']))
							{
								$arrInfo = array();
								$arrInfo['name'] = $arrResult['a'];
								$arrInfo['comment'] = '';
								if (isset($arrResult['w']))
								{
									$arrInfo['comment'] = $arrResult['w'];
								}
								$arrOntologies[] = array($arrInfo);
							}
							
						}
					}
				}
		
				if ($bolIsObject)
				{
// 					var_dump($arrVariants);
// 					var_dump($arrOntologies);
// 					echo '<br>-----------------------------------<br>';
					
					$intObjectID = $this->upload_xml_model->insertObject($strDefinition, '', 4);
					
					foreach($arrVariants as $strName)
					{
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
					}
					
					foreach ($arrOntologies as $arrOntologies1)
					{
// 						var_dump($arrOntologies1);
						foreach ($arrOntologies1 as $arrOntology)
						{
// 							echo $arrOntology['name'].'<br>'.$arrOntology['comment'].'<br>';
							$this->upload_xml_model->addObjectToObject($intObjectID, $arrOntology['name'], $arrOntology['comment']);
						}
					}
					
				}
				
				
// 				if ($i == 100) break;
				
				$reader->next('record');
			}
		
			$reader->close();
			
			echo $i.'<br>';
			echo "FINISH:<br>$intStartTime - ".time();
		}
	}
	
	
	public function mergeFields($arrFields)
	{
		$arrResult = array();
		if (isset($arrFields['a']))
		{
			// no iekavām izņem saitīto objektu
			$arrParts = explode("(", $arrFields['a']);
			if (sizeof($arrParts) > 1)
			{
				// vai nav komats un jāmaina vietām (Volga, upe)
				$arrParts2 = explode(",", $arrParts[0]);
				if (sizeof($arrParts2) > 1)
				{
					$arrResult['a'] = trim($arrParts2[1]).' '.trim($arrParts2[0]);
				}
				else
				{
					$arrResult['a'] = trim($arrParts[0]);
				}
				
				// vai nav ar komatu atdalīti vairāki saistītie objekti (Latvija, Liepāja)
				$arrParts2 = explode(",", $arrParts[1]);
				if (sizeof($arrParts2) > 1)
				{
					$arrResult['aOnt'][] = array('name' => trim($arrParts2[0]), 'comment' => '');
					$arrResult['aOnt'][] = array('name' => trim($arrParts2[1], ") "), 'comment' => '');
				}
				else
				{
					// vai ar : nav atdalīts objektu ontoloģijas komentārs (Amerikas Savienotās Valstis : štats)
					$arrParts2 = explode(":", $arrParts[1]);
					if (sizeof($arrParts2) > 1)
					{
						$arrResult['aOnt'][] = array('name' => trim($arrParts2[0]), 'comment' => trim($arrParts2[1], ") "));
					}
					else 
					{
						$arrResult['aOnt'][] = array('name' => trim($arrParts[1], ") "), 'comment' => '');
					}
				}
			}
			else
			{
				// vai nav komats un jāmaina vietām (Volga, upe)
				$arrParts = explode(",", $arrFields['a']);
				if (sizeof($arrParts) > 1)
				{
					$arrResult['a'] = trim($arrParts[1]).' '.trim($arrParts[0]);
				}
				else
				{
					$arrResult['a'] = trim($arrFields['a']);
				}
			}
			
			
			// šis ir tikai 551
			if (isset($arrFields['w']))
			{
				//ontoloģiskās saites komentārs
				if ($arrFields['w'] == 'g')
				{
					$arrResult['w'] = '"'.$arrResult['a'].'" ir plašāks jēdziens.';
				}
				elseif ($arrFields['w'] == 'h')
				{
					$arrResult['w'] = '"'.$arrResult['a'].'" ir šaurāks jēdziens.';
				}
				elseif ($arrFields['w'] == 'a')
				{
					$arrResult['w'] = 'Nosaukuma maiņa laika gaitā. "'. $arrResult['a'] .'" ir senāks nosaukums.';
				}
				elseif ($arrFields['w'] == 'b')
				{
					$arrResult['w'] = 'Nosaukuma maiņa laika gaitā. "'. $arrResult['a'] .'" ir vēlāks nosaukums.';
				}
			}
		}
				
		return $arrResult;
	}
}