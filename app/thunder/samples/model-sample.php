<?php

namespace Models;

defined('ROOTPATH') or exit('access allwoed');

class {CLASSNAME}
{
    use \Core\Model;


    protected $table = '{table}';
    protected $primaryKey  = 'ID';
    protected $loginUniqueColumn = 'UserName';
    protected $allowedColumns = [

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
    protected $validationRules = [
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
}
