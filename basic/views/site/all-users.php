<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>

<?php use yii\helpers\Html; ?>
<div class="site-index">
    <div class="body-content">
        <h1>Все пользователи, зарегистрированные в системе</h1>
        
        <ul style="list-style-type:none">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <li>
                        <h3>Username: <?= $user->username ?></h3>
                        <p>Email: <?= $user->email ?></p>
                        <?php if ($user->status == '1'): ?>
                            <?= Html::a('Заблокировать пользователя', ['block', 'id' => $user->id], ['class' => 'btn btn-primary'])?>
                        <?php else: ?>
                            <p style="font-weight:600">Пользователь заблокирован</p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-weight:600">Другие пользователи в системе отсутствуют!</p>
            <?php endif; ?>
        </ul>
    </div>
</div>