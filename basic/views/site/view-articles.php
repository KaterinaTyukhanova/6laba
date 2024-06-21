<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>

<?php use yii\helpers\Html; ?>
<div class="site-index">
    <div class="body-content">
        <h1>Мои статьи</h1>

        <?php if (Yii::$app->user->isGuest):?>
            <p style="font-weight:600">Необходимо авторизоваться, чтобы просматривать свои статьи!</p>
        <?php else:?>
            <ul style="list-style-type:none">
                <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <li>
                        <h3>Тема: <?= $article->title ?></h3>
                        <p>Хештеги: <?= $article->hashtags ?></p>
                        <p>Содержание: <?= $article->content ?></p>
                        <p>Кол-во просмотров: <?= $article->views ?></p>
                        <p>Изображение: <img src="<?php echo Yii::getAlias('@web'). '/uploads/'. $article->image?>" width="200" height="200" alt="image"></p>
                    </li>
                <?php endforeach; ?>
                <?php else: ?>
                    <p style="font-weight:600">У вас еще нет статей!</p>
                <?php endif; ?>
            </ul>
        <?php endif;?>
    </div>
</div>