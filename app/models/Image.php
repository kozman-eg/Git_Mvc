<?php

namespace Models;

defined('ROOTPATH') or exit('Access not allowed');


class Image
{
    use \Core\Model;
    protected $table = 'images';



    // تغيير حجم الصورة
    public function resize($filename, $max_size = 700)
    {
        // تحقق إن كان الملف موجود
        if (!file_exists($filename)) {
            return false;
        }

        // احصل على أبعاد الصورة ونوعها
        list($width, $height, $type) = getimagesize($filename);

        // لا نحتاج تغيير الحجم إذا كانت الصورة أصغر من الحجم الأقصى
        if ($width <= $max_size && $height <= $max_size) {
            return true;
        }

        // حساب النسبة الجديدة
        if ($width > $height) {
            $new_width = $max_size;
            $new_height = intval(($height / $width) * $max_size);
        } else {
            $new_height = $max_size;
            $new_width = intval(($width / $height) * $max_size);
        }

        // إنشاء صورة جديدة بحسب نوع الصورة الأصلية
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($filename);
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($filename);
                break;
            case IMAGETYPE_WBMP:
                $src = imagecreatefromwbmp($filename);
                break;
            default:
                return false; // نوع غير مدعوم
        }

        $dst = imagecreatetruecolor($new_width, $new_height);
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // حفظ الصورة بنفس الاسم
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($dst, $filename, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($dst, $filename, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($dst, $filename);
                break;
            case IMAGETYPE_WEBP:
                imagewbmp($dst, $filename);
                break;
        }

        // تنظيف الذاكرة
        imagedestroy($src);
        imagedestroy($dst);

        return true;
    }
    // جلب كل الصور
    public function getAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY id DESC";
        return $this->query($query);
    }

    // جلب صورة بواسطة ID
    public function getById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        return $this->query($query, ['id' => $id]);
    }


    // حذف صورة
    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        return $this->query($query, ['id' => $id]);
    }
    // إضافة صورة
    /*
    public function insert($data)
    {
        return $this->insertInto($this->table, $data);
    }*/
}
