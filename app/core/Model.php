<?php

namespace Core;

defined('ROOTPATH') or exit('access allwoed');

// Base Model class

trait Model
{
    use \Core\Database;

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "DESC";
    protected $order_column = "id";
    protected $primaryKey = "id";
    public $errors = [];

    public function getPrimaryKey()
    {
        if (empty($this->primaryKey)) {
            $this->primaryKey = 'id';
        }
        return $this->primaryKey;
    }
    public function getErrors($key)
    {
        if (!empty($this->errors[$key])) {
            return $this->errors[$key];
        }
        return "";
    }
    public function findAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        return $this->query($query);
    }

    public function where($where = [], $where_not = [])
    {
        $query = "SELECT * FROM $this->table";
        $conditions = [];

        foreach ($where as $key => $value) {
            $conditions[] = "$key = :$key";
        }

        foreach ($where_not as $key => $value) {
            $conditions[] = "$key != :$key";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";

        $params = array_merge($where, $where_not);
        return $this->query($query, $params);
    }

    /*public function first($where = [], $where_not = [])
    {
        $result = $this->where($where, $where_not);
        if (!empty($result['data'])) {
            return $result['data'][0];
        }
        return false;
    }*/

    public function insert($data)
    {
        // حذف الحقول غير المسموح بها
        if (!empty($this->allowedcolumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedcolumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(', ', $keys) . ") VALUES (:" . implode(', :', $keys) . ")";

        return $this->query($query, $data);
    }

    public function update($data, $id, $id_column = 'id')
    {
        if (!empty($this->allowedcolumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedcolumns)) {
                    unset($data[$key]);
                }
            }
        }

        $data[$id_column] = $id;

        $query = "UPDATE $this->table SET ";
        foreach ($data as $key => $value) {
            if ($key !== $id_column) {
                $query .= "$key = :$key, ";
            }
        }

        $query = rtrim($query, ', ');
        $query .= " WHERE $id_column = :$id_column";

        return $this->query($query, $data);
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column";
        return $this->query($query, $data);
    }

    public function test()
    {
        $result = $this->query("SELECT * FROM $this->table");
        show($result);
    }

    public function first($data, $data_not = [])
    {
        $query = "SELECT * FROM $this->table";
        $conditions = [];

        foreach ($data as $key => $value) {
            $conditions[] = "$key = :$key";
        }

        foreach ($data_not as $key => $value) {
            $conditions[] = "$key != :$key";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " LIMIT $this->limit OFFSET $this->offset";

        $params = array_merge($data, $data_not);

        $result = $this->query($query, $params);

        if (isset($result['data'][0])) {
            return $result['data'][0];
        }

        return false;
    }
    public function validate($data)
    {
        $this->errors = [];

        if (!empty($this->validationRules)) {

            foreach ($this->validationRules as $column => $rules) {
                $value = trim($data[$column] ?? '');

                foreach ($rules as $rule) {

                    switch ($rule) {

                        case 'email':
                            if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL))
                                $this->errors[$column] = "The $column must be a valid email.";
                            break;

                        case 'alpha':
                            if ($value !== '' && !preg_match("/^[a-zA-Z]+$/", $value))
                                $this->errors[$column] = "The $column must contain only letters.";
                            break;

                        case 'numeric':
                            if ($value !== '' && !preg_match("/^[0-9]+$/", $value))
                                $this->errors[$column] = "The $column must contain only numbers.";
                            break;

                        case 'alpha_numeric':
                            if ($value !== '' && !preg_match("/^[a-zA-Z0-9]+$/", $value))
                                $this->errors[$column] = "The $column must contain only letters and numbers.";
                            break;

                        case 'alpha_symbol':
                            if ($value !== '' && !preg_match("/^[a-zA-Z\-\_\!\@\#\$\%\*\[\]\(\)\+]+$/", $value))
                                $this->errors[$column] = "The $column may contain only letters and allowed symbols.";
                            break;

                        case 'alpha_numeric_symbol':
                            if ($value !== '' && !preg_match("/^[a-zA-Z0-9\-\_\!\@\#\$\%\*\[\]\(\)\+]+$/", $value))
                                $this->errors[$column] = "The $column may contain letters, numbers and allowed symbols.";
                            break;

                        case 'alpha_space':
                            if ($value !== '' && !preg_match("/^[a-zA-Z ]+$/", $value))
                                $this->errors[$column] = "The $column may contain only letters and spaces.";
                            break;

                        case 'strong_password':
                            if ($value !== '' && !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $value))
                                $this->errors[$column] = "The $column must have at least 8 characters, 1 uppercase, 1 lowercase and 1 number.";
                            break;

                        case 'phone_eg':
                            if ($value !== '' && !preg_match("/^01[0-2,5][0-9]{8}$/", $value))
                                $this->errors[$column] = "The $column must be a valid Egyptian phone number.";
                            break;

                        case 'longer_than_8_chars':
                            if ($value !== '' && strlen($value)  < 8)
                                $this->errors[$column] = "The $column must be at least 8 characters long.";
                            break;

                        case 'unique':
                            $key = $this->getPrimaryKey();
                            // لو فيه id يبقى تحديث → نستبعده من البحث
                            if (!empty($data[$key])) {
                                if ($this->first([$column => $value], [$key => $data[$key]])) {
                                    $this->errors[$column] = "The $column must be unique.";
                                }
                            } else {
                                if ($this->first([$column => $value])) {
                                    $this->errors[$column] = "The $column must be unique.";
                                }
                            }
                            break;


                        case 'same_password':
                            $password       = $data['Password'];
                            $again_password = $data['again_password'];

                            // بعدين: نتأكد إنهم متطابقين
                            if ($password !== $again_password) {
                                $this->errors[$column] = lang('PASSWORD_DOES_NOT_MATCH');
                            }
                            break;

                        case 'required':
                            $value = $data[$column] ?? null;

                            // لو نص: شيل المسافات
                            if (is_string($value)) {
                                $value = trim($value);
                            }

                            // تعريف الفراغ بشكل دقيق
                            $isEmpty =
                                $value === null ||                      // مفيش مفتاح أصلاً
                                $value === ''   ||                      // نص فاضي بعد الـ trim
                                (is_array($value) && count($value) === 0); // Array فاضية

                            // لو عايز تعتبر "0" قيمة صالحة، الشرط ده بيحافظ عليها
                            if ($isEmpty) {
                                $this->errors[$column] = "The $column is required.";
                                // اختياري: لو الحقل ناقص ما تكمل باقي القواعد عليه
                                // continue 2; // يكمّل للعمود اللي بعده
                            }
                            break;



                        default:
                            $this->errors['rules'] = "The rule '$rule' was not found!";
                            break;
                    }

                    // لو حصل خطأ في هذا الـ column نوقف بقية القواعد
                    if (isset($this->errors[$column])) {
                        break;
                    }
                }
            }
        }

        return empty($this->errors);
    }
}
