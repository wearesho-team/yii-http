<?php

namespace Wearesho\Yii\Http;

use yii\db\Connection;

/**
 * Class Form
 * @package Wearesho\Yii\Http
 */
abstract class Form extends Panel
{
    /** @var  Connection */
    protected $connection;

    /**
     * Form constructor.
     * @param Request $request
     * @param Response $response
     * @param Connection $connection
     * @param array $config
     */
    public function __construct(
        Request $request,
        Response $response,
        Connection $connection,
        array $config = []
    ) {
        parent::__construct($request, $response, $config);

        $this->connection = $connection;
    }

    /**
     * @return Response
     * @throws \Throwable
     */
    public function getResponse(): Response
    {
        return $this->connection->transaction(function () {
            return parent::getResponse();
        });
    }
}
