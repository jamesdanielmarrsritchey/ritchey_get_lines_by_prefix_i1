<?php
# App.php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_get_lines_by_prefix_i1_v1.php';
$return = ritchey_get_lines_by_prefix_i1_v1("{$location}/temporary/Source File.txt", 'Content:', NULL, NULL, NULL);
if (@is_string($return) === TRUE){
	print_r($return);
} else {
	echo "FALSE" . PHP_EOL;
}
?>