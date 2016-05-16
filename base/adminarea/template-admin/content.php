<?php
    require_once("adminarea/template-admin/modules/doctype.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
    $bTinyMCE = true;
    require_once("adminarea/template-admin/modules/head.php");
?>
<body>
  <div id="wrapper">
<?php
    require_once("adminarea/template-admin/modules/nav-top.php");
    require_once("adminarea/template-admin/modules/nav-side.php");
?>
    <div id="page-wrapper" class="page-wrapper-cls">
      <div id="page-inner">
        <div class="row">
          <div class="col-md-12">
            <h1 class="page-head-line"><%%>Content Manager<%%></h1>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-tabs">
              <li class=""><a href="#home" data-toggle="tab" data-lang="en">English</a></li>
              <li class="active"><a href="#home" data-toggle="tab" data-lang="ru">Русский</a></li>
              <li class=""><a href="#home" data-toggle="tab" data-lang="ua">Українська</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade active in" id="home">
                    <div class="row">
                        <div class="col-md-4">
                            <h4><%%>Content<%%></h4>
                            <div class="col-sm-12">
                                <select id="selContent" multiple style="height: 400px; width:100%" class="form-control">
                                    <option>Wait... Content loading</option>
                                </select>
                            </div>
                            <div class="col-sm-9" style="margin-top: 10px;">
                                <a href="#" id="btnNew" class="btn btn-default">New</a>
                                <a href="#" id="btnDelete" class="btn btn-default">Delete</a>
                            </div>
                            <div class="col-sm-3 center" style="text-align: center;margin-top: 5px;">
                                <a href="#" id="btnUp" class="btn btn-default btn-xs">&uArr;</a><br/>
                                <a href="#" id="btnLeft" class="btn btn-default btn-xs">&lArr;</a>
                                <a href="#" id="btnDown" class="btn btn-default btn-xs">&dArr;</a>
                                <a href="#" id="btnRight" class="btn btn-default btn-xs">&rArr;</a>
                            </div>
                        </div>
                        <div class="col-md-8" id="contentDetails" style="display:none;">
                            <h4 id="dataTitle"><%%>Details<%%><span id="divNotSaved" style="display: none; color:#FF0000;"> * not saved!</span></h4>
                            <div id="dataContent" style="padding:10px;">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inp_cntTitle" class="col-sm-2 control-label"><%%>Title<%%></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inp_cntTitle" placeholder="<%%>Title<%%>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inp_cntFileName" class="col-sm-2 control-label"><%%>FileName<%%></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inp_cntFileName" placeholder="<%%>FileName<%%>">
                                        </div>
                                    </div>
                                    <div class="form-group">        
                                        <label for="inp_cntMetaTitle" class="col-sm-2 control-label">Meta <%%>Title<%%></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inp_cntMetaTitle" placeholder="Meta <%%>Title<%%>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inp_cntMETAKeywords" class="col-sm-2 control-label">Meta <%%>Keywords<%%></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inp_cntMETAKeywords" placeholder="Meta <%%>Keywords<%%>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inp_cntMETADescription" class="col-sm-2 control-label">Meta <%%>Description<%%></label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" rows="3"  id="inp_cntMETADescription" placeholder="Meta <%%>Description<%%>"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inp_cntBody" class="col-sm-2 control-label"><%%>Content<%%></label>
                                        <label for="inp_cntVisible" class="col-sm-9 control-label"><%%>Visible<%%></label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="form-control" id="inp_cntVisible">
                                        </div>
                                        <div class="col-sm-12">
                                            <textarea class="form-control textareaMCE" rows="3"  id="inp_cntBody" placeholder="<%%>Content<%%>"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <a href="#" id="btnCancel" class="btn btn-danger">Cancel</a>
                                            <a href="#" id="btnSave" class="btn btn-success">Save</a>
                                        </div>
                                    </div>

                                </form>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
          </div>
        </div>
      </div>
      <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
  </div>
  <!-- /. WRAPPER  -->
  <footer >
    &copy; 2015 YourCompany | By : <a href="http://www.designbootstrap.com/" target="_blank">DesignBootstrap</a>
  </footer>
  <!-- /. FOOTER  -->
<?php
    require_once("adminarea/template-admin/modules/scripts-ende.php");
?>
<script>
    var currentEditID = false;
    var arPage = false;

    $(document).ready(function(){
        loadContentList();
        
        $('#selContent').click(function(){changeSelContent()});
        $('#selContent').change(function(){changeSelContent()});

        $('#btnCancel').click(function(){btnCancelClick()});
        $('#btnSave').click(function(){btnSaveClick()});
        $('#inp_cntFileName').focusout(function(){
            console.log();
            $(this).val(generateFilename($(this).val()));
        });

        $('#btnNew').click(function(){btnNewClick()});
        $('#btnDelete').click(function(){btnDeleteClick()});
    });

    function loadContentList()
    {
        $.getJSON( "/adminarea/ajax/load-content.php", function( data ) {
            var items = [];
            var countContent = data.length;

            $( "#selContent").html("");

            $.each( data, function(id, line) {
                var sPrefix = "";
                for(s = 0; s < line.cntLevel; s++)
                {
                    sPrefix = sPrefix + "-- ";
                }
                
                //icons
                var icoSymbol = "";
                /*
                if (line.cntLocked == "1") 
                {
                    icoSymbol += '<i class="fa fa-lock" title="<%%>Page is locked<%%>"></i>';
                }else{
                    icoSymbol += '<i class="fa fa-unlock"></i>';
                }
                if (line.cntVisible == "1") 
                {
                    icoSymbol += '<i class="fa fa-eye-slash" title="<%%>Page is hidden<%%>"></i>';
                }else{
                    icoSymbol += '<i class="fa fa-eye"></i>';
                }
                */
                var textcolor = "#000000";
                //console.log(line);
                $('#selContent')
                    .append($("<option></option>")
                        .attr("value", line.cntID)
                        .attr("id",'content' + line.cntID)
                        .data({cntID: line.cntID, cntParentID: line.cntParentID, cntLevel: line.cntLevel, cntOrder: line.cntOrder})
                        .html(sPrefix + icoSymbol + line.cntTitle)
                    );

                if (line.cntVisible == 0) 
                {
                    textcolor = "#888888";
                }
                
                if (line.cntLevel == 0)
                { 
                    textcolor = "#0000FF";
                    //$('#content' + line.cntID).attr({'disabled':"disabled"});
                }                
                $('#content' + line.cntID).css({'color':textcolor});
                /*
                $('#content' + line.cntID).data({
                    'id':line.cntID,
                });
                */
                //console.log($('#content' + line.cntID).data());
            });
            
        });

        checkArrows();        
    }

    function loadPage(idPage)
    {
        if(!idPage) return false;

        $.getJSON( "/adminarea/ajax/load-content-page.php", {
            cntID: idPage
        })
        .done(function( data ) {
            arPage = data[0];
            
            $('#inp_cntTitle').val(arPage.cntTitle);
            $('#inp_cntFileName').val(arPage.cntFileName);
            $('#inp_cntMetaTitle').val(arPage.cntMetaTitle);
            $('#inp_cntMETAKeywords').val(arPage.cntMETAKeywords);
            $('#inp_cntMETADescription').val(arPage.cntMETADescription);
            $('#inp_cntVisible').prop("checked", (arPage.cntVisible == "1"));
            //$('#inp_cntBody').html(arPage.cntBody);

            tinymce.get('inp_cntBody').setContent(arPage.cntBody);
            arPage.cntBody = tinymce.get('inp_cntBody').getContent();
            
            $("#dataTitle").attr("title", "content ID: " + arPage.cntID);
            
            if(arPage.cntParentID > 0) $("#contentDetails").slideDown(); else $("#contentDetails").hide();
            if(arPage.cntLocked == "1") $("#btnDelete").addClass("disabled"); else $("#btnDelete").removeClass("disabled");
            $("#content" + arPage.cntID).attr("selected", true);
        });
        checkArrows();        
    }
    
    function checkArrows()
    {
        function disableAllArrows()
        {
            if ($('#btnUp').hasClass('disabled') != true) $('#btnUp').addClass('disabled');            
            if ($('#btnLeft').hasClass('disabled') != true) $('#btnLeft').addClass('disabled');            
            if ($('#btnDown').hasClass('disabled') != true) $('#btnDown').addClass('disabled');
            if ($('#btnRight').hasClass('disabled') != true) $('#btnRight').addClass('disabled');
        }
        function enableAllArrows()
        {
            if ($('#btnUp').hasClass('disabled')) $('#btnUp').removeClass('disabled');            
            if ($('#btnLeft').hasClass('disabled')) $('#btnLeft').removeClass('disabled');            
            if ($('#btnDown').hasClass('disabled')) $('#btnDown').removeClass('disabled');        
            if ($('#btnRight').hasClass('disabled')) $('#btnRight').removeClass('disabled');
        }

        selectedOption = $('#selContent option:selected');

        if (selectedOption.length < 1) 
        {
            disableAllArrows(); 
            return;
        } else enableAllArrows();

        var selectedContent = selectedOption.data();
        console.log(selectedContent);

        if (selectedContent.cntLevel < 1) 
        {
            disableAllArrows(); 
            return;
        }else enableAllArrows();
        
        if (selectedContent.cntOrder == 1)
        {
            if ($('#btnUp').hasClass('disabled') != true) $('#btnUp').addClass('disabled');            
            if ($('#btnRight').hasClass('disabled') != true) $('#btnRight').addClass('disabled');            
        }else{
            if ($('#btnUp').hasClass('disabled')) $('#btnUp').removeClass('disabled');            
            if ($('#btnRight').hasClass('disabled')) $('#btnRight').removeClass('disabled');            
        }

        if (selectedContent.cntLevel < 2)
        {
            if ($('#btnLeft').hasClass('disabled') != true) $('#btnLeft').addClass('disabled');            
        }else{
            if ($('#btnLeft').hasClass('disabled')) $('#btnLeft').removeClass('disabled');            
        }
        var nextOpt = selectedOption.next();
        console.log("next:", nextOpt)
        if (nextOpt.length == 0)
        {
            if ($('#btnDown').hasClass('disabled') != true) $('#btnDown').addClass('disabled');            
        }else{
            if ($('#btnDown').hasClass('disabled')) $('#btnDown').removeClass('disabled');            
        }
    }
    
    function contentSaved()
    {
        var bSaved = true;
        if(arPage)
        {
            if($('#inp_cntTitle').val() != arPage.cntTitle) bSaved = false;
            if(bSaved && $('#inp_cntFileName').val() != arPage.cntFileName) bSaved = false;
            if(bSaved && $('#inp_cntMetaTitle').val() != arPage.cntMetaTitle) bSaved = false;
            if(bSaved && $('#inp_cntMETAKeywords').val() != arPage.cntMETAKeywords) bSaved = false;
            if(bSaved && $('#inp_cntMETADescription').val() != arPage.cntMETADescription) bSaved = false;
            if(bSaved && (($("#inp_cntVisible").is(":checked") != (arPage.cntVisible == "1")))) bSaved = false;
            if(bSaved && tinymce.get('inp_cntBody').getContent() != arPage.cntBody) bSaved = false;
            
            if (!bSaved)
            {
                $("#divNotSaved").slideDown();
            }else{
                $("#divNotSaved").slideUp();
            }
        } 
        return bSaved;

    }
    
    function changeSelContent()
    {
        var selectedOption = $('#selContent option:selected');
        var bSave = true;
        if(arPage == false) {
            bSave = true;
        }else{
            bSave = contentSaved();
        }
        if(currentEditID > 0)
        {
            if (selectedOption.length) 
            {
                if (selectedOption.length > 1)
                {
                    if (bSave)
                    {
                        selectedOption.each(function(el, el1){
                            var tid = $(el1).val();
                            if(currentEditID == tid)
                            {
                                $("#content" + tid).attr('selected', false);
                            }
                        });
                    }else{
                        selectedOption.each(function(el, el1){
                            var tid = $(el1).val();
                            if(currentEditID != tid)
                            {
                                $("#content" + tid).attr('selected', false);
                            }
                        });
                    }
                }else{
                    if (!bSave)
                    {
                        selectedOption.each(function(el, el1){
                            $("#content" + $(el1).val()).attr('selected', false);
                        });
                        $("#content" + currentEditID).attr('selected', true);
                    }
                
                }
                
            }else{
                
                $("#dataContent").css({"backgroundColor":"#FFFFFF"});
                $("#contentDetails").slideUp();
                currentEditID = 0;
                arPage = false;
                checkArrows();        
                return;
            }
        }
        
        if (bSave) 
        {
            selectedOption = $('#selContent option:selected');
            
            if (selectedOption.length < 1) return false; //not selected

            var selectedContent = selectedOption.data();
            
            if (currentEditID == selectedContent.cntID) return false;  // not changed

            $("#dataContent").css({"backgroundColor":"#dddddd"});
            currentEditID = selectedContent.cntID;
            loadPage(currentEditID);
        }else return false;

    }
    
    function btnCancelClick()
    {
        if (!contentSaved()) 
        {
            $.confirm({
                text: "<%%>Content not saved! Save?<%%>",
                title:"<%%>Cancel confirmation<%%>",
                confirmButton: "<%%>Save<%%>",
                cancelButton: "<%%>No<%%>",
                confirm: function() {
                    btnSaveClick();
                    hideDivContentDetails();
                },
                cancel: function() {
                    hideDivContentDetails();
                }
            });
        }else{
            hideDivContentDetails();
        }
    }
    
    function btnSaveClick()
    {
        $.post( "/adminarea/ajax/save-content-page.php", { 
            cntID: arPage.cntID,
            cntTitle: $('#inp_cntTitle').val(),
            cntFileName: $('#inp_cntFileName').val(),
            cntMetaTitle: $('#inp_cntMetaTitle').val(),
            cntMETAKeywords: $('#inp_cntMETAKeywords').val(),
            cntMETADescription: $('#inp_cntMETADescription').val(),
            cntVisible: ($('#inp_cntVisible').is(":checked") ? 1 : 0),
            cntBody: tinymce.get('inp_cntBody').getContent()
        })
        .done(function( data ) {
            loadPage(arPage.cntID);
            // check color in selContent
        });
    }

    function hideDivContentDetails()
    {
        var selectedOption = $('#selContent option:selected');
        var selectedContent = selectedOption.data();
        selectedOption.each(function(el, el1){
            $("#content" + $(el1).val()).attr('selected', false);
        });
        $("#dataContent").css({"backgroundColor":"#FFFFFF"});
        $("#divNotSaved").hide();
        $("#contentDetails").slideUp();
        currentEditID = 0;
        arPage = false;
        return;
    }
    
    function btnNewClick()
    {
        //console.log("New page", currentEditID, arPage);
        var tTitle = "";
        var parentID = "";
        var iBoxOpen = false;
        if((currentEditID == 0) || (currentEditID == false) || (currentEditID == null)) 
        {
            $.msgbox({type:'alert', content: "<%%>Select parent page or group!<%%>"});
            return false;
        }

        var
            prompt_title = "<%%>Enter new page name<%%>:",
            prompt_default = "<%%>type new page name here<%%>...",
            tTitle = window.prompt(prompt_title, prompt_default);
        
        if (
            (tTitle == null)
            ||
            (tTitle == "")
            ||
            (tTitle == prompt_default)
        ) {
            return ;
        }
            
        /*
        $.msgbox({
            type: 'prompt',
            content: '<%%>Page title<%%>: ',
            title: '<%%>Create New Page<%%>',
            onOpen: function(){
                iBoxOpen = true;            
            },
            onClose: function(){
                if (this.val() === undefined || this.val() === "") alert('This required!');
                else {
                    alert('You entered: ' + this.val());                                         
                    tTitle = this.val();
                    iBoxOpen = false;
                }
            }
        });
        /*
        do {
            parentID = currentEditID;
        }while (tTitle == "" || iBoxOpen === true);
        */
        var Filename = generateFilename(tTitle);
        
        $.getJSON( "/adminarea/ajax/new-content-page.php", {
            cntParentID: arPage.cntID,
            cntTitle: tTitle,
            cntFileName: Filename,
            cntVisible: arPage.cntVisible,
        })
        .done(function( data ) {
            console.log("done creating new page", data);
            loadContentList();
            loadPage(data.cntID);
        });
        
        console.log("end New page", tTitle, Filename);
    }
    
    function btnDeleteClick()
    {
    }

    function generateFilename(sTitle)
    {

        if (sTitle == "") return false;
        sTitle = translite(sTitle);
        //todo: check in database
        return sTitle.toLowerCase();
    }
    
    function translite(str)
    {
        var arr = {
            'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ж':'zh', 'з':'z', 'и':'i', 'й':'y', 
            'к':'k', 'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 
            'ф':'f', 'ы':'i', 'э':'e', 'А':'A', 'Б':'B', 'В':'V', 'Г':'G', 'Д':'D', 'Е':'E', 'Ж':'Zh', 
            'З':'Z', 'И':'I', 'Й':'Y', 'К':'K', 'Л':'L', 'М':'M', 'Н':'N', 'О':'O', 'П':'P', 'Р':'R', 
            'С':'S', 'Т':'T', 'У':'U', 'Ф':'F', 'Ы':'I', 'Э':'E', 'ё':'yo', 'х':'h', 'ц':'c', 'ч':'ch', 
            'ш':'sh', 'щ':'shch', 'ъ':'', 'ь':'', 'ю':'yu', 'я':'ya', 'Ё':'YO', 'Х':'H', 'Ц':'C', 'Ч':'CH', 
            'Ш':'SH', 'Щ':'SHCH', 'Ъ':'', 'Ь':'', 'Ю':'YU', 'Я':'YA', 
            'ü':'ue', 'Ü':'Ue', 'ö':'oe', 'Ö':'Oe', 'ä':'ae', 'Ä':'Ae', 'ß':'ss'
        };
        var replacer = function(a) { return arr[a]||a};
        str = str.replace(/[А-яёЁ]/g, replacer).replace(/[\s]/g, '-').replace(/[\-]+/g, '-');

        return str;
    }

</script>
</body>
</html>
