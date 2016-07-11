<?php
session_start();
class db_con
{
	function db_con()
	{
		$db_host = "localhost";
		$db_user="cyber786_scraper";
		$db_password="/E?GIT#B";
		$db_name="cyber786_scraper";
		$link = mysql_connect($db_host,$db_user,$db_password);			
		if($link)
		{
			mysql_select_db($db_name,$link);		
			//echo "connected.";		
		}
		else
		{
			echo "not connected.";
		}
	}
}
class library extends db_con
{
	function readdata( $query )
	{
		$res = mysql_query($query);
		$result = array();
		if($res!=null)
		{
			while( $r = mysql_fetch_row($res))
			{
				$row = array();
				foreach( $r as $tr)
				{
					$row[] = $tr ;
				}
				$result[] = $row;
			}
		}
		return $result;
	}
	function executeQuery($query)
	{
		//echo $query;
		mysql_query($query) or die("Query error");
	}
	function maxValue($table_name, $field_name)
	{
		$obj=new library();
		$rr=$obj->readdata("select MAX($field_name) from $table_name");
		return $rr[0][0];
	}
	function printBlogData($rr_blog) // print blog data from a 2d array
	{
		
			echo "<hr />Author :".$rr_blog[0]." <br />
				  User Name : ".$rr_blog[1]."<br />
				  Blog Title :".$rr_blog[2]."<br />
				  Image :<img src='".$rr_blog[4]."' width=150 /><br />
				  Blog :".$rr_blog[3]."<hr />";
		
	}
	function PrintData($rr_blog)
	{
		if($rr_blog[5]==0 and $rr_blog[6]==1)
				$pending_status="pending";
			else
				$pending_status="Approved";
			echo "<hr />Author :".$rr_blog[0]."<br />
				  User Name : ".$rr_blog[1]."<br />
				  Blog Title :".$rr_blog[2]."<br />
				  Image :<img src='".$rr_blog[4]."' width=150 /><br />
				  Approve Status : $pending_status<br />
				  Blog :".$rr_blog[3]."<br />
				  <select name='ddlStatus$i' id='ddlStatus$i' onchange=\"fncChangeStatus('ddlStatus$i','".$rr_blog[7]."')\">
				  	<option value=0 selected='selected'>Select Status</option>
					<option value=1>Public</option>
					<option value=2>Private</option>					
				  </select><hr />";
	}
	function print_data_with_paging_only_for_index($data_set,$number_of_data_view_each_page,$number_of_page_linked,$paging_url) // print data with paging
	{
		$obj_new=new library();
		$numberOfData=sizeof($data_set);
		
		if($_GET['pageNumber'])
			$current_page=$_GET['pageNumber'];
		else
			$current_page=1;
			
		$start_index=($current_page-1)*$number_of_data_view_each_page;
		$end_index=($current_page)*$number_of_data_view_each_page;
		if($end_index>$numberOfData)
			$end_index=$numberOfData;
		
		for($i=$start_index;$i<$end_index;$i++)
		{
			$obj_new->printBlogData($data_set[$i]);
		}
		if($numberOfData>$number_of_data_view_each_page)
		$obj_new->PrintPagingData($numberOfData,$number_of_data_view_each_page,$number_of_page_linked,$paging_url);
	}
	function print_data_with_paging($data_set,$number_of_data_view_each_page,$number_of_page_linked,$paging_url) // print data with paging
	{
		$obj_new=new library();
		$numberOfData=sizeof($data_set);
		
		if($_GET['pageNumber'])
			$current_page=$_GET['pageNumber'];
		else
			$current_page=1;
			
		$start_index=($current_page-1)*$number_of_data_view_each_page;
		$end_index=($current_page)*$number_of_data_view_each_page;
		if($end_index>$numberOfData)
			$end_index=$numberOfData;
		
		for($i=$start_index;$i<$end_index;$i++)
		{
			$obj_new->PrintData($data_set[$i]);
		}
		if($numberOfData>$number_of_data_view_each_page)
		$obj_new->PrintPagingData($numberOfData,$number_of_data_view_each_page,$number_of_page_linked,$paging_url);
	}
	function PrintPagingData($numberOfData, $number_of_data_view_each_page, $number_of_page_linked, $paging_url)
	{
		$number_of_page=floor($numberOfData/$number_of_data_view_each_page);
		if($numberOfData%$number_of_data_view_each_page!=0)
			$number_of_page++;
		if($_GET['pageNumber'])
			$current_page=$_GET['pageNumber'];
		else
			$current_page=1;
		$previous_page="";
		$next_page="";
		$first_page="";
		$last_page="";
		
		$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		$pre_num=$current_page-1;
		$next_num=$current_page+1;
		
		if($pre_num>0)
		{
			$previous_page="<a href='$paging_url?pageNumber=$pre_num'>&laquo;$space</a>";
			$first_page="<a href='$paging_url?pageNumber=1'>first$space</a>";
		}
		if($next_num<=$number_of_page)
		{
			$next_page="<a href='$paging_url?pageNumber=$next_num'>&raquo;$space</a>";
			$last_page="<a href='$paging_url?pageNumber=$number_of_page'>Last$space</a>";
		}
		if($current_page-1<1)
		{
			$start=1;
			$end=2;
		}
		else if($current_page+1>$number_of_page)
		{
			$start=$current_page-1;
			$end=$number_of_page;
		}
		else
		{
			$start=$current_page-1;
			$end=$current_page+1;
		}
		
		for($i=$start;$i<=$end;$i++)
		{
			if($current_page==$i)
				$linked.="<span class='nolink'>$i$space</a>";
			else
				$linked.="<a href='$paging_url?pageNumber=$i'>".$i."</a>$space";
		}
		echo $first_page.$previous_page.$linked.$next_page.$last_page;
	}
	
	
	function print_data_with_paging_with_ajax($data_set,$number_of_data_view_each_page,$number_of_page_linked,$paging_url) // print data with paging
	{
		$obj_new=new library();
		$numberOfData=sizeof($data_set);
		
		if($_GET['pageNumber'])
			$current_page=$_GET['pageNumber'];
		else
			$current_page=1;
			
		$start_index=($current_page-1)*$number_of_data_view_each_page;
		$end_index=($current_page)*$number_of_data_view_each_page;
		if($end_index>$numberOfData)
			$end_index=$numberOfData;
		
		for($i=$start_index;$i<$end_index;$i++)
		{
			$obj_new->PrintData($data_set[$i]);
		}
		if($numberOfData>$number_of_data_view_each_page)
		$obj_new->PrintPagingData_with_ajax($numberOfData,$number_of_data_view_each_page,$number_of_page_linked,$paging_url);
	}
	function PrintPagingData_with_ajax($numberOfData, $number_of_data_view_each_page, $number_of_page_linked, $paging_url)
	{
		$paging_url="action/searchAction.php";
		$number_of_page=floor($numberOfData/$number_of_data_view_each_page);
		if($numberOfData%$number_of_data_view_each_page!=0)
			$number_of_page++;
		if($_GET['pageNumber'])
			$current_page=$_GET['pageNumber'];
		else
			$current_page=1;
		$previous_page="";
		$next_page="";
		$first_page="";
		$last_page="";
		
		$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		$pre_num=$current_page-1;
		$next_num=$current_page+1;
		
		if($pre_num>0)
		{
			$previous_page="<a href='javascript:fncSearchPaging(\"$paging_url?pageNumber=$pre_num\")'>&laquo;$space</a>";
			$first_page="<a href='javascript:fncSearchPaging(\"$paging_url?pageNumber=1\")'>first$space</a>";
		}
		if($next_num<=$number_of_page)
		{
			$next_page="<a href='javascript:fncSearchPaging(\"$paging_url?pageNumber=$next_num\")'>&raquo;$space</a>";
			$last_page="<a href='javascript:fncSearchPaging(\"$paging_url?pageNumber=$number_of_page\")'>Last$space</a>";
		}
		if($current_page-1<1)
		{
			$start=1;
			$end=2;
		}
		else if($current_page+1>$number_of_page)
		{
			$start=$current_page-1;
			$end=$number_of_page;
		}
		else
		{
			$start=$current_page-1;
			$end=$current_page+1;
		}
		
		for($i=$start;$i<=$end;$i++)
		{
			if($current_page==$i)
				$linked.="<span class='nolink'>$i$space</a>";
			else
				$linked.="<a href='javascript:fncSearchPaging(\"$paging_url?pageNumber=$i\")'>".$i."</a>$space";
		}
		echo $first_page.$previous_page.$linked.$next_page.$last_page;
	}
	function exportMysqlToCsv($query,$filename = 'export.csv')
	{
		$csv_terminated = "\n";
		$csv_separator = ",";
		$csv_enclosed = '"';
		$csv_escaped = "\\";
		$sql_query = $query;
	 
		// Gets the data from the database
		$result = mysql_query($sql_query);
		$fields_cnt = mysql_num_fields($result);
	 	 
		$schema_insert = '';
	 
		for ($i = 0; $i < $fields_cnt; $i++)
		{
			$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
				stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;
			$schema_insert .= $l;
			$schema_insert .= $csv_separator;
		} // end for
	 
		$out = trim(substr($schema_insert, 0, -1));
		$out .= $csv_terminated;
	 
		// Format the data
		while ($row = mysql_fetch_array($result))
		{
			$schema_insert = '';
			for ($j = 0; $j < $fields_cnt; $j++)
			{
				if ($row[$j] == '0' || $row[$j] != '')
				{
	 
					if ($csv_enclosed == '')
					{
						$schema_insert .= trim($row[$j]);
					} else
					{
						$schema_insert .= $csv_enclosed . 
						str_replace(trim($csv_enclosed), trim($csv_escaped) . trim($csv_enclosed), trim($row[$j])) . trim($csv_enclosed);
					}
				} else
				{
					$schema_insert .= '';
				}
	 
				if ($j < $fields_cnt - 1)
				{
					$schema_insert .= $csv_separator;
				}
			} // end for
	 
			$out .= $schema_insert;
			$out .= $csv_terminated;
		} // end while
	 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($out));
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$filename");
		$fp=fopen($filename,w);
		if(fwrite($fp,$out))
			echo "file save successfully";
		else
			echo "file save successfully";
			
		fclose($fp);
		
		exit;
	 
	}
	function CreateArrayListForXlcExport($query,$fildName)
	{
		$obj=new library();
		$res=$obj->readdata($query);
		$assoc=array();
		for($i=0;$i<sizeof($res);$i++)
		{
			$tmp=array();
			for($j=0;$j<sizeof($fildName);$j++)
			{
				$tmp[$fildName[$j]]=$res[$i][$j];
			}
			$assoc[$i]=$tmp;
		}
		return $assoc;
	}
	function exportMysqlToXls($query,$export_file_path_name,$fildName)
	{
		$export_file = "xlsfile://export.xls";
		$obj=new library();
		require_once "excel.php";
		$fp = fopen($export_file, "wb");
		if (!is_resource($fp))
		{
			die("Cannot open $export_file");
		}
		$assoc=$obj->CreateArrayListForXlcExport($query,$fildName);
		
		fwrite($fp, serialize($assoc));
		fclose($fp);
		
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"" . basename($export_file) . "\"" );
		header ("Content-Description: PHP/INTERBASE Generated Data" );
		//readfile($export_file);
		copy($export_file,$export_file_path_name);
		exit;
	}
	function showArchive()
	{	
		
	}
}