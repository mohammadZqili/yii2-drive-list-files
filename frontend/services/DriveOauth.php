<?php

namespace frontend\services;

use yii\authclient\clients\Google;
use yii\authclient\OAuth2;

class DriveOauth extends Google
{
    private $returnUri = "files/respond";

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->setReturnUrl(env('APP_URL')."/".$this->returnUri);

        $this->clientId = env('client_id');
        $this->clientSecret = env('secret_key');

        $this->scope = empty($this->scope) ? \Google\Service\Drive::DRIVE : $this->scope . " " . \Google\Service\Drive::DRIVE;
        $this->scope = empty($this->scope) ? \Google\Service\DriveActivity::DRIVE_ACTIVITY_READONLY : $this->scope . " " . \Google\Service\DriveActivity::DRIVE_ACTIVITY_READONLY;
    }

}