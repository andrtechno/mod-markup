<?php

namespace panix\mod\markup\models;


use yii\base\Model;
use panix\engine\data\ActiveDataProvider;
use panix\mod\markup\models\Discount;


class MarkupSearch extends Markup
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
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
        $query = Markup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        //$query->andFilterWhere(['like', "DATE(CONVERT_TZ('start_date', 'UTC', '".\Yii::$app->timezone."'))", strtotime($this->start_date).' 23:59:59']);

        $query->andFilterWhere(['like', 'name', $this->name]);
        // $query->andFilterWhere(['like', 'start_date', strtotime($this->start_date)]);

        foreach (['created_at', 'updated_at'] as $date) {
            $query->andFilterWhere(['>=', $date, $this->{$date} ? strtotime($this->{$date} . ' 00:00:00') : null]);
            $query->andFilterWhere(['<=', $date, $this->{$date} ? strtotime($this->{$date} . ' 23:59:59') : null]);
        }

        return $dataProvider;
    }

}
