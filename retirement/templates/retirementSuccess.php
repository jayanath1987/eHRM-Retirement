<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>

<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Service Extension Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return checkValue();">
            <input type="hidden" name="mode" value="search" />
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>

                    <option value="emp_name" <?php if ($searchMode == "emp_name") {
            echo "selected";
        } ?>><?php echo __("Name") ?></option>
                    <option value="from_Date" <?php if ($searchMode == "from_Date") {
            echo "selected";
        } ?>><?php echo __("From Date") ?></option>
                    <option value="to_Date" <?php if ($searchMode == "to_Date") {
            echo "selected";
        } ?>><?php echo __("To Date") ?></option>
                    <option value="Clause" <?php if ($searchMode == "Clause") {
            echo "selected";
        } ?>><?php echo __("Clause") ?></option>
                </select>

                <label for="searchValue"><?php echo __("Search For") ?>:</label>
                <input type="text" size="20" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" />
                <input type="submit" class="plainbtn"
                       value="<?php echo __("Search") ?>" />
                <input type="reset" class="plainbtn" id="resetBtn"
                       value="<?php echo __("Reset") ?>" />
                <br class="clear"/>
            </div>
        </form>
        <div class="actionbar">
            <div class="actionbuttons">

                <input type="button" class="plainbtn" id="buttonAdd"
                       value="<?php echo __("Add") ?>" />


            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?></div>
            <br class="clear" />
        </div>
        <br class="clear" />
        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('retirement/retirement') ?>">
            <input type="hidden" name="mode" id="mode" value=""/>
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                        </td>



                        <td scope="col">
                            <?php if ($Culture == 'en') {
                                $ename = 'e.emp_display_name';
                            } else {
                                $ename = 'e.emp_display_name_' . $Culture;
                            } ?>
<?php echo $sorter->sortLink($ename, __('Employee Name'), '@Retirement', ESC_RAW); ?>
                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('b.from_Date', __('Date From'), '@Retirement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('b.to_Date', __('Date To'), '@Retirement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                    <?php echo $sorter->sortLink('b.Clause', __('Clause'), '@Retirement', ESC_RAW); ?>

                                </td>




                            </tr>
                        </thead>

                        <tbody>
                                <?php
                                $row = 0;
                                foreach ($retrmentlist as $listtrans) {
                                    $cssClass = ($row % 2) ? 'even' : 'odd';
                                    $row = $row + 1;
                                ?>
                        <tr class="<?php echo $cssClass ?>">
                            <td >
                            </td>

                            <td class="">
                                <a href="<?php echo url_for('retirement/retirementserviceextension/?id=' . $listtrans->getEmp_number() . '&rid=' . $listtrans->getRet_id()) ?>">
                                <?php
                                    if ($Culture == 'en') {
                                        $abcd = "getEmp_display_name";
                                    } else {
                                        $abcd = "getEmp_display_name_" . $Culture;
                                    }
                                    $dd = $listtrans->Employee->$abcd();
                                    $rest = substr($listtrans->Employee->$abcd(), 0, 100);

                                    if ($listtrans->Employee->$abcd() == null) {
                                        $dd = $listtrans->Employee->getEmp_display_name();
                                        $rest = substr($listtrans->Employee->getEmp_display_name(), 0, 100);
                                        if (strlen($dd) > 100) {
                                            echo $rest ?>.<span><a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a></span> <?php
                                        } else {
                                            echo $rest;
                                        }
                                    } else {

                                        if (strlen($dd) > 100) {
                                            echo $rest ?>.<a href="" title="<?php echo $dd ?>">...</a> <?php
                                        } else {
                                            echo $rest;
                                        }
                                    }
                                ?>
                                    </a>
                                </td>
                                <td class="">
<?php echo LocaleUtil::getInstance()->formatDate($listtrans->getFrom_date()); ?>
                                </td>
                                <td class="">
<?php echo LocaleUtil::getInstance()->formatDate($listtrans->getTo_date()); ?>
                                </td>
                                <td class="">
<?php echo $listtrans->getClause(); ?>
                                        </td>
                                        <td class="">

                                        </td>


                                    </tr>
<?php } ?>

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <script type="text/javascript">
                function disableAnchor(obj, disable){
                    if(disable){
                        var href = obj.getAttribute("href");
                        if(href && href != "" && href != null){
                            obj.setAttribute('href_bak', href);
                        }
                        obj.removeAttribute('href');
                        obj.style.color="gray";
                    }
                    else{
                        obj.setAttribute('href', obj.attributes
                        ['href_bak'].nodeValue);
                        obj.style.color="blue";
                    }
                }
                function checkValue(){
                    if($("#searchValue").val()==""){
                        alert("<?php echo __('Please enter search value') ?>");
                        return false;

                    }
                    if($("#searchMode").val()=="all"){
                        alert("<?php echo __('Please select the search mode') ?>");
                        return false;
                    }
                    else{
                        $("#frmSearchBox").submit();
                    }
                }


                $(document).ready(function() {
                    buttonSecurityCommon("buttonAdd","null","null","null");

                    //When click add button

                    $("#buttonAdd").click(function() {
                        location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/retirement/retirementserviceextension')) ?>";

                    });

                    // When Click Main Tick box
                    $("#allCheck").click(function() {
                        if ($('#allCheck').attr('checked')){

                            $('.innercheckbox').attr('checked','checked');
                        }else{
                            $('.innercheckbox').removeAttr('checked');
                        }
                    });

                    $(".innercheckbox").click(function() {
                        if($(this).attr('checked'))
                        {

                        }else
                        {
                            $('#allCheck').removeAttr('checked');
                        }
                    });



                    //When click remove button

                    $("#buttonRemove").click(function() {
                        $("#mode").attr('value', 'delete');
                        if($('input[name=chkLocID[]]').is(':checked')){
                            answer = confirm("<?php echo __("Do you really want to Delete?") ?>");
                        }


                        else{
                            alert("select at least one check box to delete");

                        }

                        if (answer !=0)
                        {

                            $("#standardView").submit();

                        }
                        else{
                            return false;
                        }

                    });

                    //When click Save Button
                    $("#buttonRemove").click(function() {
                        $("#mode").attr('value', 'save');
                    });

                    //When click reset buton
                    $("#resetBtn").click(function() {
                        location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/retirement/retirement')) ?>";
        });


    });


</script>

