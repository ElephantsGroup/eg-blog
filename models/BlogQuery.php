<?php

namespace elephantsGroup\blog\models;

/**
 * This is the ActiveQuery class for [[Blog]].
 *
 * @see Blog
 */
class BlogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Blog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Blog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function archived()
    {
        return $this->andWhere(['status' => Blog::$_STATUS_ARCHIVED]);
    }

    public function confirmed()
    {
        return $this->andWhere(['status' => Blog::$_STATUS_CONFIRMED]);
    }
}
