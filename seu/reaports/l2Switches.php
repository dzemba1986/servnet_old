<?php
require('../security.php');
require('../include/definitions.php');
require('../include/classes/reaport.php');
$repo = new Reaport();
$ips = $repo->get8000GS_IPS();
print_r($ips);
