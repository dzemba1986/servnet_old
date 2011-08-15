<?php
$data="'1234-321'";
$value = preg_replace('/\'/', '', $data);
echo "$data <br> $value";
