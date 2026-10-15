<?php

namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class QueueController extends Controller
{
    public function actionRun($ukey = null)
    {
        if ($ukey != '4e68e277-afaa-47a5-b164-0383c59020c4') {
            throw new BadRequestHttpException('Bad Request');
        }
        $beforestat = null;
        set_time_limit(599);
        $app = Yii::$app;
        $queue = $app->queue;
        $queue_stat = $queue->statisticsProvider;
        try {
            $beforestat = [
                'waiting' => $app->formatter->asInteger($queue_stat->waitingCount),
                'delayed' => $app->formatter->asInteger($queue_stat->delayedCount),
                'reserved' => $app->formatter->asInteger($queue_stat->reservedCount),
                'done' => $app->formatter->asInteger($queue_stat->doneCount),
            ];
            $data = $queue->run(false);
            return $this->asJson([
                'success' => [
                    'data' => $data,
                    'before' => $beforestat,
                    'after' => [
                        'waiting' => $app->formatter->asInteger($queue_stat->waitingCount),
                        'delayed' => $app->formatter->asInteger($queue_stat->delayedCount),
                        'reserved' => $app->formatter->asInteger($queue_stat->reservedCount),
                        'done' => $app->formatter->asInteger($queue_stat->doneCount),
                    ],
                ]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            if ($beforestat) {
                return $this->asJson([
                    'failed' => [
                        'error' => $th->getMessage(),
                        'before' => $beforestat,
                        'after' => [
                            'waiting' => $app->formatter->asInteger($queue_stat->waitingCount),
                            'delayed' => $app->formatter->asInteger($queue_stat->delayedCount),
                            'reserved' => $app->formatter->asInteger($queue_stat->reservedCount),
                            'done' => $app->formatter->asInteger($queue_stat->doneCount),
                        ],
                    ]
                ]);
            }
        }
    }
}
