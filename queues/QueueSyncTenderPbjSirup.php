<?php

namespace app\queues;

use app\modules\rup\models\Pbj;

class QueueSyncTenderPbjSirup extends QueueSyncPbjSirup
{
    public $pbj_type = Pbj::TIPE_PBJ_TENDER;
}
