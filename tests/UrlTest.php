<?php

use Applistic\Http\Url;

/**
 * Tests for the Applistic\Http\Url class.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class UrlTest extends ApplisticHttpTestCase
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    public function testInstanciation()
    {
        $url = new Url();
        $this->assertTrue(is_a($url, "Applistic\Http\Url"));
    }

    public function testInstanciationWithUrl()
    {
        $url = new Url(self::FULL_URL);
        $this->assertTrue(is_a($url, "Applistic\Http\Url"));
    }

    public function testBuild()
    {
        $url = new Url(self::FULL_URL);
        $this->assertTrue($url->build() == self::FULL_URL);
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}