<?php
extract($vars);
//var_dump($vars["bCanEdit"]);
$aTabs = $vars["#tabs"];
$bCanDelete = empty($bCanDelete)? false: true;
$bCanEdit   = empty($bCanEdit)? false: true;
$bCanAdd   = empty($bCanAdd)? false: true;
?>
<? if(!empty($vars["#title"])) { ?>
<div class="ctrlHolderTitle" >
  <?=$vars["#title"]?>
</div>
<? } ?>
<?=$this->render("home.header");?>
<div class="clear" style="height: 5px;"></div>
<div id="<?=$sViewDBFormPrefix?>tabs" class="yui-navset">
  <ul class="yui-nav">
    <li class="selected"><a href="#tab1"><em>List</em></a></li>
    <? if ($bCanEdit || $bCanAdd) { ?>
    <li><a href="#tab2"><em>Form</em></a></li>
    <? } ?>
    <? foreach($aTabs as $oTab) { ?>
    <li><a href="#<?=$sViewDBFormPrefix?><?=$oTab["name"]?>"><em><?=$oTab["label"]?></em></a></li>
    <? } ?>
  </ul>
  <div class="yui-content">
    <div id="<?=$sViewDBFormPrefix?>tab-list">
      <?=$this->render("tab-list");?>
    </div>
    <!--still render this below-->
    <div id="<?=$sViewDBFormPrefix?>tab-form">
      <?=$this->render("tab-form");?>
    </div>
    <? foreach($aTabs as $oTab) { ?>
    <div id="<?=$sViewDBFormPrefix?><?=$oTab["name"]?>"><?=empty($oTab["view"])? "": $this->render($oTab["view"]) ?></div>
    <? } ?>
  </div>
</div>

<script type="text/javascript">
//var mytabs = new YAHOO.widget.TabView("tabs");
var <?=$sViewDBFormPrefix?>tabs;

YUI().use('tabview', function(Y) {
    <?=$sViewDBFormPrefix?>tabs = new Y.TabView({
        srcNode: '#<?=$sViewDBFormPrefix?>tabs'
    });
    <?=$sViewDBFormPrefix?>tabs.render();
});

<? if (!$bCanEdit && !$bCanAdd) { ?>
jQuery(document).ready(function() {
  jQuery("#<?=$sViewDBFormPrefix?>tab-form").hide();
});
<? } ?>
</script>

<?=$this->render("home.footer");?>