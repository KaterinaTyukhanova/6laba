<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "friend".
 *
 * @property integer $id
 * @property integer $id_sender
 * @property integer $id_recipient
 * @property string $status
 */
class Friend extends ActiveRecord
{
    public static function tableName()
    {
        return 'friend';
    }

    public function rules()
    {
        return [
            [['id_sender', 'id_recipient', 'status'], 'required'],
            [['id_sender', 'id_recipient'], 'integer'],
            [['status'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_sender' => 'IdSender',
            'id_recipient' => 'IdRecipient',
            'status' => 'Status'
        ];
    }

    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'id_recipient']);
    }

    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'id_sender']);
    }

    public function getFriend()
    {
        return $this->hasOne(User::className(), ['id' => new \yii\db\Expression('CASE WHEN {{%friend}}.id_sender = {{%friend}}.id THEN {{%friend}}.id_sender ELSE {{%friend}}.id_recipient END')]);
    }
}