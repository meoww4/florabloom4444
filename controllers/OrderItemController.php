<?php
namespace app\controllers;

use Yii;
use app\models\OrderItem;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;

class OrderItemController extends FunctionController
{
    public $modelClass = 'app\models\OrderItem';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    // Создание позиции заказа
    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();
        $item = new OrderItem();
        $item->load($data, '');

        if (!$item->validate()) {
            return $this->validation($item);
        }

        $item->save();
        return $this->send(201, $item);
    }

    // Получение позиции заказа по ID
    public function actionView($id)
    {
        $item = OrderItem::findOne($id);
        if (!$item) {
            return $this->send(404, ['error'=>['code'=>404,'message'=>'Order item not found']]);
        }

        return $this->send(200, $item);
    }

    // Редактирование позиции заказа
    public function actionUpdate($id)
    {
        $item = OrderItem::findOne($id);
        if (!$item) {
            return $this->send(404, ['error'=>['code'=>404,'message'=>'Order item not found']]);
        }

        $data = Yii::$app->request->getBodyParams();
        $item->load($data, '');

        if (!$item->validate()) {
            return $this->validation($item);
        }

        $item->save();
        return $this->send(200, $item);
    }

    // Удаление позиции заказа
    public function actionDelete($id)
    {
        $item = OrderItem::findOne($id);
        if (!$item) {
            return $this->send(404, ['error'=>['code'=>404,'message'=>'Order item not found']]);
        }

        $item->delete();
        return $this->send(200, ['message'=>'Order item deleted']);
    }
}
