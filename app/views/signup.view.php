<?php

use \Models\Request;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Signin Template · Bootstrap v5.3</title>
    <script src="<?= ROOT ?>/assets/js/color-modes.js"></script>
    <link href="<?= ROOT ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ROOT ?>/assets/css/sign-in.css" rel="stylesheet">

</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="text-center form-signin w-100 m-auto">
        <form method="POST">
            <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <?php if (!empty($errors['rules'])): ?>
                <div class="text-danger"><?= $errors['rules'] ?? ''  ?></div>
            <?php endif; ?>

            <h1 class="h3 mb-3 fw-normal">Create account</h1>
            <div class="form-floating">
                <input name="UserName" type="text" class="form-control" id="floatingname" placeholder="UserName" value="<?= Request::old_value('UserName') ?>"> <label for="floatingname"><?php echo lang('name') ?></label>
                <div class="text-danger"><?= $errors['UserName'] ?? ''  ?></div>
            </div>
            <div class="form-floating">
                <input name="Password" type="password" class="form-control" id="floatingPassword" placeholder="Password" value="<?= Request::old_value('Password') ?>"> <label for="floatingPassword"><?php echo lang('password') ?></label>
                <div class="text-danger"><?= $errors['Password'] ?? ''  ?></div>
            </div>
            <div class="form-floating">
                <input name="again_password" type="password" class="form-control" id="floatingagainPassword" placeholder="Again Password" value="<?= Request::old_value('again_password') ?>"> <label for="floatingagainPassword"><?php echo lang('again password') ?></label>
                <div class="text-danger"><?= $errors['again_password'] ?? ''  ?></div>
            </div>
            <div class="form-floating">
                <input name="FullName" type="text" class="form-control" id="floatingfullname" placeholder="FullName" value="<?= Request::old_value('FullName') ?>"> <label for="floatingfullname"><?php echo lang('fullname') ?></label>
                <div class="text-danger"><?= $errors['FullName'] ?? ''  ?></div>
            </div>
            <div class="form-floating">
                <input name="Email" type="email" class="form-control" id="floatingemail" placeholder="name@example.com" value="<?= Request::old_value('Email') ?>"> <label for="floatingemail"><?php echo lang('email') ?></label>
                <div class="text-danger"><?= $errors['Email'] ?? ''  ?></div>
            </div>
            <div class="form-check text-start my-3"> <input name="Terms" class="form-check-input" type="checkbox" value="yes" id="checkDefault" <?= Request::old_checked('Terms', 'yes') ?>>
                <label class="form-check-label" for="checkDefault"><?php echo lang('Accept terms') ?></label>
            </div> <button class="btn btn-primary w-100 py-2" type="submit">Create</button>
            <a href="<?= ROOT ?>">Home</a>
            <a href="<?= ROOT ?>/login">login</a>
            <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2025</p>
        </form>
    </main>
    <script src="<?= ROOT ?>/assets/js/bootstrap.bundle.min.js" class="astro-vvvwv3sm"></script>
</body>

</html>