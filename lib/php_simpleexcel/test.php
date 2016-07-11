<?php
	
	function exportMysqlToXls($query,$export_file_path_name,$fildName)
	{
		require_once "class/class.writeexcel_workbook.inc.php";
		require_once "class/class.writeexcel_worksheet.inc.php";
		include_once("class/reader.php");
		
		$msg = "";
		$err = "";
		$sql = "";
		$err_e = "";
		
		$fname = $export_file_path_name;
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		
		$col_index=0;
		for($i=0;$i<sizeof($fildName);$i++) 
			$worksheet->write(0, $col_index++,  $fildName[$i]);
		
		$obj=new library();
		$res=$obj->readdata($query);
		for($i=0;$i<sizeof($res);$i++ )
		{
			$col_index=0;
			for($j=0;$j<sizeof($fildName);$j++) 
			{
				if($col_index==3 and strlen($res[$i][$j])>0)
					$value="http://".strip_tags($res[$i][$j]);
				else
					$value=strip_tags($res[$i][$j]);
				$worksheet->write( ($i+1), $col_index++,  $value);
			}
		}	
		$workbook->close();
	}
	
	function read_excel_file_and_return_2d_array($file_name)
	{
		//$obj=new library();
		require_once 'Excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($file_name);
		$res=$data->sheets;
		$rowNumber=sizeof($res[0]['cells']);
		$colNumber=sizeof($res[0]['cells'][1]);
		$array=array();
		for($i=0;$i<$rowNumber;$i++)
		{
			for($j=0;$j<$colNumber;$j++)
			{
				$array[$i][$j]=$res[0]['cells'][$i+1][$j+1];
			}
		}
		return $array;
	}
	$r=read_excel_file_and_return_2d_array("News(10-24-2009).xls");
	print_r($r);
?>