<?php

use App\HTML\Form;
use App\Session;

require '../vendor/autoload.php';

session_start();
$errors = Session::get('errors', []);
$oldValues = Session::get('oldValues', null);
Session::flush();

$data = $oldValues ?? [];

$form = new Form($data, $errors);

?>

<div class="container pt-5 mt-5 px-5">
    <form action="store" method="POST" onsubmit="return confirm('do you want t continue and add post')">

        <div class="mb-3">
            <?= $form->input('name', 'Post name') ?>
        </div>

        <div class="mb-3">
            <?= $form->textArea('content', 'Post Content') ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mx-auto">Save</button>
    </form>
</div>