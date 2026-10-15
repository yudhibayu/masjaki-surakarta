<?php

namespace app\modules\api\v1\models;

use app\modules\construction\models\ConstructionVacancies as ModelsConstructionVacancies;
use app\modules\construction\models\RegistrantVacancies;

class ConstructionVacancies extends ModelsConstructionVacancies
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

    function getRegistrant()
    {
        return $this->hasMany(RegistrantVacancies::class, ['id_vacancies' => 'id']);
    }
}
