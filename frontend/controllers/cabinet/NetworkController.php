<?php


namespace frontend\controllers\cabinet;


use DomainException;
use Yii;
use core\services\auth\NetworkService;
use yii\web\Controller;

class NetworkController extends Controller
{
    private $service;

    public function __construct(
        $id,
        $module,
        NetworkService $service,
        $config = []
    )
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }
}
