<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderdetail".
 *
 * @property int $no
 * @property int $Order_id
 *
 * @property Order $order
 */
class Orderdetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orderdetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no', 'Order_id'], 'required'],
            [['no', 'Order_id'], 'integer'],
            [['no', 'Order_id'], 'unique', 'targetAttribute' => ['no', 'Order_id']],
            [['Order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['Order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => Yii::t('app', 'No'),
            'Order_id' => Yii::t('app', 'Order ID'),
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'Order_id']);
    }

    /**
     * {@inheritdoc}
     * @return OderdetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OderdetailQuery(get_called_class());
    }

}
