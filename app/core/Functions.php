<?php

defined('ROOTPATH') or exit('access not allowed');

function check_extensions()
{
    $require_extensions = [
        'gd',
        'mysqli',
        'pdo_mysql',
        'pdo_sqlite',
        'exif',
        'mbstring',
        'fileinfo',
        'curl',
        'intl',
    ];
    $not_loaded = [];
    foreach ($require_extensions as $ext) {

        if (!extension_loaded($ext)) {

            $not_loaded[] = $ext;
        }
    }
    if (!empty($not_loaded)) {

        show("Plese load following extensions in Your  php.ini file: <br>" . implode("<br>", $not_loaded));
        die;
    }
}
check_extensions();

/***  */

function langs($phrase)
{
    $filePath = dirname(__DIR__, 2) . '/languages/english.php';  // يرجع للمجلد الرئيسي


    static $langs = null;

    if ($langs === null) {
        $langs = file_exists($filePath) ? include $filePath : [];
    }

    if (isset($langs[$phrase])) {
        return $langs[$phrase];
    }


    // تحديث الملف إذا كنا في وضع التطوير
    if (DEV_MODE) {

        // توليد ترجمة مؤقتة
        $generated = ucwords(strtolower(str_replace('_', ' ', $phrase)));
        $langs[$phrase] = $generated;

        $content = "<?php\n\nreturn [\n";
        foreach ($langs as $k => $v) {
            $content .= "    '" . addslashes($k) . "' => '" . addslashes($v) . "',\n";
        }
        $content .= "];\n";
        file_put_contents($filePath, $content);

        return $generated;
    }
}
function lang($key)
{
    $val = langs(strtoupper(str_replace(' ', '_', $key)));

    return $val;
}



/** */
function show($stuff)
{

    echo "<pre>";
    print_r($stuff);
    echo "<pre>";
}
function esc($str)
{
    return htmlspecialchars($str);
}
function redirect($path)
{

    header("Location: " . ROOT . "/" . $path);
    die;
}



/*
** Check Items Function v1.0
** Function to check Items In Database [ Function Accept Parameter]
** $select = the itme to select [ Ecample: user , item , category]
** $form  = the table to select from [ Ecample: user , item , category]
** $value = the value of select [ Ecample: kozman , box , Electronics]
*/
function checkItem($select, $from, $value)
{
    global $con;
    $stmtitem = $con->prepare("SELECT $select FROM $from WHERE $select =?");
    $stmtitem->execute(array($value));
    $count = $stmtitem->rowCount();
    return $count;
}

/* */
function get_image(mixed $file = '', string $type = 'POST'): string
{
    $file = $file ?? '';
    if (file_exists($file)) {
        return ROOT . '/' . $file;
    }
    if ($type == 'user') {
        return ROOT . "/assets/images/user.jpg";
    } else {
        return ROOT . "/assets/images/not_image.jpg";
    }
}
/** */
function remove_images_from_content($content, $folder = "uploads/")
{
    if (!file_exists($folder)) {
        mkdir($folder, 007, true);
        file_put_contents($folder, "index.php", "Access Denied!");
    }
    //remove inages from content
    preg_match_all('/<img[^>]+>/', $content, $matches);

    $new_content = $content;

    if (is_array($matches) && count($matches) > 0) {
        $image_class = new \Models\Image();
        foreach ($matches[0] as $match) {

            if (strstr($match, "http")) {
                //ignore images with links already
                continue;
            }
            //get the src
            preg_match('/src="[^"]+>/', $match, $matches2);

            //get the filename
            preg_match('/date-filename="[^"]+>/', $match, $matches2);

            if (strstr($matches2[0], 'date:')) {

                $parts = explode(",", $matches2[0]);
                $basename = $matches3[0] ?? 'basename.jpg';
                $basename = str_replace('data-filename="', "", $basename);

                $filename = $folder . "img_" . sha1(rand(0, 9999999999)) . $basename;
                $new_content = str_replace($parts[0] . "," . $parts[1], 'src="' . $filename, $new_content);
                file_put_contents($filename, base64_decode($parts[1]));

                //resize image
                $image_class->resize($filename, 1000);
            }
        }
    }
}

function get_pagination_vars(): array
{
    $vars = [];
    $vars['page'] = $_GET['page'] ?? 1;
    $vars['page'] = (int)$vars['page'];
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}
/** */
function message(string $msg = '', bool $clear = false)
{
    $ses = new \Models\Session();

    // إذا تم تمرير رسالة، احفظها في الجلسة
    if (!empty($msg)) {
        $ses->set('message', $msg);
        return true;
    }

    // إذا لم يتم تمرير رسالة، حاول قراءتها
    if (!empty($ses->get('message'))) {
        $msg = $ses->get('message');

        // إذا طُلب حذف الرسالة بعد قراءتها
        if ($clear) {
            $ses->pop('message');
        }

        return $msg;
    }

    return false;
}
/** desplay inbout value after a page refresh */

/** return a user readeble date format*/
function get_date($date)
{
    return date("jS M, Y", strtotime($date));
}
// return URL variavles
function URL($key): mixed
{
    $URL = $_GET['url'] ?? 'Home';
    $URL = explode("/", filter_var(rtrim($URL, "/"), FILTER_SANITIZE_URL));

    switch ($key) {
        case 'page':
        case '0':
            return $URL[0] ?? null;
            break;
        case 'section':
        case 'slug':
        case '1':
            return $URL[1] ?? null;
            break;
        case 'action':
        case '2':
            return $URL[2] ?? null;
            break;
        case 'id':
        case '3':
            return $URL[3] ?? null;
            break;
        default:
            return null;
            break;
    }
}
function downloadImage($url, $path) {
    $ch = curl_init($url);
    $fp = fopen($path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    fclose($fp);
    return $error === '';
}
