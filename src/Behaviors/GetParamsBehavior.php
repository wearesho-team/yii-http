<?php

namespace Wearesho\Yii\Http\Behaviors;

use Wearesho\Yii\Http\Panel;
use yii\base;
use yii\web;

/**
 * Class GetParamsBehavior
 * @package api\modules\staff\behaviors
 * @property Panel $owner
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
            Panel::EVENT_BEFORE_VALIDATE => 'loadParams',
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function loadParams()
    {
        if (!$this->owner instanceof Panel) {
            throw new base\InvalidConfigException(static::class . " may be append only to " . Panel::class);
        }
        foreach ((array)$this->attributes as $attribute) {
            $value = $this->request->get($attribute);
            $this->owner->setAttributes([$attribute => $value]);
        }
    }
}
