<?php

namespace app\controllers;

use app\models\Article;
use app\models\Category;
use app\models\Comment;
use app\models\CommentForm;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $data = Article::getAll(1);
        $popular = Article::getPopular(3);
        $recent = Article::getRecent(4);
        $categories = Category::find()->all();

        return $this->render('index', [
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,
        ]);
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        $popular = Article::getPopular(3);
        $recent = Article::getRecent(4);
        $categories = Category::find()->all();
        $comments = $article->getComments()->where(['status' => 1]) -> all();
        $commentForm = new CommentForm();
        $tags = $article->getTags()->all();

        $article->viewedCounter();
        return $this->render('single', [
            'article' => $article,
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,
            'comments' => $comments,
            'commentForm' => $commentForm,
            'tags' => $tags,
        ]);
    }

    public function actionCategory($id)
    {
        $date = Category::getArticlesByCategory($id);

        $popular = Article::getPopular(3);
        $recent = Article::getRecent(4);
        $categories = Category::find()->all();

        return $this->render('category',[
            'articles' => $date['articles'],
            'pagination' => $date['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,

        ]);
    }


    public function actionComment($id)
    {
        $model = new CommentForm();
        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if ($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Ваш комментарий отправлен на премодерацию');
                return $this->redirect(['site/view', 'id' => $id]);
            }
        }
    }

}
