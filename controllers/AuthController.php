<?php


use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use yii\web\Controller;
use \yii\web\Response;
class AuthController extends Controller
{
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('/site/login', [
            'model' => $model,
        ]);
    }

    public function  actionSignup()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
           if ($model->signup())
           {
               return $this->redirect(['auth/login']);
           }
        }
        return $this->render('signup', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLoginVk($uid, $first_name, $photo)
    {
        $user = new User();
        if ($user->saveFromVk($uid, $first_name, $photo))
        {
           return $this->redirect(['site/index']);
        }


    }


}