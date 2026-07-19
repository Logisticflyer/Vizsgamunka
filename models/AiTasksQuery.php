<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AiTasks]].
 *
 * @see AiTasks
 */
class AiTasksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AiTasks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AiTasks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
