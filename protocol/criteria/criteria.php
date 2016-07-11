<?php
abstract class criteria
{   
		// Common method
    public function getOutPut($filter_criteria) {
			//echo $filter_criteria[0];
			if ( !class_exists($filter_criteria[0].'_instance') ){
				//echo $GLOBALS['module_path'];
				require_once ($GLOBALS['module_path'].$filter_criteria[0].'/'.$filter_criteria[0].'_instance.php');
				//$wert = new $tmp_instance();
				
			}
			$tmp_instance = $filter_criteria[0].'_instance';
			$perform_class = new $tmp_instance();
			
			return $perform_class->getValue( implode('_', $filter_criteria) ); 						
    }
					
}
?>