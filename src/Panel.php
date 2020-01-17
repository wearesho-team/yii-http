<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\base;

/**
 * Class Panel
 * @package Wearesho\Yii\Http
 *
 * @property Action $action
 */
abstract class Panel extends base\Model
{
    /** @var  Request */
    protected $request;

    /** @var  Response */
    protected $response;

    /** @var Action|null */
    private $action;

    /**
     * Panel constructor.
     *
     * @param Request $request
     * @param Response $response
     * @param array $config
     */
    public function __construct(
        Request $request,
        Response $response,
        array $config = []
    ) {
        parent::__construct($config);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Response
     * @throws HttpValidationException
     * @throws base\InvalidConfigException
     */
    public function getResponse(): Response
    {
        $this->load($this->request->getBodyParams());
        /** @noinspection PhpUnhandledExceptionInspection HttpValidationException thrown */
        HttpValidationException::validateOrThrow($this);

        $this->response->data = $this->beforeAction()
            ? $this->afterAction($this->generateResponse())
            : [];

        return $this->response;
    }

    public function setAction(Action $action): void
    {
        $this->action = $action;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function beforeAction(): bool
    {
        $event = new base\ActionEvent($this->action);
        $this->trigger(base\Controller::EVENT_BEFORE_ACTION, $event);
        return $event->isValid;
    }

    public function afterAction(array $data)
    {
        $event = new base\ActionEvent($this->action);
        $event->result = $data;
        $this->trigger(base\Controller::EVENT_AFTER_ACTION, $event);
        return $event->result;
    }

    /**
     * @return array
     * @throws HttpValidationException
     */
    abstract protected function generateResponse(): array;
}
