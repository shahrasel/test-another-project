<?php
abstract class content
{   
		protected $templateFiles;
		protected $cssFiles;
		protected $jsFiles;		
		public $pageValues;
		
		// Force Extending class to define this method
    abstract protected function setTemplate($val);
		abstract protected function setCss($val);
		abstract protected function setJs($val);
	 
    protected function unstripAll($array){
						
			foreach($array as &$val){
				if(is_array($val)){
					$val = $this->unstripAll($val);
				}else{
					$val = stripslashes($val);
				}
			}
			return $array;
		}
		
		public function setValue($name, $val){
			$this->pageValues[$name] = $val;
		}	
		
		public function getValue($name){
			//echo 'inside content'.$name;
			print_r ($this->pageValues);
			//return $this->pageValues[$name];
		}
		// Common method
		
		public function contentView(){
			//print_r ($this->pageValues);
			
			if (!empty ($this->templateFiles) ){
        	
					ob_start();
					if (is_array($this->pageValues) ):
						foreach ($this->pageValues as $key=>$value)
							$$key = $value;
					endif;
					//echo $this->templateFiles;				
					require_once ($this->templateFiles);
					$output = ob_get_contents();
					ob_end_clean();
					return $output;
					
			}
			else
					return "---No Template Found---";
					
		}
		
    public function contentViewWithStripValue() {
								
				if (!empty ($this->templateFiles) ){
        	
					ob_start();
					if (is_array($this->pageValues) ):
						foreach ($this->pageValues as $key=>$value){
							if ( is_array($value) )
								$$key = $this->unstripAll($value);
							else
								$$key = stripslashes($value);
						}
					endif;
					
					require_once ($this->templateFiles);
					$output = ob_get_contents();
					ob_end_clean();
					return $output;
					
				}
				else
					return "---No Template Found---";
    }
					
}


?>