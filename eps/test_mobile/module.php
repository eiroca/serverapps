<?php
/**
 * EIROCA PORTAL SYSTEM - Framework to build Mobile site - GPL3 - See licence in eps.inc
 * @author Enrico Croce & Simona Burzio (staff@eiroca.net)
 * @copyright Copyright (C) 2003-2010 eIrOcA - Enrico Croce & Simona Burzio
 * @version 0.5.1
 * @link http://www.eiroca.net
 */
require_once ("../lib/eps/eps.inc");
$CONFIG["URL_portal"] = "module.php";
$CONFIG["URL_service"] = "module.php";
$CONFIG["URL_download"] = "download.php";
initContext("mobile");
$CONTEXT->process(null, KEY_DEF_SERVICE, "WEB", "Mozilla/4.0");
?>
