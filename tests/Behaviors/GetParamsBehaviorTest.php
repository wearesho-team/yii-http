<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http;

use yii\base\Model;

/**
 * Class GetParamsBehaviorTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 * @internal
 */
class GetParamsBehaviorTest extends Http\Tests\AbstractTestCase
{
    /** @var int */
    protected $fakeId;

    /** @var string */
    protected $fakeName;

    /** @var Http\Tests\Mocks\PanelMock */
    protected $panel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeId = mt_rand();
        $this->fakeName = 'fakeName';
    }

    public function testCorrectData(): void
    {
        $this->panel = new Http\Tests\Mocks\PanelMock(
            new Http\Request(),
            new Http\Response()
        );

        $_GET['id'] = $this->fakeId;
        $_GET['name'] = $this->fakeName;

        $this->panel->trigger(Http\Panel::EVENT_BEFORE_VALIDATE);

        $this->assertEquals($this->fakeId, $this->panel->id);
        $this->assertEquals($this->fakeName, $this->panel->name);
    }
}
