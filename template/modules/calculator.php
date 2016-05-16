<?php
    require_once file_translate("template/modules/validate-form.php");
?>
<div class="calc">
    <form id="calc_form" class="calc_form" action="/order/" method="POST">
        <h2 class="title">Купить <?= $arGame['moneys'].' '.$arGame['name']?></h2>
        <div class="game-title">
            <img src="/template/icon/<?=$content->arPage['cntFileName']?>.jpg"
                 title="Купить <?= $arGame['moneys'].' '.$arGame['name']?>"
                 alt="Купить <?= $arGame['moneys'].' '.$arGame['name']?>"
                />
            <ul>              
                <li><a href="#description" title="Об игре">Об игре <?= $arGame['name']?></a></li>
                <li><a href="#video" title="Видео-обзор <?= $arGame['name']?>">Видео-обзор <?= $arGame['name']?></a></li>
                <!--<li><a href="#" title="Скидки">Скидки</a></li>-->
                <!--li><a href="#calc_form" title="Купить <?= $arGame['moneys']?>">Купить <?= $arGame['moneys']?></a></li-->
                <li><a href="/sell?game=<?= $arGame['id']?>" title="Продать <?= $arGame['moneys']?>">Продать <?= $arGame['moneys']?></a></li>
            </ul>
        </div>
        <noindex>
            <input type="hidden" name="inp_server" id="inp_server" value="">
            <input type="hidden" name="inp_payment" id="inp_payment" value="">
            <input type="hidden" name="inp_delivery" id="inp_delivery" value="">

            <input type="hidden" name="game_name" id="game_name" value="<?= $arGame['name']?>">
            <input type="hidden" name="game_id" id="game_id" value="<?= $arGame['id']?>">

            <div id="block1"><!--calc_col_2 calc_col_2__b1-->
                <div class="swap"> Сервер:
                    <select id="select_serv" name="select_serv"  class="inputsBorder">
<?php
    if (is_array($arGame['servers']) && count($arGame['servers'] > 0)) {
        if (is_array($arGame['servers']) && count($arGame['servers']) > 1){
            ?>
                        <option value="" selected="selected" disabled>Нажмите для выбора сервера</option>
            <?php
        }
        foreach ($arGame['servers'] as $server) {
            ?>
                        <option value="<?= $server['id'] ?>" data-kurs="<?= $server['kurs'] ?>"><?= $server['name'] ?></option>
            <?php
        }
    }
?>
                    </select>
                </div>
            </div>
            <div id="block2" style="display:none;"><!--calc_col_2 calc_col_2__b1-->

                <!-- <div class="calc_auth_text">Если у Вас уже есть аккаунт, <a class="" href="javascript:open_reg();">авторизуйтесь</a> для использования скидок</div> -->
<?php
    if (isset($arPaysystem) && is_array($arPaysystem) && count($arPaysystem) > 0){
?>
                <div class="swap select_pay_sys_c" >
                    <select id="select_pay" name="select_pay" class="inputsBorder">
<?php
        if (is_array($arPaysystem) && count($arPaysystem) > 1){?>
                        <option value="" selected="selected" disabled>Выберите способ оплаты</option><?php
        }
        foreach ($arPaysystem as $method) {?>
                        <option value="<?= $method['id'] ?>" data-curkurs="<?= $method['kurs'] ?>" data-cursymb="<?= $method['val'] ?>"><?= $method['name'] ?></option><?php
        }?>
                    </select>
                </div>
<?php
    }
