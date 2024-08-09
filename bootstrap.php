<?php

use Setup\Core\Application;

return Application::configure(__DIR__)
    ->withRoutes(__DIR__ . '/routes/web.php')
    ->create();