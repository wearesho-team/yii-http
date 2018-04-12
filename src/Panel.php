<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\base\Model;
use yii\base\Controller as BaseController;

/**
 * Class Panel
 * @package Wearesho\Yii\Http
 */
abstract class Panel extends Model
{
    /** @var  Request */
    protected $request;

    /** @var  Response */
    protected $response;

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
     * @throws HttpValidationException
     * @return Response
     */
    public function getResponse(): Response
    {
        $this->response->format = Response::FORMAT_JSON;

        $this->load($this->request->getBodyParams());
        HttpValidationException::validateOrThrow($this);

        $this->trigger(BaseController::EVENT_BEFORE_ACTION);
        $this->response->data = $this->generateResponse();
        $this->trigger(BaseController::EVENT_AFTER_ACTION);

        return $this->response;
    }


    /**
     * @throws HttpValidationException
     * @return array
     */
    abstract protected function generateResponse(): array;
}
