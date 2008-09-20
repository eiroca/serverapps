<?php
/**
 * EIROCA PORTAL SYSTEM - Framework to build Mobile site - GPL3 - See licence in eps.inc
 * @author Enrico Croce & Simona Burzio (staff@eiroca.net)
 * @copyright Copyright (C) 2003-2008 eIrOcA - Enrico Croce & Simona Burzio
 * @version 0.5
 * @link http://www.eiroca.net
 */
class Service_NEWS extends TService {
  function Service_NEWS() {
    $this->namespace = "NEWS";
    $this->default_action = "START";
    $this->default_page = "MAIN";
  }
}
?>