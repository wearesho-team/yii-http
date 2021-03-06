<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Panel;
use yii\base\ArrayableTrait;

/**
 * Class GetPanel
 * @package Wearesho\Yii\Http
 */
class GetPanel extends Panel
{
    use RestPanelTrait, ResponseConfigurable;

    /**
     * @var array
     * @see ArrayableTrait::toArray()
     */
    public $fields = [];

    /**
     * @var array
     * @see ArrayableTrait::toArray()
     */
    public $expand = [];

    /**
     * @var bool
     */
    public $recursive = true;

    /**
     * @return array
     */
    protected function generateResponse(): array
    {
        return $this->convert($this->record);
    }
}
