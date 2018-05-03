<?php

namespace Wearesho\Yii\Http\Panels;

use Wearesho\Yii\Http\Panel;
use yii\web\HttpException;

/**
 * Class UpgradeRequired
 * @package Wearesho\Yii\Http\Panels
 */
class UpgradeRequired extends Panel
{
    /** @var string */
    public $message = 'This method was deprecated. Contact developers for details.';

    /**
     * @throws HttpException
     * @return array
     */
    protected function generateResponse(): array
    {
        throw new HttpException($upgradeRequiredCode = 426, $this->message);
    }
}
