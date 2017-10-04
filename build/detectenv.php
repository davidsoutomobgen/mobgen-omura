<?php
/**
 * Detecting deployment environment
 *
 * arg1 = osname (ex. linux, winnt, osx)
 */
$aenvlist = array();

$osname = isset($argv[1]) ? strtolower($argv[1]) : '';
$servername = isset($argv[2]) ? strtolower($argv[2]) : '';
//$projectbase = isset($argv[3]) ? strtolower($argv[3]) : '';
if (isset($argv[3])) {
	$path = str_replace('\\', '/', $argv[3]); // Make sure we always use the same path devider
	$parts = explode('/', $path);
	$projectbase = $parts[ count($parts)-1 ];
} else {
	$projectbase = '';
}

if (!empty($osname)) {
	$aenvlist[] = 'os-'. $osname;
}

// This ENV var will only work on Azure
$sitename = getenv ('WEBSITE_SITE_NAME');
if (!empty($sitename)) {
	$aenvlist[] = 'server-'. $sitename;
} else {
	if (!empty($servername)) {
		$aenvlist[] = 'server-'. $servername;
	}
}

$path = realpath(__FILE__);
$path = str_replace('\\', '/', $path); // Make sure we always use the same path devider
$parts = explode('/', $path);
//$rootfolder = $parts[ count($parts)-3 ];
if ($parts[1] == 'data' && $parts[2] == 'people') {
	$aenvlist[] = 'development';
	$aenvlist[] = 'dev-' . $parts[3];
} else if ($parts[1] == 'home') {
	$aenvlist[] = 'development';
	$aenvlist[] = 'dev-' . $parts[2];
} else {
	if ($servername == 'shelldynamoxl') {
		$aenvlist[] = 'development';
		$aenvlist[] = 'env-' . $projectbase;
	} else if ($servername == 'prod1.mobgen.cyso.net' || $servername == 'prod2.mobgen.cyso.net') {
		$aenvlist[] = 'production';
	} else {
		$aenvlist[] = 'production';
	}
}

echo implode(',', $aenvlist);

//echo getenv ('WEBSITE_SITE_NAME'); //$_ENV['WEBSITE_SITE_NAME']; // shellappcfgcmsuat
