<?php 
include('/home/fg8ojcom/www/contest/config.php');
include_once('/home/fg8ojcom/www/script_connect.php');

$dxcc=array();
$sql='SELECT id,name FROM dxcc WHERE old=0;';
$rs=mysql_query($sql);
while ($j=mysql_fetch_array($rs)) {
	array_push($dxcc, $j['id']);
	$dxcc[$j['id']]=$j['name'];
}

$path='/home/fg8ojcom/www/contest/results/';
exec('ps aux|grep "/home/fg8ojcom/www/contest/cron.php"|grep -v grep',$o);
if (count($o)>2) exit();
$a=0;
while ($a==0) {
	foreach ($contest as $cc2=>$cc) {
		$s=scandir($path.'/'.$cc2);
		scan($s,$cc2);
	}
	sleep(5);
}


function scan($s,$cc) {
	global $path,$contest,$dxcc;
	foreach ($s as $sc) {
		if (substr($sc,strlen($sc)-3)=='.up') {
			$sess=substr($sc,0,strlen($sc)-3);
echo $path.$cc.'/'.$sess.'.adif'."\n";
			$up=file_get_contents($path.$cc.'/'.$sc);
			$upconfig=explode("\n",$up);
			$gcontest=$contest[$upconfig[0]];
			$start_date=$gcontest['start_date'];
			$end_date=$gcontest['end_date'];
			$adif=strtoupper(file_get_contents($path.$cc.'/'.$sess.'.adif'));
			if (strpos($adif,'<EOH>')!==false) $adif=substr($adif,strpos($adif,'<EOH>')+6);
			$adif=explode("\n",$adif);
			$grida=array();
			$gridv=0;
			$dxcca=array();
			$calla=array();
			$dxccv=0;
			$qsov=0;
			$x='<table><tr class="total"><td>DATE</td><td>TIME</td><td>CALLSIGN</td><td>SATELLITE</td><td colspan="2">GRID (*)</td><td colspan="2">DXCC (*)</td></tr>';
			foreach ($adif as $li) {
				$QSO_DATE=getadif('QSO_DATE',$li);
				$QSO_TIME=getadif('TIME_ON',$li);
				$CALL=getadif('CALL',$li);
				$SAT_NAME=getadif('SAT_NAME',$li);
				$GRIDSQUARE=getadif('GRIDSQUARE',$li);
				$DXCC=getadif('DXCC',$li);
				
				if (strlen($SAT_NAME)==0) continue;
				if ($QSO_DATE>$end_date) continue;
				if ($QSO_DATE<$start_date) continue;
				if (strlen($QSO_DATE)==0) continue;
				
				if (array_search($CALL,$calla)==false) {
					array_push($calla,$CALL);
					$qsov++;
				} else {
					continue;
				}
				
				
				$sql='SELECT id FROM qrz WHERE callsign="'.$CALL.'"';
				$rs=mysql_query($sql);
				if (mysql_num_rows($rs)==0) {
					$ch = curl_init('http://fg8oj.com/qrz.com.php?c='.$CALL);
					$resultat = curl_exec ($ch);
					print_r($resultat);
					curl_close($ch);
				}
				$dxccprovided=false;
				if ((strlen( $DXCC)==0)||( $DXCC=='0')) {
					$sql='SELECT dxcc FROM qrz WHERE callsign="'.$CALL.'"';
					$rs=mysql_query($sql);
					if (mysql_num_rows($rs)>0) {
						$j=mysql_fetch_array($rs);
						$DXCC=$j[0];
						if ($DXCC==0) $DXCC=getprefix($CALL);
						if ($DXCC!=='0') $dxccprovided=true;
					} else {
						
						$DXCC=getprefix($CALL);

					}
				}
				if (strlen($GRIDSQUARE)>4) $GRIDSQUARE=substr($GRIDSQUARE,0,4);
				if (strlen($GRIDSQUARE)<4) $GRIDSQUARE='';
				$gridprovided=false;
				if ((strlen( $GRIDSQUARE)==0)||( $GRIDSQUARE=='0')) {
					$sql='SELECT grid FROM qrz WHERE callsign="'.$CALL.'"';
					$rs=mysql_query($sql);
					if (mysql_num_rows($rs)>0) {
						$j=mysql_fetch_array($rs);
						$GRIDSQUARE=$j[0];
						$gridprovided=true;
					} else {
						$GRIDSQUARE='';
					}
				}
				$commentg='';
				$commentd='';
				if (strlen($GRIDSQUARE)>4) $GRIDSQUARE=substr($GRIDSQUARE,0,4);
				if (strlen($GRIDSQUARE)<4) $GRIDSQUARE='';
				if (strlen($GRIDSQUARE)==4) {
					if (array_search($GRIDSQUARE,$grida)==false) {
						array_push($grida,$GRIDSQUARE);
						$gridv++;
						$commentg='('.$gridv.')';
					}
				}
				if ($DXCC>0) {
					if (($DXCC!=='0') && (array_search($DXCC,$dxcca)==false)) {
						array_push($dxcca,$DXCC);
						$dxccv++;
						$commentd='('.$dxccv.')';
					}
	
				}
				
				$c='black';
				if (strlen($GRIDSQUARE)==0) $c='red';
				if ($DXCC==0) $c='red';
				$DXCCtext=$dxcc[$DXCC];
				$x.='<tr style="color:'.$c.';"><td>'. $QSO_DATE.'</td><td>'. $QSO_TIME.'</td><td>';
				$x.= $CALL.'</td><td>';
				$x.= $SAT_NAME.'</td><td>';
				if (strlen($commentg)==0) $x.= '<s>';
				if ($gridprovided==true) $x.= '<b>';
				$x.= $GRIDSQUARE.'</b></s></td><td>'.$commentg.'</td><td>';
				if (strlen($commentg)==0) $x.= '<s>';
				if ($dxccprovided==true) $x.= '<b>';
				$x.= $DXCCtext.'</td><td>'.$commentd."</b></s></td></tr>";
			}
			if ($qsov>0) {
				$x.='<tr><td>&nbsp;</td></tr><tr class="total"><td colspan="4" valign="right">TOTAL QSO :</td><td>'.$qsov.'</td></tr>';
				$x.='<tr class="total"><td colspan="4" valign="right">TOTAL GRIDSQUARE :</td><td>'.$gridv.'</td></tr>';
				$x.='<tr class="total"><td colspan="4" valign="right">TOTAL DXCC :</td><td>'.$dxccv.'</td></tr>';
				$x.='<tr class="total"><td colspan="4" valign="right">TOTAL POINTS :</td><td>'.number_format($qsov*$dxccv*$gridv,0,'',' ').'</td></tr>';
				$x.='</table>';
			}


			rename($path.$cc.'/'.$sess.'.up',$path.$cc.'/'.$sess.'.log');
			file_put_contents($path.$cc.'/'.$sess.'.log2',$x );
			
		}
	}
}

