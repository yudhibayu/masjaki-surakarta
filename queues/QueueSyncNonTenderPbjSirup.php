<?php

namespace app\queues;

use app\modules\rup\models\Pbj;

class QueueSyncNonTenderPbjSirup extends QueueSyncPbjSirup
{
    public $pbj_type = Pbj::TIPE_PBJ_NONTENDER;
}
