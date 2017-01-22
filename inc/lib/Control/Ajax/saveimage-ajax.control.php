<?php

defined('GX_LIB') or die('Direct Access Not Allowed!');
/*
 * GeniXCMS - Content Management System
 *
 * PHP Based Content Management System and Framework
 * @package GeniXCMS
 * @since 0.0.1 build date 20141003
 * @version 1.0.0
 * @link https://github.com/semplon/GeniXCMS
 * @link http://genixcms.org
 * @author Puguh Wijayanto <psw@metalgenix.com>
 * @copyright 2014-2016 Puguh Wijayanto
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
$data = Router::scrap($param);
$token = (SMART_URL) ? $data['token'] : Typo::cleanX($_GET['token']);
$url = Site::canonical();
if (isset($token) && Token::isExist($token) && Http::validateUrl($url)) {
    if (User::access(2)) {
        // A list of permitted file extensions
        $allowed = array('png', 'jpg', 'jpeg', 'gif');
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error"}';
                exit;
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], GX_PATH.'/assets/media/images/'.$_FILES['file']['name'])) {
                $tmp = GX_PATH.'/assets/media/images/'.$_FILES['file']['name'];
                if (Image::isPng($tmp)) {
                    Image::compressPng($tmp);
                } elseif (Image::isJpg($tmp)) {
                    Image::compressJpg($tmp);
                }
                echo Site::$url.'/assets/media/images/'.$_FILES['file']['name'];
                //echo '{"status":"success"}';
                exit;
            }
        }
    } else {
        echo '{"status":"error"}';
    }
} else {
    echo '{"status":"Token not exist"}';
}
