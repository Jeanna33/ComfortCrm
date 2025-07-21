<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель событий календаря
 *
 * @property int $id
 * @property string $title Название события
 * @property string|null $description Описание
 * @property string $start Дата и время начала (формат Y-m-d H:i:s)
 * @property string|null $end Дата и время окончания
 * @property bool $all_day Флаг "весь день"
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 */
class Event extends ActiveRecord
{
    public static function tableName()
    {
        return 'Event';
    }

    public function rules()
    {
        return [
            [['title', 'start_event'], 'required'],
            [['description'], 'string'],
            [['start_event', 'end_event'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['all_day'], 'boolean'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->start_event = Yii::$app->formatter->asDatetime($this->start_event, 'php:Y-m-d H:i:s');

        if ($this->end_event) {
            $this->end_event = Yii::$app->formatter->asDatetime($this->end_event, 'php:Y-m-d H:i:s');
        }

        return true;
    }

    /**
     * Форматирование данных для FullCalendar
     */
    public function toFullCalendarEvent()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start_event,
            'end' => $this->end_event,
            'allDay' => $this->all_day,
            'description' => $this->description,
            // Дополнительные параметры при необходимости
        ];
    }
}