        <!-- Правый блок -->
        <div id="sidebar-right">
            <div class="block-money">
                <div class="title-box">
                    <h2>Способы оплаты</h2> 
                </div>
                    <div class="content">
                        <nav>
                            <ul class="methodpay">
                                <li><a href="" title="">Qiwi кошелек<img src="/template/images/payment/qiwi.jpg" title="" alt=""/></a></li>
                                <li><a href="" title="">WMR, WMU, WMZ, WME<img src="/template/images/payment/webmoney.jpg" title="" alt=""/></a></li>
                                <li><a href="" title="">Яндекс деньги<img src="/template/images/payment/yandex-money.jpg" title="" alt=""/></a></li>
                                <li><a href="" title="">Платежные карты<img src="/template/images/payment/cards.jpg" title="" alt=""/></a></li>
                            </ul>
                        </nav>
<?php
/*
$arCurr = array(
    'eur' => $kurses[1][0],
    'rur' => $kurses[1][1],
    'uah' => $kurses[1][2],
);
*/
    if (isset($arCurr) && is_array($arCurr) && count($arCurr)){
?>
        <hr/>
        <div style="text-align: center;">
            <table id="curr_table">
                <tr>
                    <td nowrap>Валюта</td>
                    <td nowrap>Цена</td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        <span style="margin-right:5px;">&nbsp;</span><span>USD/EUR</span>
                    </td>
                    <td style="direction:ltr;"><?= $arCurr['eur']?></td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        <span style="margin-right:5px;">&nbsp;</span><span>USD/RUB</span>
                    </td>
                    <td style="direction:ltr;"><?= $arCurr['rur']?></td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        <span style="margin-right:5px;">&nbsp;</span><span>USD/UAH</span>
                    </td>
                    <td style="direction:ltr;"><?= $arCurr['uah']?></td>
                </tr>
            </table>

        </div>

<?php
    }
?>
                    </div>
                
            </div>  
        </div>
        <!-- Правый блок /-->
