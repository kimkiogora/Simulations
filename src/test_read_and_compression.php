<?php
//ini_set('memory_limit', '4M');
//
require_once "DatabaseHandler.php";
$path = "contacts.csv";
$threshold = 1000000;
test_compression($path, $threshold);




####################################################################
function getMTime() {
        $mtime = microtime();
        $ftime = explode(' ', $mtime);
        $stime = $ftime[1] + $ftime[0];
        return $stime;
}


function read($path, $marker){
	$contacts = array();
	$handle = fopen($path, "r");
	if ($handle) {
	    $j=0;
	    while (($line = fgets($handle)) !== false) {
		    $contacts [$j++] = $line;
		    #if($j>=$marker)break;
		    #QUEUE
	    }
	    fclose($handle);
	}
	return $contacts;
}


/**
 * Compress and strore data
 */ 
function test_compression($path, $N){
	#Get the data
	$start = getMTime();
	$data = read($path, $N);
	#Compress the data
	$count = count($data);
	$json = json_encode($data);
	$gzc = base64_encode(gzcompress($json,9));//gzdeflate($msg,9);
	
	$db = new DB("localhost","root","r00t","Simulation");
	$groupname = __FUNCTION__.date("Ymdhis");
	#$sql = "INSERT into contacts (group_name,contacts) VALUES ('$groupname',COMPRESS('".$db->_clean_input($gzc)."'));";
	$sql = "INSERT into contacts (group_name,contacts) VALUES ('$groupname','".$db->_clean_input($gzc)."');";
	$res = $db->add_record($sql);

	#save to redis
	#key = $group_name;data=>gzc;

	echo "\n $sql \n \nRes $res\n\n";
	echo "\n--UNPACK FROM DB--\n";
	$data = gzuncompress(base64_decode($gzc));
	$end = getMTime();
	$tat = sprintf("%0.2f",($end - $start));

	print_r($data);

	echo "\nTEST $count END - COMPRESSION DECOMPRESSION TIME - $tat (secs) \n";
}
?>
