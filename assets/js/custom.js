
$(document).ready(function () {

    var host = 'http://mycodebusters.com/games';
    var dialog_loaded;
    var eur_s;
    var rub_s;
    var usd_s;
    var url = host + "/index.php/currency/rate/";
    $.post(url, {id: 1}, function (data) {
        var currency = $.parseJSON(data);
        eur_s = currency.euro_s;
        rub_s = currency.rub_s;
        usd_s = currency.usd_s;
    });


    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(email);
    }

    /*************** Signup form submit ****************/
    $("#signup").click(function (event) {
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var addr = $('#addr').val();
        var icq = $('#icq').val();
        var skype = $('#skype').val();
        if (firstname != '' && lastname != '' && email != '' && phone != '' && addr != '') {
            if (validateEmail(email)) {
                var url = "/games/ajax/is_email_exists.php";
                $.post(url, {email: email}).done(function (data) {
                    if (data == 0) {
                        $('#signup_err').html('');
                        $("#signup_form").submit();
                    } // end if data==0
                    else {
                        $('#signup_err').html('Этот email (' + email + ') уже используется');
                    } // end else
                }).fail(function (data) {
                    console.log('Server response: ' + data);
                    event.preventDefault();
                });
                //return true;
            } // end if validateEmail(email)
            else {
                $('#signup_err').html('Пожалуйста укажите правильный Email');
                event.preventDefault();
            } // end else
        } // end if email!='' && phone!='' && addr!=''
        else {
            $('#signup_err').html('Пожалуйста укажите обязательные поля');
            event.preventDefault();
        } // end else
    }); // end of form signup

    /***************** Restore link *******************/
    $('#restore_btn').click(function () {
        var email = $('#email').val();
        if (email != '') {
            if (validateEmail(email)) {
                $('#forgot_err').html('');
                $('#restore_pwd').submit();
            } // end if validateEmail()
            else {
                $('#forgot_err').html('Пожалуйста укажите правильный Email');
            }
        } // end if email!=''
        else {
            $('#forgot_err').html('Пожалуйста укажите Email');
        }
    });
    $('#del_user').click(function () {
        var id = $('#id').val();
        if (id > 0) {
            if (confirm('Удалить текущего пользователя?')) {
                var url = host + "/index.php/user/del_user/";
                var menu_url = host + "/index.php/user/page/3";
                $.post(url, {id: id}).done(function () {
                    $('#user_container').html("Пользователь успешно удален &nbsp;&nbsp;<a href='" + menu_url + "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a>");
                });
            } // end if confirm()
        } // end if id>0
    });
    $('#restore_btn_done').click(function () {
        var pwd1 = $('#pwd1').val();
        var pwd2 = $('#pwd2').val();
        if (pwd1 != '' && pwd2 != '') {
            if (pwd1 != pwd2) {
                $('#forgot_err').html('Пароли не совпадают');
            } // end if pwd1!=pwd2 
            else {
                $('#forgot_err').html('');
                $('#restore_pwd').submit();
            }
        } // end if pwd1!='' && pwd2!=''
        else {
            $('#forgot_err').html('Пожалуйста укажите обязательные поля');
        } // end else 

    });
    /********************** Login *************************/
    $('#login').click(function () {
        var username = $('#username').val();
        var pwd = $('#pwd').val();
        if (username != '' && pwd != '') {
            var url = "/games/ajax/validate_user.php";
            $.post(url, {email: username, pwd: pwd}).done(function (data) {
                if (data == 0) {
                    $('#login_err').html('Логин или пароль неверны.');
                } // end if data==0
                else {
                    $('#login_err').html('');
                    $('#login_form').submit();
                } // end else
            }).fail(function (data) {
                console.log('Server response: ' + data);
                event.preventDefault();
            });
        } // end if username!='' && pwd!=''
        else {
            $('#login_err').html('Пожалуйста укажите обязательные поля');
        } // end else 
    });
    /*************************** Other dropdwon ***************************/
    $("#other").change(function () {
        var selected = $("#other").val();
        console.log('Selected item: ' + selected);
        var url;
        if (selected == 'exit') {
            vurl = host + "/index.php/user/logout";
        } // end if event.target.id=='exit'        
        if (selected == 'add_game') {
            url = host + "/index.php/games/add_game";
        }
        if (selected == 'add_server') {
            url = host + "/index.php/servers/add_server";
        }

        if (selected == 'add_user') {
            url = host + "/index.php/user/add_user";
        }

        if (selected == 'news') {
            url = host + "/index.php/menu/adminpage/9719147";
        }
        if (selected == 'buy') {
            url = host + "/index.php/menu/adminpage/9719146";
        }
        if (selected == 'service') {
            url = host + "/index.php/menu/adminpage/9719145";
        }
        if (selected == 'supplier') {
            url = host + "/index.php/menu/adminpage/9719143";
        }
        if (selected == 'guarantee') {
            url = host + "/index.php/menu/adminpage/9719144";
        }
        if (selected == 'contacts') {
            url = host + "/index.php/menu/adminpage/3068";
        }
        if (selected == 'about') {
            url = host + "/index.php/menu/adminpage/1";
        }

        if (selected == 'report') {
            url = host + "/index.php/user/report";
        }
        window.document.location = url;
    });
    /********************** Logout *****************************/
    $('#logout').click(function () {
        var url = host + "/index.php/user/logoutdone";
        window.document.location = url;
    });
    $('#user_page').submit(function (event) {
        var body = CKEDITOR.instances.body.getData();
        if (body != '') {

        } // end if body!=''
        else {
            event.preventDefault();
        }

    });
    $('#cancel_logout').click(function () {
        var type = $('#type').val();
        var url = host + "/index.php/user/page/" + type;
        window.document.location = url;
    });
    /********************* Games edit block ***********************/
    $('#games').change(function () {
        var selected = $('#games').val();
        var url = host + "/index.php/games/edit/" + selected;
        window.document.location = url;
    });
    /********************* Users edit block ***********************/
    $('#users').change(function () {
        var selected = $('#users').val();
        var url = host + "/index.php/user/edit/" + selected;
        window.document.location = url;
    });
    $('#update_user').submit(function (event) {
        var id = $('#id').val();
        var lastname = $('#lastname').val();
        var firstname = $('#firstname').val();
        var pwd = $('#pwd').val();
        var phone = $('#phone').val();
        var addr = $('#addr').val();
        var skype = $('#skype').val();
        var icq = $('#icq').val();
        var type = $('#icq').val();
        if (id > 0 && lastname != '' && firstname != '' && pwd != '' && phone != '' && addr != '') {
            $('#user_err').html('');
        }
        else {
            $('#user_err').html('Пожалуйста укажите все обязательные поля');
            event.preventDefault();
        }
    });
    $('#add_manager').submit(function (event) {
        var lastname = $('#lastname').val();
        var firstname = $('#firstname').val();
        var email = $('#email').val();
        var pwd = $('#pwd').val();
        var phone = $('#phone').val();
        var addr = $('#addr').val();
        var skype = $('#skype').val();
        var icq = $('#icq').val();
        var games = $('#manager_games').val();
        //var games_length=games.length;
        console.log('Games: ' + games);
        //event.preventDefault();
        if (lastname != '' && firstname != '' && pwd != '' && phone != '' && addr != '' && games != null) {
            if (validateEmail(email)) {
                $('#user_err').html('');
            } // end if validateEmail(email)
            else {
                $('#user_err').html('Пожалуйста укажите правильный email');
                event.preventDefault();
            } // end else
        } // end if lastname != '' && firstname != '' && pwd != '' && phone != '' && addr != '' && games.length > 0
        else {
            $('#user_err').html('Пожалуйста укажите все обязательные поля');
            event.preventDefault();
        }
    });
    $("#update_game").submit(function (event) {
        var title = $('#title').val();
        var body = $('#body').val();
        var currency = $('#currency').val();
        var minamount = $('#minamount').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        if (title != '' && body != '' && currency != '' && $.isNumeric(minamount) && min_price > 0 && $.isNumeric(min_price) && max_price > 0 && $.isNumeric(max_price)) {
            $('#game_err').html('');
        } // end if title!='' && body!='' && currency!='' && $.isNumeric(minamount)
        else {
            $('#game_err').html('Пожалуйста укажите обязательные поля');
            event.preventDefault();
        } // end else
    });
    $("#add_server").submit(function (event) {
        var name = $('#name').val();
        var rate = $('#rate').val();
        var game = $('#game').val();
        if (name != '' && $.isNumeric(rate) && game > 0) {
            $('#server_err').html('');
        } // end if name!='' && rate!='' && game>0        
        else {
            $('#server_err').html('Пожалуйста укажите обязательные поля');
            event.preventDefault();
        } // end else
    });
    function get_game_description_block(id) {
        var url = host + "/index.php/games/get_game_modal_box/";
        $('#game_container').fadeTo(0.33);
        if (dialog_loaded !== true) {
            $.post(url, {id: id}).done(function (data) {
                dialog_loaded = true;
                $('#game_container').fadeTo(1);
                $("body").append(data);
                $("#myModal").modal('show');
            });
        } // end if dialog_loaded !== true
        else {
            $('#game_container').fadeTo(1);
            $("#myModal").modal('show');
        }
    }

    /********************************************************************
     * 
     *                  Events processing block
     * 
     ********************************************************************/

    $("#make_order").one('click', function () {
        var game_amount = $('#currency').val();
        var amount = $('#count_money').html();
        var usd_amount = $('#amount').val();
        var currency = $('#CURRENCY_NAME').html();
        var phone = $('#inp_phone').val();
        var skype = $('#inp_skype').val();
        var icq = $('#inp_icq').val();
        var delivery_way = $('#s_delivery').val();
        var email = $('#inp_email').val();
        var nick = $('#inp_nickname').val();
        var comment = $('#ta_comment').val();
        var min_amount = $('.min_sum_order_js').html();
        var gameid = $('#gameid').val();
        console.log("Game id: " + gameid);
        console.log('Usd amount: ' + usd_amount);
        if (amount > 0 && phone != '' && email != '' && nick != '' && currency != 0) {
            if (!validateEmail(email)) {
                $('#order_err').html('Пожалуйста укажите правильный email');
            }
            else {
                if ((usd_amount - min_amount) > 0) {
                    $('#add_order').fadeTo("slow", 0.3);
                    $('#order_err').html('');
                    var order = {gameid: gameid,
                        game_amount: game_amount,
                        amount: amount,
                        usd_amount: usd_amount,
                        currency: currency,
                        nick: nick,
                        email: email,
                        phone: phone,
                        skype: skype,
                        icq: icq,
                        delivery_way: delivery_way,
                        comment: comment};
                    var url = host + "/index.php/user/add_order";
                    $.post(url, {order: order}).done(function (data) {
                        $('#add_order').css("opacity", "1");
                        $('#add_order').html(data);
                    });
                } // end if usd_amount - min_amount
                else {
                    $('#order_err').html('Вы не сделали заказ на минимальную сумму');
                } // end else
            } // end else
        } // end if amount>0 && phone!='' && email!='' && nick!=''
        else {
            $('#order_err').html('Пожалуйста укажите все обязатяельные поля');
        }
    });

    $('#server').change(function () {
        $('#ptype').prop("disabled", false);
    });

    $('#ptype').change(function () {
        $('#currency').prop("disabled", false);
        var currency = $('#ptype').val();
        $('#CURRENCY_NAME').html(currency);
    });

    $("#currency").blur(function () {
        var rate;
        var server_rate = $('#server').val();
        var amount = $('#currency').val();
        var currency = $('#ptype').val();
        if (amount != "" && $.isNumeric(amount)) {
            // Set USD value
            var usd_amount = amount * server_rate;
            $('#amount').val(usd_amount.toFixed(2));
            $('#const_zoloto').html(amount);

            // Set selected currency value
            switch (currency) {
                case 'eur':
                    rate = (eur_s / usd_s).toFixed(3);
                    break;

                case 'rur':
                    rate = (rub_s / usd_s).toFixed(3);
                    break;

                case 'usd':
                    rate = 1;
                    break;

                case 'uah':
                    rate = (1 / usd_s).toFixed(3);
                    break;
            }
            var total_amount = usd_amount / rate;
            $('#count_money').html(total_amount.toFixed(2));

        } // end if amount != "" && $.isNumeric(amount)
        else {
            $('#amount').val('');
        }
    });


    $("body").click(function (event) {
        //console.log('Element clicked: ' + event.target.id);
        if (event.target.id.indexOf("game_detailes") >= 0) {
            var id = event.target.id.replace("game_detailes_id_", "");
            get_game_description_block(id);
        }

        if (event.target.id == 'upd_game') {
            var id = $('#id').val();
            var body = CKEDITOR.instances.body.getData();
            if (id > 0 && body != '') {
                var url = host + "/index.php/games/update_game_content/";
                $.post(url, {id: id, body: body}).done(function (data) {
                    console.log('Server response: ' + data);
                });
            } // end if id>0 && body!=''
        }

        if (event.target.id.indexOf("update_server_") >= 0) {
            var id = event.target.id.replace("update_server_", "");
            var server_name_id = '#name_' + id;
            var server_rate_id = '#exchange_' + id;
            var server_name = $(server_name_id).val();
            var server_rate = $(server_rate_id).val();
            if (id > 0 && server_name != '' && $.isNumeric(server_rate)) {
                var url = host + "/index.php/servers/update_server/";
                $.post(url, {id: id, name: server_name, rate: server_rate}).done(function (data) {
                    alert('Сервер обвновлен.');
                    console.log('Server response: ' + data);
                });
            } // end if id>0 && server_name!='' && $.isNumeric(server_rate)
        }

        if (event.target.id.indexOf("del_game_") >= 0) {
            var id = event.target.id.replace("del_game_", "");
            if (id > 0) {
                if (confirm('Удалить игру?')) {
                    var url = host + "/index.php/games/delete/" + id;
                    window.document.location = url;
                } // end if confirm
            } // end if id>0
        }

        $("#add_game").submit(function (event) {
            var file_data = $('#files').prop('files');
            var name = $('#name').val();
            var currency = $('#currency').val();
            var min_amount = $('#min_amount').val();
            if (file_data != '' && name != '' && currency != '' && $.isNumeric(min_amount)) {

            } // end if file_data!=''
            else {
                $('#game_err').html('Пожалуйста укажите обязательные поля');
                event.preventDefault();
            }
        });

    }); // end of $("body").click(function (event) {

}); // document).ready(function ()

