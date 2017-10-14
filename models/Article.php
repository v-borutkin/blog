<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property integer $viewed
 * @property integer $user_id
 * @property integer $status
 * @property integer $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comment
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }


    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);

    }

    public function getImage()
    {
        if ($this->image)
        {
            return '/uploads/' .$this->image;
        }
        return '/no-image.png';
    }

    public function deleteImage(){
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteFile($this->image);
    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

    public function getCategory()
    {
      return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if ($category !=null)
        {
            $this->link('category', $category);
            return true;
        }
        return $this->save(false);
    }

    public function getTags()
    {
       return $this->hasMany(Tag::className(), ['id'=> 'tag_id'])
           ->viaTable('article_tag', ['article_id' => 'id']);
    }
    public function getSelectedTag()
    {
        $selectedTags = $this->getTags()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedTags, 'id');
    }

    public function saveTags($tags)
    {
        if (is_array($tags))
        {


            $this->clearCurrentTags();
            foreach ($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }
    public function clearCurrentTags()
    {
        ArticleTag::deleteAll(['article_id' => $this->id]);
    }
    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }
    public static function getAll($pageSize = 5)
    {
        $query = Article::find();

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)->orderBy('date desc')
            ->all();
        $date['articles'] = $articles;
        $date['pagination'] = $pagination;
        return $date;
    }

    public static function getRecent($limit)
    {
       $recent =  Article::find()
            ->orderBy('date asc')
            ->limit($limit)
            ->all();

       return $recent;
    }

    public static function getPopular($limit)
    {
        $popular = Article::find()
            ->orderBy('viewed desc')
            ->limit($limit)
            ->all();

        return $popular;
    }

    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;

        return $this->save();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id' => 'id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' =>'user_id']);
    }
}
