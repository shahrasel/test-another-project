<?php
abstract class filter
{   
		// Common method
    public function getOutPut($filter_criteria) {
			//echo $filter_criteria[0];
			if ( !class_exists($filter_criteria[0].'_instance') ){
				//echo $GLOBALS['module_path'];
				require_once (getConfigValue('module').$filter_criteria[0].'/'.$filter_criteria[0].'_instance.php');
				//$wert = new $tmp_instance();
				
			}
			$tmp_instance = $filter_criteria[0].'_instance';
			$perform_class = new $tmp_instance();
			
			return $perform_class->getFilterOutPut( $filter_criteria[1] ); 						
			//return call_user_func ($perform_class->filter1);
			//return call_user_func(array($perform_class, 'filter1'));
			//echo $filter_criteria[1];
			//return $perform_class->$filter_criteria[1]();
    }
					
}
?>