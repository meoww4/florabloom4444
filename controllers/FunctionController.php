<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii\widgets\ActiveForm;

class FunctionController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            return parent::beforeAction($action);
        } catch (ForbiddenHttpException $e) {
            return $this->sendError(403, 'Нет доступа');
        } catch (UnauthorizedHttpException $e) {
            return $this->sendError(401, 'Не авторизован');
        }
    }

    /**
     * Отправка успешного ответа
     */
    public function send(int $statusCode, $data)
    {
        Yii::$app->response->statusCode = $statusCode;

        // Если это объект ActiveRecord или массив, преобразуем в массив
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        Yii::$app->response->data = $data;
        return Yii::$app->response;
    }

    /**
     * Отправка ошибки
     */
    public function sendError(int $statusCode = 400, string $message = 'Ошибка')
    {
        Yii::$app->response->statusCode = $statusCode;
        Yii::$app->response->data = [
            'error' => [
                'message' => $message
            ]
        ];
        return Yii::$app->response;
    }

    /**
     * Валидация модели и возврат ошибок
     */
    public function validation($model)
    {
        $errors = ActiveForm::validate($model);

        // Преобразуем объект ошибок в читаемый массив
        $errorsArray = [];
        foreach ($errors as $attr => $msgs) {
            $errorsArray[$attr] = $msgs;
        }

        return $this->send(422, [
            'error' => [
                'code' => 422,
                'message' => 'Ошибка валидации',
                'errors' => $errorsArray,
            ]
        ]);
    }
}

