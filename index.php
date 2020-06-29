<?php

echo("Unauthorized");
// echo(getcwd());
$old = getcwd();
$new = chdir("/");
echo getcwd();
chdir($old);