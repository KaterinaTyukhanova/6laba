<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>


<?php use yii\helpers\Html; ?>
<div class="site-index">
    <div class="body-content">
        <?php if (Yii::$app->user->isGuest):?>
            <p style="font-weight:600">Необходимо авторизоваться, чтобы посмотреть заявки в друзья!</p>
        <?php else:?>
            <?php if (Yii::$app->user->identity->status === 1):?>
                <ul style="list-style-type:none">
                <h1>Мои заявки (мне прислали):</h1>
                    <?php if (!empty($friendRequests)): ?>
                        <?php foreach ($friendRequests as $request):?>
                            <li>
                                <h3>Username: <?= $request->sender->username?></h3>
                                <p>Статус заявки: <?= $request->status?></p>

                                <?php if ($request->status == 'waiting'): ?>
                                    <?= Html::a('Подтвердить', ['confirm', 'id' => $request->id], ['class' => 'btn btn-primary'])?>
                                    <?= Html::a('Отменить', ['cancel', 'id' => $request->id], ['class' => 'btn btn-primary'])?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach;?>
                    <?php else: ?>
                        <p style="font-weight:600">Вам не присылали заявки!</p>
                    <?php endif; ?>
                
                <h1>Мои заявки (я отправил):</h1>
                    <?php if (!empty($friendImRequests)): ?>
                        <?php foreach ($friendImRequests as $requestIm):?>
                            <li>
                                <h3>Username: <?= $requestIm->recipient->username?></h3>
                                <p>Статус заявки: <?= $requestIm->status?></p>
                            </li>
                        <?php endforeach;?>
                    <?php else: ?>
                        <p style="font-weight:600">Вы не отправляли заявки!</p>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <p style="font-weight:600">Пользователь заблокирован</p>
            <?php endif; ?>
        <?php endif;?>

    </div>
</div>