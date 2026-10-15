<div class="container">
    <section class="card pt-0">
        <div class="card-header pt-4 pb-4">
            <h1>PERMOHONAN AKUN TENAGA KERJA</h1>
        </div>
        <div class="card-body">
            <div class="tbl-account-request-update">

                <?= $this->render('_form_labor', [
                    'model'         => $model,
                    'dropdown_data' => $dropdown_data,
                    'url_prev'      => $url_prev,
                ]) ?>

            </div>
        </div>
    </section>
</div>