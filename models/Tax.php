<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax".
 *
 * @property int $id
 * @property float $value
 *
 * @property Price[] $prices
 */
class Tax extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * Gets query for [[Prices]].
     *
     * @return \yii\db\ActiveQuery|PriceQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::class, ['Tax_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TaxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaxQuery(get_called_class());
    }

}
