<?php
require 'vendor/autoload.php'; // если yii2 basic
echo Yii::$app->getSecurity()->generatePasswordHash('password123');
