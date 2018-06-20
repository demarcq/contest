<?php 
session_start();
include('config.php');
$file=$_FILES['adif'][tmp_name];
$cc=$_POST['contest'];

if (file_exists('results/'.$cc.'/')==false) mkdir('results/'.$cc.'/', $mode=0777, $recursive=true);
if (file_exists('results/'.$cc.'/'.session_id().'.adif')==true) {
	unlink('results/'.$cc.'/'.session_id().'.adif');
	unlink('results/'.$cc.'/'.session_id().'.log');
	unlink('results/'.$cc.'/'.session_id().'.log2');
	echo '...suppressing old file...';
}
if ($_FILES['adif']['error']>0) {
	echo '<h1>Error while uploading, please contact info@fg8oj.com</h1>';
	echo 'Error # '.$_FILES['adif']['error'];
	exit();
}
copy($file,'results/'.$cc.'/'.session_id().'.adif');

$data=$_POST['contest']."\n".strtoupper($_POST['callsign'])."\n".strtoupper($_POST['email'])."\n";
file_put_contents('results/'.$cc.'/'.session_id().'.up',$data);
$bgcolor='#FFFFFF';
if (strlen($contest[$_POST['contest']]['bgcolor'])>0) $bgcolor=$contest[$_POST['contest']]['bgcolor'];
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

</style>
<META http-equiv="refresh" content="5; URL=result.php?id=<?php echo $cc.'/'.session_id(); ?>">
</head>

<body>
<div id="body">
<br />
<br />
<br />

<h1>Successful upload # <?php echo session_id(); ?></h1>
<h2>You will receive an email for confirmation after calculation.</h2>
