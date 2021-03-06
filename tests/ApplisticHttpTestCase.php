<?php

$vendorPath = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."vendor";
require_once($vendorPath.DIRECTORY_SEPARATOR."autoload.php");

/**
 * Base test case for Applistic\Http
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class ApplisticHttpTestCase extends PHPUnit_Framework_TestCase
{
// ===== CONSTANTS =============================================================

    const FULL_URL = "http://username:password@hostname:8080/path/subpath?arg1=value1&arg2=value2#anchor";

// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================
// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}