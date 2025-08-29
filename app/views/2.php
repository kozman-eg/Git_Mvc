<?php
   use \Models\Request;

?>
<form method ="POST">
    <div class="form-floating">
    <input name="UserName" type="text" class="form-control" id="floatingemail" placeholder="name@example.com" value="<?= Request::old_value('UserName') ?>">
    <label for="floatingemail">Email</label>
    <div class="text-danger"><?= $errors['UserName'] ?? '' ?></div>
</div>

<div class="form-floating">
    <input name="Password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
    <label for="floatingPassword">Password</label>
    <div class="text-danger"><?= $errors['Password'] ?? '' ?></div>
</div>
<div><button class="btn btn-primary w-100 py-2" type="submit">login</button></div>
</form>

