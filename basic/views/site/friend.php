<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>


<?php use yii\helpers\Html; ?>
<div class="site-index">
    <div class="body-content">
        <h1>Добавить пользователей в друзья</h1>

        <?php if (Yii::$app->user->isGuest):?>
            <p style="font-weight:600">Необходимо авторизоваться, чтобы добавлять пользователей в друзья!</p>
        <?php else:?>
            <?php if (Yii::$app->user->identity->status === 1):?>
                <ul style="list-style-type:none">
                    <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <li>
                            <h3>Username: <?= $user->username?></h3>
                            <p>Email: <?= $user->email?></p>

                            <?php if ($user->status === 1):?>
                                <?php if ($friends[$user->id]):?>
                                    <?= Html::a('Добавить в друзья', ['add-friend', 'id' => $user->id], ['class' => 'btn btn-primary'])?>
                                <?php else:?>
                                    <p style="font-weight:600">Вы уже отправили заявку в друзья данному пользователю!</p>
                                <?php endif;?>
                            <?php else:?>
                                <p style="font-weight:600">Пользователь заблокирован, вы не можете добавить его в друзья!</p>
                            <?php endif;?>
                        </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <p style="font-weight:600">Другие пользователи в системе отсутствуют!</p>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <p style="font-weight:600">Пользователь заблокирован</p>
            <?php endif; ?>
        <?php endif;?>
    </div>
</div>