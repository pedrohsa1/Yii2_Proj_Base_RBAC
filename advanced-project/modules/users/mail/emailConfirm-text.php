<?php

/**
 * @var $this yii\web\View
 * @var $user modules\users\models\User
 */

use modules\users\Module;

// $confirmLink = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['email-confirm', 'token' => $user->email_confirm_token]);
$confirmLink = Yii::$app->urlManager->hostInfo.'/email-confirm?token='.$user->email_confirm_token;
?>

<?= Module::t('mail', 'HELLO {username}', ['username' => $user->username]); ?>!

<?= Module::t('mail', 'FOLLOW_TO_CONFIRM_EMAIL') ?>:

<?= $confirmLink ?>

<?= Module::t('mail', 'IGNORE_IF_DO_NOT_REGISTER') ?>
