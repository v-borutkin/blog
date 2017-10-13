<?php
namespace app\modules\admin\controllers;

use app\models\Comment;
use yii\web\Controller;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $comments = Comment::find()->orderBy('id desc')->all();

        return $this->render('index', ['comment' => $comments]);

    }

    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);

        if ($comment->delete())
        {
            return $this->redirect(['comment/index']);
        }
    }

    public function actonAllow($id)
    {
        $comment = Comment::findOne($id);

        if ($comment->allow())
        {
            return $this->redirect(['index']);
        }
    }

    public function actonDisallow($id)
    {
        $comment = Comment::findOne($id);

        if ($comment->disallow())
        {
            return $this->redirect(['index']);
        }
    }
}