

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "">
<html xmlns="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Weather Forecast</title>
    <style type="text/css">
.totalWrapper {
	width:100%;
	float:left;
	position:relative;
	
	
}
.mainWrapper {
	width:276px;
	position:relative;
	margin:auto;
	
	
}
.wrapper
{
width:274px;
float:left;
position:relative;

}
.middle{
float:left;
position:relative;
width:239px;
padding-left:18px;
padding-right:17px;
}
.top-text{
width:239px;
float:left;
position:relative;
font-family:Tahoma, Arial, "Times New Roman";
font-size:9px;
font-weight:bold;
color:#ffe76a;
text-decoration:underline;
padding-bottom:7px;
}
.top-text-1{
width:239px;
float:left;
position:relative;
font-size:9px;
font-family:Tahoma, Arial, "Times New Roman";
font-weight:bold;
color:#ffe76a;
padding-bottom:10px;
}
.img-text{
float:left;
position:relative;
width:239px;

}
.image{
float:left;
position:relative;
width:42px;
padding-right:13px;

}
.img-text span{
width:184px;
font-family:Tahoma, Arial, "Times New Roman";
font-size:9px;
color:#FFFFFF;
float:left;
position:relative;
}
.text-bottom{
width:239px;
float:left;
position:relative;
font-size:9px;
font-family:Tahoma, Arial, "Times New Roman";
font-weight:bold;
color:#FFFFFF;
padding-top:10px;
padding-bottom:15px;
}
</style>
</head>

<body>
    <div class="mainWrapper">
  
    <!--Start mainWrapper-->
    <div class="wrapper">
<?php
	
	error_reporting(0);
	
	require_once getConfigValue('lib'). "xmltoarray/xmlreader.php";
		
	//XML Data
	$xml_data = file_get_contents('http://api.wxbug.net/getForecastRSS.aspx?ACode=A4473038782&lat=14.6708333&long=-17.4380556&unittype=1');
	//echo $xml_data; exit;
	$xmlObj = new xmlreadernew();
	
	//Creating Array
	$arrayData = $xmlObj->xml2array($xml_data);
	//print_r($arrayData);
	//exit;
	
	
	for($i= 0; $i<7; $i++) { ?>
		
		
		<div class="middle">
		
			<div class="top-text">
			
			</div>
			<div class="top-text-1">
			<?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:title']; ?>'s forecast for <?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:location']['aws:city'] ?>,<?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:location']['aws:country'] ?>
			</div>
			<div class="img-text">
						<div class="image">
						<img src="<?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:image'] ?>" width="42" height="35">
						</div>
						<span><?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:prediction'] ?></span>
			</div>
			<div class="text-bottom">
			 High : <?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:high'] ?> <?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:high_attr']['units'] ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 Low : <?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:low'] ?> <?php echo $arrayData['rss']['channel']['aws:weather']['aws:forecasts']['aws:forecast'][$i]['aws:low_attr']['units'] ?>
			</div>
			
		</div>
		<?php 
			
	}



exit;

?>
</div>
</div>