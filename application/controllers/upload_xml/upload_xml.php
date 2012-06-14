<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_xml extends CI_Controller 
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
				$arrVariants = array();
				foreach ($record->datafield as $datafield)
				{
					foreach ($datafield->attributes() as $v)
					{
						if ($v == '111')
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
									else
									{
										$strName .= ' '.trim($subfield);
									}
								}
							}
							$arrVariants[] = $strName;
							$strDefinition = $strName;
							$bolIsObject = TRUE;
						}
						elseif (($v == '411' || $v == '511') && $bolIsObject)
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
									else
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
					
					$intObjectID = $this->upload_xml_model->insertObject($strDefinition, '', 2);
					
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