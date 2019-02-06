<?php

$services       = array(
                '/var/www/mgovph/jobs/send_email_queue.php'
        );

exec("ps aux | awk '!/grep/ && /php/ {print $12,$13,$14}'", $running_process);

foreach ($services as $key => $value) {


        if (is_array($value)) {

                foreach ($value as $item) {

                        $service = $key . ' ' . $item;
                        if (!in_array($service, $running_process)) {
                                exec("nohup php {$service} > /dev/null 2> /dev/null &");
                        }

                }

        } else {

                if (!in_array($value, $running_process)) {
                        exec("nohup php {$value} > /dev/null 2> /dev/null &");
                }

        }

}