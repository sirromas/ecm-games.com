
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

    $('[data-toggle="popover"]').popover();


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
    $("#user_types").change(function () {
        var selected = $("#user_types").val();
        console.log('Selected item: ' + selected);
        var url = host + "/index.php/user/get_user_accounts/" + selected;
        window.document.location = url;
    });

    $("#other").change(function () {
        var selected = $("#other").val();
        console.log('Selected item: ' + selected);
        var url;
        if (selected == 'exit') {
            url = host + "/index.php/user/logout";
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

        if (selected == 'exchange_rate') {
            url = host + "/index.php/user/exchange_rate";
        }
        window.document.location = url;
    });
    /********************** Logout *****************************/
    $('#logout').click(function () {
        var url = host + "/index.php/user/logoutdone";
        window.document.location = url;
    });

    $('#get_revenue').click(function () {
        var start = $('#start').val();
        var end = $('#end').val();
        var game = $('#games').val();
        if (start != '' && end != '') {
            $('#revenue_err').html('');
            $('#ajax_loader').show();
            var url = host + "/index.php/user/get_revenue";
            $.post(url, {game: game, start: start, end: end}).done(function (data) {
                $('#ajax_loader').hide();
                var revenue_obj = JSON.parse(data);
                var games = (revenue_obj.games)
                var paid = (revenue_obj.paid)
                console.log('Games data: ' + games);
                console.log('Paid data: ' + paid);
                var data_url = host + "/tmp/data.csv";
                console.log('Data URL: ' + data_url);
                $.get(data_url, function (csv) {
                    $('#chartdiv').highcharts({
                        chart: {
                            type: 'column'
                        },
                        data: {
                            csv: csv
                        },
                        title: {
                            text: 'Fruit Consumption'
                        },
                        yAxis: {
                            title: {
                                text: 'Units'
                            }
                        }
                    });
                });

            }); // end of POST
        } // end if start!='' && end1=''
        else {
            $('#revenue_err').html('Пожалуйста укажите даты');
        } // end else
    });

    $('#search_orders').click(function () {
        console.log('Search clicked ...');
        var orders = $('#orders').val();
        var managers = $('#managers').val();
        var start = $('#start').val();
        var end = $('#end').val();

        if (start != '' && end != '') {
            $('#orders_err').html('');
            $('#ajax_loader').show();
            var url = host + "/index.php/user/search_orders";
            $.post(url, {orders: orders, managers: managers, start: start, end: end}).done(function (data) {
                $('#ajax_loader').hide();
                $('#orders_container').html(data);
            });
        } // end if start!='' && end!=''
        else {
            $('#orders_err').html('Пожалуйста укажите даты');
        }
    });

    $('#user_page').submit(function (event) {
        var body = CKEDITOR.instances.body.getData();
        if (body != '') {

        } // end if body!=''
        else {
            event.preventDefault();
        }

    });

    $('#get_cashier_orders').click(function () {
        var status = $('#orders').val();
        var start = $('#start').val();
        var end = $('#end').val();
        if (status > 0 && start != '' && end != '') {
            $('#orders_err').html('');
            $('#ajax_loader').show();
            var url = host + "/index.php/user/get_cashier_orders";
            $.post(url, {status: status, start: start, end: end}).done(function (data) {
                $('#ajax_loader').hide();
                $('#dashboard_container').html(data);
            });
        }
        else {
            $('#orders_err').html('Пожалуйста выберите период');
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

    $('#deals').change(function () {
        var url;
        var selected = $('#deals').val();
        if (selected == 'deals') {
            url = host + "/index.php/user/orders/";
        }
        if (selected == 'revenue') {
            url = host + "/index.php/user/revenue/";
        }
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
        var type = $('#user_type').val();
        //var games_length=games.length;
        //console.log('Games: ' + games);
        //event.preventDefault();
        if (type == 2) {
            // It is manager
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
        } // end if type == 2
        else {
            if (lastname != '' && firstname != '' && pwd != '' && phone != '' && addr != '') {
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
        } // end else
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

    $("#make_order").click(function () {
        console.log('Make order ...');
        var game_amount = $('#currency').val();
        var amount = $('#count_money').html();
        var currency = $('#CURRENCY_NAME').html();
        var phone = $('#inp_phone').val();
        var skype = $('#inp_skype').val();
        var icq = $('#inp_icq').val();

        var server_value = $('#server').val();
        var server_data = server_value.split('_');
        var server_id = server_data[0];

        var delivery_way = $('#s_delivery').val();
        var email = $('#inp_email').val();
        var nick = $('#inp_nickname').val();
        var comment = $('#ta_comment').val();

        var gameid = $('#gameid').val();
        console.log("Game id: " + gameid);
        console.log('Amount: ' + amount);
        console.log('Currency: ' + currency);
        console.log('Nick: ' + nick);
        console.log('Email: ' + email);
        console.log('Phone: ' + phone);
        console.log('Server id: ' + server_id);
        if (amount > 0 && email != '' && nick != '' && currency != 0) {
            if (!validateEmail(email)) {
                $('#order_err').html('Пожалуйста укажите правильный email');
            }
            else {
                //if ((usd_amount - min_amount) > 0) {
                $('#add_order').fadeTo("slow", 0.3);
                $('#order_err').html('');
                var order = {gameid: gameid,
                    game_amount: game_amount,
                    amount: amount,
                    currency: currency,
                    nick: nick,
                    server: server_id,
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
                //} // end if usd_amount - min_amount
                //else {
                //  $('#order_err').html('Вы не сделали заказ на минимальную сумму');
                //} // end else
            } // end else
        } // end if amount>0 && phone!='' && email!='' && nick!=''
        else {
            $('#order_err').html('Пожалуйста укажите все обязательные поля');
        }
    });


    function update_price() {
        var rate;
        var server_value = $('#server').val();
        var server_data = server_value.split('_');
        var server_id = server_data[0];
        var server_rate = server_data[1];
        var server_currency_num = server_data[2];

        console.log('Server ID: ' + server_id);
        console.log('Server rate: ' + server_rate);
        console.log('Server game currency amount multiplier: ' + server_currency_num);

        var amount = $('#currency').val();
        var currency = $('#ptype').val();
        if (amount != "" && $.isNumeric(amount)) {

            /*
             var human_amount = (amount * server_currency_num).toString();
             console.log('Human amount: ' + human_amount);
             var re = new RegExp('000', 'g');
             var server_currency_num_human = human_amount.replace(re, 'k');
             console.log('Server currency num: ' + server_currency_num_human);
             */

            $('#const_zoloto').html(amount + server_currency_num);
            //$('#const_zoloto').html(server_currency_num_human);

            // Set selected currency value
            switch (currency) {
                case 'eur':
                    rate = (eur_s / usd_s);
                    break;

                case 'rur':
                    rate = (rub_s / usd_s);
                    break;

                case 'usd':
                    rate = 1;
                    break;

                case 'uah':
                    rate = (1 / usd_s);
                    break;
            }
            
            var usd_amount = amount * server_rate;
            // Real money attached to selected currency
            var total_amount = usd_amount / rate; 

            $('#count_money').html(total_amount.toFixed(2));            

            var rur_val = usd_amount.toFixed(2) / (rub_s / usd_s);
            var eur_val = usd_amount.toFixed(2) / (eur_s / usd_s);
            var uah_val = usd_amount.toFixed(2) / (1 / usd_s);

            console.log('RUR value: ' + rur_val);
            console.log('UAH value: ' + uah_val);
            console.log('EUR value: ' + eur_val);
            console.log('USD value: ' +usd_amount);
            
            var discount;
            var discount_amount;
            var amount_with_discount;            

            if (rur_val >= 80000) {
                discount = 5;
            }

            if (rur_val > 15000 && rur_val < 79999) {
                discount = 4;
            }

            if (rur_val > 10000 && rur_val < 14999) {
                discount = 3;
            }

            if (rur_val > 3000 && rur_val < 9999) {
                discount = 2;
            }

            if (rur_val > 1000 && rur_val < 2999) {
                discount = 1;
            }

            if (rur_val < 1000) {
                discount = 0;
            }           

            if (discount > 0) {
                switch (currency) {
                    case 'eur':
                        discount_amount = (eur_val * discount) / 100;
                        amount_with_discount = eur_val - discount_amount;
                        break;
                    case 'rur':
                        discount_amount = (rur_val * discount) / 100;
                        amount_with_discount = rur_val - discount_amount;
                        break;
                    case 'usd':
                        discount_amount = (usd_amount * discount) / 100;
                        amount_with_discount = usd_amount - discount_amount;
                        break;
                    case 'uah':
                        discount_amount = (uah_val * discount) / 100;
                        amount_with_discount = uah_val - discount_amount;
                        break;
                } // end switch
            } // end if
            else {
                amount_with_discount=total_amount;
            } // end else

            console.log('Discount size %' + discount);
            console.log('Discount amount: ' + discount_amount);
            console.log('Amount with discount: ' + amount_with_discount);
            
            $('#amount').val(amount_with_discount.toFixed(2));
            $('#count_money').html(amount_with_discount.toFixed(2));

        } // end if amount != "" && $.isNumeric(amount)
        else {
            $('#amount').val('');
        }
    }

    $('#server').change(function () {
        $('#ptype').prop("disabled", false);
        update_price();
    });

    $('#ptype').change(function () {
        $('#currency').prop("disabled", false);
        var currency = $('#ptype').val();
        $('#CURRENCY_NAME').html(currency);
        $('#real_currency').html(currency);
        update_price();
    });

    $("#currency").blur(function () {
        update_price();
    });

    $("#pending_orders").change(function () {
        var id = $('#pending_orders').val();
        if (id > 0) {
            var url = host + "/index.php/user/get_order_details/";
            $.post(url, {id: id, status: 1}).done(function (data) {
                $('#dashboard_container').html(data);
            });
        } // end if id > 0
    });

    $("#processed_orders").change(function () {
        var id = $('#processed_orders').val();
        if (id > 0) {
            var url = host + "/index.php/user/get_order_details/";
            $.post(url, {id: id, status: 2}).done(function (data) {
                $('#dashboard_container').html(data);
            });
        } // end if id > 0
    });

    $("#money_received").change(function () {
        var id = $('#processed_orders').val();
        if (id > 0) {
            var url = host + "/index.php/user/get_order_details/";
            $.post(url, {id: id, status: 3}).done(function (data) {
                $('#dashboard_container').html(data);
            });
        } // end if id > 0
    });

    $("#money_sent").change(function () {
        var id = $('#processed_orders').val();
        if (id > 0) {
            var url = host + "/index.php/user/get_order_details/";
            $.post(url, {id: id, status: 4}).done(function (data) {
                $('#dashboard_container').html(data);
            });
        } // end if id > 0
    });

    $("#supplier_paid").change(function () {
        var id = $('#processed_orders').val();
        if (id > 0) {
            var url = host + "/index.php/user/get_order_details/";
            $.post(url, {id: id, status: 5}).done(function (data) {
                $('#dashboard_container').html(data);
            });
        } // end if id > 0
    });




    $("#user_type").change(function () {
        var id = $('#user_type').val();
        if (id == 2) {
            $('#manager_games').show();
        }
        else {
            $('#manager_games').hide();
        }
    });

    $(document).on('click', '#addPayment', function () {
        var id = $('#order_id').val();
        var url = host + "/index.php/user/get_add_payment_modal_box/";
        if (dialog_loaded !== true) {
            $.post(url, {id: id}).done(function (data) {
                dialog_loaded = true;
                $("body").append(data);
                $("#myModal").modal('show');
            });
        } // end if dialog_loaded !== true
        else {
            $("#myModal").modal('show');
        }
    });

    $(document).on('click', '#cancel_add_payment', function () {
        console.log('cancel_add_payment. ...');
        $('#myModal').data('modal', null);
        dialog_loaded = false;
    });

    $(document).on('click', '#cancel_add_news', function () {
        console.log('cancel_add_news. ...');
        CKEDITOR.instances.body.destroy();
        $('#myModal').data('modal', null);
        dialog_loaded = false;
    });

    $(document).on('click', '#cancel_edit_game', function () {
        console.log('cancel_edit_game. ...');
        CKEDITOR.instances.body.destroy();
        $('#myModal').data('modal', null);
        dialog_loaded = false;
    });

    //cancel_edit_game

    $(document).on('change', '#order_status', function () {
        var id = $('#order_id').val();
        var status = $('#order_status').val();

        if (status >= 2) {
            var url = host + "/index.php/user/get_order_currency_price/";
            $.post(url, {id: id}).done(function (data) {
                if (data > 0) {
                    $('#notes_err').html('');
                    if (confirm('Изменить статус заказа?')) {
                        $('#notes_err').html('');
                        var url = host + "/index.php/user/set_order_status/";
                        $.post(url, {id: id, status: status}).done(function (data) {
                            console.log(data);
                            document.location.reload();
                        });
                    } // end if confirm
                } // end if data>0
                else {
                    $('#notes_err').html('Пожалуйста укажите цену покупки валюты');
                }
            }); // end if $.post(url
        } // end if status ==2

    });


    $(document).on('blur', '#notes', function () {
        console.log('Notes blur event ...');
        var id = $('#order_id').val();
        var notes = $('#notes').val();
        var url = host + "/index.php/user/update_order_notes/";
        $.post(url, {id: id, notes: notes}).done(function (data) {
            console.log(data);
        });
    });

    $("body").click(function (event) {
        //console.log('Element clicked: ' + event.target.id);

        if (event.target.id.indexOf("game_detailes") >= 0) {
            var id = event.target.id.replace("game_detailes_id_", "");
            get_game_description_block(id);
        }

        if (event.target.id.indexOf("add_sup_payment") >= 0) {
            console.log('Add payment clicked ...');
            if ($('#supplier').is(':visible')) {
                $('#supplier').hide();
            }
            else {
                $('#supplier').show();
            }
        }

        if (event.target.id == 'add_supplier_payment2_btn') {
            var orderid = $('#order_id').val();
            var amount = $('#supp_amount').val();
            if (amount != '' && $.isNumeric(amount)) {
                var url = host + "/index.php/user/add_supplier_order_payment/";
                $.post(url, {amount: amount, orderid: orderid}).done(function (data) {
                    $('#currency_price').html(data);
                });
            }
        }



        if (event.target.id.indexOf("get_details_") >= 0) {
            var id = event.target.id.replace("get_details_", "");
            console.log('ID: ' + id);
            var detailes_id = "#det_" + id;
            var el = $(detailes_id);
            console.log('Element: ' + el);
            if (el.is(":visible")) {
                console.log('Inside if ...');
                $(detailes_id).hide();
            }
            else {
                console.log('Inside else ...');
                $(detailes_id).show();
            }
        }


        /*
         if (event.target.id.indexOf("action_") >= 0) {
         var id = event.target.id.replace("action_", "");
         var section_id = "action_" + id;
         var url = host + "/index.php/games/get_game_action/";
         $.post(url, {id: id}).done(function (data) {
         console.log('Action: ' + data);
         $(body).append(data);
         });
         }
         */



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

        if (event.target.id.indexOf("game_action_") >= 0) {
            var id = event.target.id.replace("game_action_", "");
            var area_id = "#game_action_text_" + id;
            var area_data = $(area_id).val();
        }

        if (event.target.id == 'search_news') {
            var start = $('#start').val();
            var end = $('#end').val();
            if (start != '' && end != '') {
                $('#news_err').html('');
                $('#ajax_loader').show();
                var url = host + "/index.php/menu/search_news/";
                $.post(url, {start: start, end: end}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#news_container').html(data);
                });
            } // end if start!='' && end!=''
            else {
                $('#news_err').html('Пожалуйста укажите дату');
            } // end else
        }

        if (event.target.id == 'add_news') {
            var url = host + "/index.php/menu/get_add_news_modal_box/";
            if (dialog_loaded !== true) {
                $('#news_err').html('');
                $('#ajax_loader').show();
                $.post(url, {id: 1}).done(function (data) {
                    dialog_loaded = true;
                    $('#ajax_loader').hide();
                    $('#myModal').data('modal', null);
                    $('#myModal').remove();
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            } // end if dialog_loaded !== true
            else {
                $('#news_err').html('');
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'add_news_btn') {
            var title = $('#title').val();
            var body = CKEDITOR.instances.body.getData();
            if (title != '' && body != '') {
                $('#add_news_err').html('');
                var url = host + "/index.php/menu/add_news/";
                $.post(url, {title: title, body: body}).done(function () {
                    $('#myModal').modal('hide');
                    $('#myModal').data('modal', null);
                    $('#news_container').html('Новость успешно добавлена');
                });
            } // end if title!='' && body!=''
            else {
                $('#add_news_err').html('Пожалуйста укажите обязательные поля');
            }
        }

        if (event.target.id == 'add_payment_btn') {
            console.log('Inside add payment ..');
            var amount = $('#amount').val();
            var ptype = $('#ptype').val();
            var id = $('#id').val();
            var comment = $('#payment_comment').val();
            if (amount != '') {
                if ($.isNumeric(amount)) {
                    $('#amount_err').html('');
                    var url = host + "/index.php/user/add_payment/";
                    $.post(url, {id: id, amount: amount, ptype: ptype, comment: comment}).done(function (data) {
                        $('#myModal').modal('hide');
                        $('#myModal').data('modal', null);
                        $('#client_payments').html(data);
                    });

                } // end if $.isNumeric(amount)
                else {
                    $('#amount_err').html('Сумма указана неверно');
                } // end else
            } // end if amount!=''
            else {
                $('#amount_err').html('Пожалуйста укажите сумму');
            } // end else
        }

        if (event.target.id.indexOf("edit_news_") >= 0) {
            var id = event.target.id.replace("edit_news_", "");
            var url = host + "/index.php/menu/edit_news/";
            $('#ajax_loader').show();
            $.post(url, {id: id}).done(function (data) {
                $('#ajax_loader').hide();
                $('#myModal').data('modal', null);
                $('#myModal').remove();
                $("body").append(data);
                $("#myModal").modal('show');
            });

        }

        if (event.target.id.indexOf("update_news_") >= 0) {
            var id = event.target.id.replace("update_news_", "");
            var title = $('#title').val();
            var body = CKEDITOR.instances.body.getData();
            if (title != '' && body != '' && id > 0) {
                $('#add_news_err').html('');
                $('#ajax_loader').hide();
                var url = host + "/index.php/menu/update_news/";
                $.post(url, {id: id, title: title, body: body}).done(function (data) {
                    $('#myModal').modal('hide');
                    $('#myModal').data('modal', null);
                    $('#news_container').html(data);
                });
            } // end if title!='' && body!=''
            else {
                $('#add_news_err').html('Пожалуйста укажите обязательные поля');
            }
        }

        if (event.target.id.indexOf("add_supplier_payment_") >= 0) {
            var id = event.target.id.replace("add_supplier_payment_", "");
            var url = host + "/index.php/user/get_add_payment_modal_box2/";
            if (dialog_loaded !== true) {
                $.post(url, {id: id}).done(function (data) {
                    dialog_loaded = true;
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            } // end if dialog_loaded !== true
            else {
                $("#myModal").modal('show');
            }

        }

        if (event.target.id == 'add_payment_supplier_btn') {
            var amount = $('#amount').val();
            var supplier_data = $('#supplier_data').val();
            var ptype = $('#ptype').val();
            var id = $('#id').val();
            var comment = $('#payment_comment').val();
            if (amount != '' && supplier_data) {
                if ($.isNumeric(amount)) {
                    $('#amount_err').html('');
                    var url = host + "/index.php/user/add_supplier_payment/";
                    $.post(url, {id: id, amount: amount, ptype: ptype, comment: comment, supplier_data: supplier_data}).done(function (data) {
                        $('#myModal').modal('hide');
                        $('#myModal').data('modal', null);
                        $('#client_payments').html(data);
                        document.location.reload();
                    });

                } // end if $.isNumeric(amount)
                else {
                    $('#amount_err').html('Сумма указана неверно');
                } // end else
            } // end if amount!=''
            else {
                $('#amount_err').html('Пожалуйста укажите поставщика и сумму');
            } // end else            
        }

        if (event.target.id.indexOf("update_server_") >= 0) {
            var id = event.target.id.replace("update_server_", "");
            var server_name_id = '#name_' + id;
            var server_rate_id = '#exchange_' + id;
            var server_amount_id = '#server_amount_' + id
            var server_name = $(server_name_id).val();
            var server_rate = $(server_rate_id).val();
            var server_amount = $(server_amount_id).val();
            if (id > 0 && server_name != '') {
                $('#game_err').html('');
                var url = host + "/index.php/servers/update_server/";
                $.post(url, {id: id, name: server_name, rate: server_rate, server_amount: server_amount}).done(function (data) {
                    alert('Сервер обвновлен.');
                    console.log('Server response: ' + data);
                });
            } // end if id>0 && server_name!='' && $.isNumeric(server_rate)
            else {
                $('#game_err').html('Пожалуйста укажите обязательные поля');
            }
        }

        if (event.target.id.indexOf("delete_news_") >= 0) {
            var id = event.target.id.replace("delete_news_", "");
            if (id > 0) {
                if (confirm('Удалить текущую новость?')) {
                    var url = host + "/index.php/menu/del_news/";
                    $.post(url, {id: id}).done(function (data) {
                        $('#news_container').html(data);
                    });
                } // end if confirm
            } // end if id>0
        }

        if (event.target.id.indexOf("more_") >= 0) {
            var id = event.target.id.replace("more_", "");
            if (id > 0) {
                var tr_id = '#content_' + id;
                var el = $(tr_id);
                if (el.is(':visible')) {
                    $(tr_id).hide();
                } // end if 
                else {
                    $(tr_id).show();
                } // end else
            } // end if id>0
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

