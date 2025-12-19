<?php
namespace app\controllers;

use Yii;
use app\models\User;
use app\models\LoginForm;
use app\controllers\FunctionController;

class UserController extends FunctionController
{
    public $modelClass = 'app\models\User';

    /**
     * Регистрация пользователя
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();
        $user = new User();
        $user->load($data, '');

        // Валидация данных
        if (!$user->validate()) {
            return $this->send(422, $user->errors);
        }

        // Проверка существующего e-mail
        if (User::find()->where(['email' => $user->email])->exists()) {
            return $this->send(409, ['error' => ['code' => 409, 'message' => 'Email already exists']]);
        }

        // Хеширование пароля и генерация токена
        $user->password = Yii::$app->security->generatePasswordHash($user->password);
        $user->token = Yii::$app->security->generateRandomString(32);

        $user->save(false);

        return $this->send(201, $user);
    }

    /**
     * Авторизация пользователя (логин)
     */
    public function actionLogin()
    {
        $data = Yii::$app->request->getBodyParams();
        $loginForm = new LoginForm();
        $loginForm->load($data, '');

        // Валидация email и пароля через rules LoginForm
        if (!$loginForm->validate()) {
            return $this->send(422, $loginForm->errors);
        }

        $user = $loginForm->getUser();

        if (!$user) {
            return $this->send(401, [
                'error' => [
                    'code' => 401,
                    'message' => 'Неверный email или пароль'
                ]
            ]);
        }

        // Генерация нового токена
        $user->token = Yii::$app->security->generateRandomString(32);
        $user->save(false);

        return $this->send(200, ['data' => ['token' => $user->token]]);
    }

    /**
     * Получение информации о текущем пользователе
     */
    public function actionMe()
    {
        $authHeader = Yii::$app->request->headers->get('Authorization');
        if (!$authHeader) {
            return $this->send(401, ['error' => 'Token required']);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $user = User::find()->where(['token' => $token])->one();

        if (!$user) {
            return $this->send(401, ['error' => 'Invalid token']);
        }

        return $this->send(200, $user);
    }
}

