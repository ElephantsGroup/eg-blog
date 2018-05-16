<?php

namespace elephantsGroup\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use elephantsGroup\blog\models\BlogCategory;

/**
 * BlogCategorySearch represents the model behind the search form about `elephantsGroup\blog\models\BlogCategory`.
 */
class BlogCategorySearch extends BlogCategory
{
    public $title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'title'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     *  TODO: add title to search
     */
    public function search($params)
    {
        $module = \Yii::$app->getModule('base');

        $query = BlogCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'status',
                /*'title' => [
                    'asc' => [NewsCategoryTranslation::tableName() . '.title' => SORT_ASC],
                    'desc' => [NewsCategoryTranslation::tableName() . '.title' => SORT_DESC],
                    'label' => $module::t('Title'),
                    'default' => SORT_ASC
                ],*/
            ]
        ]);

        if (!($this->load($params) && $this->validate()))
        {
            $query->joinWith(['translations']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            //'title' => ($this->translationByLang ? $this->translationByLang->title : ''),
        ]);

        /*$query->joinWith(['translationByLang' => function ($q) {
            $q->where(NewsCategoryTranslation::tableName() . '.title LIKE "%' . $this->title . '%"');
        }]);*/

        return $dataProvider;
    }
}
