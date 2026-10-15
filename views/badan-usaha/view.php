<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
use newerton\fancybox3\FancyBox;
/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\business-entity */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rup', 'Construction Work'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>
<div class="container mt-4 mb-4">
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
            <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Yii::t('app', 'List of Construction Work') ?></h3>
            <?php echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'class' => 'breadcrumb mb-0',
                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
            ]);
            ?>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12 detail-data p-5 pb-0">
                <div class="photo-container mb-4">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'photo',
                                'format' => 'raw',
                                'label' => false,
                                'value' => function ($model) {
                                    $photoPath = ['/business-entity/business-entity/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                    if (!empty($model->file_extension) && in_array(strtolower($model->file_extension), ['png', 'jpg', 'jpeg'])) {
                                        $photoUrl = Url::to($photoPath);
                                        return Html::tag(
                                            'div',
                                            Html::a(
                                                Html::img($photoUrl, ['class' => 'img-fluid photo-image']),
                                                $photoUrl,
                                                [
                                                    'class' => 'badge',
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "image-fancybox",
                                                    ],
                                                ]
                                            ),
                                            ['class' => 'photo-container']
                                        );
                                    } else {
                                        return " ";
                                    }
                                },
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="mb-3">
                    <div class="card-headerr fw-bold">
                        <h4><i class='bx bx-info-circle'></i> <?= Html::encode($model->company_name) ?> </h4>
                    </div>
                    <div class="badanusaha-view p-3">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <?= DetailView::widget([
                                    'model' => $model,
                                    'options' => ['class' => 'table table-responsive table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                    'attributes' => [
                                        'npwp',
                                        'email:email',
                                        'username',
                                        'phone_number',
                                        'pkp_confirmation_number',
                                        'website',
                                        'fax',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3" style="width: auto;">
                    <div class="card-headerr">
                        <h4><i class='bx bx-map-pin'></i> <?= Yii::t('app', 'Project Location') ?></h4>
                    </div>
                    <div class="badanusaha-view p-3">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                'attributes' => [
                                    'country',
                                    'province',
                                    'regency',
                                    'subdistrict',
                                    'ward',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 rekomen-data">
                <div class="card-rekomen p-3 m-3 mt-5 rounded-lg overflow-hidden">
                    <div class="card-header">
                        <h4 class=""><?= Yii::t('app', 'Other Companies') ?></h4>
                    </div>
                    <div class="badanusaha-view">
                        <?php foreach ($modelbadanusaha as $badanusaha) { ?>
                            <p><i class="icon-rekomen tf-icons bx bx-store"></i> <a class="rekomen-link" href="/badan-usaha/view?id=<?= Yii::$app->hasher->encode($badanusaha['id']) ?>"><?= $badanusaha['username'] ?></a></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .photo-container {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .photo-image {
        border-radius: 12px;
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
        max-width: auto;
        height: 250px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .photo-image:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .badanusaha-view p {
        display: flex;
        align-items: center;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 18px;
    }

    .card-rekomen {
        background-color: #F8F9FC;
        border-radius: 20px;
    }

    .icon-rekomen {
        font-size: 24px;
        color: var(--accent-color);
        margin-right: 8px;
    }

    .card-rekomen .card-body {
        background-color: #ffffff;
    }

    .card-rekomen .card {
        border: 1px solid #98211B;
    }

    .card-rekomen .card-header h3 {
        margin: 0;
    }

    .card-rekomen .badanusaha-view {
        padding: 1rem;
    }

    .card-rekomen .card {
        border-radius: 0.5rem;
    }

    .rekomen-link {
        color: black;
        transition: color 0.3s ease, text-decoration 0.3s ease;
    }

    .rekomen-link:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }
</style>