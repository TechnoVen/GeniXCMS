<?php

defined('GX_LIB') or die('Direct Access Not Allowed!');
/**
 * GeniXCMS - Content Management System
 *
 * PHP Based Content Management System and Framework
 *
 * @since 0.0.1 build date 20150131
 *
 * @version 1.1.7
 *
 * @link https://github.com/semplon/GeniXCMS
 * @link http://genix.id
 *
 * @author Puguh Wijayanto <psw@metalgenix.com>
 * @copyright 2014-2019 Puguh Wijayanto
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
    System::gZip();

    Rss::create();
    
    System::Zipped();
/* End of file rss.control.php */
/* Location: ./inc/lib/Control/Frontend/rss.control.php */
