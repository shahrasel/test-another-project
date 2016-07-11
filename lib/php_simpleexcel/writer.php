<?php 

/*include_once("./include/admin_common.php");

login_validation($root_path);
*/

require_once "class/class.writeexcel_workbook.inc.php";
require_once "class/class.writeexcel_worksheet.inc.php";
include_once("class/reader.php");

$msg = "";
$err = "";
$sql = "";
$err_e = "";

$fname = "file_name.xls";
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$col_index=0;

$worksheet->write(0, $col_index++,  "success");
$worksheet->write(0, $col_index++,  "Client Name");
$worksheet->write(0, $col_index++,  "Month Ending (Month, Year)");
$worksheet->write(0, $col_index++,  "Entered date");
$worksheet->write(0, $col_index++,  "Total Bill");
$worksheet->write(0, $col_index++,  "Commission (%)");
$worksheet->write(0, $col_index++,  "Commission Amount");
$worksheet->write(0, $col_index++,  "Total Commission");
$worksheet->write(0, $col_index++,  "Invoice / Report Sent");
$worksheet->write(0, $col_index++,  "Billed");

for ($i=0; $i < 5; $i++ ){

	$col_index=0;
	
	$worksheet->write( ($i+1), $col_index++,  'login_name');
	$worksheet->write( ($i+1), $col_index++,  'business_name');
	$worksheet->write( ($i+1), $col_index++,  'bill_month');
	$worksheet->write( ($i+1), $col_index++,  'date_entered');
	$worksheet->write( ($i+1), $col_index++,  '$' . 'bill_price');
	$worksheet->write( ($i+1), $col_index++,  'pre_commission_percentage');
	$worksheet->write( ($i+1), $col_index++,  '$' . 'commission');
	$worksheet->write( ($i+1), $col_index++,  '$' . 'total_commission_for_month');
	$worksheet->write( ($i+1), $col_index++,  'report_sent_check');
	$worksheet->write( ($i+1), $col_index++,  'billed_check');
}

$workbook->close();

/*header("Content-Type: application/x-msexcel; name=\"Commission-Summary.xls\"");
header("Content-Disposition: inline; filename=\"Commission-Summary.xls\"");

$fh=fopen($fname, "rb");
fpassthru($fh);*/
//unlink($fname);

?>