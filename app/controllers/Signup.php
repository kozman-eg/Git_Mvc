<?php

namespace Controllers;

class Signup
{
    use \Core\Controller;
    use \Core\Model;



    public function index()
    {
        $req  = new \Models\Request;
        $data = [];

        // لو Request::posted() بتشتغل من غير بارامترات
        if ($req->posted()) {

            $user = new \Models\User;

            // لو فضّلت على اسمك القديم:
            $user->Signup($_POST);

            // أو بالنسخة الأوضح:
            // if ($user->signup($_POST)) { redirect('login'); exit; }

            $data['errors'] = $user->errors ?? [];
        }

        $this->view('signup', $data);
    }
}
