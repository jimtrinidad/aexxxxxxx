<?php

while (true) {

	// send queue
	syslog(LOG_INFO, 'Start data importer process.');
	exec("php /var/www/mgovph/public/index.php importer reader");

	sleep(30);
}