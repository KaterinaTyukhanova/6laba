<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $title
 * @property string $hashtags
 * @property string $content
 * @property string $image
 * @property integer $views
 * @property integer $author_id
 */
class Article extends ActiveRecord
{
    public static function tableName()
    {
        return 'article';
    }

    public function rules()
    {
        return [
            [['title', 'hashtags', 'content', 'image', 'author_id'], 'required'],
            [['views', 'author_id'], 'integer'],
            [['title', 'hashtags', 'image'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, png, jpeg', 'maxSize' => 1024 * 1024 * 10]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'hashtags' => 'Hashtags',
            'content' => 'Content',
            'image' => 'Image',
            'views' => 'Views',
            'author_id' => 'AuthorID',
        ];
    }

    public function upload()
    {
        if ($this->image) {
            $filename = time(). rand(0, 100). '.'. $this->image->extension;
            $filepath = Yii::getAlias('@webroot'). '/uploads/'. $filename;
            if (!file_exists(Yii::getAlias('@webroot'). '/uploads')) {
                mkdir(Yii::getAlias('@webroot'). '/uploads', 0777, true);
            }
            if ($this->image->saveAs($filepath)) {
                return $filename;
            } else {
                Yii::error('Failed to save uploaded file: '. $this->image->error, 'Article');
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
}