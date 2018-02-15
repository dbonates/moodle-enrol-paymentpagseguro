<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin return file.
 *
 * @package    enrol_paymentpagseguro
 * @copyright  2018 Eduardo Kraus
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../config.php");
require(dirname(__FILE__) . '/classes/PagSeguroLibrary.php');

admin_externalpage_setup('returpaymentpagseguro');

$notification = optional_param('notificationType', false, PARAM_RAW);

if ($notification) {
    if ($notification == 'transaction') {
        proccess_transaction();
    } else if ($notification == 'preApproval') {
        proccess_preapproval();
    }
}

die('API de retorno do PagSeguro!');

function proccess_preapproval() {

    global $DB;

    $notificationcode = optional_param('notificationCode', false, PARAM_RAW);

    if (!$notificationcode) {
        die('API de retorno do PagSeguro!');
    }

    try {

        $response = PagSeguroNotificationService::checkPreApproval(
            PagSeguroConfig::getAccountCredentials(),
            $notificationcode
        );

        $paymentpagseguroid = str_replace("REF", "", $response->getReference());

        $paymentpagseguro = $DB->get_record('enrol_paymentpagseguro', array('id' => $paymentpagseguroid));
        if ($paymentpagseguro) {

            $plugininstance = $DB->get_record("enrol", array("id" => $paymentpagseguro->instanceid, "status" => 0));

            if ($response->getStatus()->getValue() == 3) {
                $plugin = enrol_get_plugin('paymentpagseguro');
                $plugin->enrol_user($plugininstance, $paymentpagseguro->userid,
                    $plugininstance->roleid, time(), 0, ENROL_USER_ACTIVE);
            } else if ($response->getStatus()->getValue() >= 5) {

                if ($plugininstance->customint2) {
                    $plugin = enrol_get_plugin('paymentpagseguro');
                    $plugin->enrol_user($plugininstance, $paymentpagseguro->userid,
                        $plugininstance->roleid, time(), 0, ENROL_USER_SUSPENDED);
                }
            }
        }

    } catch (Exception $e) {
        die($e->getMessage());
    }

}

function proccess_transaction() {

    global $DB;

    $notificationcode = optional_param('notificationCode', false, PARAM_RAW);

    if (!$notificationcode) {
        die('API de retorno do PagSeguro!');
    }

    try {
        $response = PagSeguroNotificationService::checkTransaction(
            PagSeguroConfig::getAccountCredentials(),
            $notificationcode
        );

        $paymentpagseguroid = str_replace("REF", "", $response->getReference());

        $paymentpagseguro = $DB->get_record('enrol_paymentpagseguro', array('id' => $paymentpagseguroid));
        if ($paymentpagseguro) {

            $plugininstance = $DB->get_record("enrol", array("id" => $paymentpagseguro->instanceid, "status" => 0));

            if ($response->getStatus()->getValue() == 3) {
                $plugin = enrol_get_plugin('paymentpagseguro');
                $plugin->enrol_user($plugininstance, $paymentpagseguro->userid,
                    $plugininstance->roleid, time(), 0, ENROL_USER_ACTIVE);
            } else if ($response->getStatus()->getValue() >= 5) {

                if ($plugininstance->customint2) {
                    $plugin = enrol_get_plugin('paymentpagseguro');
                    $plugin->enrol_user($plugininstance, $paymentpagseguro->userid,
                        $plugininstance->roleid, time(), 0, ENROL_USER_SUSPENDED);
                }
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
