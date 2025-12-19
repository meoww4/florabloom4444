<?php
namespace app\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['user_id', 'address', 'payment_type', 'total_price'], 'required'],
            [['user_id'], 'integer'],
            [['total_price'], 'number'],
            [['payment_type'], 'in', 'range' => ['online','cash']],
            [['status'], 'default', 'value' => 'Новый'],
            
            // Валидатор существующего пользователя
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    // Связь с пользователем
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    // Связь с товарами в заказе
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
}

