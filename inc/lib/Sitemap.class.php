<?php

defined('GX_LIB') or die('Direct Access Not Allowed!');
/**
 * GeniXCMS - Content Management System.
 *
 * PHP Based Content Management System and Framework
 *
 * @since 0.0.1 build date 20141005
 *
 * @version 1.0.0
 *
 * @link https://github.com/semplon/GeniXCMS
 * @link http://genixcms.org
 *
 * @author Puguh Wijayanto <psw@metalgenix.com>
 * @copyright 2014-2016 Puguh Wijayanto
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class Sitemap
{
    public function __construct()
    {
    }

    public static function create($count = 20, $url = 'post', $type = 'post', $class = 'Url')
    {
        $var = array(
                'num' => $count,
                'type' => $type,
            );
        $posts = Posts::recent($var);
        header('Content-Type: text/xml');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            ';
            //print_r($posts);
        if (!isset($posts['error'])) {
            

            foreach ($posts as $p) {
                
                $xml .= '
                    <url>
                        <loc>'.$class::$url($p->id).'</loc>
                    </url>
                    ';
            }
        }

        $xml .= '
            </urlset>
                ';
        echo $xml;
    }
}

/* End of file Sitemap.class.php */
/* Location: ./inc/lib/Sitemap.class.php */
