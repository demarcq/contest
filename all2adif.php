<?php
ini_set('memory_limit','512M');
$fin=$argv[1];
$fout=$argv[2];
$x="<eoh>\n";
$in=file_get_contents($fin);
$ine=explode("\n",$in);
$mshv=false;
if (strpos($ine[1],"UTC Date:")!==false) $mshv=true;
$dated=trim(substr($ine[1],strpos($ine[1],":")+1));
$datede=explode(" ",$dated);
$mois=trim($datede[1]);
$annee=trim($datede[0]);

if ($mshv==true) {
  $dated=substr($ine[1],strpos($ine[1],":")+1);
  $nbe=0;
  $li=0;
  foreach($ine as $l) {
	$li++;
	$c=explode("|",$l);
//if (array_key_exists('1',$c)==false) $print_r($c);
	if (count($c)<=1) continue;
	if (strpos($c[1],'TX')!==false){
//print_r($c);
	$freq=trim(substr($c[1],3));
	$mode=trim(substr($freq,strpos($freq,' ')));
	$freq=trim(substr($freq,0,strpos($freq,' ')));
        $freqi=intval($freq);
	if ($freqi==0) {
		echo "Line: ".$li."\t".$l."\tFrequency error : " .$freq." / ".$ofreq."\n";
		$freq=$ofreq;
	}
        $ofreq=$freq;
        $freq=($freqi/1000);
	$time=$c[2];
	$date=$annee.$mois.$c[0];
        $m=explode("#",$c[3]);
	foreach($m as $c) {
	  if (strpos($c,' RR73')!==false ) {
//echo $c."\n";
	  $nbe++;
	  $call=trim(str_replace('RR73','',$c));
          $call=substr($call,0,strpos($call," "));
//echo $call."\t".$freq."\t".$mode."\t".$date."\t".$time."\n";
	  $t= "<call:".strlen($call).">".$call." <mode:".strlen($mode).">".$mode." <qso_date:".strlen($date).">".$date." <time_on:".strlen($time).">".$time." <time_off:".strlen($time).">".$time." <freq:".strlen($freq).">".$freq." <eor>\n"; 
//echo $t;
	  $x.=$t;
	}
  }

 }
}
}
$x.="<oef>";
file_put_contents($fout,$x);
$n=explode("\n",$x);
echo "Export ".($nbe)." qsos in Adif file ".$fout."\n";
