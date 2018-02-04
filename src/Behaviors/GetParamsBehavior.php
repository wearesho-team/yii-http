<?php

namespace Wearesho\Yii\Http\Behaviors;

use Wearesho\Yii\Http\Panel;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Class GetParamsBehavior
 * @package api\modules\staff\behaviors
 * @property Panel $owner
 */
class GetParamsBehavior extends Behavior
{
    /** @var string|string[] */
    public $attributes;

    /** @var Request */
    protected $request;

    /**
     * GetParamsBehavior constructor.
     * @param Request $request
     * @param array $config
     */
    public function __construct(Request $request, array $config = [])
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
     * @throws InvalidConfigException
     */
    public function loadParams()
    {
        if (!$this->owner instanceof Panel) {
            throw new InvalidConfigException(static::class . " may be append only to " . Panel::class);
        }
        foreach ((array)$this->attributes as $attribute) {
            $value = $this->request->get($attribute);
            $this->owner->setAttributes([$attribute => $value]);
        }
    }
}
