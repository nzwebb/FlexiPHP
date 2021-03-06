<?php

$mValue = isset($vars["#value"]) ?
	$vars["#value"] : ( isset($vars["#default_value"]) ? $vars["#default_value"] : null);
$sMaxLen = isset($vars["#maxlength"]) ? " maxlength=\"" . $vars["#maxlength"] . "\"" : "";

$bDisabled = isset($vars["#disabled"]) ? $vars["#disabled"] : false;
$sRequired = isset($vars["#required"]) ?
	($vars["#required"] ? "<span class=\"required\">*</span>": "") : "";
	
$sMinDate = "";
$sMaxDate = "";

if (isset($vars["#mindate"]))
{
	$aDate = explode("-", $vars["#mindate"]);
	$sMinDate = ", minDate: new Date(" . $aDate[0] . ", " . $aDate[1] . ", " . $aDate[2] . ")";
}

if (isset($vars["#maxdate"]))
{
	$aDate = explode("-", $vars["#maxdate"]);
	$sMaxDate = ", maxDate: new Date(" . $aDate[0] . ", " . $aDate[1] . ", " . $aDate[2] . ")";
}

$sFormat = isset($vars["#format"]) ? $vars["#format"] : FlexiConfig::$sInputDateFormat;
$sPHPFormat = FlexiDateUtil::getPHPDateTimeFormat($sFormat); //fix double i, which only 1 i(min) in php
$sDisplayValue = "";
if (!empty($mValue)) {
  if (substr($mValue,0, 4)=="0000") {
    //empty date
    $sDisplayValue = "";
  } else {
    $iDatetime = strtotime($mValue);
    $sDisplayValue = date($sPHPFormat, $iDatetime);
  }
}

?>
<?=isset($vars["#prefix"]) ? $vars["#prefix"] : ""; ?>
	<input type="text" name="<?=$vars["#name"]?>label" <?=is_null($mValue) ? "" : " value=\"" . $sDisplayValue . "\""?><?=$sMaxLen?>
		<?=empty($vars["#id"]) ? "" : " id=\"" . $vars["#id"] . "label\""?><?=$bDisabled ? " disabled=\"disabled\"" : ""?>
		<?=isset($vars["#size"]) ? " size=\"" . $vars["#size"] . "\"": ""?> 
		<? if (isset($vars["#attributes"])) { echo FlexiStringUtil::attributesToString($vars["#attributes"]); } ?>>
  <input type="hidden" name="<?=$vars["#name"]?>" id="<?=$vars["#id"]?>" value="<?=$mValue?>" />
	<? if(isset($vars["#notice"])) { ?>
	<div class="flexiphp_div_notice"><?=$vars["#notice"]["msg"]?></div>
	<? } ?>
	<? if(isset($vars["#description"])) { ?>
	<div class="flexiphp_div_description"><?=$vars["#description"]?></div>
	<? } ?>
	<script type="text/javascript">
	jQuery(document).ready( function() {
		jQuery("#<?=$vars["#id"]?>label").datepicker({
			showWeek: true,
			dateFormat: '<?=$sFormat ?>',
			altFormat: 'yy-mm-dd',
      altField: '#<?=$vars["#id"]?>',
			changeMonth: true,
			changeYear: true
			<?=$sMinDate?>
			<?=$sMaxDate?>
			});

    //jQuery("#<?=$vars["#id"]?>label").datepicker("setDate", jQuery("#<?=$vars["#id"]?>").val());
    //jQuery("#<?=$vars["#id"]?>label").datepicker({value: jQuery("#<?=$vars["#id"]?>label").val()});
	});
	</script>
<?=isset($vars["#suffix"]) ? $vars["#suffix"] : "" ?>
