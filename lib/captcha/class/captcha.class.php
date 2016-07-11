<?php

  /******************************************************************

   Projectname:   CAPTCHA class
   Version:       2.0
   Author:        Pascal Rehfeldt <Pascal@Pascal-Rehfeldt.com>
   Last modified: 15. January 2006

   * GNU General Public License (Version 2, June 1991)
   *
   * This program is free software; you can redistribute
   * it and/or modify it under the terms of the GNU
   * General Public License as published by the Free
   * Software Foundation; either version 2 of the License,
   * or (at your option) any later version.
   *
   * This program is distributed in the hope that it will
   * be useful, but WITHOUT ANY WARRANTY; without even the
   * implied warranty of MERCHANTABILITY or FITNESS FOR A
   * PARTICULAR PURPOSE. See the GNU General Public License
   * for more details.

   Description:
   This class can generate CAPTCHAs, see README for more details!

  ******************************************************************/

  error_reporting(E_ALL);

  require('filter.class.php');  
  require('error.class.php');

  class captcha
  {

    var $Length;
    var $CaptchaString;
    var $fontpath;
    var $fonts;
		var $noise_type;
		//function captcha ($length = 6){
		//}
		
    function createCaptcha ($length = 6, $noise_type ='sign')
    {
			
			
      header('Content-type: image/png');
      			
      $this->Length   = $length;
      $this->noise_type = $noise_type;
      //$this->fontpath = dirname($_SERVER['SCRIPT_FILENAME']) . '/fonts/';
			//echo $_SERVER['SCRIPT_FILENAME'];
			//echo getConfigValue('lib')."<br />";
      $this->fontpath = getConfigValue('lib').'captcha/fonts/';      
			//if ( file_exists(getConfigValue('lib').'captcha/fonts') )
				//echo $this->fontpath;
				
      $this->fonts    = $this->getFonts();
      $errormgr       = new error;

      if ($this->fonts == FALSE)
      {
				
				//echo '12';
      	//$errormgr = new error;
				
      	$errormgr->addError('No fonts available!');
      	$errormgr->displayError();
      	die();
      	
      }
			
      if (function_exists('imagettftext') == FALSE)
      {

        $errormgr->addError('');
        $errormgr->displayError();
        die();

      }

      $this->stringGen();

      $this->makeCaptcha();

    } //captcha
    
    function getFonts ()
    {
    
      $fonts = array();
    
      if ($handle = @opendir($this->fontpath))
      {
   
        while (($file = readdir($handle)) !== FALSE)
        {
       
          $extension = strtolower(substr($file, strlen($file) - 3, 3));
       
          if ($extension == 'ttf')
          {
          	
            $fonts[] = $file;
            
          }
        
        }
        
        closedir($handle);

      }
      else
      {
      	
      	return FALSE;
      	
      }
      
      if (count($fonts) == 0)
      {
      	
      	return FALSE;
      	
      }
      else
      {
      	
      	return $fonts;
      	
      }
    
    } //getFonts
    
    function getRandFont ()
    {
    
      return $this->fontpath . $this->fonts[mt_rand(0, count($this->fonts) - 1)];
    
    } //getRandFont

    function stringGen ()
    {

      $uppercase  = range('A', 'Z');
      $lowercase  = range('a', 'z');
      $numeric    = range(0, 9);

      $CharPool   = array_merge($uppercase, $numeric, $lowercase);
      $PoolLength = count($CharPool) - 1;

      for ($i = 0; $i < $this->Length; $i++)
      {

        $this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];

      }
			
			
    } //StringGen

    function makeCaptcha ()
    {
			unsetSessionValue('captcha_string');
			
      $imagelength = 170;//$this->Length * 25 + 16;
      $imageheight = 50;

      $image       = imagecreate($imagelength, $imageheight);

      //$bgcolor     = imagecolorallocate($image, 222, 222, 222);
      $bgcolor     = imagecolorallocate($image, 255, 255, 255);

      // $_red = mt_rand (1, 255);
			//$_green = mt_rand (1, 255);
			//$_blue = mt_rand (1, 255);
			
			$_red = mt_rand (1, 210);
			$_green = $_red;
			$_blue = $_red;
				
			//$stringcolor = imagecolorallocate($image, 255, 0, 0);
			$stringcolor = imagecolorallocate($image, $_red, $_green, $_blue);
				

      $filter      = new filters;
			
			//$filter->noise ($image);	
      if ($this->noise_type == 'sign')
				$filter->signs($image, $this->getRandFont());
			if ($this->noise_type == 'noise')
				$filter->noise($image);
			if ($this->noise_type == 'blurWithImage')
				$filter->blurWithImage($image);
				
			setSessionValue('captcha_string', $this->CaptchaString);
			
			if ($this->Length <= 7)
				$offset = (7-$this->Length) * 15 ;
				
      for ($i = 0; $i < strlen($this->CaptchaString); $i++)
      {
      	
				
				
        imagettftext($image, 20, mt_rand(-10, 10), $offset + $i * 20 + 10,
                     mt_rand(25, 35),
                     $stringcolor,
                     $this->getRandFont(),
                     $this->CaptchaString{$i});
      
      }
			
			//echo $this->CaptchaString;
			//echo '-'.getSessionValue ('captcha_string');
      //$filter->noise($image, 10);
      //$filter->blur($image, 6);

      imagepng($image);
      imagedestroy($image);

    } //MakeCaptcha

    function getCaptchaString ()
    {

      return $this->CaptchaString;
			

    } //GetCaptchaString
    
  } //class: captcha

?>
