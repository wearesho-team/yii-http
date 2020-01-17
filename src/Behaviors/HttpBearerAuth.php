<?php declare(strict_types=1);

namespace Wearesho\Yii\Http\Behaviors;

use yii\filters\auth;

class HttpBearerAuth extends auth\HttpBearerAuth
{
    public function authenticate($user, $request, $response)
    {
        $response->headers->add('Vary', $this->header);
        return parent::authenticate($user, $request, $response);
    }
}
