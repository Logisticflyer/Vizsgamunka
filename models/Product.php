<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $typecode
 * @property string $barcode
 * @property int $brandid
 * @property int $Producttype_id
 *
 * @property Brand $brand
 * @property Price[] $prices
 * @property Producttype $producttype
 */
class Product extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'typecode', 'barcode', 'brandid', 'Producttype_id'], 'required'],
            [['brandid', 'Producttype_id'], 'integer'],
            [['name', 'typecode'], 'string', 'max' => 45],
            [['barcode'], 'string', 'max' => 13],
            [['brandid'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::class, 'targetAttribute' => ['brandid' => 'id']],
            [['Producttype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producttype::class, 'targetAttribute' => ['Producttype_id' => 'id']],
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
            'typecode' => Yii::t('app', 'Typecode'),
            'barcode' => Yii::t('app', 'Barcode'),
            'brandid' => Yii::t('app', 'Brandid'),
            'Producttype_id' => Yii::t('app', 'Producttype ID'),
        ];
    }

    /**
     * Gets query for [[Brand]].
     *
     * @return \yii\db\ActiveQuery|BrandQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brandid']);
    }

    /**
     * Gets query for [[Prices]].
     *
     * @return \yii\db\ActiveQuery|PriceQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::class, ['Product_id' => 'id']);
    }

    /**
     * Gets query for [[Producttype]].
     *
     * @return \yii\db\ActiveQuery|ProducttypeQuery
     */
    public function getProducttype()
    {
        return $this->hasOne(Producttype::class, ['id' => 'Producttype_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

}
