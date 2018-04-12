<?php

namespace Wearesho\Yii\Http\Tests\Common;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Http;

/**
 * Class RequestTest
 * @package Wearesho\Yii\Http\Tests\Common
 */
class RequestTest extends TestCase
{
    /** @var Http\Request */
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new class extends Http\Request
        {
            /** @var string */
            protected $referrer;

            public function getReferrer()
            {
                return $this->referrer;
            }

            public function setReferrer(string $referrer = null): Http\Request
            {
                $this->referrer = $referrer;
                return $this;
            }
        };
    }

    public function testEmptyReferrer(): void
    {
        $this->request->referrer = null;
        $this->assertEquals('', $this->request->referrerBase);
    }

    public function testNotFullReferrer(): void
    {
        $this->request->referrer = '//google.com/path';
        $this->assertEquals('', $this->request->referrerBase);
    }

    public function testValidReferrer(): void
    {
        $this->request->referrer = 'https://google.com/some/path';
        $this->assertEquals('https://google.com', $this->request->referrerBase);
    }

    public function testReferrerWithPort(): void
    {
        $this->request->referrer = 'http://localhost:8080/some/path-in';
        $this->assertEquals('http://localhost:8080', $this->request->referrerBase);
    }
}
