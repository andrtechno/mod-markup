<?php

namespace panix\mod\markup\models;

use yii\db\ActiveQuery;

class MarkupQuery extends ActiveQuery {

    public function published($state = 1) {
        return $this->andWhere(['switch' => $state]);
    }

    public function applyDate() {
        return $this->andWhere(['<=', 'start_date', time()])->andWhere(['>=', 'end_date', time()]);
    }

}
