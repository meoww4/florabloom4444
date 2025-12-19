<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\controllers\FunctionController;
use yii\web\UploadedFile;
use yii\filters\auth\HttpBearerAuth;

class ProductController extends FunctionController
{
    public $modelClass = Product::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index', 'view'], // публичные методы
        ];
        return $behaviors;
    }

    // Список товаров
    public function actionIndex()
    {
        $products = Product::find()->all();
        return $this->send(200, $products);
    }

    // Просмотр товара
    public function actionView($id)
    {
        $product = Product::findOne($id);
        if (!$product) return $this->sendError(404, 'Товар не найден');
        return $this->send(200, $product);
    }

    // Создание товара (только админ)
    public function actionCreate()
{
    $user = Yii::$app->user->identity;
    if (!$user) return $this->sendError(401, 'Не авторизован');
    if (!$user->administrator) return $this->sendError(403, 'Нет доступа');

    $model = new Product();
    $model->scenario = Product::SCENARIO_CREATE;

    // Загружаем текстовые данные
    $model->load(Yii::$app->request->post(), ''); 

    // Загружаем файл
    $model->imageFile = UploadedFile::getInstanceByName('imageFile');

    if (!$model->imageFile) {
        return $this->send(422, [
            'error' => [
                'message' => 'Не загружен файл изображения',
                'post' => Yii::$app->request->post()
            ]
        ]);
    }

    // Валидация модели
    if (!$model->validate()) {
        return $this->send(422, [
            'error' => [
                'message' => 'Ошибка валидации',
                'errors' => $model->getErrors(),
                'imageFile' => $model->imageFile ? $model->imageFile->name : null,
                'post' => Yii::$app->request->post()
            ]
        ]);
    }

    // Сохраняем
    $model->save(false);

    return $this->send(201, $model);
}



    // Редактирование товара (только админ)
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        if (!$user) return $this->sendError(401, 'Не авторизован');

        $model = Product::findOne($id);
        if (!$model) return $this->sendError(404, 'Товар не найден');
        if (!$user->administrator) return $this->sendError(403, 'Нет доступа');

        $model->scenario = Product::SCENARIO_UPDATE;

        $model->load(Yii::$app->request->post(), 'Product');

        // Файл необязателен при обновлении
        $imageFile = UploadedFile::getInstance($model, 'imageFile');
        if ($imageFile) $model->imageFile = $imageFile;

        if (!$model->validate()) return $this->validation($model);

        $model->save(false);
        return $this->send(200, $model);
    }

    // Удаление товара (только админ)
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        if (!$user) return $this->sendError(401, 'Не авторизован');
        if (!$user->administrator) return $this->sendError(403, 'Нет доступа');

        $product = Product::findOne($id);
        if (!$product) return $this->sendError(404, 'Товар не найден');

        $product->delete();
        return $this->send(200, ['message' => 'Товар удалён']);
    }
}

