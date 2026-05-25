<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "producttype".
 *
 * @property int $id
 * @property string $name
 *
 * @property Product[] $products
 */
class Producttype extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'producttype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['Producttype_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProducttypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProducttypeQuery(get_called_class());
    }

}
