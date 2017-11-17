<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function wes_install() {
	$cron = cron::byClassAndFunction('wes', 'daemon');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('wes');
		$cron->setFunction('daemon');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setTimeout(1440);
		$cron->setSchedule('* * * * *');
		$cron->save();
	}
	config::save('temporisation_lecture', 60, 'wes');
	$cron->start();
	jeedom::getApiKey('wes');
	if (config::byKey('api::wes::mode') == '') {
		config::save('api::wes::mode', 'enable');
	}
}

function wes_update() {
    $cron = cron::byClassAndFunction('wes', 'pull');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
    $cron = cron::byClassAndFunction('wes', 'cron');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
	$daemon = cron::byClassAndFunction('wes', 'daemon');
	if (!is_object($daemon)) {
		$daemon = new cron();
		$daemon->setClass('wes');
		$daemon->setFunction('daemon');
		$daemon->setEnable(1);
		$daemon->setDeamon(1);
		$daemon->setTimeout(1440);
		$daemon->setSchedule('* * * * *');
		$daemon->save();
		$daemon->start();
	}
	else
	{
		wes::deamon_start();
	}
	$FlagBasculeClass = false;
	foreach (eqLogic::byType('wes') as $eqLogic) {
		if ( $eqLogic->getConfiguration('type', '') == '' )
		{
			$eqLogic->setConfiguration('type', 'carte');
			$eqLogic->save();
			$FlagBasculeClass = true;
		}
		foreach (cmd::byEqLogicId($eqLogic->getId()) as $cmd) {
			if ( $cmd->getEqType() != 'wes')
			{
				$cmd->setEqType('wes');
				$cmd->save();
				$FlagBasculeClass = true;
			}
		}
	}
	foreach (eqLogic::byType('wes_bouton') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'bouton');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_temperature') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'temperature');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_relai') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'relai');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_compteur') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'compteur');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_teleinfo') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'teleinfo');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_analogique') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'analogique');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_pince') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'pince');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('wes_relai') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'relai');
		$SubeqLogic->setEqType_name('wes');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('wes');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	if ( $FlagBasculeClass )
	{
		log::add('wes','error',__('Les Urls de push ont changer. Pensez à les reconfigurer pour chaque carte.',__FILE__));
	}
	config::remove('subClass', 'wes');
	jeedom::getApiKey('wes');
	if (config::byKey('api::wes::mode') == '') {
		config::save('api::wes::mode', 'enable');
	}
	foreach (array("bouton", "relai", "compteur", "analogique", "teleinfo", "pince", "temperature") as $type)
	{
		if (file_exists (dirname(__FILE__) . '/../core/class/wes_'.$type.'.class.php'))
			unlink(dirname(__FILE__) . '/../core/class/wes_'.$type.'.class.php');
		if (file_exists (dirname(__FILE__) . '/../desktop/php/wes_'.$type.'.php'))
			unlink(dirname(__FILE__) . '/../desktop/php/wes_'.$type.'.php');
	}
}

function wes_remove() {
    $cron = cron::byClassAndFunction('wes', 'daemon');
    if (is_object($cron)) {
        $cron->remove();
    }
	config::remove('subClass', 'wes');
}
?>