<?php

namespace app\modules\api\v1\models;

use app\modules\construction\models\RegistrantTraining;
use app\modules\construction\models\WorkTraining as ModelsWorkTraining;

class WorkTraining extends ModelsWorkTraining
{
    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return [
            'registrant',
        ];
    }

    function getRegistrant() {
        return $this->hasMany(RegistrantTraining::class, ['id_training' => 'id']);
    }
}
