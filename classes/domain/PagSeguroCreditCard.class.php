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
 * CreditCard information
 */

defined('MOODLE_INTERNAL') || die();

class PagSeguroCreditCard {

    /**
     * Card Number
     */
    private $number;

    /**
     * Card CVV
     */
    private $cvv;

    /**
     * Card Expiration Month
     */
    private $expirationMonth;

    /**
     * Card Expiration Year
     */
    private $expirationYear;

    /**
     * Initializes a new instance of the PagSeguroCreditCard class
     * @param array $data
     */
    public function __construct(array $data = null) {

        if ($data) {
            if (isset($data['number'])) {
                $this->setNumber($data['number']);
            }
            if (isset($data['cvv'])) {
                $this->setCvv($data['cvv']);
            }
            if (isset($data['expirationMonth'])) {
                $this->setExpirationMonth($data['expirationMonth']);
            }
            if (isset($data['expirationYear'])) {
                $this->setExpirationYear($data['expirationYear']);
            }
        }
    }

    /**
     * Sets the card number
     * @param int $number
     */
    public function setNumber($number) {
        $this->number = $number;
    }

    /**
     * @return int the card number
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Sets the card cvv
     * @param int $cvv
     */
    public function setCvv($cvv) {
        $this->cvv = $cvv;
    }

    /**
     * @return int the card cvv
     */
    public function getCvv() {
        return $this->cvv;
    }

    /**
     * Sets the card expirationMonth
     * @param int $expirationMonth
     */
    public function setExpirationMonth($expirationMonth) {
        $this->expirationMonth = $expirationMonth;
    }

    /**
     * @return int expirationMonth from credit card
     */
    public function getExpirationMonth() {
        return $this->expirationMonth;
    }

    /**
     * Sets the card expirationYear
     * @param int $expirationYear
     */
    public function setExpirationYear($expirationYear) {
        $this->expirationYear = $expirationYear;
    }

    /**
     * @return int expirationYear from credit card
     */
    public function getExpirationYear() {
        return $this->expirationYear;
    }
}
