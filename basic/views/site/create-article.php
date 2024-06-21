<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавление новой статьи';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->user->isGuest):?>
    <p style="font-weight:600">Необходимо авторизоваться, чтобы создать статью!</p>
<?php else:?>
    <?php if (Yii::$app->user->identity->status === 1):?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'hashtags')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'image')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Создать статью', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p style="font-weight:600">Пользователь заблокирован</p>
    <?php endif; ?>
<?php endif; ?>

