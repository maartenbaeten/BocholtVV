<?php

echo 'setting up your application... ';
$v = shell_exec('../foo.sh');
var_dump($v);
exit;