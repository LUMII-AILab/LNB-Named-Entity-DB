<?php 
/**
 * Fails: upload_xml_ielas.php
 * Autors: Madara Paegle
 * Radīts: 2012.02.20
 * Pēdējās izmaiņas: 2012.05.23.
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Klase Upload_xml_ielas
* Nolūks: kontrolieris datu ievadei XML Rigas ielu dokumentam
*/
class Upload_xml_ielas extends CI_Controller 
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
		
		$file_name = 'ielas.xml';
		if (file_exists('uploads/'.$file_name))
		{
			$reader = new XMLReader();
			$reader->open('uploads/'.$file_name);
			$doc = new DOMDocument;
		
			while ($reader->read() && $reader->name !== 'object');
		
			while($reader->name === 'object')
			{
				$object = simplexml_import_dom($doc->importNode($reader->expand(), true));
		
				$strOrgComment = '';
				foreach ($object->years->year as $year)
				{
					foreach ($year->attributes() as $v)
					{
						if ($v == 'adr')
						{
							if ($strOrgComment != '') $strOrgComment .= '; ';
							$strOrgComment .= trim($year).' - datējums no Rīgas adrešu grāmatas';
						}
						elseif ($v == 'katorgraf')
						{
							if ($strOrgComment != '') $strOrgComment .= '; ';
							$strOrgComment .= trim($year).' - datējums no kartogrāfiskā materiāla';
						}
						elseif ($v == 'katorgraf_bez')
						{
							if ($strOrgComment != '') $strOrgComment .= '; ';
							$strOrgComment .= trim($year).' - iela iezīmēta kartogrāfiskajā materiālā bez nosaukuma';
						}
						elseif ($v == 'ter')
						{
							if ($strOrgComment != '') $strOrgComment .= '; ';
							$strOrgComment .= trim($year).' - gads, kad attiecīgā teritorija pievienota Rīgai';
						}
						elseif ($v == 'none')
						{
							if ($strOrgComment != '') $strOrgComment .= '; ';
							$strOrgComment .= trim($year).' - pieminēts rakstītā avotā';
						}
					}
				}
				
				$arrNames = array();
				foreach ($object->names as $names)
				{
					$bolIsOrg = FALSE;
					foreach ($names->attributes() as $v)
					{
						if ($v == 'org')
						{
							$bolIsOrg = TRUE;
						}
					}
					
					foreach ($names->name as $name)
					{
						$arrName['name'] = trim($name->title);
						foreach ($name->title->attributes() as $v)
						{
							if ($v == 1)
							{
								$strDefinition = trim($name->title);
							}
						}
						
						$strComment = '';
						foreach ($name->year as $year)
						{
							foreach ($year->attributes() as $v)
							{
								if ($v == 'adr')
								{
									if ($strComment != '') $strComment .= '; ';
									$strComment .= trim($year).' - datējums no Rīgas adrešu grāmatas';
								}
								elseif ($v == 'katorgraf')
								{
									if ($strComment != '') $strComment .= '; ';
									$strComment .= trim($year).' - datējums no kartogrāfiskā materiāla';
								}
								elseif ($v == 'katorgraf_bez')
								{
									if ($strComment != '') $strComment .= '; ';
									$strComment .= trim($year).' - iela iezīmēta kartogrāfiskajā materiālā bez nosaukuma';
								}
								elseif ($v == 'ter')
								{
									if ($strComment != '') $strComment .= '; ';
									$strComment .= trim($year).' - gads, kad attiecīgā teritorija pievienota Rīgai';
								}
								elseif ($v == 'none')
								{
									if ($strComment != '') $strComment .= '; ';
									$strComment .= trim($year).' - pieminēts rakstītā avotā';
								}
							}
						}
						
						if ($bolIsOrg)
						{
							if ($strComment != '') $strComment .= '; ';
							$strComment .= $strOrgComment;
						}
						
						$arrName['comment'] = $strComment;
						$arrNames[] = $arrName;
					}
					
				}
				
				$intObjectID = $this->upload_xml_model->insertObject($strDefinition, '', 5, false);
				
				foreach($arrNames as $arrName)
				{
					$this->upload_xml_model->addNameToObjectIelas($intObjectID, $arrName['name'], $arrName['comment'], 1);
				}
				
								
				$reader->next('object');
			}
		
			$reader->close();
			
			echo "FINISH:<br>$intStartTime - ".time();
		}
	}
	
	
	public function mergeFields($arrFields)
	{
		$arrResult = array();
		if (isset($arrFields['a']))
		{
			
		}
				
		return $arrResult;
	}
}