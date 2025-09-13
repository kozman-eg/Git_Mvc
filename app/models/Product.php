<?php

namespace Models;

defined('ROOTPATH') or exit('access not allowed');

class Product
{
    use \Core\Model;

    public function __construct()
    {
        // هنا تقدر تغير الـ primaryKey
        $this->getPrimaryKey('ID');
    }
    protected $table = "products";

    // الأعمدة المسموح إدخالها
    protected $allowedColumns = [
        'Name',
        'Description',
        'Price',
        'Quantity',
        'CategoryID',
    ];

    /* ***********************************
    * قواعد التحقق (Validation rules)
    */
    protected $onInsertValidationRules = [
        'Name' => [
            'required',
            'alpha_space',
            'unique',
        ],
        'Description' => [
            'required',
        ],
        'Price' => [
            'required',
            'numeric',
        ],
        'Quantity' => [
            'required',
            'numeric',
        ],
        'CategoryID' => [
            'required',
            'numeric',
        ],
    ];

    protected $onUpdateValidationRules = [
        'Name' => [
            'required',
            'alpha_space',
        ],
        'Price' => [
            'required',
            'numeric',
        ],
        'Quantity' => [
            'required',
            'numeric',
        ],
    ];

    // استرجاع المنتجات حسب القسم
    public function getByCategory($categoryID)
    {
        $query = "SELECT * FROM $this->table WHERE CategoryID = :cat ORDER BY created_at DESC";
        return $this->query($query, ['cat' => $categoryID]);
    }

    // تحديث الكمية
    public function updateStock($productID, $newQty)
    {
        $query = "UPDATE $this->table SET Quantity = :qty, updated_at = NOW() WHERE ID = :id";
        return $this->query($query, [
            'qty' => $newQty,
            'id' => $productID,
        ]);
    }
}
