<?php

class Menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function get_menu_russian_name($name) {
        switch ($name) {
            case 'index':
                $item = "Главная";
                break;
            case 'news':
                $item = 'Новости';
                break;
            case 'buy':
                $item = 'Как купить';
                break;
            case 'garant':
                $item = 'Услуги';
                break;
            case 'supplier':
                $item = 'Поставщикам';
                break;
            case 'guarantee':
                $item = 'Гарантии';
                break;
            case 'contact':
                $item = 'Контакты';
                break;
            case 'about':
                $item = 'О нас';
                break;
            case 'discount':
                $item = 'Скидки';
                break;
            case 'login':
                $status = $this->session->userdata('email');
                if ($status == '') {
                    $item = 'Вход';
                } // end if $status==''
                else {
                    $item = 'Выход';
                } // end else 
                break;
        }
        return $item;
    }

    public function get_top_menu() {
        $list = "";
        $query = "select * from top_menu order by id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $menu_name = $this->get_menu_russian_name($row->name);
            if ($row->name != 'index') {
                if ($row->name != 'login') {
                    $link = $this->config->item('base_url') . "index.php/menu/page/" . $row->link . "";
                } // end if $row->name!=login
                else {
                    $status = $this->session->userdata('email');
                    if ($status == '') {
                        $link = $this->config->item('base_url') . "index.php/menu/page/" . $row->link . "";
                    } // end if $stattus == ''
                    else {
                        $link = $this->config->item('base_url') . "index.php/user/logoutdone/";
                    } // end else 
                } // end else
            } // end if $row->name!='index'
            else {
                $link = $this->config->item('base_url');
            } // end else     
            $list.="<li style='min-width: 10%;'><a href='$link' title='$menu_name'>$menu_name</a></li>";
        } // end foreach
        return $list;
    }

    function get_page_content($item) {
        $list = "";
        if ($item == 'news') {
            $list.="<br>";
            $query = "select * from news order by added desc limit 0, 7";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $date = date('Y-m-d', $row->added);
                    $list.="<div class='row'>";
                    $list.="<span class='span9'>" . $row->content . "</span>";
                    $list.="</div>";
                } // end foreach
            } // end if $num > 0
            else {
                
            } // end else
            $list.="</table>";
        } // end if $item=='news'
        else {
            $query = "select * from top_menu where link='$item'";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $list.="<br><br>" . $row->content;
            } // end foreach
        } // end else
        return $list;
    }

    function get_admin_page2($item) {
        $list = "";
        switch ($item) {
            case 'news':                 
                $id=9719147;
                $title = "Новости";
                break;
            case 'buy': 
                $id=9719146;
                $title = "Как купить";
                break;
            case 'garant':                
                $id=9719145;
                $title = "Услуги гаранта";
                break;
            case 'supplier':
                $id=9719143;
                $title = "Поставщикам";
                break;
            case 'guarantee':                
                $id=9719144;
                $title = "Гарантии";
                break;
            case 'contact':                
                $id=3068;
                $title = "Контакты";
                break;
            case 'about':
                $id=1;
                $title = "О нас";
                break;
        }

        if ($id != 9719147) {
            $query = "select * from content where cntID=$id and cntLanguageID=2";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $id = $row->cntID;
                $content = $row->cntBody;
            }
            $list.="<form id='user_page' method='post' action='" . $this->config->item('base_url') . "index.php/menu/update_page'>";
            $list.="<input type='hidden' id='id' name='id' value='$id'>";
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>$title</span>";
            $list.="<span class='2'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></span>";
            $list.="</div>";
            $list.="<br><div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script>
                </div>";
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span2'><button id='update_user_page' style='background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fdfbec 0%, #e3c271 48%, #d9ad43 50%, #ce9428 100%) repeat scroll 0 0;
                                                                            border: 0 none;
                                                                            border-radius: 15px;
                                                                            box-shadow: 0 0 20px 1px #000000;
                                                                            color: #000000;
                                                                            font-size: 16px;
                                                                            font-weight: bold;
                                                                            line-height: 50px;
                                                                            margin: 10px auto;
                                                                            text-align: center;
                                                                            text-decoration: none;
                                                                            text-shadow: 1px 1px 1px #ffffff;
                                                                            text-transform: uppercase;
                                                                            width: 150px;'>Ok</button></span>";
            $list.="</div>";
        } // end if $id!=9719147
        else {
            $list.="<br/><div class=''>";
            $list.="<form class='calc_form'>";
            $list.= "<br>";
            $list.= "<table align='center' border='0' style='width: 100%;'>";

            $list.="<table align='center' border='0' width=100%>";

            if ($this->session->userdata('type') == 3) {
                // It is admin
                $list.="<tr>";
                $list.="<td colspan='6' align='center'><span style=''><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color:black;font-weight:bolder;'>Меню</a></span></td>";
                $list.="</tr>";
            }

            $list.= "<tr>";
            $list.="<td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='start'></td><td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='end'></td><td style='padding:15px;'><a href='#' onClick='return false;' id='search_news' style='color:black;'>Ok</a></td><td style='padding:15px;'><a href='#' onClick='return false;' id='add_news' style='color:black;padding:15px;'>Добавить</a></td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.= "<td align='center' colspan='6' ><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
            $list.= "</tr>";

            $list.= "<tr>";
            $list.="<td colspan='6' align='center' style='padding:15px;'><span id='news_container'></span></td>";
            $list.="</tr>";

            $list.= "<tr>";
            $list.="<td colspan='6' align='center' style='padding:15px;'><span id='news_err'></span></td>";
            $list.="</tr>";

            $list.="</table><br>";

            $list.="</form>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function get_admin_page($id) {

        $list = "";
        switch ($id) {
            case 9719147:
                $title = "Новости";
                break;
            case 9719146:
                $title = "Как купить";
                break;
            case 9719145:
                $title = "Услуги гаранта";
                break;

            case 9719143:
                $title = "Поставщикам";
                break;

            case 9719144:
                $title = "Гарантии";
                break;

            case 3068:
                $title = "Контакты";
                break;

            case 1:
                $title = "О нас";
                break;
        }

        if ($id != 9719147) {
            $query = "select * from content where cntID=$id and cntLanguageID=2";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $id = $row->cntID;
                $content = $row->cntBody;
            }
            $list.="<form id='user_page' method='post' action='" . $this->config->item('base_url') . "index.php/menu/update_page'>";
            $list.="<input type='hidden' id='id' name='id' value='$id'>";
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>$title</span>";
            $list.="<span class='2'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></span>";
            $list.="</div>";
            $list.="<br><div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script>
                </div>";
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span2'><button id='update_user_page' style='background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fdfbec 0%, #e3c271 48%, #d9ad43 50%, #ce9428 100%) repeat scroll 0 0;
                                                                            border: 0 none;
                                                                            border-radius: 15px;
                                                                            box-shadow: 0 0 20px 1px #000000;
                                                                            color: #000000;
                                                                            font-size: 16px;
                                                                            font-weight: bold;
                                                                            line-height: 50px;
                                                                            margin: 10px auto;
                                                                            text-align: center;
                                                                            text-decoration: none;
                                                                            text-shadow: 1px 1px 1px #ffffff;
                                                                            text-transform: uppercase;
                                                                            width: 150px;'>Ok</button></span>";
            $list.="</div>";
        } // end if $id!=9719147
        else {
            $list.="<br/><div class=''>";
            $list.="<form class='calc_form'>";
            $list.= "<br>";
            $list.= "<table align='center' border='0' style='width: 100%;'>";

            $list.="<table align='center' border='0' width=100%>";

            if ($this->session->userdata('type') == 3) {
                // It is admin
                $list.="<tr>";
                $list.="<td colspan='6' align='center'><span style=''><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color:black;font-weight:bolder;'>Меню</a></span></td>";
                $list.="</tr>";
            }

            $list.= "<tr>";
            $list.="<td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='start'></td><td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='end'></td><td style='padding:15px;'><a href='#' onClick='return false;' id='search_news' style='color:black;'>Ok</a></td><td style='padding:15px;'><a href='#' onClick='return false;' id='add_news' style='color:black;padding:15px;'>Добавить</a></td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.= "<td align='center' colspan='6' ><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
            $list.= "</tr>";

            $list.= "<tr>";
            $list.="<td colspan='6' align='center' style='padding:15px;'><span id='news_container'></span></td>";
            $list.="</tr>";

            $list.= "<tr>";
            $list.="<td colspan='6' align='center' style='padding:15px;'><span id='news_err'></span></td>";
            $list.="</tr>";

            $list.="</table><br>";

            $list.="</form>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function get_news_by_date($start, $end) {
        $list = "";
        $news = array();
        $unix_start = strtotime($start);
        $unix_end = strtotime($end);
        $query = "select * from news "
                . "where added between $unix_start and $unix_end "
                . "order by added desc";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $news[] = $row;
            } // end foreach

            foreach ($news as $new) {
                $date = date('Y-m-d h:i:s', $new->added);
                $list.="<table align='center' width='100%' border='0'>";

                $list.="<tr>";
                $list.="<td style='padding:5px;' width='80%'>$new->title</td>";
                $list.="<td style='padding:5px;' width='10%'>$date</td>";
                $list.="<td style='padding:5px;' width='5%'><a href='#' onClick='return false;' style='color:black;'><img id='edit_news_$new->id'   src='/games/assets/images/edit.png' width='32' height='32' title='Редактировать'></a></td>";
                $list.="<td style='padding:5px;' width='5%'><a href='#' onClick='return false;' style='color:black;'><img id='delete_news_$new->id' src='/games/assets/images/delete.png' width='32' height='32' title='Удалить'></a></td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td colspan='2' align='center'><span id='news_err_$new->id'></span></td>";
                $list.="</tr>";

                $list.="</table>";
            } // end foreach            
        } // end if $num > 0
        else {
            $list.="<p align='center'>Ничего не найдено</p>";
        } // end else
        return $list;
    }

    function update_user_page($id, $body) {
        $list = "";
        $query = "update content "
                . "set cntBody='$body' "
                . "where cntID=$id "
                . "and cntLanguageID=2";
        $this->db->query($query);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Страница успешно обновлена. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_add_news_modal_box() {
        $list = "";
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Добавить новость</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>                         
                
                <table align='center' border='0'>             
                
                <tr>
                <td style='padding:15px;'>Заголовок*</span><td style='padding:15px;'><input type='text' id='title' style='width:395px;'></td>
                </tr>

                <tr>
                <td style='padding:15px;' colspan='2' align='center'><textarea name='body' ></textarea>
                <script>
                CKEDITOR.replace('body');
                </script></td>
                </tr>              
                
                <tr>
                <td colspan='2' style='padding:15px;'><span style='text-align:center' id='add_news_err'></span></td>
                </tr>
                
                </table>               
                                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_add_news'>Отмена</button></span>
                <span align='center'><button type='button' class='btn btn-primary'  id='add_news_btn'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";
        return $list;
    }

    public function add_news($title, $body) {
        $date = time();
        $query = "insert into news "
                . "(title, content, added) "
                . "values(" . $this->db->escape($title) . ","
                . "" . $this->db->escape($body) . ", "
                . "'" . $date . "')";
        $this->db->query($query);
    }

    public function get_edit_news_box($id) {
        $list = "";

        $query = "select * from news where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $title = $row->title;
            $content = $row->content;
        }

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Редактировать новость</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>                         
                
                <table align='center' border='0'>             
                
                <tr>
                <td style='padding:15px;'>Заголовок*</span><td style='padding:15px;'><input type='text' id='title' style='width:395px;' value='$title'></td>
                </tr>

                <tr>
                <td style='padding:15px;' colspan='2' align='center'><textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script></td>
                </tr>              
                
                <tr>
                <td colspan='2' style='padding:15px;'><span style='text-align:center' id='add_news_err'></span></td>
                </tr>
                
                </table>               
                                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_add_news'>Отмена</button></span>
                <span align='center'><button type='button' class='btn btn-primary'  id='update_news_$id'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";
        return $list;
    }

    public function update_news($id, $title, $body) {
        $query = "update news "
                . "set title='$title', "
                . "content='$body' "
                . "where id=$id";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
        $list = "Новость успешно обновлена";
        return $list;
    }

    public function del_news($id) {
        $query = "delete from news where id=$id";
        $this->db->query($query);
        $list = "Новость успешно удалена";
        return $list;
    }

}