?>
            </div>

            <div id="block3" class="readyCalc none"><!--calc_col_2 calc_col_2__b1-->

                <div>
                    <table>
                        <tr>
                            <td id="calc_zoloto">
                                <span>Получу:</span><br/>
                                <input type="text" id="inp_zoloto" name="inp_zoloto" value=""  class="inputsBorder">
                            </td>
                            <td id="calc_money">
                                <span>Стоимость:</span><br/>
                                <input type="text" id="inp_money" name="inp_money" value="<?= $arGame['mincount'] * 1 ?>"  class="inputsBorder">
                            </td>
                        </tr>
                    </table>

                    <div class="calc_min_sum_order"><small><strong>*Минимальная сумма заказа <span class="min_sum_order_js"><?= $arGame['mincount'] * 1 ?></span><span class="CURRENCY_NAME">$</span></strong></small></div>
                </div>
                <hr/>
                <div id="change_kurs">
                    <div><strong>Цена:</strong></div>
                    <div><span id="count_money" class="red"><?= $arGame['mincount'] ?></span> <span id="CURRENCY_NAME">руб</span></div>
                    <div> за <span id="const_zoloto" class="red">0</span> <span id="text_money"><?= $arGame['moneys']?></span></div>
                </div>
                <hr/>
            </div>

            <div id="block4" class="readyCalc none">
                <div class="contactField">
                    <label for="inp_phone"><%%>Telefon<%%>:</label>
                    <input type="text" class="optionalInput inputsBorder" name="inp_phone" id="inp_phone" value="">
                </div>
                <div class="contactField">
                    <label for="inp_skype"><%%>Skype<%%>:</label>
                    <input type="text" class="optionalInput inputsBorder" name="inp_skype" id="inp_skype" value="">
                </div>
                <div class="contactField">
                    <label for="inp_icq"><%%>ICQ<%%>:</label>
                    <input type="text" class="optionalInput inputsBorder" name="inp_icq" id="inp_icq" value="">
                </div>
                <div>
                    <div id="infoContact" class="calc_infoContact">Можно заполнить одно поле из 3-х (телефон, skype или icq).</div>
                </div>

                <div class="swap delivery_select" data-id-server="502">
                    <select name="s_delivery" id="s_delivery" class="inputsBorder">
                        <option value="">Выберите способ доставки</option>
                        <option value="1" >Способ доставки на усмотрение оператора</option>
                        <option value="2" >Игровая почта</option>
                    </select>
                </div>
                <div>
                    <div>
                        <label for="inp_email"><%%>Email<%%>:</label>
                        <input type="email" required="required" id="inp_email" name="inp_email" value="" class="inputsBorder">
                    </div>
                    <div>
                        <label for="inp_nickname"><%%>Nick<%%>:</label>
                        <input type="text" required="required" id="inp_nickname" name="inp_nickname" value="" class="inputsBorder">
                    </div>
                    <div>
                        <label for="ta_comment"><%%>Comment<%%>:</label>
                        <textarea name="ta_comment" id="ta_comment" class="inputsBorder"></textarea>
                    </div>

                </div>
            </div>

            <div class="readyCalc none">
                <div class="calc_itogo none">Сумма:
                    <span class="text_b c_sale_price">0</span>
                    <div class="CURRENCY_NAME">руб</div>
                    Получите:
                    <span class="text_b c_total_count">0</span>
                    <span class="c_price_2">валюты</span>.
                    Скидка: <span class="text_g total_discount">0%</span>
                </div>
            </div>
            <div class="calc_order">
                <button type="submit" class="calc_order_send">Заказать</button>

                <div class="popup_visibility_visible popup popup_name_agreement popup_theme_ededed popup_autoclosable_yes popup_adaptive_yes popup_animate_yes agreement i-bem agreement_js_inited popup_js_inited popup_to_right" onclick="return {&quot;popup&quot;:{&quot;directions&quot;:{&quot;to&quot;:&quot;right&quot;}},&quot;agreement&quot;:{}};" style=": -17px; left: -300px;">
                    <div class="popup__under"></div><i class="popup__tail" style="top: 24.98px;right:1px;"></i>
                    <div class="popup__content">
                        Оформляя заказ, Вы принимаете <a target="_blank" style="color:#1D7485" href="/rules/">условия соглашения</a>.
                    </div>
                </div>
            </div>
        </noindex>
    </form>
</div>

<script type="application/javascript">
    jQuery(document).ready(function($){
        if ($("#select_serv").find("option").length == 1) {
            $("#block2").slideDown();
        }else{
            $("#select_serv").bind( "change", function() {
                $("#block2").slideDown();
                recalc(1);
            });
        }
        $(".optionalInput").each(function(){
            $(this).bind("change keypressed", function(){
                $("#infoContact").removeClass("form-error");
                $(".optionalInput").each(function(){
                    $(this).removeClass('error');
                });
                $(".contactField").removeClass("has-error");
            });
        });

        if ($("#select_pay").find("option").length == 1) {
            $(".readyCalc").each(function( index ) {
                $( this ).slideDown();
            });

            recalc(1);
            $("#inp_zoloto").bind( "change", function() {
                recalc(1);
            });
            $("#inp_money").bind( "change", function() {
                recalc(2);
            });
        }else{
            $("#select_pay").bind( "change", function() {
                $(".readyCalc").each(function( index ) {
                    $( this ).slideDown();
                });
                recalc(1);
                $("#inp_zoloto").bind( "change", function() {
                    recalc(1);
                });
                $( "#inp_zoloto" ).keypress(function() {
                    recalc(1)
                });
                $("#inp_money").bind( "change", function() {
                    recalc(2);
                });
                $( "#inp_money" ).keypress(function() {
                    recalc(2)
                });
            });
        }
        $.validate({
            language : myLanguage,
            form : "#calc_form",
            borderColorOnError : '#F00',
            modules : 'html5',
            onSuccess : function($form) {
                alert('The form '+$form.attr('id')+' is valid!');
                //check 3 field contacts
                if(!$("#inp_phone").val() && !$("#inp_skype").val() && !$("#inp_icq").val())
                {
                    $("#infoContact").addClass("form-error");
                    console.log($(".optionalInput"));
                    $(".optionalInput").each(function(){
                        $(this).addClass('error');
                    });
                    $(".contactField").addClass("has-error");

                    $("#inp_phone").focus();
                    return false;
                }

                $("#inp_server").val($("#select_serv option:selected").text());
                $("#inp_payment").val($("#select_pay option:selected").text());
                $("#inp_delivery").val($("#s_delivery option:selected").text());

                return true; // Will stop the submission of the form
            }
        });
    });
    function recalc(inp){
        var zoloto = $("#inp_zoloto").val() * 1;
        var money = $("#inp_money").val() * 1;
        var server = $("#select_serv option:selected" ).data();
        var methodPay = $("#select_pay option:selected" ).data();
        if (!(server.kurs && methodPay.curkurs)) {return;}
        if (zoloto == 0 || inp == 2)
        {
            zoloto = (money / server.kurs).toFixed();
            $("#inp_zoloto").val(zoloto);
        }else{
            money = (zoloto * server.kurs).toFixed(2);
            $("#inp_money").val(money);
        }

    }
</script>