<?php
require_once ('content.php');

class content_instance extends content{
		
		public function setTemplate($val) {
       $this->templateFiles = $val;
    }
		public function setCss($val) {
				if ( is_array($val) )
        	$this->cssFiles = $val;
    }
		public function setJs($val) {
				if ( is_array($val) )
        	$this->jsFiles = $val;
    }
		
}

?>