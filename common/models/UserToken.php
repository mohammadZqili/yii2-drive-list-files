<?php

namespace common\models;

/**
 * This is the model class for table "user_tokens".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $oauth2_token
 * @property string|null $next_page_token
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['oauth2_token', 'next_page_token'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'oauth2_token' => 'Oauth2 Token',
            'next_page_token' => 'Next Page Token',
        ];
    }


    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>"user_id"]);
    }
}
