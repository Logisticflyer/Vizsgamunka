<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Producttype]].
 *
 * @see Producttype
 */
class ProducttypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Producttype[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Producttype|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
