<?php

$mValue = isset($vars["#value"]) ?
	$vars["#value"] : ( isset($vars["#default_value"]) ? $vars["#default_value"] : null);

$bDisabled = isset($vars["#disabled"]) ? $vars["#disabled"] : false;

$sRequired = isset($vars["#required"]) ?
	($vars["#required"] ? "<span class=\"required\">*</span>": "") : "";
$sLabel = isset($vars["#label"]) ? $vars["#label"] : "";
?>
<div id="div-<?=$vars["#id"]?>" class="flexi_field<?=@$vars["#required"]? " required" : ""?>">
<?=isset($vars["#prefix"]) ? $vars["#prefix"] : ""; ?>
<? if (isset($vars["#title"])) { ?>
<div class="flexiphp_div_label">
	<?=$vars["#title"]?><?=$sRequired?>
</div>
<? } ?>
<div class="flexiphp_div_input">

	<? 
	$iCnt= 0;
	foreach($vars["#options"] as $sKey => $sValue) { 
		
		$sChecked = $sKey == $mValue ? " checked=\"checked\"" : false;
		?>
	<div style="clear:both;">
		<input type="radio" name="<?=$vars["#name"]?>" <?=!is_null($mValue) && $mValue==$sKey ? " selected " : ""?><?="value=\"" . $sKey . "\""?>
			<?=empty($vars["#id"]) ? "" : " id=\"" . $vars["#id"] . "-" . $iCnt . "\""?><?=$bDisabled ? " disabled=\"disabled\"" : ""?>
			<?=isset($vars["#size"]) ? " size=\"" . $vars["#size"] . "\"": ""?><?=$sChecked?>
			<? if (isset($vars["#attributes"])) { echo FlexiStringUtil::attributesToString($vars["#attributes"]); } ?>>
		<label <?=isset($vars["#id"]) ? "for=\"" . $vars["#id"] . "-" . $iCnt . "\"": "" ?>><?=$sValue?></label>
	</div>
	<? 
		$iCnt++;
	} ?>
	<? if(isset($vars["#notice"])) { ?>
	<div class="flexiphp_div_notice"><?=$vars["#notice"]["msg"]?></div>
	<? } ?>
	<? if(isset($vars["#description"])) { ?>
	<div class="flexiphp_div_description"><?=$vars["#description"]?></div>
	<? } ?>
</div>
<?=isset($vars["#suffix"]) ? $vars["#suffix"] : "" ?>
</div>
