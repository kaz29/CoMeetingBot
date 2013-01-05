<?php
if ($_SERVER['REQUEST_URI'] === '/') {
    trigger_error('Invalid URL.', E_USER_ERROR);
}

list(,$tmppath) = explode('/', $_SERVER['REQUEST_URI'], 2);
if (strpos($tmppath, '/') === false) {
    trigger_error('Invalid URL.', E_USER_ERROR);
}

list($path,$meeting_id) = explode('/', $tmppath, 2);
if (strpos($path, '/') !== false) {
    trigger_error('Invalid URL.', E_USER_ERROR);
}

$instanse = null;
$path = ucfirst(strtolower($path));
if ($path === 'Github' || $path === 'Jenkins') {
    $className = $path.'Notify';
    require("../lib/{$className}.php");

    $instanse = new $className($meeting_id);
}

if (is_object($instanse)) {
    $instanse->notify();
}
