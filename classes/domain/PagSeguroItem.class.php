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
 * Represents a product/item in a transaction
 */

defined('MOODLE_INTERNAL') || die();

class PagSeguroItem {

    /**
     * Product identifier, such as SKU
     */
    private $id;

    /**
     * Product description
     */
    private $description;

    /**
     * Quantity
     */
    private $quantity;

    /**
     * Product unit price
     */
    private $amount;

    /**
     * Single unit weight, in grams
     */
    private $weight;

    /**
     * Single unit shipping cost
     */
    private $shippingCost;

    /**
     *
     * Initializes a new instance of the Item class
     * @param array $data
     */
    public function __construct(array $data = null) {
        if ($data) {
            if (isset($data['id'])) {
                $this->setId($data['id']);
            }
            if (isset($data['description'])) {
                $this->setDescription($data['description']);
            }
            if (isset($data['quantity'])) {
                $this->setQuantity($data['quantity']);
            }
            if (isset($data['amount'])) {
                $this->setAmount($data['amount']);
            }
            if (isset($data['weight'])) {
                $this->setWeight($data['weight']);
            }
            if (isset($data['shippingCost'])) {
                $this->setShippingCost($data['shippingCost']);
            }
        }
    }

    /**
     * @return integer the product identifier
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the product identifier
     * @param String $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return String the product description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the product description
     * @param String $description
     */
    public function setDescription($description) {
        $this->description = PagSeguroHelper::formatString($description, 255);
    }

    /**
     * @return integer the quantity
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Sets the quantity
     * @param String $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * @return the unit amount for this item
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * sets the unit amount fot this item
     * @param String $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return float the weight
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Sets the single unit weight
     * @param String $weight
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * @return float the unit shipping cost for this item
     */
    public function getShippingCost() {
        return $this->shippingCost;
    }

    /**
     * Sets the unit shipping cost for this item
     * @param String $shippingCost
     */
    public function setShippingCost($shippingCost) {
        $this->shippingCost = $shippingCost;
    }
}
