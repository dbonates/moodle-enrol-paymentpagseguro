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
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    PagSeguro Internet Ltda.
 * @copyright 2007-2014 PagSeguro Internet Ltda.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Encapsulates web service calls regarding PagSeguro notifications
 */

defined('MOODLE_INTERNAL') || die();

class PagSeguroNotificationService {

    /**
     *
     */
    const SERVICE_NAME = 'notificationService';

    /**
     * @var
     */
    private static $service;

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $notificationCode
     * @return string
     */
    private static function buildTransactionNotificationUrl(
        PagSeguroConnectionData $connectionData,
        $notificationCode
    ) {
        $url = $connectionData->getServiceUrl();
        return "{$url}/{$notificationCode}/?" . $connectionData->getCredentialsUrlQuery();
    }

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $notificationCode
     * @return string
     */
    private static function buildAuthorizationNotificationUrl(
        PagSeguroConnectionData $connectionData,
        $notificationCode
    ) {
        $url = $connectionData->getWebserviceUrl() . '/' . $connectionData->getResource('applicationPath');
        return "{$url}/{$notificationCode}/?" . $connectionData->getCredentialsUrlQuery();
    }

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $preApprovalCode
     * @return string
     */
    private static function buildPreApprovalNotificationUrl(
        PagSeguroConnectionData $connectionData,
        $preApprovalCode
    ) {
        $url = $connectionData->getWebserviceUrl() . '/' . $connectionData->getResource('preApprovalPath');
        return "{$url}/{$preApprovalCode}/?" . $connectionData->getCredentialsUrlQuery();
    }

    /**
     * Returns a transaction from a notification code
     *
     * @param PagSeguroCredentials $credentials
     * @param String $notificationCode
     * @throws PagSeguroServiceException
     * @throws Exception
     * @return PagSeguroTransaction
     * @see PagSeguroTransaction
     */
    public static function checkTransaction(PagSeguroCredentials $credentials, $notificationCode) {

        LogPagSeguro::info(
            "PagSeguroNotificationService.CheckTransaction(notificationCode=$notificationCode) - begin"
        );
        $connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->get(
                self::buildTransactionNotificationUrl($connectionData, $notificationCode),
                $connectionData->getServiceTimeout(),
                $connectionData->getCharset()
            );

            self::$service = "CheckTransaction";
            return self::getResult($connection, $notificationCode);

        } catch (PagSeguroServiceException $err) {
            throw $err;
        } catch (Exception $err) {
            LogPagSeguro::error("Exception: " . $err->getMessage());
            throw $err;
        }
    }

    /**
     * Returns a authorization from a notification code
     * @param PagSeguroCredentials $credentials
     * @param $notificationCode
     * @return bool|mixed|string
     * @throws Exception
     * @throws PagSeguroServiceException
     */
    public static function checkAuthorization(PagSeguroCredentials $credentials, $notificationCode) {

        LogPagSeguro::info(
            "PagSeguroNotificationService.CheckAuthorization(notificationCode=$notificationCode) - begin"
        );

        $connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->get(
                self::buildAuthorizationNotificationUrl($connectionData, $notificationCode),
                $connectionData->getServiceTimeout(),
                $connectionData->getCharset()
            );

            self::$service = "CheckAuthorization";
            return self::getResult($connection, $notificationCode);

        } catch (PagSeguroServiceException $err) {
            throw $err;
        } catch (Exception $err) {
            LogPagSeguro::error("Exception: " . $err->getMessage());
            throw $err;
        }
    }

    /**
     * Returns a pre approval from a notification code
     * @param PagSeguroCredentials $credentials
     * @param $notificationCode
     * @return bool|mixed|string
     * @throws Exception
     * @throws PagSeguroServiceException
     */
    public static function checkPreApproval(PagSeguroCredentials $credentials, $notificationCode) {

        LogPagSeguro::info(
            "PagSeguroNotificationService.CheckPreApproval(notificationCode=$notificationCode) - begin"
        );

        $connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->get(
                self::buildPreApprovalNotificationUrl($connectionData, $notificationCode),
                $connectionData->getServiceTimeout(),
                $connectionData->getCharset()
            );

            self::$service = "CheckPreApproval";
            return self::getResult($connection, $notificationCode);

        } catch (PagSeguroServiceException $err) {
            throw $err;
        } catch (Exception $err) {
            LogPagSeguro::error("Exception: " . $err->getMessage());
            throw $err;
        }
    }

    /**
     * @param PagSeguroConnectionData $connection
     * @param $code
     * @return null|PagSeguroAuthorization|PagSeguroParserData|PagSeguroTransaction
     * @throws PagSeguroServiceException
     */
    private static function getResult($connection, $code) {
        $httpStatus = new PagSeguroHttpStatus($connection->getStatus());
        $response = $connection->getResponse();

        switch ($httpStatus->getType()) {
            case 'OK':
                switch (self::$service) {
                    case "CheckPreApproval":
                        $response = PagSeguroPreApprovalParser::readPreApproval($response);
                        break;
                    case "CheckAuthorization":
                        $response = PagSeguroAuthorizationParser::readAuthorization($response);
                        break;
                    case "CheckTransaction":
                        $response = PagSeguroTransactionParser::readTransaction($response);
                        break;
                }

                $log['text'] = sprintf(
                    "PagSeguroNotificationService.%s(notificationCode=$code) - end ",
                    self::$service
                );
                $log['action'] = $response->toString();
                LogPagSeguro::info($log['text'] . $log['action'] . ")");

                break;
            case 'BAD_REQUEST':

                $errors = PagSeguroServiceParser::readErrors($connection->getResponse());
                $errors = new PagSeguroServiceException($httpStatus, $errors);

                $log['text'] = sprintf(
                    "PagSeguroNotificationService.%s(notificationCode=$code) - error ",
                    self::$service
                );
                LogPagSeguro::error($log['text'] . $errors->getOneLineMessage());

                throw $errors;

                break;
            default:
                $errors = new PagSeguroServiceException($httpStatus);

                $log['text'] = sprintf(
                    "PagSeguroNotificationService.%s(notificationCode=$code) - error ",
                    self::$service
                );
                LogPagSeguro::info($log['text'] . $errors->getOneLineMessage());

                throw $errors;
                break;
        }
        return isset($response) ? $response : null;
    }
}
