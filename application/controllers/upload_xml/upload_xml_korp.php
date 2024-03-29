﻿<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_xml_korp extends CI_Controller 
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
				foreach ($record->datafield as $datafield)
				{
					foreach ($datafield->attributes() as $v)
					{
						if ($v == '110')
						{
							++$i;
							$strName = '';
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a')
									{
										$strName = trim($subfield);
									}
									elseif ($v1 != 'x' && $v1 != 'v')
									{
										$strName .= ' '.trim($subfield);
									}
								}
							}
							$arrVariants[] = $strName;
							$strDefinition = $strName;
							$bolIsObject = TRUE;
						}
						elseif (($v == '410' || $v == '510') && $bolIsObject)
						{
							$strName = '';
							foreach ($datafield->subfield as $subfield)
							{
								foreach ($subfield->attributes() as $v1)
								{
									if ($v1 == 'a')
									{
										$strName = trim($subfield);
									}
									elseif ($v1 != 'w' && $v1 != 'v' && $v1 != 't' && $v1 != 'x')
									{
										$strName .= ' '.trim($subfield);
									}
								}
							}
							$arrVariants[] = $strName;
						}
					}
				}
		
				if ($bolIsObject)
				{
// 					var_dump($arrVariants);
// 					echo '<br>-----------------------------------<br>';
					
					$intObjectID = $this->upload_xml_model->insertObject($strDefinition, '', 13);
					
					foreach($arrVariants as $strName)
					{
						$this->upload_xml_model->addNameToObject($intObjectID, $strName, 1);
					}
					
				}
								
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
			
		}
				
		return $arrResult;
	}
}