function getprefix($CALL) {
	global $dxccprovided;
	$DXCC=0;
	for ($i=0;$i<=strlen($CALL);$i++) {
		$qrzf=substr($CALL,0,strlen($CALL)-$i);
		$sql='SELECT * FROM dxcc_prefix WHERE prefix LIKE "'.$qrzf.'%"';
		$rs=mysql_query($sql);
		$print=false;
		if (mysql_num_rows($rs)>0) {
			while ($j=mysql_fetch_array($rs)) {
				if (strpos($CALL,$j['prefix'])!==false) {
					$print=true;
					$DXCC=$j['dxcc'];
					if ($DXCC!=='0') $dxccprovided=true;
				}
			}
			if ($print==true) break;
		}
	}
	return $DXCC;
}

function getadif($champ,$ligne) {
	//$ligne='<QSO_DATE:8>20180316<TIME_ON:6>021000<MY_GRIDSQUARE:6>FK96IG<STATION_CALLSIGN:5>FG8OJ<CALL:5>KE9AJ<BAND:2>2M<BAND_RX:4>70CM<MODE:3>SSB<SAT_NAME:5>FO-29<PROP_MODE:3>SAT<GRIDSQUARE:6>EN50EN<OPERATOR:5>FG8OJ';
	preg_match('~<'.$champ.':(\d+)>(.*)~', $ligne, $matches) ;
	if (count($matches)>0) {
//print_r($matches);
	$rawcall=substr($matches[2],0,$matches[1]);
//echo 'rawcall='.$rawcall. "\n\n\n";
	return $rawcall;
	}
}
?>