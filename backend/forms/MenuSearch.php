<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Core\Menu;

/**
 * MenuSearch represents the model behind the search form of `core\entities\Core\Menu`.
 */
class MenuSearch extends Model
{
    public $id;
    public $name;
    public $slug;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Menu::find()->andWhere(['=', 'depth', 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['lft' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
