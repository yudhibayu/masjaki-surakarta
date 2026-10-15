<?php

namespace app\modules\report\controllers;

use app\modules\construction\models\ConstructionAccident;
use app\modules\construction\models\ConstructionAccidentFile;
use app\modules\construction\models\ConstructionAccidentSearch;
use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\overrides\user\models\User;
use yii\web\ServerErrorHttpException;
use app\modules\construction\models\Labor;
use app\modules\construction\models\LaborSearch;
use app\modules\referencesMasjaki\models\Education;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\construction\models\ConstructionWork;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\construction\models\RegistrantTraining;
use app\modules\construction\models\RegistrantVacancies;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\RegistrantTrainingSearch;
use app\modules\construction\models\RegistrantVacanciesSearch;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * ReportController implements the CRUD actions for Labor model.
 */
class ReportController extends Controller
{
    private $dropdown_data;
    private $sess;
    public $password;

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Team', 'Badan Usaha'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->sess = Yii::$app->session;
        //monggo
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        //monggo


    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->populate_dropdown();
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = Yii::$app->session['report'];
            if(isset(Yii::$app->session['report']['page']))
            $_GET['page'] = Yii::$app->session['report']['page'];
        } else {
            Yii::$app->session['report'] = $params;
        }

        $searchModel = new ConstructionAccidentSearch();
        $dataProvider = $searchModel->searchReport($params);
     
        Url::remember();

        $content = $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dropdown_data' => $this->dropdown_data,
        ]);

        $content = preg_replace('~>\s+<~', '><', $content);
        return $content;
    }

    /**
     * Renders the export view for the module
     * @return string
     */
    public function actionExport()
    {
        //init
        $spreadsheet = new Spreadsheet();
        $params = Yii::$app->request->queryParams;

        $searchModel = new ConstructionAccidentSearch();
        $dataProvider = $searchModel->searchReport($params);
        //detail/author
        $spreadsheet->getProperties()
            ->setCreator('Masjaki Surakarta')
            ->setLastModifiedBy('Masjaki Surakarta')
            ->setTitle('Laporan K3')
            ->setSubject('Laporan K3')
            ->setDescription('Laporan K3, diekspor tanggal ' . date('d-m-Y'));
        // ->setKeywords('office PhpSpreadsheet php')
        // ->setCategory('Test result file');
        $spreadsheet->setActiveSheetIndex(0);

        //formatting
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setwidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setwidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setwidth(40);

        //judul
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'Laporan K3')
            ->setCellValue('A2', 'Manajemen Informasi Jasa Konstruksi Dan Pelaporan Kecelakaan Kerja')
            ->setCellValue('A3', 'Kota Surakarta')
            ->setCellValue('A4', 'Tanggal Ekspor : '. date('d-m-Y'));
        $spreadsheet->getActiveSheet()->getStyle('A1:A4')->applyFromArray($this->cellStyles(3));
        $spreadsheet->getActiveSheet()
            ->mergeCells('A1:N1')
            ->mergeCells('A2:N2')
            ->mergeCells('A3:N3')
            ->mergeCells('A4:N4');

        //data
        $i = 6;
        $dataaccident = $dataProvider;

        //judul tabel
        $i = $i + 1;
        $spreadsheet->getActiveSheet()
        ->setCellValue('A' . $i, Yii::t('app', 'Construction Work'))
        ->setCellValue('B' . $i, Yii::t('app', 'Accident'))
        ->setCellValue('C' . $i, Yii::t('app', 'Date Occur'))
        ->setCellValue('D' . $i, Yii::t('app', 'Accident Category'))
        ->setCellValue('E' . $i, Yii::t('app', 'Amount Victims Minor Injuries'))
        ->setCellValue('F' . $i, Yii::t('app', 'Amount Victims Moderate Injuries'))
        ->setCellValue('G' . $i, Yii::t('app', 'Amount Victims Serious Injuries'))
        ->setCellValue('H' . $i, Yii::t('app', 'Amount Victims Die'))
        ->setCellValue('I' . $i, Yii::t('app', 'Description'))
        ->setCellValue('J' . $i, Yii::t('app', 'Use BPJS'))
        ->setCellValue('K' . $i, Yii::t('app', 'BPJS Number'))
        ->setCellValue('L' . $i, Yii::t('app', 'Total Worker'))
        ->setCellValue('M' . $i, Yii::t('app', 'Worker Contribution'))
        ->setCellValue('N' . $i, Yii::t('app', 'File Upload'));

        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->cellStyles(2));

        foreach ($dataaccident->query->all() as $dataacc) {
           
            $i = $i + 1;

            $status = ''; 
            $bpjs = '';

            if($dataacc['accident'] == 1){
                $status = 'Ya'; 
            } else {
                $status = 'Tidak'; 
            }

            if ($dataacc['bpjs'] == 1) {
                $bpjs = 'Ya';
            } else {
                $bpjs = 'Tidak';
            }

            $file = ConstructionAccidentFile::find()->select('id')->where(['construction_accident_id' => $dataacc->id])->all();
            $arr_file = [];
            foreach($file as $f){
                array_push($arr_file, Url::base(true) . '/construction/construction-accident-file/fetch-file?id=' . Yii::$app->hasher->encode($f['id']). "\n");                           
            }

            $spreadsheet->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I' . $i)->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('N' . $i)->getAlignment()->setWrapText(true);
            
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $i, wordwrap($dataacc['constructionWork']['name'], 30, "\n"))
                ->setCellValue('B' . $i, $status)
                ->setCellValue('C' . $i, $dataacc['date'])
                ->setCellValue('D' . $i, $dataacc['accident_category'])
                ->setCellValue('E' . $i, $dataacc['amount_victims_minor_injuries'])
                ->setCellValue('F' . $i, $dataacc['amount_victims_moderate_injuries'])
                ->setCellValue('G' . $i, $dataacc['amount_victims_serious_injuries'])
                ->setCellValue('H' . $i, $dataacc['amount_victims_die'])
                ->setCellValue('I' . $i, $dataacc['description'])
                ->setCellValue('J' . $i, $bpjs)
                ->setCellValue('K' . $i, $dataacc['no_bpjs'])
                ->setCellValue('L' . $i, $dataacc['total_pekerja'])
                ->setCellValue('M' . $i, $dataacc['iuran_pekerja'])
                ->setCellValue('N' . $i, implode("\n", $arr_file))
                ->getStyle('M' . $i)->getNumberFormat()->setFormatCode('_("Rp."* #,##0.00_);_("Rp."* \(#,##0.00\);_("Rp."* "-"??_);_(@_)');
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->cellStyles(4));
           
        }

        //export ke file
        $writer = new Xlsx($spreadsheet);
        $writer->save(Yii::$app->runtimePath . '/Laporan_K3.xlsx');
        return \Yii::$app->response->sendFile(Yii::$app->runtimePath . '/Laporan_K3.xlsx');
    }

    private function cellStyles($type = 0)
    {
        if ($type == 0) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];
            return $styleArray;
        } else if ($type == 1) {
            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'font' => [
                    'bold' => true,
                ],
            ];
            return $styleArray;
        } else if ($type == 2) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];
            return $styleArray;
        } else if ($type == 3) {
            $styleArray = [
                'font' => [
                    'bold' => true,
                    'size' => 13,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];
            return $styleArray;
        } else if ($type == 4) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];
            return $styleArray;
        }
    }



    public function actionReset_session()
    {
        $this->sess->remove('report');
        return $this->redirect(['index']);
    }
}