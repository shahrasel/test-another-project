<?php
@extract($_GET);
require_once("libs/gonximage.class.php");
if (isset($img) and $img!="") {
    gonximage::getimage($img);
}

?>