<?php

use Setup\Transport\Request;

require_once __DIR__ . '/../' . 'vendor/autoload.php';

(require_once __DIR__ . '/../bootstrap.php')->handleRequest(Request::capture());