<?php


namespace Wearesho\Yii\Http;

use yii\base\Controller;

use yii\db\Connection;

use yii\web\Request;
use yii\web\Response;

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
    public function __construct(Request $request, Response $response, Connection $connection, array $config = [])
    {
        parent::__construct($request, $response, $config);

        $this->connection = $connection;
    }

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->on(Controller::EVENT_BEFORE_ACTION, [$this, 'begin']);
        $this->on(Controller::EVENT_AFTER_ACTION, [$this, 'commit']);
    }

    /**
     * @return Response
     * @throws \Throwable
     */
    public function getResponse(): Response
    {
        try {
            return parent::getResponse();
        } catch (\Throwable $exception) {
            $this->rollBack();
            throw $exception;
        }
    }

    /**
     * @return static
     */
    protected function begin()
    {
        $transaction = $this->connection->getTransaction();
        if (!$transaction || !$transaction->isActive) {
            $this->connection->beginTransaction();
        }

        return $this;
    }

    /**
     * @return static
     */
    protected function commit()
    {
        $transaction = $this->connection->getTransaction();
        if ($transaction && $transaction->isActive) {
            $transaction->commit();
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function rollBack()
    {
        $transaction = $this->connection->getTransaction();
        if ($transaction && $transaction->isActive) {
            $transaction->rollBack();
        }

        return $this;
    }
}