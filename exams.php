<?php 
	$filename = 'files/sched.pdf';
	if (!file_exists($filename)
		|| filemtime($filename) < strtotime("5 minutes ago")){
		echo exec("./fetch-schedule.sh");
		clearstatcache();
	}
	echo date ("F d Y H:i:s.", filemtime($filename));
?>