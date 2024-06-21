<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>


<?php use yii\helpers\Html; ?>
<div class="site-index">
    <div class="body-content">
        <h1>Мои друзья</h1>

        <?php if (Yii::$app->user->isGuest):?>
            <p style="font-weight:600">Необходимо авторизоваться, чтобы просматривать список своих друзей!</p>
        <?php else:?>
            <ul style="list-style-type:none">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <li>
                            <h3>Username: <?= $user->username ?></h3>
                            <p>Email: <?= $user->email ?></p>
                            <?= Html::a('Посмотреть статьи', ['friend-articles', 'id' => $user->id], ['class' => 'btn btn-primary'])?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="font-weight:600">У вас ещё нет друзей!</p>
                <?php endif; ?>
            </ul>
        <?php endif;?>
    </div>
</div>