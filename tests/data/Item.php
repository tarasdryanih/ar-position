<?php

namespace yii2tech\tests\unit\ar\position\data;

use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property boolean $position
 */
class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }
}