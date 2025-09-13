<?php

namespace Models;

defined('ROOTPATH') or exit('access allwoed');

class {CLASSNAME}
{
    use \Core\Model;

    protected $table = '{table}';
    protected $loginUniqueColumn = 'UserName';
    use \Core\Model;

    public function __construct()
    {
        // هنا تقدر تغير الـ primaryKey
        $this->getPrimaryKey('ID');
    }
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
}
