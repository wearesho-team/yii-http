<?php

namespace Wearesho\Yii\Http\Tests\Mocks;

use yii\web\IdentityInterface;

/**
 * Class UserMock
 * @package Wearesho\Yii\Http\Tests\Mocks
 */
class UserMock implements IdentityInterface
{
    /** @var int */
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function findIdentity($id)
    {
        return new static($id);
    }

    /**
     * @param mixed $token
     * @param null  $type
     *
     * @return void|IdentityInterface
     * @throws \Exception
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new \Exception('Method not implemented!');
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function getAuthKey()
    {
        throw new \Exception('Method not implemented!');
    }

    /**
     * @param string $authKey
     *
     * @return bool|void
     * @throws \Exception
     */
    public function validateAuthKey($authKey)
    {
        throw new \Exception('Method not implemented!');
    }
}
