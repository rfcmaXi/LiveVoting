<?php
/**
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 *         User starts here. Use a RewriteRule to access this page a bit simpler
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once 'dir.php';

use LiveVoting\Conf\xlvoConf;
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
$puk = false;
/**
 * @var xlvoVotingConfig|null $xlvoVotingConfig
 */
$xlvoVotingConfig = NULL;
if ($existing_pin) {
	CookieManager::setCookiePIN($existing_pin);

	$puk = trim($_REQUEST['puk'], '/');
	if ($puk) {
		CookieManager::setCookiePUK($puk);
	}

	$voting = trim($_REQUEST['voting'], '/');
	if ($voting) {
		CookieManager::setCookieVoting($voting);
	}

	$ppt = trim($_REQUEST['ppt'], '/');
	if ($ppt) {
		CookieManager::setCookiePpt($ppt);
	}

	$xlvoVotingConfig = xlvoVotingConfig::where([ 'pin' => $existing_pin ])->first();
}
global $DIC;
$ilCtrl = $DIC->ctrl();
$ilCtrl->initBaseClass(ilUIPluginRouterGUI::class);
$ilCtrl->setTargetScript(xlvoConf::getFullApiURL());
if ($xlvoVotingConfig !== NULL) {
	$ilCtrl->setParameterByClass(xlvoPlayerGUI::class, "ref_id", current(ilObject2::_getAllReferences($xlvoVotingConfig->getObjId())));
}
$ilCtrl->redirectByClass(array(
	ilUIPluginRouterGUI::class,
	xlvoPlayerGUI::class,
), xlvoPlayerGUI::CMD_START_PRESENTER);
