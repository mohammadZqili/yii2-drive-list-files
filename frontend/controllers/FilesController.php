<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\services\DriveFilesListService;


class FilesController extends Controller
{

    private DriveFilesListService $fileService;

    public function behaviors()
    {
        return array_merge([
            'access' => [
                'class' => AccessControl::class,
                'only' => ['access', 'reset', 'respond', 'file'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['access', 'reset', 'respond', 'file'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ], parent::behaviors());
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->fileService = new  DriveFilesListService;
    }

    public function actionAccess()
    {

        $response = $this->fileService->authorizeRequest();
        if ($response)
            return $response;

        return $this->redirect('/files/respond');
    }

    function actionReset(): bool
    {
        $this->fileService->resetToken();
        return $this->redirect('/files/respond')->isOk;
    }

    function actionRespond()
    {
        $code = Yii::$app->getRequest()->get('code');
        $this->fileService->handleReturnOauth($code);
        return $this->redirect('/files/file');
    }

    function actionFile()
    {
        $results = $this->fileService->listFiles();
        if (empty($results))
            return $this->redirect('/files/access');

        return $this->render('list', ['data' => $results]);
    }

}
