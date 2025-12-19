<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user = false;

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Email обязателен'],
            ['email', 'email', 'message' => 'Неверный формат'],
            ['password', 'required', 'message' => 'Пароль обязателен'],
            ['password', 'string', 'min' => 6, 'message' => 'Минимум 6 символов'],
        ];
    }

    /**
     * Проверка пароля
     */
    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = $this->getUser();
        if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
            $this->addError($attribute, 'Неверный email или пароль');
        }
    }

    /**
     * Получение пользователя по email
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->where(['email' => $this->email])->one();
        }
        return $this->_user;
    }
}

