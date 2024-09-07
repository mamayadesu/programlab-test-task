<?php

session_start();

$_SESSION['csrf'] = md5(microtime(true));

session_destroy();