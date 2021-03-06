<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\User;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;

/**
 * Class UserController
 * @package api\modules\v1\controllers
 */
class UserController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'api\modules\v1\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'only' => ['update'],
            'authMethods' => [
                'bearerAuth' => [
                    'class' => HttpBearerAuth::class,
                ],
                'paramAuth' => [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'auth_key', // This value can be changed to its own, for example hash
                ],
                'basicAuth' => [
                    'class' => HttpBasicAuth::class,
                    'auth' => function ($username, $password) {
                        return $this->processBasicAuth($username, $password);
                    }
                ],
            ]
        ];

        //Estava vindo no formato XML
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formatParam' => 'format',
            'formats' => [
                'application/json' => Response::FORMAT_JSON // FORMAT_XML
            ]
        ];

        return $behaviors;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function processBasicAuth($username, $password)
    {
        /** @var User $modelClass */
        $modelClass = $this->modelClass;
        /** @var User $user */
        if ($user = $modelClass::find()->where(['username' => $username])->one()) {
            return $user->validatePassword($password) ? $user : null;
        }
        return null;
    }
}
