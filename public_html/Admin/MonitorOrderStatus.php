<?php

//Hardcoding the server path as the GoDaddy is not able to read the server variables

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
//include_once '/home/content/22/10609722/html' .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'CronOrder.php';

$order = new CronOrder();

ECHO 'Updating status for the orders on which the timer is activated...';

$order->updateTimerActivatedOrders();

ECHO 'Updating status for the orders on which the timer is activated is DONE.';

?>
