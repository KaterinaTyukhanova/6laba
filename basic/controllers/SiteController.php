<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\Article;
use app\models\User;
use app\models\Friend;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionViewArticles()
    {
        $userId = Yii::$app->user->id;
        $articles = Article::find()->where(['author_id' => $userId])->all();
        
        return $this->render('view-articles', [
            'articles' => $articles
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->goHome();
        }
        
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionCreateArticle()
    {
        $model = new Article();
        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Yii::$app->user->id;
            $image = UploadedFile::getInstance($model, 'image');
            $model->image = $image;
            $model->image = $model->upload();
            $model->views = 0;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статья успешно добавлена!');
                return $this->redirect(['view-articles']);
            }
        }
        return $this->render('create-article', [
            'model' => $model,
        ]);
    }

    public function actionFriend()
    {
        $currentUserId = Yii::$app->user->id;
        $users = User::find()->where(['<>', 'id', $currentUserId])
        ->andWhere(['role' => 1])
        ->all();

        $friends = [];
        foreach ($users as $user) {
            $friendExists = Friend::find()->where(['id_sender' => $currentUserId, 'id_recipient' => $user->id])
            ->orWhere(['id_recipient' => $currentUserId, 'id_sender' => $user->id])
            ->exists();
            $friends[$user->id] =!$friendExists;
        }

        return $this->render('friend', [
            'users' => $users,
            'friends' => $friends,
        ]);
    }

    public function actionAddFriend($id)
    {
        if(Yii::$app->user->identity->status === 1){
            $model = new Friend();
            $currentUserId = Yii::$app->user->id;
            $model->id_sender = $currentUserId;
            $model->id_recipient = $id;
            $model->status = 'waiting';
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Заявка в друзья успешно отправлена!');
                return $this->redirect(['friend']);
            }
        }
        return $this->goHome();
    }

    public function actionFriendRequest()
    {
        $currentUserId = Yii::$app->user->id;
        $friendRequests = Friend::find()
        ->where(['id_recipient' => $currentUserId])->with('sender')
        ->all();

        $friendImRequests = Friend::find()
        ->where(['id_sender' => $currentUserId])->with('recipient')
        ->all();

        return $this->render('friend-request', [
            'friendRequests' => $friendRequests,
            'friendImRequests' => $friendImRequests,
        ]);
    }

    public function actionConfirm($id)
    {
        if(Yii::$app->user->identity->status === 1){
            $currentUserId = Yii::$app->user->id;
            $request = Friend::findOne(['id' => $id, 'id_recipient' => $currentUserId, 
            'status' => 'waiting']);

            if ($request) {
                $request->status = 'confirm';
                $request->save();
                Yii::$app->session->setFlash('success', 'Вы добавили пользователя в друзья!');
            }
            return $this->redirect(['friend-request']);
        }
    }

    public function actionCancel($id)
    {
        if(Yii::$app->user->identity->status === 1){
            $currentUserId = Yii::$app->user->id;
            $request = Friend::findOne(['id' => $id, 'id_recipient' => $currentUserId, 
            'status' => 'waiting']);

            if ($request) {
                $request->status = 'cancel';
                $request->save();
                Yii::$app->session->setFlash('success', 'Вы отклонили заявку в друзья!');
            }
            return $this->redirect(['friend-request']); 
        }
    }

    public function actionMyFriends()
    {
        $currentUserId = Yii::$app->user->id;
        $friends = Friend::find()
        ->where(['and', 
        ['or', 
            ['id_sender' => $currentUserId], ['id_recipient' => $currentUserId]
        ], ['status' => 'confirm']])->all();

        $friendIds = [];
        foreach ($friends as $friend) {
            if ($friend->id_sender == $currentUserId) {
                $friendIds[] = $friend->id_recipient;
            } else {
                $friendIds[] = $friend->id_sender;
            }
        }
        $users = User::find()->where(['id' => $friendIds])->all();

        return $this->render('my-friends', [
            'users' => $users,
        ]);
    }

    public function actionFriendArticles($id)
    {
        $user = User::findOne($id);
        
        $articles = Article::find()->where(['author_id' => $id])->all();

        return $this->render('friend-articles', [
            'articles' => $articles,
            'user' => $user
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
    
        $article->views += 1;
        $article->save(); 
        
        $user = User::findOne($article->author_id);
        $articles = Article::find()->where(['author_id' => $user->id])->all();

        return $this->render('friend-articles', [
            'articles' => $articles,
            'user' => $user
        ]);
    }

    public function actionAllUsers()
    {
        if(Yii::$app->user->identity->role === 2){
            $users = User::find()->where(['role' => 1])->all();

            return $this->render('all-users', [
                'users' => $users
            ]);
        }
        return $this->goHome();
    }

    public function actionBlock($id)
    {
        if(Yii::$app->user->identity->role === 2){
            $user = User::findOne($id);
            
            $user->status = 2;
            $user->save();

            return $this->redirect(['all-users']);
        }
    }
}