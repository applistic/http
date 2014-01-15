<?php

use Applistic\Http\Request;

/**
 * Tests for the Applistic\Http\Request class.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class RequestTest extends ApplisticHttpTestCase
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
        $request = new Request();
        $this->assertTrue(is_a($request, "Applistic\Http\Request"));
    }

    public function testInstanciationWithUrl()
    {
        $request = new Request(self::FULL_URL);

        $this->assertTrue(is_a($request, "Applistic\Http\Request"));
    }

    public function testUrlClass()
    {
        $request = new Request(self::FULL_URL);

        $this->assertTrue(is_a($request->url(), "Applistic\Http\Url"));
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}