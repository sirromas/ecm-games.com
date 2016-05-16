<?php

?>
<form class="form-horizontal" id="formSendRequest">
    <div>
        <div id="sell_step1" class="form-group">
            <label for="selGames" class="col-sm-3 control-label"><%%>Select Game<%%>:</label>
            <div class="col-sm-9">
                <select id="selGames" name="selGames" class="form-control"></select>
            </div>
        </div>
        <div id="sell_step2" class="form-group none">
            <label for="selServer" class="col-sm-3 control-label"><%%>Select Server<%%>:</label>
            <div class="col-sm-9">
                <select id="selServer" name="selServer" class="form-control"></select>
            </div>
        </div>
        <div id="sell_step3" class="none">
            <div class="form-group">
                <label for="inpEmail" class="col-sm-3 control-label"><%%>Email<%%></label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="inpEmail" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inpName" class="col-sm-3 control-label"><%%>Name<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpName" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inpNick" class="col-sm-3 control-label"><%%>Nickname<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpNick" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inpSkype" class="col-sm-3 control-label"><%%>Skype<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpSkype" >
                </div>
            </div>
            <div class="form-group">
                <label for="inpPhone" class="col-sm-3 control-label"><%%>Phone<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpPhone" >
                </div>
            </div>
            <div class="form-group">
                <label for="inpICQ" class="col-sm-3 control-label"><%%>ICQ<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpICQ">
                </div>
            </div>
        </div>
        <div id="sell_step4" class="none">
            <div class="form-group">
                <label for="inpCount" class="col-sm-3 control-label"><%%>Count<%%></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inpCount" required>
                </div>
            </div>
            <div class="form-group">
                <label for="taMessage" class="col-sm-3 control-label"><%%>Message, Comments<%%></label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="taMessage"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-primary" id="sendButton"><%%>Send Request<%%></button>
                </div>
            </div>
        </div>
    </div>    
</form>

<div id="mainThanks" class="none">
    <h1>Магазин игровой валюты</h1>
    <h3>Благодарим Вас за сделаный запрос!</h3>
    <p>В близжайшее время с Вами свяжется оператор, по указанным при оформлении контактам, для согласования сделки.</p>
    <p>Спасибо за доверие к нашему сервису!</p>
</div>

<script>
    var currGame = <?= (isset($_GET['game']) && $_GET['game'] > 0 ? $_GET['game']*1 : 0) ?>;
    var arGames = false;
    var arServers = false;
    $('document').ready(function(){
        $.ajax({
            method: "POST",
            url: "/ajax/sell-form-load.php",
            data: { 
                requestType: "game"
            },
            dataType: "json"
        })
        .done(function( data ) {
            $( "#selGames").html("");
            arGames = data;
            $.each( data, function(id, line) {
                $('#selGames')
                    .append($("<option></option>")
                        .attr("value", line.gamID)
                        .attr("id",'game' + line.gamID)
                        .html(line.gamName)
                    );

                $('#game' + line.gamID).data({
                    'id':line.gamID,
                });
                if (currGame == line.gamID)
                {
                    $('#game' + line.gamID).attr('selected', "selected");
                }
            });
            if(currGame == 0)
            {
                $('#selGames')
                    .append($("<option></option>")
                        .attr("value", 0)
                        .attr("id",'game0')
                        .attr('selected', "selected")
                        .html("<%%>Select game<%%>")
                    );
            }else{
                selGamesChange();
            }
            
            $("#selGames").change(function(){
                selGamesChange();
            });
        });
        $("#sell_step3").find("input").each(function(num, el){
            $(el).bind("change", function(){ checkContactData(); });
            $(el).bind("keyup", function(){ checkContactData(); });
        });
        $("#sendButton").bind("click", function(){ 
            var result = sendData(); 
            if (result)
            {
                //hide form
                $("#formSendRequest").slideUp();
                //show Thank page
                $("#mainThanks").slideDown();
                
            }
            return false;
        });
    });
    
    function sendData()
    {
        $.ajax({
            method: "POST",
            url: "/ajax/sell-send-data.php",
            data: { 
                requestType: "save",
                game_name: $("#selGames option:selected" ).text(), 
                server_name: $("#selServer option:selected" ).text(),
                ordEmail: $("#inpEmail").val(),
                ordNickname: $("#inpNick").val(),
                ordComment: $("#taMessage").val(),
                ordPhone: $("#inpPhone").val(),
                ordSkype: $("#inpSkype").val(),
                ordICQ: $("#inpICQ").val(),
                ordCountGold: $("#inpCount").val(),
                ordGameID: $("#selGames").val(),
                ordServerID: $("#selServer").val(),

           },
            dataType: "json"
        })
        .done(function( data ) {
            console.log( data );
        });
    }
    
    function selGamesChange()
    {
        var gamesID = $("#selGames").val();
        if (gamesID == 0) return false;
        $("#sell_step2").slideDown();
        $("#sell_step3").slideUp();
        $("#sell_step4").slideUp();
        $.ajax({
            method: "POST",
            url: "/ajax/sell-form-load.php",
            data: { 
                requestType: "server",
                gamID: gamesID
           },
            dataType: "json"
        })
        .done(function( data ) {
            arServers = data;
            $( "#selServer").html("");
            $.each( data, function(id, line) {
                $('#selServer')
                    .append($("<option></option>")
                        .attr("value", line.gasID)
                        .attr("id",'server' + line.gasID)
                        .html(line.gasName)
                    );

                $('#server' + line.gasID).data({
                    'id':line.gasID,
                    'kurs':line.gasKurs,
                });
            });

            if(data.length > 1)
            {
                $('#selServer')
                    .append($("<option></option>")
                        .attr("value", 0)
                        .attr("id",'server0')
                        .attr('selected', "selected")
                        .html("<%%>Select game server<%%>")
                    );
            }else{
                selServerChange();
            }
            
            $("#selServer").change(function(){
                selServerChange();
            });
        });
    }

    function selServerChange()
    {
        $("#sell_step3").slideDown();
        checkContactData();
    }
    
    function checkContactData()
    {
        if ($("#inpEmail").val() == "" || $("#inpName").val() == "" || $("#inpNick").val() == "")
        {
            return false;
        }
       
        $("#sell_step4").slideDown();
    }
    

</script>