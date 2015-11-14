<?php
define('MENU_STAFF', true);
require_once DIRNAME(__DIR__) . '/includes/globals.php';
require_once DIRNAME(__DIR__) . '/includes/class/class_mtg_paginate.php';
$pages = new Paginator();
$_SESSION['staff_authed_time'] = time() + 900;
$_GET['pull'] = isset($_GET['pull']) && ctype_alpha(str_replace('.php', '', $_GET['pull'])) ? str_replace('.php', '', $_GET['pull']) : null;
$file = empty($_GET['pull']) ? 'index.php' : $_GET['pull'].'.php';

$path = __DIR__ . '/pull/'.$file;
if(file_exists($path))
	require_once $path;
else
	$mtg->error('Invalid pull request'.($users->hasAccess('override_all') ? ' - '.$path : ''));