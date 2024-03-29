<?php

/**
 * EIROCA PORTAL SYSTEM - Framework to build Mobile site - GPL3 - See licence in eps.php
 *
 * @author Enrico Croce & Simona Burzio (staff@eiroca.net)
 * @copyright Copyright (C) 2003-2015 eIrOcA - Enrico Croce & Simona Burzio
 * @version 0.5.2
 * @link http://www.eiroca.net
 */
class DSPage extends TPage {
	var $conf;
	var $dir;

	function DSPage(&$res) {
		parent::TPage($res);
		global $SERVICE;
		global $CONFIG;
		$this->dir = $this->getRequestVar('d');
		if ($this->dir == '') {
			$this->dir = '000';
		}
		$index = YADS_INDEX . 'download_' . $this->dir . '.ini';
		$this->check_metadata($index, $this->dir);
		$this->conf = parse_ini_file($index, true);
		$this->_cacheKey[] = $this->dir;
	}

	function check_metadata($index, $category) {
		if (!file_exists($index)) {
			include EPS_DIR . 'setup' . DIR_SEP . 'buildIndex.php';
			buildIndex($index, YADS_DATA . DIR_SEP . $category, array (
					'.jad' 
			));
		}
	}

	function getName() {
		return 'DS Service';
	}

	function getTitle() {
		return 'DOwnload SErver';
	}
}
?>