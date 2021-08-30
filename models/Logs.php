<?php

namespace app\models;

use Yii;
use yii\web\Request;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "logs".
 *
 * @property int|null $user_id
 * @property string|null $type
 * @property string|null $ip
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Logs extends \yii\db\ActiveRecord
{
    const TYPE_UNKNOWN = 'UNKNOWN';
	const TYPE_CONTROLLER = 'CONTROLLER';
	const TYPE_USER = 'USER';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['type', 'ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'ip' => 'Ip',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function addLog($data)
    {
        if(!isset($data['type'])){
			$type= LOGS::TYPE_UNKNOWN;	
		}else{
			$type = $data['type'];
		}

        if(!isset($data['description'])){
			$description = 'No Description';
		}else{
			$description = $data['description'];
		}

        if(!isset($data['user_id'])){
            $userId = \Yii::$app->user->id;
		}else{
            $userId = $data['user_id'];
		}

        $log = new Logs;
		$log->user_id = $userId;
		$log->type = $type;
		$log->ip = \Yii::$app->request->userIP;
		$log->description = $description;
        $log->save();
    }
}