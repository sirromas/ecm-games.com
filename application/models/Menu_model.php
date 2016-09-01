<?php
class Menu_model extends CI_Model {
	public $allowed_urls;
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->library ( 'session' );
		$this->load->model ( 'user_model' );
		$this->allowed_urls = $this->get_nav_urls ();
	}
	public function get_menu_russian_name($name) {
		switch ($name) {
			case 'index' :
				$item = "Главная";
				break;
			case 'news' :
				$item = 'Новости';
				break;
			case 'buy' :
				$item = 'Как купить';
				break;
			case 'garant' :
				$item = 'Услуги';
				break;
			case 'supplier' :
				$item = 'Поставщикам';
				break;
			case 'guarantee' :
				$item = 'Гарантии';
				break;
			case 'contact' :
				$item = 'Контакты';
				break;
			case 'about' :
				$item = 'О нас';
				break;
			case 'discount' :
				$item = 'Скидки';
				break;
			case 'login' :
				$status = $this->session->userdata ( 'email' );
				if ($status == '') {
					$item = 'Вход';
				}  // end if $status==''
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
		$result = $this->db->query ( $query );
		foreach ( $result->result () as $row ) {
			$menu_name = $this->get_menu_russian_name ( $row->name );
			if ($row->name != 'index') {
				if ($row->name != 'login') {
					$link = $this->config->item ( 'base_url' ) . "index.php/menu/page/" . $row->link . "";
				} else {
					$status = $this->session->userdata ( 'email' );
					if ($status == '') {
						$link = $this->config->item ( 'base_url' ) . "index.php/menu/page/" . $row->link . "";
					} else {
						$link = $this->config->item ( 'base_url' ) . "index.php/user/logoutdone/";
					} // end else
				} // end else
			} else {
				$link = $this->config->item ( 'base_url' );
			} // end else
			$list .= "<li style='min-width: 10%;'><a href='$link' title='$menu_name'>$menu_name</a></li>";
		} // end foreach
		return $list;
	}
	function get_nav_urls() {
		$urls = array ();
		$query = "select * from nav_allowed_urls";
		$result = $this->db->query ( $query );
		foreach ( $result->result () as $row ) {
			$urls [] = $row->url;
		}
		return $urls;
	}
	
	function full_news($id) {
		$list = "";
		$query = "select * from news where id=$id";
		$result = $this->db->query ( $query );
		$num = $result->num_rows ();
		if ($num > 0) {
			foreach ( $result->result () as $row ) {
				$news = $row;
			}
			$doc = new DOMDocument ();
			$doc->loadHTML ( '<?xml encoding="utf-8" ?>' . $news->content );
			foreach ( $doc->getElementsByTagName ( 'img' ) as $image ) {
				foreach ( array (
						'width',
						'height' 
				) as $attribute_to_remove ) {
					if ($image->hasAttribute ( $attribute_to_remove )) {
						$image->removeAttribute ( $attribute_to_remove );
					}
					$image->setAttribute ( 'class', 'img-responsive' );
				} // end foreach
			} // end foreach
			$content = $doc->saveHTML ();
			$list .= "<div class='news_block'>";
			$list .= "$content";
			$list .= "</div>";
		}
		return $list;
	}
	function get_page_content($item) {
		$list = "";
		$status = $this->user_model->validate_user ();
		if ($item == 'news') {
			$list .= "<div class = '' style='text-align:left;'>";
			$query = "select * from news order by added desc limit 0, 3";
			$result = $this->db->query ( $query );
			$num = $result->num_rows ();
			if ($num > 0) {
				foreach ( $result->result () as $row ) {
					$id = $row->id;
					$date = date ( 'm-d-Y', $row->added );
					$list .= "<a href = '" . $this->config->item ( 'base_url' ) . "index.php/menu/fullnews/$id' class = 'list-group-item' style='border-style: hidden;'>
                    <p><span style='font-weight:bold;'>$date &nbsp | &nbsp</span>  $row->title  ....<br><br><br></p>
                    </a><hr>";
				} // end foreach
			}
			$list .= "</div>";
		} else {
			if ($item == 'discount') {
				$list .= $this->get_disocunt_page ();
			} else {
				$query = "select * from top_menu where link='$item'";
				$result = $this->db->query ( $query );
				foreach ( $result->result () as $row ) {
					if ($status) {
						$list .= $row->content;
					} else {
						$list .= "<br>" . $row->content;
					}
				} // end foreach
			} // end else
		} // end else
		return $list;
	}
	function get_disocunt_page() {
		$list = "";
		$list .= "<br><span>Делая заказ в нашем игровом магазине, система скидок запомнит вашу покупку, которую вы сделали, указав 'e-mail' при регистрации. Запомнив вашу покупку система дает вам скидку и делая заказ в следующий раз, вы получаете скидку. </span><br><br>";
		
                /*
		$list .= " 
                
                    <div class='row' style='text-align:center;margin:auto;'>
                    
                    <div class='diagram-item' id='discount5' data-discount='5' id='two-block' style='float:left;position: relative;width:70px;margin-right:40px;margin-left:15px;'>
                    <div class='block-action-text5'>5%</div>
                    <div class='text-block5'>Premium</div>
                    <div class='help-text' id='help5' style='display: none; opacity: 1;'>
                     скидка 5% (покупка на сайте от 80000р)
                    </div>
                    </div>&nbsp;&nbsp;
                    
                    <div data-discount='4' id='discount4' style='float:left;position: relative;width:70px; margin-right:40px'>
                    <div class='block-action-text4'>4%</div>
                    <div class='text-block4'>VIP</div>
                    <div class='help-text' id='help4' style='display: none; opacity: 1;' style=''>
                        скидка 4% (покупка на сайте от 15000р до 79999р)
                    </div>
                    </div>

                    <div data-discount='3' id='discount3' style='float:left;position: relative;width: 70px;margin-right:40px;'>
                    <div class='block-action-text3'>3%</div>
                    <div class='text-block3'>Gold</div>
                    <div class='help-text' id='help3' style='display: none; opacity: 1;' style=''>
                        скидка 3% (покупка на сайте от 10000р до 14999р)
                    </div>
                    </div>

                    <div data-discount='2' id='discount2' style='float:left;position: relative;width: 70px;margin-right:40px;'>
                    <div class='block-action-text2'>2%</div>
                    <div class='text-block2'>Silver</div>
                    <div class='help-text' id='help2' style='display: none; opacity: 1;'>
                        скидка 2% (покупка на сайте от 3000р до 9999р)
                    </div>
                    </div>

                    <div data-discount='1' id='discount1' style='float:left;position: relative;width: 70px;'>
                    <div class='block-action-text1'>1%</div>
                    <div class='text-block1'>Стандарт</div>
                    <div class='help-text' id='help1' style='display: none; opacity: 1;'>
                        скидка 1% (покупка на сайте от 1000р до 2999р)
                    </div>
                    </div>
                    
                    </div><br>";
                    */
                
                    $list.="<div class='mobile_discount' style='text-align:center;'><img src='/games/assets/images/mobile_discount.jpg' class='img-responsive' alt='Discount'></div>";
                
		$discount_content = $this->get_discount_popover_content ();
		$list .= "<div class='panel panel-default'>";
		$list .= "<div class='panel-heading' style='text-align:left;font-weight:bold;'>Проверь уровень скидки </div>";
		$list .= "<div class='panel-body' style='text-align:left;'>";
		$list .= "<div>Email: &nbsp;<input type='text' id='email' style='width:145px;'> " . "&nbsp; " . "<button type='button' class='btn btn-default' id='check_discount'>Проверить</button>&nbsp; - Узнать какая Ваша <span onClick='return false;' style='color:black;cursor:pointer;font-weight:bolder;' data-toggle='popover' data-html='true' title='Скидки' data-content='$discount_content'>скидка</span></div>";
		$list .= "<div stlyle='' id='discount_result'></div>";
		$list .= "";
		$list .= "</div>";
		$list .= "</div>";
		return $list;
	}
	function get_discount_popover_content() {
		$list = "";
		$list .= "<span>Стандарт</span> - скидка 1% (покупка на сайте от 1000р до 2999р)<br><br>";
		$list .= "<span>Silver</span> - скидка 2% (покупка на сайте от 3000р до 9999р)<br><br>";
		$list .= "<span>Gold</span> - скидка 3% (покупка на сайте от 10000р до 14999р)<br><br>";
		$list .= "<span>VIP</span> - скидка 4% (покупка на сайте от 15000р до 79999р)<br><br>";
		$list .= "<span>Premium</span> - скидка 5% (покупка на сайте от 80000р)";
		return $list;
	}
	function get_admin_page2($item) {
		$list = "";
		switch ($item) {
			case 'news' :
				$id = 9719147;
				$title = "Новости";
				break;
			case 'buy' :
				$id = 9719146;
				$title = "Как купить";
				break;
			case 'garant' :
				$id = 9719145;
				$title = "Услуги гаранта";
				break;
			case 'supplier' :
				$id = 9719143;
				$title = "Поставщикам";
				break;
			case 'guarantee' :
				$id = 9719144;
				$title = "Гарантии";
				break;
			case 'contact' :
				$id = 3068;
				$title = "Контакты";
				break;
			case 'about' :
				$id = 1;
				$title = "О нас";
				break;
		}
		
		if ($id != 9719147) {
			$query = "select * from content where cntID=$id and cntLanguageID=2";
			$result = $this->db->query ( $query );
			foreach ( $result->result () as $row ) {
				$id = $row->cntID;
				$content = $row->cntBody;
			}
			$list .= "<form id='user_page' method='post' action='" . $this->config->item ( 'base_url' ) . "index.php/menu/update_page'>";
			$list .= "<input type='hidden' id='id' name='id' value='$id'>";
			$list .= "<div class='container-fluid' style='text-align:left;'>";
			$list .= "<span class='span2'>$title</span>";
			$list .= "<span class='2'><a href='" . $this->config->item ( 'base_url' ) . "index.php/user/page/" . $this->session->userdata ( 'type' ) . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></span>";
			$list .= "</div>";
			$list .= "<br><div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script>
                </div>";
			$list .= "<div class='container-fluid' style='text-align:center;'>";
			$list .= "<span class='span2'><button id='update_user_page' style='background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fdfbec 0%, #e3c271 48%, #d9ad43 50%, #ce9428 100%) repeat scroll 0 0;
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
			$list .= "</div>";
		}  // end if $id!=9719147
else {
			$list .= "<br/><div class=''>";
			$list .= "<form class='calc_form'>";
			$list .= "<br>";
			$list .= "<table align='center' border='0' style='width: 100%;'>";
			
			$list .= "<table align='center' border='0' width=100%>";
			
			if ($this->session->userdata ( 'type' ) == 3) {
				// It is admin
				$list .= "<tr>";
				$list .= "<td colspan='6' align='center'><span style=''><a href='" . $this->config->item ( 'base_url' ) . "index.php/user/page/" . $this->session->userdata ( 'type' ) . "' style='color:black;font-weight:bolder;'>Меню</a></span></td>";
				$list .= "</tr>";
			}
			
			$list .= "<tr>";
			$list .= "<td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='start'></td><td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='end'></td><td style='padding:15px;'><a href='#' onClick='return false;' id='search_news' style='color:black;'>Ok</a></td><td style='padding:15px;'><a href='#' onClick='return false;' id='add_news' style='color:black;padding:15px;'>Добавить</a></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td align='center' colspan='6' ><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td colspan='6' align='center' style='padding:15px;'><span id='news_container'></span></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td colspan='6' align='center' style='padding:15px;'><span id='news_err'></span></td>";
			$list .= "</tr>";
			
			$list .= "</table><br>";
			
			$list .= "</form>";
			$list .= "</div>";
		} // end else
		return $list;
	}
	function get_admin_page($id) {
		$list = "";
		switch ($id) {
			case 9719147 :
				$title = "Новости";
				break;
			case 9719146 :
				$title = "Как купить";
				break;
			case 9719145 :
				$title = "Услуги гаранта";
				break;
			
			case 9719143 :
				$title = "Поставщикам";
				break;
			
			case 9719144 :
				$title = "Гарантии";
				break;
			
			case 3068 :
				$title = "Контакты";
				break;
			
			case 1 :
				$title = "О нас";
				break;
		}
		
		if ($id != 9719147) {
			$query = "select * from content where cntID=$id and cntLanguageID=2";
			$result = $this->db->query ( $query );
			foreach ( $result->result () as $row ) {
				$id = $row->cntID;
				$content = $row->cntBody;
			}
			$list .= "<form id='user_page' method='post' action='" . $this->config->item ( 'base_url' ) . "index.php/menu/update_page'>";
			$list .= "<input type='hidden' id='id' name='id' value='$id'>";
			$list .= "<div class='container-fluid' style='text-align:left;'>";
			$list .= "<span class='span2'>$title</span>";
			$list .= "<span class='2'><a href='" . $this->config->item ( 'base_url' ) . "index.php/user/page/" . $this->session->userdata ( 'type' ) . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></span>";
			$list .= "</div>";
			$list .= "<br><div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script>
                </div>";
			$list .= "<div class='container-fluid' style='text-align:center;'>";
			$list .= "<span class='span2'><button id='update_user_page' style='background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fdfbec 0%, #e3c271 48%, #d9ad43 50%, #ce9428 100%) repeat scroll 0 0;
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
			$list .= "</div>";
		}  // end if $id!=9719147
else {
			$list .= "<div class=''>";
			$list .= "<form class='calc_form'>";
			$list .= "<br>";
			$list .= "<table align='center' border='0' style='width: 100%;'>";
			
			$list .= "<table align='center' border='0' width=100%>";
			
			if ($this->session->userdata ( 'type' ) == 3) {
				// It is admin
				$list .= "<tr>";
				$list .= "<td colspan='6' align='center'><span style=''><a href='" . $this->config->item ( 'base_url' ) . "index.php/user/page/" . $this->session->userdata ( 'type' ) . "' style='color:black;font-weight:bolder;'>Меню</a></span></td>";
				$list .= "</tr>";
			}
			
			$list .= "<tr>";
			$list .= "<td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='start'></td><td style='padding:15px;'>Дата*</td><td style='padding:15px;'><input type='text' style='width:75px;' id='end'></td><td style='padding:15px;'><a href='#' onClick='return false;' id='search_news' style='color:black;'>Ok</a></td><td style='padding:15px;'><a href='#' onClick='return false;' id='add_news' style='color:black;padding:15px;'>Добавить</a></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td align='center' colspan='6' ><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td colspan='6' align='center' style='padding:15px;'><span id='news_container'></span></td>";
			$list .= "</tr>";
			
			$list .= "<tr>";
			$list .= "<td colspan='6' align='center' style='padding:15px;'><span id='news_err'></span></td>";
			$list .= "</tr>";
			
			$list .= "</table><br>";
			
			$list .= "</form>";
			$list .= "</div>";
		} // end else
		return $list;
	}
	function get_news_by_date($start, $end) {
		$list = "";
		$news = array ();
		$unix_start = strtotime ( $start );
		$unix_end = strtotime ( $end ) + 86400;
		$query = "select * from news " . "where added between $unix_start and $unix_end " . "order by added desc";
		$result = $this->db->query ( $query );
		$num = $result->num_rows ();
		if ($num > 0) {
			foreach ( $result->result () as $row ) {
				$news [] = $row;
			} // end foreach
			
			foreach ( $news as $new ) {
				$date = date ( 'Y-m-d h:i:s', $new->added );
				$list .= "<table align='center' width='100%' border='0'>";
				
				$list .= "<tr>";
				$list .= "<td style='padding:5px;' width='80%'>$new->title</td>";
				$list .= "<td style='padding:5px;' width='10%'>$date</td>";
				$list .= "<td style='padding:5px;' width='5%'><a href='#' onClick='return false;' style='color:black;'><img id='edit_news_$new->id'   src='/games/assets/images/edit.png' width='32' height='32' title='Редактировать'></a></td>";
				$list .= "<td style='padding:5px;' width='5%'><a href='#' onClick='return false;' style='color:black;'><img id='delete_news_$new->id' src='/games/assets/images/delete.png' width='32' height='32' title='Удалить'></a></td>";
				$list .= "</tr>";
				
				$list .= "<tr>";
				$list .= "<td colspan='2' align='center'><span id='news_err_$new->id'></span></td>";
				$list .= "</tr>";
				
				$list .= "</table>";
			} // end foreach
		}  // end if $num > 0
else {
			$list .= "<p align='center'>Ничего не найдено</p>";
		} // end else
		return $list;
	}
	function update_user_page($id, $body) {
		$list = "";
		$query = "update content " . "set cntBody='$body' " . "where cntID=$id " . "and cntLanguageID=2";
		$this->db->query ( $query );
		$list .= "<br/><div class=''>";
		$list .= "<form class='calc_form'>";
		$list .= "<br>";
		$list .= "<table align='center' border='0' style='width: 100%;'>";
		$list .= "<tr>";
		$list .= "<td align='center'>&nbsp;&nbsp;<span>Страница успешно обновлена. &nbsp; <a href='" . $this->config->item ( 'base_url' ) . "index.php/user/page/" . $this->session->userdata ( 'type' ) . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
		$list .= "</tr>";
		$list .= "</table><br>";
		$list .= "</form>";
		$list .= "</div>";
		return $list;
	}
	public function get_add_news_modal_box() {
		$list = "";
		$list .= "<div id='myModal' class='modal fade'>
        <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Добавить новость</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>                         
                
                <table align='center' border='0'>             
                
                <tr>
                <td style='padding:15px;'>Заголовок/Вступление*</span><td style='padding:15px;'><textarea id='title' cols='77'></textarea></td>
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
		$date = time ();
		$query = "insert into news " . "(title, content, added) " . "values(" . $this->db->escape ( $title ) . "," . "" . $this->db->escape ( $body ) . ", " . "'" . $date . "')";
		$this->db->query ( $query );
	}
	public function get_edit_news_box($id) {
		$list = "";
		
		$query = "select * from news where id=$id";
		$result = $this->db->query ( $query );
		foreach ( $result->result () as $row ) {
			$title = $row->title;
			$content = $row->content;
		}
		
		$list .= "<div id='myModal' class='modal fade'>
        <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Редактировать новость</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>                         
                
                <table align='center' border='0'>             
                
                <tr>
                <td style='padding:15px;'>Заголовок/Вступление*</span><td style='padding:15px;'><textarea id='title' cols='77'>$title</textarea></td>
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
		$query = "update news " . "set title='$title', " . "content='$body' " . "where id=$id";
		// echo "Query: " . $query . "<br>";
		$this->db->query ( $query );
		$list = "Новость успешно обновлена";
		return $list;
	}
	public function del_news($id) {
		$query = "delete from news where id=$id";
		$this->db->query ( $query );
		$list = "Новость успешно удалена";
		return $list;
	}
}
