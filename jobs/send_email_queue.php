<?php

$i = 1;
while (true) {

	// send queue
	syslog(LOG_INFO, 'sending queued email.');
	exec("php /var/www/mgovph/public/index.php adskfhasdfadsfasdfajgfkjsdf jasdfgkjgkjgasd");

	// resend every 5 minutes
	// if ($i % 5 == 0) {
	// 	syslog(LOG_INFO, 'resending failed email.');
	// 	exec("php /var/www/mgovph/public/index.php adskfhasdfadsfasdfajgfkjsdf aldkslkjfgdsj");		
	// }

	$i++;
	sleep(30);
}