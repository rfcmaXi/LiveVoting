<?php
/**
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 *         User starts here. Use a RewriteRule to access this page a bit simpler
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once 'dir.php';

use LiveVoting\Api\xlvoApi;
use LiveVoting\Pin\xlvoPin;
use LiveVoting\Context\cookie\CookieManager;
use LiveVoting\Context\InitialisationManager;
use LiveVoting\Context\xlvoContext;

InitialisationManager::startMinimal();
CookieManager::setContext(xlvoContext::CONTEXT_PIN);
CookieManager::resetCookiePIN();
CookieManager::resetCookiePUK();
CookieManager::resetCookieVoting();
CookieManager::resetCookiePpt();

$existing_pin = trim($_REQUEST['pin'], '/');
if ($existing_pin !== "") {
	CookieManager::setCookiePIN($existing_pin);
	$api = new xlvoApi(new xlvoPin($existing_pin), $_GET['token']);
	if ($_GET['type']) {
		$api->setType($_GET['type']);
	}
	$api->send();
}