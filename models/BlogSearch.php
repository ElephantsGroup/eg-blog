<?php

namespace elephantsGroup\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogQuery;

/**
 * BlogSearch represents the model behind the search form about `elephantsGroup\blog\models\Blog`.
 */
class BlogSearch extends Blog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'views', 'author_id', 'status'], 'integer'],
            [['update_time', 'creation_time', 'archive_time', 'Publish_time', 'thumb'], 'safe'],
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
     */
    public function search($params)
    {
        $module = \Yii::$app->getModule('base');

        $query = Blog::find()->notEdited();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'version',
                'category_id',
                'views',
                'author_id',
                'status',
                'title' => [
                    'asc' => [BlogTranslation::tableName() . '.title' => SORT_ASC],
                    'desc' => [BlogTranslation::tableName() . '.title' => SORT_DESC],
                    'label' => $module::t('Title'),
                    'default' => SORT_ASC
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate()))
        {
            $query->joinWith(['translations']);
            return $dataProvider;
        }

        if($this->id)
            $query->andFilterWhere(['id' => $this->id]);
        if($this->category_id)
            $query->andFilterWhere(['category_id' => $this->category_id]);
        if($this->views)
            $query->andFilterWhere(['views' => $this->views]);
        if($this->author_id)
            $query->andFilterWhere(['author_id' => $this->author_id]);
        if($this->status)
            $query->andFilterWhere(['status' => $this->status]);
        /*$query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            //'creation_time' => $this->creation_time,
            //'update_time' => $this->update_time,
            //'archive_time' => $this->archive_time,
            'views' => $this->views,
            'author_id' => $this->author_id,
            'status' => $this->status,
        ]);*/

        if($this->title)
            $query->joinWith(['translationByLang' => function ($q) {
                $q->where(BlogTranslation::tableName() . '.title LIKE "%' . $this->title . '%"');
            }]);

        return $dataProvider;
    }
}
