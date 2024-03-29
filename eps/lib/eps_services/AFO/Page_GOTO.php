<?php
/**
 * EIROCA PORTAL SYSTEM - Framework to build Mobile site - GPL3 - See licence in eps.php
 *
 * @author Enrico Croce & Simona Burzio (staff@eiroca.net)
 * @copyright Copyright (C) 2003-2015 eIrOcA - Enrico Croce & Simona Burzio
 * @version 0.5.2
 * @link http://www.eiroca.net
 */
require_once (SRV_DIR . 'AFO' . DIR_SEP . 'AFOPage.php');

class Page extends AFOPage {
	var $cid;
	var $count;
	var $aforismi;
	var $caption;
	var $description;

	function Page(&$res) {
		parent::AFOPage($res);
		$this->cid = $this->getRequestVar('c');
		$this->_cacheKey[] = $this->cid;
	}

	function setup(&$cached) {
		if (!$cached) {
			global $CONFIG;
			global $SERVICE;
			$path = $SERVICE->findData($this->conf['category_' . $this->cid]['path']);
			$afo = parse_ini_file($path, true);
			$this->count = (int)$afo['main']['count'];
			$this->aforismi = range(1, $this->count);
			$this->caption = $afo['main']['caption'];
			$this->description = $afo['main']['description'];
			foreach ($this->aforismi as $id) {
				$this->register_link('AFO_afo_' . $id, new TLink('voce #' . $id, '#service#?s=AFO&amp;a=SHOW&amp;t=' . $this->kind_id . '&amp;c=' . $this->cid . '&amp;i=' . $id));
			}
		}
	}

	function getTemplate() {
		return 'goto';
	}

	function getFooterLinks() {
		return array (
				'AFO_home' 
		);
	}
}
?>
