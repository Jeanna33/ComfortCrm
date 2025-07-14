<?php

namespace app\models;

use yii\db\ActiveRecord;

class Location extends ActiveRecord
{
    public static function tableName()
    {
        return 'location';
    }

    public function rules()
    {
        return [
            [['latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['user_id'], 'integer'],
            [['created_at'], 'integer'],
        ];
    }
}
