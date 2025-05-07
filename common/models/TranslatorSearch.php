<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class TranslatorSearch extends Translator
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type', 'available_days', 'name', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Translator::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['available_days' => $this->available_days]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['created_at' => $this->created_at]);
        $query->andFilterWhere(['updated_at' => $this->updated_at]);

        return $dataProvider;
    }
} 