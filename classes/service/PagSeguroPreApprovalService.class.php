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
 * @author    André da Silva Medeiros <andre@swdesign.net.br>
 * @copyright 2007-2014 PagSeguro Internet Ltda.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Encapsulates web service calls regarding PagSeguro payment requests
 */

defined('MOODLE_INTERNAL') || die();

class PagSeguroPreApprovalService {

    /**
     *
     */
    const SERVICE_NAME = 'preApproval';
    /**
     * @var
     */
    private static $service;
    /**
     * @var
     */
    private static $connectionData;

    /**
     * @param PagSeguroConnectionData $connectionData
     * @return string
     */
    private static function buildPreApprovalUrl(PagSeguroConnectionData $connectionData) {
        return $connectionData->getServiceUrl();
    }

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $code
     * @return string
     */
    private static function buildPreApprovalRequestUrl(PagSeguroConnectionData $connectionData, $code) {
        return $connectionData->getPaymentUrl() . $connectionData->getResource('requestUrl') . "?code=$code";
    }

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $code
     * @return string
     */
    private static function buildPreApprovalPaymentUrl(PagSeguroConnectionData $connectionData) {
        return $connectionData->getWebserviceUrl() . $connectionData->getResource('paymentUrl');
    }

    /**
     * @param PagSeguroConnectionData $connectionData
     * @param $code
     * @return string
     */
    private static function buildPreApprovalCancelUrl(PagSeguroConnectionData $connectionData, $code) {
        $credentialsArray = $connectionData->getCredentials()->getAttributesMap();
        return $connectionData->getWebserviceUrl() . $connectionData->getResource('cancelUrl') .
            "$code?" . $connectionData->getCredentialsUrlQuery();
    }

    /**
     * @param PagSeguroCredentials $credentials
     * @param PagSeguroPaymentRequest $request
     * @return null|PagSeguroParserData
     * @throws Exception
     * @throws PagSeguroServiceException
     */
    public static function createPreApprovalRequest(
        PagSeguroCredentials $credentials,
        PagSeguroPreApprovalRequest $request
    ) {

        LogPagSeguro::info(
            "PagSeguroPreApprovalService.PreApprovalRequest(" .
            $request->toString() . ") - begin"
        );

        self::$connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);
        $data = array_merge(
            self::$connectionData->getCredentials()->getAttributesMap(),
            PagSeguroPreApprovalParser::getData($request)
        );

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->post(
                self::buildPreApprovalUrl(self::$connectionData),
                $data,
                self::$connectionData->getServiceTimeout(),
                self::$connectionData->getCharset()
            );

            self::$service = "PreApprovalRequest";
            return self::getResult($connection);

        } catch (PagSeguroServiceException $err) {

            LogPagSeguro::error("PagSeguroServiceException: " . $err->getMessage());

            throw $err;

        } catch (Exception $err) {

            LogPagSeguro::error("Exception: " . $err->getMessage());

            throw $err;
        }

    }

    /**
     * @param PagSeguroCredentials $credentials
     * @param PagSeguroPreApprovalCharge $charge
     * @return null|PagSeguroParserData
     * @throws Exception
     * @throws PagSeguroServiceException
     */
    public static function paymentCharge(
        PagSeguroCredentials $credentials,
        PagSeguroPreApprovalCharge $charge
    ) {

        LogPagSeguro::info(
            "PagSeguroPreApprovalService.PreApprovalPaymentCharge(" .
            $charge->toString() . ") - begin"
        );

        self::$connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);
        $data = array_merge(
            self::$connectionData->getCredentials()->getAttributesMap(),
            PagSeguroPreApprovalParser::getCharge($charge)
        );

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->post(
                self::buildPreApprovalPaymentUrl(self::$connectionData),
                $data,
                self::$connectionData->getServiceTimeout(),
                self::$connectionData->getCharset()
            );

            self::$service = "PreApprovalPaymentCharge";
            return self::getResult($connection);

        } catch (PagSeguroServiceException $err) {

            LogPagSeguro::error("PagSeguroServiceException: " . $err->getMessage());

            throw $err;

        } catch (Exception $err) {

            LogPagSeguro::error("Exception: " . $err->getMessage());

            throw $err;
        }
    }

    /**
     * @param PagSeguroCredentials $credentials
     * @param $code
     * @return null|PagSeguroParserData
     * @throws Exception
     * @throws PagSeguroServiceException
     */
    public static function cancelPreApproval(PagSeguroCredentials $credentials, $code) {

        $log['text'] = "PagSeguroNotificationService.PreApprovalCancel($code) - begin";
        LogPagSeguro::info($log['text']);

        self::$connectionData = new PagSeguroConnectionData($credentials, self::SERVICE_NAME);

        try {
            $connection = new PagSeguroHttpConnection();
            $connection->get(
                self::buildPreApprovalCancelUrl(
                    self::$connectionData,
                    $code
                ),
                self::$connectionData->getServiceTimeout(),
                self::$connectionData->getCharset()
            );

            self::$service = "PreApprovalCancel";
            return self::getResult($connection, $code);

        } catch (PagSeguroServiceException $err) {

            LogPagSeguro::error("PagSeguroServiceException: " . $err->getMessage());

            throw $err;

        } catch (Exception $err) {

            LogPagSeguro::error("Exception: " . $err->getMessage());

            throw $err;
        }
    }

    /**
     * @param $connection
     * @param null $code
     * @return null|PagSeguroParserData
     * @throws PagSeguroServiceException
     */
    private static function getResult($connection, $code = null) {

        $httpStatus = new PagSeguroHttpStatus($connection->getStatus());
        $response = $connection->getResponse();

        switch ($httpStatus->getType()) {
            case 'OK':
                switch (self::$service) {
                    case "PreApprovalRequest":

                        $response = PagSeguroPreApprovalParser::readSuccessXml($response);

                        $result = array('code' => $response->getCode(),
                            'cancelUrl' => self::buildPreApprovalCancelUrl(
                                self::$connectionData,
                                $response->getCode()
                            ),
                            'checkoutUrl' => self::buildPreApprovalRequestUrl(
                                self::$connectionData,
                                $response->getCode()
                            ));

                        break;
                    case "PreApprovalCancel":
                        $result = PagSeguroPreApprovalParser::readCancelXml($response);
                        break;
                    case "PreApprovalPaymentCharge":
                        $result = PagSeguroPreApprovalParser::readTransactionXml($response);
                        break;
                }

                if (is_null($code) && self::$service == "PreApprovalRequest") {
                    $log['text'] = sprintf(
                        "PagSeguroPreApprovalService.%s(" . $response->toString() . ") - end ",
                        self::$service
                    );
                    LogPagSeguro::info($log['text'] . ")");
                } else {
                    $log['text'] = sprintf("PagSeguroPreApprovalService.%s($code) - end ", self::$service);
                    LogPagSeguro::info($log['text']);
                }

                break;
            case 'BAD_REQUEST':

                $errors = PagSeguroServiceParser::readErrors($response);
                $errors = new PagSeguroServiceException($httpStatus, $errors);

                $log['text'] = sprintf("PagSeguroPreApprovalService.%s($code) - error ", self::$service);
                LogPagSeguro::error($log['text'] . $errors->getOneLineMessage());

                throw $errors;
                break;
            default:

                $errors = new PagSeguroServiceException($httpStatus);

                $log['text'] = sprintf("PagSeguroPreApprovalService.%s($code) - error ", self::$service);
                LogPagSeguro::error($log['text'] . $errors->getOneLineMessage());

                throw $errors;
                break;
        }
        return isset($result) ? $result : null;
    }
}
