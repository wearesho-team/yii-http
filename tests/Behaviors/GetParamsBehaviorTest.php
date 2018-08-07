<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http;

use yii\base;

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
    protected $fakePanel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeId = mt_rand();
        $this->fakeName = 'fakeName';
    }

    public function testExample(): void
    {
        $examplePanel = new class(
            new Http\Request(),
            new Http\Response()
        ) extends Http\Panel
        {
            /** @var int */
            public $param;

            public function behaviors(): array
            {
                return [
                    'get' => [
                        'class' => Http\Behaviors\GetParamsBehavior::class,
                        'attributes' => ['param',],
                    ],
                ];
            }

            public function rules(): array
            {
                return [
                    ['param', 'safe'],
                    ['param', 'integer'],
                ];
            }

            /**
             * @return array
             * @throws \Exception
             */
            protected function generateResponse(): array
            {
                throw new \Exception("Method not implemented");
            }
        };

        $paramValue = mt_rand();
        $_GET['param'] = $paramValue;
        $examplePanel->trigger(Http\Panel::EVENT_BEFORE_VALIDATE);
        $this->assertEquals($paramValue, $examplePanel->param);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testNotPanel(): void
    {
        $this->fakePanel = new class extends base\Model
        {
            /** @var int */
            public $id;

            /** @var string */
            public $name;

            public function behaviors(): array
            {
                return [
                    'get' => [
                        'class' => Http\Behaviors\GetParamsBehavior::class,
                        'attributes' => [
                            'id',
                            'name'
                        ],
                    ],
                ];
            }

            public function rules(): array
            {
                return [
                    [
                        ['id', ],
                        'integer',
                    ],
                    [
                        ['name', ],
                        'string',
                    ],
                    [
                        ['id', 'name', ],
                        'required',
                    ]
                ];
            }

            protected function generateResponse(): array
            {
                return [
                    'paneMock' => 'testResult',
                ];
            }
        };

        $_GET['id'] = $this->fakeId;
        $_GET['name'] = $this->fakeName;

        $this->fakePanel->trigger(Http\Panel::EVENT_BEFORE_VALIDATE);
    }
}
