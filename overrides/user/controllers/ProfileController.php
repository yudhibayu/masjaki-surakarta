<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\controllers;

use yii\web\Controller;
use infinindotech\user\Finder;
use app\components\Theming;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use infinindotech\user\controllers\ProfileController as Testing;

/**
 * ProfileController shows users profiles.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ProfileController extends Testing
{

    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => \Yii::$app->user->getId()]);
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('show', [
            'profile' => $profile,
        ]);
    }
}
