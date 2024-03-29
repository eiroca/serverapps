<?php
/**
 * EIROCA PORTAL SYSTEM - Framework to build Mobile site - GPL3 - See licence in eps.php
 *
 * @author Enrico Croce & Simona Burzio (staff@eiroca.net)
 * @copyright Copyright (C) 2003-2015 eIrOcA - Enrico Croce & Simona Burzio
 * @version 0.5.2
 * @link http://www.eiroca.net
 */
if (!defined('BR')) define('BR', "<br/>\n");
if (!defined('SITEMAP_EXTENDED')) define('SITEMAP_EXTENDED', false);
require_once ('eps.php');
require_once (LIB_DIR . 'utils' . DIR_SEP . 'smarty' . DIR_SEP . 'Smarty.class.php');
require_once (LIB_DIR . 'utils' . DIR_SEP . 'smarty' . DIR_SEP . 'internals' . DIR_SEP . 'core.create_dir_structure.php');
require_once (LIB_DIR . 'utils' . DIR_SEP . 'smarty' . DIR_SEP . 'internals' . DIR_SEP . 'core.rmdir.php');
require_once (LIB_DIR . 'utils' . DIR_SEP . 'Image_Toolbox.class.php');
require_once (EPS_DIR . 'setup' . DIR_SEP . 'buildWebIndex.php');
require_once (EPS_DIR . 'setup' . DIR_SEP . 'buildWebSiteMap.php');
require_once (EPS_DIR . 'setup' . DIR_SEP . 'buildSiteMap.php');

function checkLibs() {
	global $smarty;
	error_reporting(E_ALL);
	$toolbox = new Image_Toolbox();
	$ver = (int)$toolbox->_gd_version;
	echo 'GD Library ... ' . (($ver >= 2) ? 'OK v. ' . $ver : 'KO') . BR;
	echo 'Testing directory ... ' . BR;
	$dir = '.' . DIR_SEP . 'dir1' . DIR_SEP . 'dir2';
	$params['dir'] = $dir;
	smarty_core_create_dir_structure($params, $smarty);
	$params['dirname'] = 'dir1';
	$fp = fopen($dir . DIR_SEP . 'test.txt', 'w+');
	fwrite($fp, 'text');
	fclose($fp);
	smarty_core_rmdir($params, $smarty);
}

function deleteDir($basepath, $dir) {
	global $smarty;
	if ($dir != '') {
		$item = get_absolute_path($basepath . DIR_SEP . $dir);
		echo 'D ' . $item . BR;
		$params['dirname'] = $item;
		$params['level'] = 1;
		smarty_core_rmdir($params, $smarty);
	}
}

function makeDir($basepath, $dir) {
	global $smarty;
	if ($dir != '') {
		$item = get_absolute_path($basepath . DIR_SEP . $dir);
		echo 'C ' . $item . BR;
		$params['dir'] = $item;
		smarty_core_create_dir_structure($params, $smarty);
	}
}

function addPortal($path, $portal) {
	global $to_build;
	unset($CONFIG);
	include $path;
	$fullDir = @$CONFIG['mobile'];
	$url = 'http://' . $_SERVER['HTTP_HOST'] . get_absolute_path($_SERVER['REQUEST_URI'] . '/../../..') . '/' . $portal;
	if ($fullDir) {
		echo '<b>';
	}
	echo '<a href="' . $url . '">' . $portal . '</a>';
	if ($fullDir) {
		echo '</b>';
	}
	echo BR;
	$dir = get_absolute_path(dirname($path));
	deleteDir($dir, @$CONFIG['path_counters']);
	deleteDir($dir, @$CONFIG['path_data']);
	deleteDir($dir, @$CONFIG['path_cache_srv']);
	deleteDir($dir, @$CONFIG['path_cache_eps']);
	deleteDir($dir, @$CONFIG['path_dynamic']);
	deleteDir($dir, @$CONFIG['path_indexfile']);
	foreach ($CONFIG as $entry => $val) {
		if (!(strpos($entry, 'path_') === false)) {
			if (strpos($entry, 'path_') == 0) {
				makeDir($dir, $val);
			}
		}
	}
	$epscache = @$CONFIG['path_cache_eps'];
	if ($epscache) {
		makeDir($dir, $epscache . 'devices');
		makeDir($dir, $epscache . 'services');
		makeDir($dir, $epscache . 'services' . DIR_SEP . 'RSS');
	}
	$servcache = @$CONFIG['path_cache_srv'];
	if ($servcache) {
		makeDir($dir, $servcache . 'pages');
		makeDir($dir, $servcache . 'pages' . DIR_SEP . 'WEB');
		makeDir($dir, $servcache . 'pages' . DIR_SEP . 'WML');
		makeDir($dir, $servcache . 'pages' . DIR_SEP . 'XHTML');
		makeDir($dir, $servcache . 'templates');
		makeDir($dir, $servcache . 'templates' . DIR_SEP . 'WEB');
		makeDir($dir, $servcache . 'templates' . DIR_SEP . 'WML');
		makeDir($dir, $servcache . 'templates' . DIR_SEP . 'XHTML');
	}
	$indexPath = @$CONFIG['path_indexfile'];
	if ($indexPath) {
		$indexFile = get_absolute_path($dir . DIR_SEP . $indexPath . $CONFIG['file_indexfile']);
		echo 'I ' . $indexFile . BR;
		buildWebIndex($indexFile, $dir . DIR_SEP);
		$indexFile = get_absolute_path($dir . DIR_SEP . $indexPath . 'sitemap.xml');
		$handler = $url . '/index.php';
		echo 'S ' . $indexFile . BR;
		buildWebSiteMap($indexFile, $handler, $dir . DIR_SEP);
	}
	else {
		$indexFile = get_absolute_path($dir . DIR_SEP . $CONFIG['path_data'] . 'sitemap.xml');
		echo 'S ' . $indexFile . BR;
		buildSiteMapMobile($url . '/module.php', $dir . DIR_SEP, $CONFIG, $indexFile);
	}
}
$smarty = new Smarty();
echo "<html><body>\n";
echo 'Checking...' . BR;
$ok = checkLibs();
$tmp = get_absolute_path(BASE_DIR);
echo 'Searching portals in ' . $tmp . BR;
if ($dir = opendir($tmp)) {
	while (($file = readdir($dir)) !== false) {
		if ('.' == $file) continue;
		if ('..' == $file) continue;
		$path = $tmp . $file;
		if (is_dir($path)) {
			$path .= DIR_SEP . 'eps_config.php';
			if (file_exists($path)) {
				addportal($path, $file);
			}
		}
	}
	closedir($dir);
}
echo BR;
echo "</body></html>\n";
?>