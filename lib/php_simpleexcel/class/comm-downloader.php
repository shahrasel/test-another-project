<?php 

include_once("./include/admin_common.php");

login_validation($root_path);

require_once "./include/class/class.writeexcel_workbook.inc.php";
require_once "./include/class/class.writeexcel_worksheet.inc.php";
include_once("./include/class/reader.php");

$msg = "";
$err = "";
$sql = "";
$err_e = "";

$fname = tempnam("/tmp", "temp_bill_sheet.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$sql = "SELECT * FROM " . BILL_INFO . " as B, " . CLIENT_INFO . " as C, " . SUB_ADMIN_SECURITY . " as S, " . COMM_INFO . " as CI WHERE B.client_id=C.client_id AND (bill_service1 = 'PPC_Monthly' OR bill_service1 = 'PPC_Set_Up' OR bill_service2 = 'PPC_Monthly' OR bill_service2 = 'PPC_Set_Up' OR bill_service3 = 'PPC_Monthly' OR bill_service3 = 'PPC_Set_Up' OR bill_service4 = 'PPC_Monthly' OR bill_service4 = 'PPC_Set_Up') AND C.sub_admin_id_for_client=S.sub_admin_id AND S.sub_admin_id = CI.sub_admin_id AND B.bill_month=CI.comm_month AND B.bill_year=CI.comm_year ";
	
	$info_sql .= $sql;
	if (!empty($sel_sub_admin_id_for_bill)){
		$info_sql .= " AND C.sub_admin_id_for_client = $sel_sub_admin_id_for_bill ";
	}	
	if (!empty($search_client)){
		$info_sql .= " AND B.client_id = $search_client ";
	}
	//echo $sel_month;
	if (!empty($sel_month)){
		$info_sql .= " AND B.bill_month = '$sel_month' ";
	}
	
	//$info_sql = $outer_sql_pre. $info_sql . $outer_sql_post;
	$info_sql .= " GROUP BY B.bill_id ";
	$info_sql .= " ORDER BY ";
	
	if (empty($sort_by) && empty($sort_as)){
		$info_sql .= " S.login_name ASC, C.business_name ASC, B.bill_year DESC, B.bill_month_int DESC ";
	}
	
	else{
		if (!empty($sort_by)){
			if ($sort_by == 'client_name'){						
				$info_sql .= " C.business_name ";
			}
			elseif ($sort_by == 'subadmin_name'){						
				$info_sql .= " S.login_name ";
			}
			elseif ($sort_by == 'month'){						
				$info_sql .= " B.bill_month_int ";
			}	
			
			elseif ($sort_by == 'total_bill'){						
				$info_sql .= " B.bill_price ";
			}	
			elseif ($sort_by == 'bill_with_commission'){						
				$info_sql .= " B.new_total_bill ";
			}	
			elseif ($sort_by == 'inv_report_sent'){						
				$info_sql .= " B.report_sent_check ";
			}	
			elseif ($sort_by == 'bill_sent'){						
				$info_sql .= " B.billed_check ";
			}	
			elseif ($sort_by == 'commission_rate'){						
				$info_sql .= " B.commission_percentage ";
			}	
			elseif ($sort_by == 'commission_total'){						
				$info_sql .= " B.commission ";
			}
			
			$smarty->assign("sort_by",$sort_by);		
		}
		if (!empty($sort_as)){
			$info_sql .= " $sort_as ";
		}
		
	}
	
	
	
	//$info_sql .= $sql .  " ORDER BY business_name LIMIT " . $start . ", " . $per_page;
	if ( !($result = $db->sql_query($info_sql)) ){
		message_die(CRITICAL_ERROR,"Could not config information","",__LINE__,__FILE__,$info_sql);
	}
	$info = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

$col_index=0;

$worksheet->write(0, $col_index++,  "Sub-admin Name");
$worksheet->write(0, $col_index++,  "Client Name");
$worksheet->write(0, $col_index++,  "Month Ending (Month, Year)");
$worksheet->write(0, $col_index++,  "Entered date");
$worksheet->write(0, $col_index++,  "Total Bill");
$worksheet->write(0, $col_index++,  "Commission (%)");
$worksheet->write(0, $col_index++,  "Commission Amount");
$worksheet->write(0, $col_index++,  "Total Commission");
$worksheet->write(0, $col_index++,  "Invoice / Report Sent");
$worksheet->write(0, $col_index++,  "Billed");

for ($i=0; $i < count ($info); $i++ ){

	$col_index=0;
	
	$worksheet->write( ($i+1), $col_index++,  $info[$i]['login_name']);
	$worksheet->write( ($i+1), $col_index++,  $info[$i]['business_name']);
	$worksheet->write( ($i+1), $col_index++,  $info[$i]['bill_month'] . ", " . $info[$i]['bill_year']);
	$worksheet->write( ($i+1), $col_index++,  $info[$i]['date_entered']);
	$worksheet->write( ($i+1), $col_index++,  '$' . $info[$i]['bill_price']);
	$worksheet->write( ($i+1), $col_index++,  (!empty($info[$i]['pre_commission_percentage']) ? $info[$i]['pre_commission_percentage'] : 0.00));
	$worksheet->write( ($i+1), $col_index++,  '$' . (!empty($info[$i]['commission']) ? $info[$i]['commission'] : 0.00) );
	$worksheet->write( ($i+1), $col_index++,  '$' . (!empty($info[$i]['total_commission_for_month']) ? $info[$i]['total_commission_for_month'] : 0.00) );
	$worksheet->write( ($i+1), $col_index++,  (!empty($info[$i]['report_sent_check']) ? "Sent" : "Not Sent"));
	$worksheet->write( ($i+1), $col_index++,  (!empty($info[$i]['billed_check']) ? "Billed" : "Not Billed"));
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"Commission-Summary.xls\"");
header("Content-Disposition: inline; filename=\"Commission-Summary.xls\"");

$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

?>