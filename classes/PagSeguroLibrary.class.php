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

defined('MOODLE_INTERNAL') || die();

class PagSeguroLibrary {

    const VERSION = "2.6.0";
    public static $resources;
    public static $config;
    public static $log;
    private static $path;
    private static $library;
    private static $module_version;
    private static $cms_version;
    private static $php_version;

    public function __construct() {
        self::$path = (dirname(__FILE__));
        PagSeguroAutoloader::init();
        self::$resources = PagSeguroResources::init();
        self::$config = PagSeguroConfig::init();
        self::$log = LogPagSeguro::init();
    }

    public static function init() {
        if (self::$library == null) {
            require_once("loader" . DIRECTORY_SEPARATOR . "PagSeguroAutoLoader.class.php");
            self::verifyDependencies();
            self::$library = new PagSeguroLibrary();
        }
        return self::$library;
    }

    private static function verifyDependencies() {

        $dependencies = true;

        try {
            if (!function_exists('curl_init')) {
                $dependencies = false;
                throw new Exception('PagSeguroLibrary: cURL library is required.');
            }

            if (!class_exists('DOMDocument')) {
                $dependencies = false;
                throw new Exception('PagSeguroLibrary: DOM XML extension is required.');
            }

        } catch (Exception $e) {
            return $dependencies;
        }

        return $dependencies;

    }

    final public static function getVersion() {
        return self::VERSION;
    }

    final public static function getPath() {
        return self::$path;
    }

    final public static function getModuleVersion() {
        return self::$module_version;
    }

    final public static function setModuleVersion($version) {
        self::$module_version = $version;
    }

    final public static function getPHPVersion() {
        return self::$php_version = phpversion();
    }

    final public static function setPHPVersion($version) {
        self::$php_version = $version;
    }

    final public static function getCMSVersion() {
        return self::$cms_version;
    }

    final public static function setCMSVersion($version) {
        self::$cms_version = $version;
    }
}
