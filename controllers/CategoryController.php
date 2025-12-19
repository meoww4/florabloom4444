<?php
namespace app\controllers;

use Yii;
use app\models\Category;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;

class CategoryController extends FunctionController
{
    public $modelClass = Category::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index'], // список категорий доступен всем
        ];

        return $behaviors;
    }

    // 🔹 Получение списка категорий
    public function actionIndex()
    {
        $categories = Category::find()->all();
        return $this->send(200, $categories);
    }

    // 🔹 Создание категории (только администратор)
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        if (!$user) {
            return $this->send(401, [
                'error' => ['message' => 'Пользователь не авторизован']
            ]);
        }

        if (!$user->administrator) {
            return $this->send(403, [
                'error' => ['message' => 'Недостаточно прав доступа']
            ]);
        }

        $category = new Category();
        $category->load(Yii::$app->request->getBodyParams(), '');

        if (!$category->validate()) {
            return $this->send(422, [
                'error' => [
                    'message' => 'Обязательное поле',
                    'errors' => $category->getErrors(),
                ]
            ]);
        }

        $category->save(false);

        return $this->send(201, $category);
    }

    // 🔹 Обновление категории (только администратор)
    public function actionUpdate($id)
    {
        $category = Category::findOne($id);

        if (!$category) {
            return $this->send(404, [
                'error' => ['message' => 'Категория не найдена']
            ]);
        }

        $user = Yii::$app->user->identity;

        if (!$user) {
            return $this->send(401, [
                'error' => ['message' => 'Пользователь не авторизован']
            ]);
        }

        if (!$user->administrator) {
            return $this->send(403, [
                'error' => ['message' => 'Недостаточно прав доступа']
            ]);
        }

        $category->load(Yii::$app->request->getBodyParams(), '');

        if (!$category->validate()) {
            return $this->send(422, [
                'error' => [
                    'message' => 'Ошибка валидации',
                    'errors' => $category->getErrors(),
                ]
            ]);
        }

        $category->save(false);

        return $this->send(200, $category);
    }

    // 🔹 Удаление категории (только администратор)
    public function actionDelete($id)
    {
        $category = Category::findOne($id);

        if (!$category) {
            return $this->send(404, [
                'error' => ['message' => 'Категория не найдена']
            ]);
        }

        $user = Yii::$app->user->identity;

        if (!$user) {
            return $this->send(401, [
                'error' => ['message' => 'Пользователь не авторизован']
            ]);
        }

        if (!$user->administrator) {
            return $this->send(403, [
                'error' => ['message' => 'Недостаточно прав доступа']
            ]);
        }

        $category->delete();

        return $this->send(200, [
            'message' => 'Категория успешно удалена'
        ]);
    }
}

