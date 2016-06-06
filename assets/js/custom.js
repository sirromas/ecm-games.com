
$(document).ready(function () {

    var host = 'http://mycodebusters.com/games';

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

    /*************************** Logout ***************************/
    $("#other").change(function () {
        var selected = $("#other").val();
        if (selected == 'exit') {
            var url = host + "/index.php/user/logout";
        } // end if event.target.id=='exit'        
        window.document.location = url;
    });

    /********************** Logout *****************************/
    $('#logout').click(function () {
        var url = host + "/index.php/user/logoutdone";
        window.document.location = url;
    });

    $('#cancel_logout').click(function () {
        var type=$('#type').val();
        var url = host + "/index.php/user/page/"+type;
        window.document.location = url;
    });



}); // ocument).ready(function ()

