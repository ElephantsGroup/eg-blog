<?php

namespace elephantsGroup\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use elephantsGroup\blog\models\BlogTranslation;

/**
 * BlogTranslationSearch represents the model behind the search form about `elephantsGroup\blog\models\BlogTranslation`.
 */
class BlogTranslationSearch extends BlogTranslation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blog_id', 'version'], 'integer'],
            [['language', 'title', 'subtitle', 'intro', 'description'], 'safe'],
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
        $query = BlogTranslation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'blog_id' => $this->blog_id,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
