<?php

use App\Auth\Authenticator;
use App\Database;
use App\HTML\Form;
use App\Model\User;

require '../vendor/autoload.php';

$user = new User();
$errors = [];

if (Authenticator::isLogged()) {
    header('location:' . $router->url('admin'));
    exit();
};


if (!empty($_POST)) {
    $db = new Database();
    $user->setUsername($_POST['username']);
    $user->setPassword($_POST['password']);
    $authenticator = new Authenticator($db, $user);

    if ($authenticator->attempt()) {
        header('location:' . $router->url('admin'));
        exit();
    } else {
        $errors['password'][] = 'no matching account found';
    }
}

$data = $user ?? [];

$form = new Form($data, $errors);
?>

<div class="container h-100 d-flex flex-column align-items-center mt-5 pt-5">
    <?php if (isset($_GET['forbidden'])) : ?>
        <div class="alert alert-warning">
            Access Forbidden : Log in to Access this page
        </div>
    <?php endif ?>
    <form class="login" action="/login" method="POST">
        <!-- User Name input -->
        <div class="form-outline mb-4">
            <?= $form->input('username', 'User Name') ?>
        </div>
        <!-- password input  -->
        <div class="form-outline mb-4 passwordInput">
            <?= $form->password('password', 'Password') ?>
            <!-- <i class="fa-solid fa-eye-slash"></i> -->
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-center">
                <!-- Checkbox -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" value="" id="rememberMe" checked />
                    <label class="form-check-label" for="rememberMe"> Remember me </label>
                </div>
            </div>

            <div class="col">
                <!-- Simple link -->
                <a href="#!">Forgot password?</a>
            </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

    </form>
</div>