<?php

namespace elephantsGroup\blog\models;

/**
 * This is the ActiveQuery class for [[CategoryTranslation]].
 *
 * @see CategoryTranslation
 */
class BlogCategoryTranslationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryTranslation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryTranslation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
