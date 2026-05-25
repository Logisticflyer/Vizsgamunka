<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property int $id
 * @property float $amount
 * @property string $startdate
 * @property string|null $enddate
 * @property string $currency
 * @property int $Product_id
 * @property int $Tax_id
 *
 * @property Product $product
 * @property Tax $tax
 */
class Price extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enddate'], 'default', 'value' => null],
            [['amount', 'startdate', 'currency', 'Product_id', 'Tax_id'], 'required'],
            [['amount'], 'number'],
            [['startdate', 'enddate'], 'safe'],
            [['Product_id', 'Tax_id'], 'integer'],
            [['currency'], 'string', 'max' => 3],
            [['Product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['Product_id' => 'id']],
            [['Tax_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tax::class, 'targetAttribute' => ['Tax_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'amount' => Yii::t('app', 'Amount'),
            'startdate' => Yii::t('app', 'Startdate'),
            'enddate' => Yii::t('app', 'Enddate'),
            'currency' => Yii::t('app', 'Currency'),
            'Product_id' => Yii::t('app', 'Product ID'),
            'Tax_id' => Yii::t('app', 'Tax ID'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'Product_id']);
    }

    /**
     * Gets query for [[Tax]].
     *
     * @return \yii\db\ActiveQuery|TaxQuery
     */
    public function getTax()
    {
        return $this->hasOne(Tax::class, ['id' => 'Tax_id']);
    }

    /**
     * {@inheritdoc}
     * @return PriceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PriceQuery(get_called_class());
    }

}
