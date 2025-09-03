<?php

namespace Models;

defined('ROOTPATH') or exit('access allwoed');


class User
{

    use \Core\Model;
    public $primaryKey = 'UserID';
    protected $table = "users";
    protected $allowedcolumns = [

        'UserName',
        'Password',
        'Email',
        'FullName',
        'GroupID',
        'TrustStatus',
        'RegStatus',
        'Date',
    ];
    /* ***********************************
    * rules include:
    * required
    * email
    * alpha
    * numeric
    * alpha_numeric
    * alpha_sumbol
    * alpha_numeric_sumbol
    * phone_eg
    * strong_password
    * alpha_numeric_sumbol
    * unique
    * same_password
    */
    protected $onInsertValidationRules = [
        'UserName' => [
            'required',
            'alpha',
            'unique',
        ],
        'Password' => [
            'required',
            'longer_than_8_chars',
            'alpha_numeric_symbol',
            'strong_password',
            'same_password',
        ],
        'again_password' => [
            'required',
            'strong_password',
            'alpha_numeric_symbol',
            'longer_than_8_chars',
            'same_password',

        ],
        'FullName' => [
            'required',
            'alpha_space',
        ],
        'Email' => [
            'required',
            'email',
            'unique',
        ],
    ];
    protected $onUpdateValidationRules = [
        'UserName' => [
            'required',
            'alpha',
            'unique',
        ],
        'Password' => [
            'required',
            'longer_than_8_chars',
            'alpha_numeric_symbol',
            'strong_password',
            'same_password',
        ],
        'again_password' => [
            'required',
            'strong_password',
            'alpha_numeric_symbol',
            'longer_than_8_chars',
            'same_password',

        ],
        'FullName' => [
            'required',
            'alpha_space',
        ],
        'Email' => [
            'required',
            'email',
            'unique',
        ],
    ];

    public function usernameExists($username)
    {
        $query = "SELECT * FROM $this->table WHERE UserName = :username LIMIT 1";
        $result = $this->query($query, ['username' => $username]);

        // تأكد أن النتيجة مصفوفة وفيها بيانات
        return isset($result['count']) && $result['count'] > 0;
    }


    public function reset($date)
    {
        if (!empty($this->errors)) {
            return false;
        }

        $date['Password'] = sha1($date['Password']);
        $date['GroupID']     = '0';
        $date['TrustStatus'] = '0';
        $date['RegStatus']   = '0';
        $date['Date']        = date('Y-m-d H:i:s');
        $date['DateCreated']        = date('Y-m-d H:i:s');
        return $date;
    }
    public function signup($data)
    {
        if ($this->validate($data)) {
            $clean_data = $this->reset($data);
            $this->insert($clean_data);
            redirect('login');
        } else {
            $this->errors[] = "Invalid data";
        }
    }


    public function  loging($data)
    {
        $row = $this->first(['UserName' => $data['UserName']]);
        $password = sha1($data['Password']);

        if ($row) {

            if ($row->Password == $password) {

                $ses = new \Models\Session;
                $ses->auth($row);
                redirect('Home');
            } else {

                $this->errors['Password'] = lang('WRONG_PASSWORD');
            }
        } else {

            $this->errors['UserName'] = lang('WRONG_USERNAME');
        }
    }
}
