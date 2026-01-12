<?php
echo '<pre>';
echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;
echo 'mysqli loaded: ';
var_dump(extension_loaded('mysqli'));
echo 'mysqli class exists: ';
var_dump(class_exists('mysqli'));
