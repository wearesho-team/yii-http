<?php

namespace Wearesho\Yii\Http\Behaviors;

use yii\base;
use yii\web;

/**
 * Class GetParamsBehavior
 * @package api\modules\staff\behaviors
 * @property base\Model $owner
 */
class GetParamsBehavior extends base\Behavior
{
    /** @var string|string[] */
    public $attributes;

    /** @var web\Request */
    protected $request;

    /**
     * GetParamsBehavior constructor.
     * @param web\Request $request
     * @param array $config
     */
    public function __construct(web\Request $request, array $config = [])
    {
        parent::__construct($config);
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            base\Model::EVENT_BEFORE_VALIDATE => 'loadParams',
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function loadParams()
    {
        if (!$this->owner instanceof base\Model) {
            throw new base\InvalidConfigException(static::class . " may be append only to " . base\Model::class);
        }
        foreach ((array)$this->attributes as $attribute) {
            $value = $this->request->get($attribute);
            $this->owner->setAttributes([$attribute => $value]);
        }
    }
}
