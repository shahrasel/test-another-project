<?php
	
class pdfHelper{	

	function initPDF(){
		//echo '12';
		require_once (getConfigValue('lib').'tcpdf/config/lang/eng.php');
		require_once (getConfigValue('lib').'tcpdf/tcpdf.php');
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		return $pdf; 
	}
		
}

?>