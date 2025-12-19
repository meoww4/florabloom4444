<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $password_repeat; // Для подтверждения пароля при регистрации

    /**
     * Название таблицы
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'password'], 'required'],
            ['email', 'email', 'message' => 'Неверный формат'],
            ['email', 'unique', 'message' => 'Этот email уже зарегистрирован'],
            ['password', 'string', 'min' => 6, 'message' => 'Минимум 6 символов'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают"],
        ];
    }

    /**
     * Атрибуты для вывода в API
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password']); // Не отдаём пароль
        return $fields;
    }

    /**
     * Хеширование пароля перед сохранением
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) return false;

        // Хешируем пароль, если это новая запись или изменён пароль
        if ($this->isNewRecord || $this->isAttributeChanged('password')) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        }
        return true;
    }

    /**
     * Проверка пароля
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    // Методы IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where(['token' => $token])->one();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
}

