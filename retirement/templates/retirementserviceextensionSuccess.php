<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>

<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>


<div class="formpage4col">
    <div class="navigation">


    </div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Service Extension") ?></h2></div>
        <?php echo message() ?>
        <br class="clear"/>
        <form name="frmSave" id="frmSave" method="post"  action="" >

            <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input type="text" name="txtEmployeeName" disabled="disabled" id="txtEmployee" value="<?php echo $empfname; ?>" readonly="readonly"/>
                <input type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $etid; ?>"/>&nbsp;
            </div>
            <div class="centerCol">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label class="controlLabel" ><?php echo __("Service Extension") ?></label>
            </div>
            <div class="centerCol">
                <input id="txtretid" type="text" name="txtretid" value="<?php echo $retid; ?>" maxlength="1" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label class="controlLabel" ><?php echo __("Date From") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="fromdate" type="text" name="txtfromdate" value="<?php echo LocaleUtil::getInstance()->formatDate($extfdate); ?>" />
            </div>
            <div class="centerCol">
                <label class="controlLabel" style="width: 300px"><?php echo __("Under Which Clause was the Service Extended ?") ?> <span class="required">*</span></label>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label class="controlLabel" ><?php echo __("Date To") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="todate" type="text" name="txttodate" value="<?php echo LocaleUtil::getInstance()->formatDate($exttdate); ?>" />
            </div>


            <div class="centerCol">
                <input type="text" name="txtclause"  value="<?php echo $extclause; ?>" maxlength="20"/>

            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label class="controlLabel" ><?php echo __("Comment") ?> </label>
            </div>
            <div class="centerCol">
                <textarea  class="formTextArea" style="margin-left: 0px; margin-top: 0px; height: 80px; width: 320px;" name="txtcomment" rows="8" cols="5" ><?php echo $extcomment; ?></textarea>
            </div>

            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                       value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                       value="<?php echo __("Reset"); ?>" />
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" tabindex="10" />
            </div>
        </form>
        <table id="fpp" cellpadding='0' cellspacing='0' class='data-table'>

            <thead>
                <tr>
                    <td scope='col' style='width: 120px'> <?php echo __('Service Extension')//echo $sorter->sortLink('trans_reason_en', __('Job Title Name'), '@jobtitle_list', ESC_RAW);  ?>
                    </td>
                    <td  scope='col' style='width: 100px'>
                        <?php echo __('Date From'); ?>
                    </td>

                    <td scope='col' style='width: 100px'>
                        <?php echo __('Date To'); ?>
                    </td>

                    <td scope='col' style='width: 100px'>
                        <?php echo __('Clause'); ?>
                    </td>
                    <td scope='col' style='width: 350px'>
                        <?php echo __('Comment'); ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                        $row = 0;
                        foreach ($symplodret as $reasons) {

                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            $row = $row + 1;
                ?>
                            <tr class="<?php echo $cssClass ?>">

                                <td class="">
                                    &nbsp;&nbsp;&nbsp;<a href="<?php echo url_for('retirement/retirementserviceextension?id=' . $reasons['emp_number'] . '&rid=' . $reasons['ret_id']) ?>"><?php echo $reasons['ret_id']; ?></a>
                                </td>
                                <td class="">
                        <?php echo LocaleUtil::getInstance()->formatDate($reasons['from_date']); ?>
                        </td>
                        <td class="">
                        <?php echo LocaleUtil::getInstance()->formatDate($reasons['to_date']); ?>
                        </td>
                        <td class="">
                        <?php echo $reasons['clause']; ?>
                        </td>
                        <td class="">
                        <?php echo $reasons['comment']; ?>
                        </td>
                        <td class="">
                            <a onClick="deleteconf(<?php echo $reasons['emp_number']; ?>, <?php echo $reasons['ret_id']; ?>)">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                    </tbody>
                </table>

            </div>
            <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
            <br class="clear" />
        </div>

<?php
                        require_once '../../lib/common/LocaleUtil.php';
                        $sysConf = OrangeConfig::getInstance()->getSysConf();
                        $sysConf = new sysConf();
                        $inputDate = $sysConf->dateInputHint;
                        $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
?>
                        <script type="text/javascript">
                            // <![CDATA[
                            function multipledelete(row){
                                for(var i=0; i<row; i++ ){
                                    var btn="del_"+i;
                                    buttonSecurityCommon("null","null","editBtn",btn);

                                }
                            }


                            function AjaxADateConvert(date){

                                var day;
                                $.ajax({
                                    type: "POST",
                                    async:false,
                                    url: "<?php echo url_for('retirement/AjaxADateConvert') ?>",
                                    data: { date: date },
                                    dataType: "json",
                                    success: function(data){day = data.date;}
                                });
                                return day;
                            }


                            function SelectEmployee(data){

                                myArr = data.split('|');
                                $("#txtEmpId").val(myArr[0]);
                                $("#txtEmployee").val(myArr[1]);
                                sendValue($("#txtEmpId").val());
                                sendValue2($("#txtEmpId").val());

                            }
                            function LoadCurrentDep(){
                                sendValue($("#txtEmpId").val());
                                sendValue2($("#txtEmpId").val());
                            }

                            function sendValue2(str){

                                $.getJSON(

                                "<?php echo url_for('retirement/AjaxCalllast') ?>",  //Ajax file

                                { sendValue2: str },  // create an object will all values

                                //function that is called when server returns a value.
                                function(data){
                                    var No=Number(data);
                                    No+=1;
                                    $("#txtretid").val(No);
                                },

                                //How you want the data formated when it is returned from the server.
                                "json"
                            );

                            }




                            function sendValue(str){
                                $.getJSON(

                                "<?php echo url_for('retirement/AjaxCall') ?>", //Ajax file

                                { sendValue: str },  // create an object will all values

                                //function that is called when server returns a value.
                                function(data){

                                    var list="<table cellpadding='0' cellspacing='0' class='data-table'>";
                                    list+="<tbody><thead><tr><td width='30'><input type='checkbox' class='checkbox' name='allCheck' value='' id='allCheck' />";
                                    list+="</td>";

                                    list+="<td scope='col' style='width: 100px'>";
                                    list+="<?php echo __('Service Extension') ?>";
                                    list+="</td>";

                                    list+="<td  scope='col' style='width: 100px'>";
                                    list+="<?php echo __('Date From') ?>";
                                    list+="</td>"

                                    list+="<td scope='col' style='width: 100px'>";
                                    list+="<?php echo __('Date To') ?>";
                                    list+="</td>";

                                    list+="<td scope='col' style='width: 110px'>";
                                    list+="<?php echo __('Clause') ?>";
                                    list+="</td>";

                                    list+="<td scope='col' style='width: 350px'>";
                                    list+="<?php echo __('Comment') ?>";
                                    list+="</td>";

                                    list+="</tr>";
                                    list+="</thead><tbody>";

                                    var row=0;
                                    var css='even';
                                    $.each(data, function(key, value) {
                                        css=(row % 2 ?'even':'odd');



                                        var com=value.comment;
                                        list+="<tr class="+css+"><td ><input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id='chkLoc' value='r"+value.ret_id+"' /></td>";
                                        list += "<td ><a href= '<?php echo url_for('retirement/retirementserviceextension/?id=') ?>"+value.emp_number+"?rid="+value.ret_id+"'>"+value.ret_id+"</a></td><td>"+AjaxADateConvert(value.from_date)+"</td><td>"+AjaxADateConvert(value.to_date)+"</td><td>"+value.clause+"</td>";
                                        list += "<td>"+(com).substr(0, 50);"</td>";
                                        list += "<td ><a  id='del_"+row+"' onClick='deleteconf("+value.emp_number+","+value.ret_id+");'><?php echo __("Delete") ?></a></td></tr>";

                                        row+=1;
                                    });
                                    list+="</tbody>";


                                    $('#fpp').html(list);
                                    multipledelete(row);
                                    "json"
                                });

                            }
                            function deleteconf(id,rid){
                                answer = confirm("<?php echo __("Do you really want to Delete?") ?>");
                                if (answer !=0)
                                {
                                    location.href="<?php echo url_for('retirement/DeleteRetirement?id=') ?>"+id+"?rid="+rid;

                                }

                            }
                            $(document).ready(function() {
                                buttonSecurityCommon("null","null","editBtn","del");
                                $("#fromdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });
                                $("#todate").datepicker({ dateFormat: '<?php echo $inputDate; ?>' });

<?php if ($editMode == true) { ?> <?php if ($btn == "new") {
                                $editMode = 0; ?>//alert("<?php echo $editMode; ?>");
                                                                       $('#editBtn').val("<?php echo __("Save") ?>");
                                                                       $('#frmSave :input').attr('disabled', false);
                                                                       $("#frmSave").data('edit')== 0;
                                                                       $('#btnBack').removeAttr('disabled');
<?php } else { ?>
                                                              $('#frmSave :input').attr('disabled', true);
                                                              $('#editBtn').removeAttr('disabled');
                                                              $('#btnBack').removeAttr('disabled');

<?php }
                        } ?>



                                                  $('#empRepPopBtn').click(function() {
                                                      var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                                                      if(!popup.opener) popup.opener=self;
                                                      popup.focus();
                                                  });

                                                  jQuery.validator.addMethod("orange_date",
                                                  function(value, element, params) {

                                                      var format = params[0];

                                                      // date is not required
                                                      if (value == '') {

                                                          return true;
                                                      }
                                                      var d = strToDate(value, "<?php echo $format ?>");


                                                      return (d != false);

                                                  }, ""
                                              );

                                                  //Validate the form
                                                  $("#frmSave").validate({
                                                      rules: {
                                                          txtEmployeeName:{required: true},
                                                          txtfromdate: { required: true ,orange_date:true },
                                                          txtretid:{number: true },
                                                          txttodate: { required: true ,orange_date:true },
                                                          txtclause: { required: true, noSpecialCharsOnly: true,maxlength:20 },
                                                          txtcomment: { noSpecialCharsOnly: true ,maxlength:200 }

                                                      },
                                                      messages: {
                                                          txtEmployeeName:"<?php echo __("Please Select the Employee Name") ?>",
                                                          txtfromdate: {required:"<?php echo __("Please Enter Date") ?>",orange_date: "<?php echo __("Please specify valid date"); ?>"},
                                                          txtretid:{number:"<?php echo __("Please Enter Digits") ?>"},
                                                          txttodate: {required:"<?php echo __("Please Enter Date") ?>",orange_date: "<?php echo __("Please specify valid date"); ?>"},
                                                          txtclause:{required:"<?php echo __("This Fiels is required") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",maxlength:"<?php echo __("Maximum 20 Characters") ?>"},
                                                          txtcomment:{noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>"}

                                                      }
                                                  });


                                                  // When click edit button

                                                  $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                                                  $("#editBtn").click(function() {
                                                      var editMode = $("#frmSave").data('edit');
                                                      if (editMode == 1) {

                                                          location.href="<?php echo url_for('retirement/retirementserviceextension?id=' . $etid . '&rid=' . $retid . '&lock=1') ?>";
                                                      }
                                                      else {
                                                          var startDate = $('#fromdate').val();
                                                          var endDate = $('#todate').val();
                                                          if(startDate > endDate){
                                                              alert("<?php echo __("Service Extension From date should be less than To date") ?>");
                                                              return false;
                                                          }
                                                          var retid=$('#txtretid').val();
                                                          if(retid > 6){
                                                              alert("<?php echo __("Service Extension has Maximum Point") ?>");
                                                              return false;
                                                          }
                                                          $('#frmSave').submit();
                                                      }


                                                  });

                                                  //When click reset buton
                                                  $("#btnClear").click(function() {
                                                      document.forms[0].reset('');
                                                  });

                                                  //When Click back button
                                                  $("#btnBack").click(function() {
                                                      location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/retirement/retirement')) ?>";
                          });

                      });
                      // ]]>
</script>