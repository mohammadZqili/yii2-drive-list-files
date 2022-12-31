<?php

namespace frontend\services;

use common\models\User;
use common\models\UserToken;
use Exception;
use Google\Client;
use Google\Service\Drive;
use Yii;

class DriveFilesListService
{

    public function resetToken()
    {
        $user = User::findOne(Yii::$app->user->id);
        $userTokens = UserToken::findOne($user->oauthTokens->id);
        $userTokens->oauth2_token = null;
        $userTokens->save();
    }

    public function authorizeRequest()
    {
        $user = User::findOne(Yii::$app->user->id);
        $userToken = $user->oauthTokens;

        if (empty($userToken?->oauth2_token)) {
            $oauthClient = new DriveOauth();
            $oauthClient->init();
            $oauthClient->buildAuthUrl();
            $url = $oauthClient->buildAuthUrl(); // Build authorization URL
            $response = Yii::$app->getResponse()->redirect($url); // Redirect to authorization URL.
            return $response;
        }
        return null;
    }


    public function handleReturnOauth($code)
    {
        $user = User::findOne(Yii::$app->user->id);
        $userToken = new UserToken();

        if (empty($user->oauthTokens)) {
            //create new token row
            $userToken->user_id = $userToken;
            $userToken->save();
            $user->link("oauthTokens", $userToken);
        }

        $userToken = $user->getOauthTokens()->one();

        if (empty($userToken?->oauth2_token) && !empty($code)) {
            $accessToken = (new DriveOauth())->fetchAccessToken($code);
            $userToken->oauth2_token = $accessToken->getToken();
            $userToken->save();
        }
    }


    public function listFiles()
    {
        $user = User::findOne(Yii::$app->user->id);
        $userToken = $user->oauthTokens;

        if (empty($userToken?->oauth2_token)) {
            return null;
        }

        //store access token inside db
        $client = new Client;
        $client->addScope(\Google\Service\Drive::DRIVE);
        $client->setAccessToken($userToken->oauth2_token);
        $dr_service = new Drive($client);

        //next or first
        $nextToken = \Yii::$app->request->get('next');
        $results = $this->retrieveNextFiles($dr_service, $userToken, !empty($nextToken));
        return $results;
    }

    public function retrieveNextFiles($service, UserToken $userToken, $nextPage = false): array
    {
        $result = [];
        $nextPageToken = null;
        if ($nextPage)
            $nextPageToken = $userToken->next_page_token ?? null;
        //check if this user have a next page token from database

        try {
            $parameters = array();
            $parameters['pageSize'] = 50;

            if ($nextPageToken) {
                $parameters['pageToken'] = $nextPageToken;
            }
            $files = $service->files->listFiles($parameters);
            foreach ($files->files as $file) {
                $result[] = [
                    'title' => $file->name,
                    'thumbLink' => $file->thumbLink,
                    'embedLink' => "",//todo
                    'modifiedDate' => $file->modifiedTime,
                    'size' => $file->size,
                    'owners' => $file->owners
                ];
            }


            $pageToken = $files->getNextPageToken();

            //save new token
            $userTokens = UserToken::findOne($userToken->id);
            $userTokens->next_page_token = $pageToken;
            $userTokens->save();

            return $result;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
            $pageToken = NULL;
        }
        return $result;
    }


    public function retrieveAllFiles($service)
    {
        $result = [];
        $pageToken = NULL;

        do {
            try {
                $parameters = array();
                $parameters['pageSize'] = 50;

                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }
                $files = $service->files->listFiles($parameters);

                foreach ($files->files as $file) {
                    $result[] = [
                        'title' => $file->name,
                        'thumbLink' => $file->thumbLink,
                        'embedLink' => "",//todo
                        'modifiedDate' => $file->modifiedTime,
                        'size' => $file->size,
                        'owners' => empty($file->owners) ? "" : implde(" ", $file->owners)
                    ];
                }
                $pageToken = $files->getNextPageToken();
            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
                $pageToken = NULL;
            }
        } while ($pageToken);
        return $result;
    }


}