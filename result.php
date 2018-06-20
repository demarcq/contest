<?php 
$id=$_GET['id'];
$path='/home/fg8ojcom/www/contest/results/';
$bgcolor='#FFFFFF';
$refresh='';
if (file_exists($path.$id.'.log')) {
	$x=file_get_contents($path.$id.'.log');
	$cc=substr($x,0,strpos($x,"\n"));
	$x2=str_replace("\n",'<br />',$x);
	include('config.php');
	if (strlen($contest[$cc]['bgcolor'])>0) $bgcolor=$contest[$cc]['bgcolor'];	
	$image=$contest[$cc]['image'];
	$x='';
	if (strlen($image)>0) $x.= '<center><img src="'.$image.'" alt="" /><br /></center>';
	$x.= '<h2>'.$x2.'</h2>';
	if (file_exists($path.$id.'.log2')) {
		$x2=file_get_contents($path.$id.'.log2');
		$x.=str_replace("\n",'<br />',$x2);	}
} else {
	if (file_exists($path.$id.'.up')) {
		$x= '<br /><br /><center><h1>Calculation in progress, please wait...</h1><img src="wait.gif" style="max-width:25em;" /></center>';
		$refresh='<META http-equiv="refresh" content="5;">';
		$x2=file_get_contents($path.$id.'.up');
		$cc=substr($x2,0,strpos($x2,"\n"));
		include('config.php');
		if (strlen($contest[$cc]['bgcolor'])>0) $bgcolor=$contest[$cc]['bgcolor'];	
	}
}

?>
<html>
<head>
	<title>Contest Submitter</title>
	<?php echo $refresh; ?>
<style>
	* {
		font-family: Arial;
	}
	body {
		background-color: <?php  echo $bgcolor; ?>;
	}
	#body {
		width:800px; margin:0 auto;
	}
	input[type=text] {
		width: 400px;
		 text-transform: uppercase;
	}
	input[type=submit] {
		width: 400px;
		text-align: center;
	}
	#label_273 {
	   cursor: pointer;
	   margin:0px;
	   padding:0px;
	   height:0px;
	}
	#label_273 img {
		height:45px;
	}
	
	#file_273 {
	   opacity: 0;
	   position: absolute;
	   z-index: -1;
	}
	.total {
		font-weight: bold;
	}
	table {
		min-width: 50em;
	}
</style>

</head>

<body>
<div id="body"><?php
echo $x;
?>
</div></body></html>