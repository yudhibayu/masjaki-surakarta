<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <section class="startup-expertise-area pt-120 pt-md-80 pt-sm-60 pb-114 pb-md-76 pb-sm-56">
        <div class="container">
            <div class="row">
                <!-- Start Section title -->
                <div class="col-lg-8 m-auto text-center">
                    <div class="section-title-wrap layout--2">
                        <h1><?= Html::encode($this->title) ?></h1>

                        <div class="alert alert-danger">
                            <?= nl2br(Html::encode($message)) ?>
                        </div>

                        <p>
                        <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?>
                        </p>
                        <p>
                        <?= Yii::t('app', 'Please contact us if you think this is a server error. Thank you.') ?>
                            
                        </p>
                    </div>
                </div><!-- End Section title -->
            </div>
        </div>
    </section>
</div>
