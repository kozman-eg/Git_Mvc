<?php

namespace Models;

defined('ROOTPATH') or exit('access not allowed');

class User
{
    use \Core\Model;

    public function __construct()
    {
        // هنا تقدر تغير الـ primaryKey
        $this->getPrimaryKey('ID');
    }
    protected $table = "users";

    // الأعمدة المسموح إدخالها
    protected $allowedColumns = [
        'Name',
        'Email',
        'Password',
        'Role',
    ];
    
    /* ***********************************
    * قواعد التحقق (Validation rules)
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
        'Name' => [
            'required',
            'alpha_space',
        ],
        'Email' => [
            'required',
            'email',
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
            'same_password',
        ],
    ];
    
    protected $onUpdateValidationRules = [
        'Name' => [
            'required',
            'alpha_space',
        ],
        'Email' => [
            'required',
            'email',
            'unique',
        ],
    ];
    
    // 🔍 التأكد أن اسم المستخدم (الإيميل) موجود
    public function emailExists($email)
    {
        $query = "SELECT * FROM $this->table WHERE Email = :email LIMIT 1";
        $result = $this->query($query, ['email' => $email]);
        return $result ? true : false;
    }    

    // تجهيز البيانات قبل الإدخال
    public function reset($data)
    {
        if (!empty($this->errors)) {
            return false;
        }    
        
        $data['Password'] = sha1($data['Password']); // تشفير الباسورد
        return $data;
    }    

    // التسجيل
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

    // تسجيل الدخول
    public function login($data)
    {
        $row = $this->first(['Email' => $data['Email']]);
        $password = sha1($data['Password']);

        if ($row) {
            if ($row->Password == $password) {
                $ses = new \Models\Session;
                $ses->auth($row);
                redirect('home');
            } else {
                $this->errors['Password'] = lang('WRONG_PASSWORD');
            }    
        } else {
            $this->errors['Email'] = lang('WRONG_EMAIL');
        }    
    }    
}    
