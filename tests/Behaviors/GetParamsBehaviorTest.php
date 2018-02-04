<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http\Behaviors\GetParamsBehavior;
use Wearesho\Yii\Http\Panel;
use Wearesho\Yii\Http\Request;
use Wearesho\Yii\Http\Response;
use Wearesho\Yii\Http\Tests\AbstractTestCase;

/**
 * Class GetParamsBehaviorTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 * @internal
 */
class GetParamsBehaviorTest extends AbstractTestCase
{
    public function testLoading()
    {
        $panelMockIntance = new class(new Request(), new Response()) extends Panel
        {
            public $param;

            public function behaviors()
            {
                return [
                    'get' => [
                        'class' => GetParamsBehavior::class,
                        'attributes' => ['param',],
                    ],
                ];
            }

            public function rules()
            {
                return [
                    ['param', 'safe'],
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

        $panelMockIntance->trigger(Panel::EVENT_BEFORE_VALIDATE);
        $this->assertEquals($paramValue, $panelMockIntance->param);
    }
}