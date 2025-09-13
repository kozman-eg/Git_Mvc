<?php
// keyapi viRrHm8CjLmwDTbT4IJnwBhBq1mWdgBrffdRCFRkhEmlnrImhMn1nMMj

namespace Controllers;

class CariatImage
{
    public function index()
    {
        // 1. الاتصال بقاعدة البيانات
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "shop";

        $conn = new \mysqli($host, $user, $pass, $dbname);
        if ($conn->connect_error) {
            die("فشل الاتصال: " . $conn->connect_error);
        }

        // 2. جلب أسماء المنتجات
        $sql = "SELECT ID, Name FROM products";
        $result = $conn->query($sql);

        // 3. مسار حفظ الصور
        $savePath = __DIR__ . "/../../public/assets/images/products/";
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        // 4. مفتاح API من Pexels
        $pexelsApiKey = "viRrHm8CjLmwDTbT4IJnwBhBq1mWdgBrffdRCFRkhEmlnrImhMn1nMMj";

        while ($row = $result->fetch_assoc()) {
            $productId = $row['ID'];
            $productName = urlencode($row['Name']);

            // 5. طلب 5 صور لكل منتج
            $pexelsUrl = "https://api.pexels.com/v1/search?query={$productName}&per_page=5";
            //$pexelsUrl = "https://api.pexels.com/v1/search?query=iPhone&per_page=5";


            $ch = curl_init($pexelsUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: $pexelsApiKey"
            ]);
            
            $response = curl_exec($ch);
            //var_dump($response);
            if (curl_errno($ch)) {
                echo "❌ خطأ في cURL: " . curl_error($ch) . "<br>";
                continue;
            }
            curl_close($ch);

            $data = json_decode($response, true);
            if (!isset($data['photos'])) {
                echo "❌ لم يتم جلب صور للمنتج: {$row['Name']}<br>";
                continue;
            }

            foreach ($data['photos'] as $index => $photo) {
                $imageUrl = $photo['src']['medium']; // حجم متوسط
                
                // 1. نجهز اسم المنتج كـ slug صالح للملفات
                $productSlug = preg_replace("/[^a-zA-Z0-9_-]/", "_", $row['Name']); 

                // 2. نستخدمه في اسم الصورة
                $filename = $productId . "_" . ($index+1) . "_" . $productSlug . ".jpg";

                $imageData = file_get_contents($imageUrl);
                if ($imageData !== false) {
                    file_put_contents($savePath . $filename, $imageData);

                    // حفظ المسار في قاعدة البيانات
                    $imagePathInDb =  $filename;
                    $insert = "INSERT INTO product_images (ProductID, Image) 
                               VALUES ($productId, '$imagePathInDb')";
                    $conn->query($insert);

                    echo "✅ تم حفظ صورة للمنتج: {$row['Name']}<br>";
                } else {
                    echo "❌ فشل تحميل صورة للمنتج: {$row['Name']}<br>";
                }
            }
        }

        $conn->close();
    }
}
?>
