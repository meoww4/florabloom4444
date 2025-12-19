<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

class Product extends ActiveRecord
{
    public $imageFile;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public static function tableName()
    {
        return 'products';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'category_id', 'price', 'imageFile', 'image'],
            self::SCENARIO_UPDATE => ['title', 'category_id', 'price', 'imageFile', 'image'],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'category_id', 'price'], 'required'],
            ['price', 'number'],
            ['category_id', 'integer'],
            [['title', 'image'], 'string', 'max' => 255],

            // imageFile обязателен только при создании
            ['imageFile', 'file',
                'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'Файл слишком большой (макс 2 МБ)',
                'wrongExtension' => 'Разрешены только png, jpg, jpeg',
                'on' => self::SCENARIO_CREATE,
            ],

            // imageFile необязателен при редактировании
            ['imageFile', 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'Файл слишком большой (макс 2 МБ)',
                'wrongExtension' => 'Разрешены только png, jpg, jpeg',
                'on' => self::SCENARIO_UPDATE,
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) return false;

        if ($this->imageFile instanceof UploadedFile) {
            $dir = Yii::getAlias('@webroot/uploads');
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $fileName = uniqid() . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($dir . '/' . $fileName);
            $this->image = 'uploads/' . $fileName;
        }

        return true;
    }
}

