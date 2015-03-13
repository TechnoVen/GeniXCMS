<?php if(!defined('GX_LIB')) die("Direct Access Not Allowed!");
/**
* GeniXCMS - Content Management System
* 
* PHP Based Content Management System and Framework
*
* @package GeniXCMS
* @since 0.0.1 build date 20141006
* @version 0.0.2
* @link https://github.com/semplon/GeniXCMS
* @link http://genixcms.org
* @author Puguh Wijayanto (www.metalgenix.com)
* @copyright 2014-2015 Puguh Wijayanto
* @license http://www.opensource.org/licenses/mit-license.php MIT
*
*/


if(isset($_GET['act'])) $act = $_GET['act']; else $act = "";
switch ($act) {
    case 'add':
        # code...
        $data[] = '';
        switch (isset($_POST['submit'])) {
            case true:
                # code...
                if (!isset($_POST['token']) || !Token::isExist($_POST['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if(isset($alertred)){
                    $data['alertred'] = $alertred;
                }else{
                    if (!isset($_POST['date']) || $_POST['date'] == "") {
                        # code...
                        $date = date("Y-m-d H:i:s");
                    }else{
                        $date = $_POST['date'];
                    }
                    $vars = array(
                                    'title' => Typo::cleanX($_POST['title']),
                                    'cat' => $_POST['cat'],
                                    'content' => Typo::cleanX($_POST['content']),
                                    'date' => $date,
                                    'type' => 'post',
                                    'author' => Session::val('username'),
                                    'status' => $_POST['status'],
                                );
                    //print_r($vars);
                    Posts::insert($vars);
                    $data['alertgreen'][] = "Post : {$_POST['title']} Added.";
                }
                    
                break;
            
            default:
                # code...
                
                break;
        }
        System::inc('posts_form', $data);
        //echo "add";
        break;

    case 'edit':
        //echo "edit";
        switch (isset($_POST['submit'])) {
            case true:
                # code...
                if (!isset($_POST['token']) || !Token::isExist($_POST['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if (isset($alertred)) {
                    # code...
                    $data['alertred'] = $alertred;
                }else{
                    $moddate = date("Y-m-d H:i:s");
                    $vars = array(
                                    'title' => Typo::cleanX($_POST['title']),
                                    'cat' => $_POST['cat'],
                                    'content' => Typo::cleanX($_POST['content']),
                                    'modified' => $moddate,
                                    'date' => $_POST['date'],
                                    'status' => $_POST['status'],
                                );
                    //print_r($vars);
                    
                    Posts::update($vars);
                    $data['alertgreen'][] = "Post : <b>{$_POST['title']}</b> Updated.";
                }
                
                break;
            
            default:
                # code...
                //System::inc('posts_form', $data);
                break;
        }

        $data['post'] = Db::result("SELECT * FROM `posts` WHERE `id` = '{$_GET['id']}' ");
        System::inc('posts_form', $data);

        break;


    default:
        # code...
        if(isset($_GET['act']) && $_GET['act'] == 'del'){
            if(isset($_GET['id'])){
                if (!isset($_GET['token']) || !Token::isExist($_GET['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if (isset($alertred)) {
                    # code...
                    $data['alertred'] = $alertred;
                }else{

                    $title = Posts::title($_GET['id']);
                    $del = Posts::delete($_GET['id']);
                    //echo $title['error'];
                    if(isset($del['error'])){
                        $data['alertred'][] = $del['error'];
                    }else{
                        $data['alertgreen'][] = 'Post <b>'.$title.'</b> Removed';
                    }
                }
                
            }else{
                $data['alertred'][] = 'No ID Selected';
            }
            
        }
        if(isset($_POST['action'])) {
            $action = $_POST['action'];
        }else{
            $action = '';
        }
        if(isset($_POST['post_id'])) { $post_id = $_POST['post_id']; } else { $post_id = ""; }
        switch ($action) {

            case 'publish':
                # code...
                if (!isset($_POST['token']) || !Token::isExist($_POST['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if (isset($alertred)) {
                    # code...
                    $data['alertred'] = $alertred;
                }else{
                    foreach ($post_id as $id) {
                        # code...
                        Posts::publish($id);
                    }
                }
                break;
            case 'unpublish':
                # code...
                if (!isset($_POST['token']) || !Token::isExist($_POST['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if (isset($alertred)) {
                    # code...
                    $data['alertred'] = $alertred;
                }else{
                    foreach ($post_id as $id) {
                        # code...
                        Posts::unpublish($id);
                    }
                }
                break;
            case 'delete':
                # code...
                if (!isset($_POST['token']) || !Token::isExist($_POST['token'])) {
                    // VALIDATE ALL
                    $alertred[] = TOKEN_NOT_EXIST;
                }
                if (isset($alertred)) {
                    # code...
                    $data['alertred'] = $alertred;
                }else{
                    foreach ($post_id as $id) {
                        # code...
                        Posts::delete($id);
                    }
                }
                break;
            
            default:
                # code...
                break;
        }

        // search query 
        $where = "";
        $qpage = "";
        if(isset($_GET['q']) && $_GET['q'] != ''){
            $where .= "AND (`title` LIKE '%%{$_GET['q']}%%' OR `content` LIKE '%%{$_GET['q']}%%') ";
            $qpage .= "&q={$_GET['q']}";
        }
        if(isset($_GET['cat']) && $_GET['cat'] != ''){
            $where .= "AND `cat` = '{$_GET['cat']}' ";
            $qpage .= "&cat={$_GET['cat']}";
        }
        if(isset($_GET['from']) && $_GET['from'] != ''){
            $where .= "AND `date` >= '{$_GET['from']}' ";
            $qpage .= "&from={$_GET['from']}";
        }
        if(isset($_GET['to']) && $_GET['to'] != ''){
            $where .= "AND `date` <= '{$_GET['to']}' ";
            $qpage .= "&to={$_GET['to']}";
        }
        if(isset($_GET['status']) && $_GET['status'] != ''){
            $where .= "AND `status` LIKE '%%{$_GET['status']}%%' ";
            $qpage .= "&status={$_GET['status']}";
        }
        

        $max = "10";
        if(isset($_GET['paging'])){
            $paging = $_GET['paging'];
            $offset = ($_GET['paging']-1)*$max;
        }else{
            $paging = 1;
            $offset = 0;
        }


        
        $data['posts'] = Db::result("SELECT * FROM `posts`
                        WHERE `type` = 'post' {$where} 
                        ORDER BY `date` DESC 
                        LIMIT {$offset},{$max}");
        $data['num'] = Db::$num_rows;
        System::inc('posts', $data);

        $page = array(
                    'paging' => $paging,
                    'table' => 'posts',
                    'where' => "`type` = 'post' ".$where,
                    'max' => $max,
                    'url' => 'index.php?page=posts'.$qpage,
                    'type' => 'pager'
                );
        echo Paging::create($page);
        break;
}

/* End of file posts.control.php */
/* Location: ./inc/lib/Control/Backend/posts.control.php */