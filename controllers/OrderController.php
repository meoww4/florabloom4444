<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\OrderItem;
use app\models\Product;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;

class OrderController extends FunctionController
{
    public $modelClass = Order::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    // Создание заказа
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return $this->send(401, ['error' => ['message' => 'Не авторизован']]);
        }

        $data = Yii::$app->request->post();
        $errors = [];

        // Проверка товаров
        if (!isset($data['products']) || !is_array($data['products']) || count($data['products']) === 0) {
            $errors['products'] = ['Не указан товар'];
        } else {
            $productErrors = [];
            foreach ($data['products'] as $index => $item) {
                $itemErrors = [];
                if (!isset($item['id']) || empty($item['id'])) {
                    $itemErrors[] = 'Не указан товар';
                } elseif (!Product::findOne($item['id'])) {
                    $itemErrors[] = 'Товар не найден';
                }

                if (!isset($item['count']) || !is_numeric($item['count']) || $item['count'] < 1) {
                    $itemErrors[] = 'Неверное количество';
                }

                if (!empty($itemErrors)) {
                    $productErrors[$index] = $itemErrors;
                }
            }
            if (!empty($productErrors)) {
                $errors['products'] = $productErrors;
            }
        }

        // Проверка адреса
        if (empty($data['address'])) {
            $errors['address'] = ['Обязательное поле'];
        }

        // Проверка способа оплаты
        if (empty($data['payment_type']) || !in_array($data['payment_type'], ['online', 'cash'])) {
            $errors['payment_type'] = ['Допустимые значения: online, cash'];
        }

        // Если есть ошибки — возвращаем 422
        if (!empty($errors)) {
            return $this->send(422, ['error' => $errors]);
        }

        // Создание заказа
        $order = new Order();
        $order->user_id = $user->id;
        $order->address = $data['address'];
        $order->payment_type = $data['payment_type'];
        $order->total_price = 0;
        $order->status = 'Новый';
        $order->save(false);

        foreach ($data['products'] as $item) {
            $product = Product::findOne($item['id']);
            if (!$product) continue;

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->count = $item['count'] ?? 1;
            $orderItem->price = $product->price;
            $orderItem->save(false);

            $order->total_price += $product->price * $orderItem->count;
        }

        $order->save(false);

        return $this->send(201, $order);
    }

    // Получение списка заказов пользователя
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return $this->send(401, ['error' => ['message' => 'Не авторизован']]);
        }

        $orders = Order::find()
            ->where(['user_id' => $user->id])
            ->with('orderItems.product')
            ->all();

        return $this->send(200, $orders);
    }

    // Просмотр конкретного заказа по ID
    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return $this->send(401, ['error' => ['message' => 'Не авторизован']]);
        }

        $order = Order::find()
            ->where(['id' => $id, 'user_id' => $user->id])
            ->with('orderItems.product')
            ->one();

        if (!$order) {
            return $this->send(404, ['error' => ['message' => 'Заказ не найден']]);
        }

        return $this->send(200, $order);
    }
}

