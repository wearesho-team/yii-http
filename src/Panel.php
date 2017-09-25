<?php


namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\ValidationException;
use yii\base\Model;
use yii\base\Controller as BaseController;
use yii\web\Request as WebRequest;
use yii\web\Response as WebResponse;

/**
 * Class Panel
 * @package Wearesho\Yii\Http
 */
abstract class Panel extends Model
{
    const EVENT_ON_EXCEPTION = 'onException';

    /** @var  WebRequest */
    protected $request;

    /** @var  Response */
    protected $response;

    /**
     * Panel constructor.
     *
     * @param WebRequest $request
     * @param WebResponse $response
     * @param array $config
     */
    public function __construct(WebRequest $request, WebResponse $response, array $config = [])
    {
        parent::__construct($config);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @throws ValidationException
     * @return WebResponse
     */
    public function getResponse(): WebResponse
    {
        $this->response->format = WebResponse::FORMAT_JSON;

        $this->load($this->request->getBodyParams());
        ValidationException::validateOrThrow($this);

        $this->trigger(BaseController::EVENT_BEFORE_ACTION);
        $this->response->data = $this->generateResponse();
        $this->trigger(BaseController::EVENT_AFTER_ACTION);

        return $this->response;
    }


    /**
     * @throws ValidationException
     * @return array
     */
    abstract protected function generateResponse(): array;
}