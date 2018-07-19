<?php

namespace we7\HttpRoute;


spl_autoload_register(function ($class) {
    if (strpos($class, 'we7\HttpRoute\\') === 0) {
        $name = substr($class, strlen('we7\HttpRoute'));
        require __DIR__ . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});
