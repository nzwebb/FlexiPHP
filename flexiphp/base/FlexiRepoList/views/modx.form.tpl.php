<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h1>Repository: List Management</h1>
<?=$this->render("headerbar");?>
<div class="sectionHeader">Select an action</div>

<div class="sectionBody">
  <div class="tab-pane" id="docManagerPane">
    <script type="text/javascript">
        tpResources = new WebFXTabPane(document.getElementById('docManagerPane'));
    </script>

    <!--BEGIN TAB1-->
    <div class="tab-page" id="tabTemplates">
      <?=$vars["form"] ?>
    </div>
    <!--END TAB1-->

  </div> <!--tabs-->
</div>
