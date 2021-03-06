<?php

class FlexiBaseViewManager {
  protected $oView = null;
  protected $oObjectListManager = null;
  protected $sFieldPrefix = "field";
  protected $sListLinkCol = "id";
  protected $sListName = "list";
  protected $aView = array();
  protected $aTabs = array();
  
  protected $sInputPrefix = "";
  protected $sInputSuffix = "";
  
  protected $iMaxImageWidth = 100;
  
  public function  __construct($aParam=array()) {
    //parent::__construct($aParam);
  }
  
  public function setImageMaxWidth($iWidth) {
    $this->iMaxImageWidth = $iWidth;
  }
  
  public function setFieldInputPrefix($sPrefix) {
    $this->sInputPrefix = $sPrefix;
  }
  
  public function setFieldInputSuffix($sSuffix) {
    $this->sInputSuffix = $sSuffix;
  }
  
  public function setFieldInputPrefixSuffix($sPrefix=null, $sSuffix=null) {
    $this->sInputPrefix = $sPrefix;
    $this->sInputSuffix = $sSuffix;
  }
  
  public function setListName($sName) {
    $this->sListName = $sName;
  }
  
  public function setListFields($aList) {
    $oTable = $this->oObjectListManager->getObject();
    foreach($oTable->aChild["field"] as $sField => $oField) {
      if (! in_array($sField, $aList)) {
        $oTable->aChild["field"][$sField]->canlist = false;
      } else {
        $oTable->aChild["field"][$sField]->canlist = true;
      }
    }
  }
  public function getMethodName() {
    if (is_null($this->oView)) throw new Exception("View is not ready yet");
    return $this->oView->getVar("#method");
  }
  //override this
  public function onInit() {}
  public function onSetView($sName) {}
  public function onGetDisplayRow(&$oResult, $oRow){}
  public function onGetFieldList(&$aListField, $sListName="") {}
  public function onRenderFieldListHeader(&$aHeaderFields) {}
  /**
   * return true to render field to list
   * @param FlexiTableFieldObject $oField
   * @return boolean
   */
  public function onBeforeRenderFieldHeader(FlexiTableFieldObject &$oField) { return true; }
  public function onAfterRenderFieldHeader(& $sOutput, FlexiTableFieldObject & $oField) {}
  /**
   * return true to render field to list
   * @param FlexiTableFieldObject $oField
   * @return boolean
   */
  public function onBeforeRenderListField(FlexiTableFieldObject &$oField) { return true; }
  public function onAfterRenderListField(& $sOutput, FlexiTableFieldObject & $oField) {}
  /**
   * passed in value is alias name of primary
   * @param array $aPrimary
   */
  public function onGetPrimaryLink(&$aPrimary) {}

  public function addTab($sName, $sLabel, $sView="") {
    $this->aTabs[] = array("name" => $sName, "label" => $sLabel, "view" => $sView);
  }

  public function onFieldLabel(&$sLabel, FlexiTableFieldObject &$oField) {}
  /**
   * to change field label event
   * @param String $sLabel
   */
  public function onInputFieldLabel(& $sLabel, FlexiTableFieldObject &$oField, $sType) {}
  /**
   * return true to continue, or false to not render
   * @param FlexiTableFieldObject $oField
   * @param String $sType
   * @return boolean
   */
  public function onBeforeRenderFieldInput(FlexiTableFieldObject &$oField, $sType) { return true; }
  public function onRenderFieldInput(& $oForm, FlexiTableFieldObject &$oField, $oRow, $sType) {}
  public function onAfterRenderFieldInput(& $sOutput, &$sLabel, FlexiTableFieldObject & $oField, $oRow, $sType) {}
  public function onAfterRenderFieldDisplay(& $sOutput, &$sLabel, FlexiTableFieldObject & $oField, $oRow) {}
  public function onGetFieldDisplay(FlexiTableFieldObject &$oField, &$oRow, &$mValue) {}
  /**
   * Render a view template of a child/other view
   *  and assign to main view as a variable
   * @param String $sName Child view name
   * @param String $sView view template
   * @param String $sVarName var name for main view
   */
  public function renderChildView($sName, $sView, $sVarName) {
    $sHTML = $this->renderView($sView, $sName);
    $this->oView->addVar($sVarName, $sHTML);
  }
  /**
   * Render a view template,
   *  empty for main view, if sTargetView specified, will be child / other view
   * @param String $sView view template
   * @param String $sTargetView target view object
   * @return String
   */
  public function renderView($sView, $sTargetView="") {
    if (empty($sTargetView)) {
      return $this->oView->render($sView);
    }

    return $this->getView($sTargetView)->render($sView);
  }

  /**
   * Set DB Form Prefix, will be used in template
   * @param String $sPrefix
   */
  public function setViewDBFormPrefix($sPrefix) {
    $this->oView->addVar("sViewDBFormPrefix", $sPrefix);
  }

  public function getViewDBFormPrefix() {
    return $this->oView->getVar("sViewDBFormPrefix");
  }

  public function setView(FlexiView &$oView, $sName="") {
    $oView->addVar("#maximagewidth", $this->iMaxImageWidth);
    //throw new Exception("setting view: " . $oView->getVar("#maximagewidth"));
    if (empty($sName)) {
      $this->oView = &$oView;
    }
    if (isset($this->aView[$sName])) {
      throw new Exception("View instance already exists: " . $sName);
    }
    $this->aView[$sName] = &$oView;
    $this->onSetView($sName);
  }

  /**
   * Get view instances
   * @param <type> $sName
   * @return <type>
   */
  public function getView($sName="") {
    if (empty($sName)) return $this->oView;
    if (!isset($this->aView[$sName])) {
      throw new Exception("View instance does not exists: " . $sName);
    }
    return $this->aView[$sName];
  }

  /**
   * create new view instances
   *  will copy existing view to populate controller path and module
   * @param String $sName
   */
  public function getNewView($sName) {
    if (is_null($oView)) {
      throw new Exception("Main view not set");
    }
    $oMainView = $this->oView;
    $oView = new FlexiView();
    $oView->addVar("#module", $oMainView->getVar("#module"));
    $oView->addVar("#method", $oMainView->getVar("#method"));
    $oView->addVar("#modulepath", $oMainView->getVar("#modulepath"));
    $oView->addVar("#moduleurl", $oMainView->getVar("#moduleurl"));
    $oView->addVar("#modulepath", $oMainView->getVar("#modulepath"));
    
    $this->setView($oView, $sName);
  }

  public function getListLinkCol($sName) {
    $this->sListLinkCol = $sName;
  }

  public function setFormFieldPrefix($sPrefix) {
    $this->sFieldPrefix = $sPrefix;
  }

  public function getFormFieldPrefix() {
    return $this->sFieldPrefix;
  }

  
  public function setObjectListManager(FlexiObjectListManager $oManager) {
    $this->oObjectListManager = $oManager;
    $this->onInit();
  }
  
  /**
   * Prepare data for listing
   *  work closely with method prepareListHeader
   * @param array $aCond
   * @param array $aGroupBy
   * @param array $aOrder
   * @param String $sSelect
   * @param int $iLimit
   * @param int $iOffset
   */
  public function getQueryDisplayList($aCond, $aGroupBy, $aOrder, $sSelect, $iLimit, $iOffset) {
    //echo __METHOD__ . ": query select: " . $sSelect . "<br/>\n";
    $aList = $this->oObjectListManager->doTableQuery($aCond, $aGroupBy, $aOrder, $sSelect, $iLimit, $iOffset);
    //may add additional fields
    $aResult = array();
    for($c = 0; $c < count($aList); $c++) {
      $oRow = $aList[$c];
      $aPrimary = $this->getPrimaryLink($oRow);
      //$sLink = $this->oView->getVar("sLoadURL") . "&" . http_build_query($aLink);
      $oResultRow = $this->getDisplayRow($oRow);
      $oResultRow["_link"] = $aPrimary["link"];
      $oResultRow["_primary"] = $aPrimary["primary"];
      //keep the original display value
      $oResultRow[$this->sListLinkCol."_raw"] = $oResultRow[$this->sListLinkCol];
      $oResultRow[$this->sListLinkCol] = "<a href='javascript:' onClick='" . $aPrimary["link"].  "'>" . $oResultRow[$this->sListLinkCol] . "</a>";
      $aResult[] = $oResultRow;
    }
    return $aResult;
  }

  public function getPrimaryLink($oRow) {
    $oTable = $this->oObjectListManager->getObject();
    $aPrimary = $this->oObjectListManager->getPrimaryValue($oRow);
    $aAlias = array();
    foreach($aPrimary as $sField => $sValue) {
      $aAlias[$oTable->aChild["field"][$sField]->linkname] = $sValue;
    }
    $this->onGetPrimaryLink($aAlias);
    $aLink = $this->jsCleanArray($aAlias);
    $sLink = $this->oView->getVar("sViewDBFormPrefix") . "doLoadObject(" . json_encode($aLink) . ")";
    
    return array("primary" => $aAlias, "link" => $sLink);
  }
  

  /*
   * not used
  public function getInputRow($oRow, $sType) {
    $aResult = array();
    $oTable = $this->oObjectListManager->getObject();
    foreach($oRow as $sField => $sValue) {
      $aResult[$sField] = $this->getFieldDisplay($oTable->aChild["field"][$sField], $oRow);
    }
    return $aResult;
  }
   */

  public function getDisplayRow($oRow) {
    $aResult = array();
    $oTable = $this->oObjectListManager->getObject();
    foreach($oRow as $sField => $sValue) {
      if (isset($oTable->aChild["field"][$sField])) {
        $aResult[$sField] = $this->getFieldDisplay($oTable->aChild["field"][$sField], $oRow);
      } else {
        $aResult[$sField] = $sValue; //if field not declared, is added additional col, value should apear as it is
      }
    }
    $this->onGetDisplayRow($aResult, $oRow);
    return $aResult;
  }
  
  public function jsCleanArray($aValue, $sep="\"") {
    $aResult = array();
    foreach($aValue as $sKey => $sValue) {
      $aResult[$sKey] = str_replace($sep, "\\" . $sep, $sValue);
    }
    return $aResult;
  }

  public function prepareListHeader($sListName="") {
    if (is_null($this->oView)) throw new Exception("View not set");
    if (is_null($this->oObjectListManager)) throw new Exception("ObjectListManager not set");
    
      $oObject = $this->oObjectListManager->getObject();
      $aHeaderFields = $this->renderFieldsListHeader($oObject);
    $this->oView->addVar("aFieldHeader", $aHeaderFields);
    
    $aListField = $oObject->getListFields();
    $this->onGetFieldList($aListField, $sListName);
    $this->oView->addVar("aListFieldName", $aListField);
    
    $this->oView->addVar("#tabs", $this->aTabs);
  }


  public function getFormToObjectStore($oRow, $sFormType) {
    $bDebug = false;
    $oForm = array();
    $oTable = $this->oObjectListManager->getObject();
    foreach($oTable->aChild["field"] as $sName => & $oField) {
      $sField = $this->getFieldInputName($sName);
      if ($bDebug) echo __METHOD__ . ":Field:" . $sName . "<br/>\n";
      
      $sCond = "can" . $sFormType;
      if ($oField->$sCond == 1) {
        $sCond = "input".$sFormType;
        //ensure form has the field
        switch($oField->type) {
          case "file-varchar":
          case "file-text":
          case "image-varchar":
          case "image-text":
            //ensure this form field is updatable or insertable or is primary
            if (($sFormType=="update" && $oField->primary) ||
              in_array($oField->$sCond, array("edit","display","hidden"))
            ){
              $aValue = $_FILES[$sField];
              $oForm[$sName] = $aValue;
            }
            break;
          case "multiimage-text":
            if (($sFormType=="update" && $oField->primary) ||
              in_array($oField->$sCond, array("edit","display","hidden"))
            ){
              for($c=1; $c <= $oField->uploadcount; $c++) {
                $sField = $this->getFieldInputName($sName . "_" . $c);
                //echo "field: " . $sField;
                $aValue = $_FILES[$sField];
                $oForm[$sName . "_" . $c] = $aValue;
              }
            }
            break;
          default:

            if (isset($oRow[$sField])) {
              $sCond = "input".$sFormType;
              //ensure this form field is updatable or insertable or is primary
              if (($sFormType=="update" && $oField->primary) ||
                in_array($oField->$sCond, array("edit","display","hidden"))
              ){
                $sValue = $oRow[$sField];
                $oForm[$sName] = $sValue;
              }
            }
        }//switch
      }//caninsert / canupdate
      
    }//foreach
    return $oForm;
  }

  public function prepareForm($oRow, $sType) {
    $oObject = $this->oObjectListManager->getObject();
    $aFieldsInput = $this->renderFieldsInput($oObject, $oRow, $sType);
    $this->oView->addVar("aFieldsInput", $aFieldsInput);
    return $aFieldsInput;
  }

  public function renderFieldsListHeader(FlexiTableObject $oTable) {
    if (is_null($oTable)) throw new Exception("Object cannot be null");
    if (get_class($oTable)!="FlexiTableObject") throw new Exception("Unknown class: " . get_class($oTable) .", " . serialize ($oTable));
    $aResult = array();
    $sTable = $oTable->getTableName();
    foreach($oTable->aChild["field"] as $sName => & $oField) {
      if (!$this->onBeforeRenderFieldHeader($oField)) continue;
      
      if ($oField->canlist) {
        $sOutput = $this->renderFieldHeader($oField);
        $this->onAfterRenderFieldHeader($sOutput, $oField);
        $aResult[$oField->sName] = $sOutput;
      }
    }
    $this->onRenderFieldListHeader($aResult);
    return $aResult;
  }

  public function renderFieldHeader(FlexiTableFieldObject $oField) {
    return $oField->label;
  }
  
  
  /**
   * Render input fields
   * @param FlexiTableObject $oTable : object schema
   * @param array $oRow : data in array
   * @return array()
   */
  public function renderFieldsDisplay(FlexiTableObject $oTable, $oRow) {
    $aResult = array();
    $sTable = $oTable->getTableName();
    foreach($oTable->aChild["field"] as $sName => & $oField) {
      //echo "ok check\n";
      $sOutput = $this->getFieldDisplay($oField, $oRow);
      $sLabel  = $this->getFieldLabel($oField);
      
      $this->onAfterRenderFieldDisplay($sOutput, $sLabel, $oField, $oRow);
      $aResult[$oField->sName] = array("label" => $sLabel, "display" => $sOutput);
    }
    return $aResult;
  }

  /**
   * Render input fields
   * @param FlexiTableObject $oTable : object schema
   * @param array $oRow : data in array
   * @param String $sType : insert / update
   * @return array()
   */
  public function renderFieldsInput(FlexiTableObject $oTable, $oRow, $sType) {
    $aResult = array();
    $sTable = $oTable->getTableName();
    foreach($oTable->aChild["field"] as $sName => & $oField) {
      if (!$this->onBeforeRenderFieldInput($oField, $sType)) continue;
      
      $sCheck = "can" . $sType;
      //echo "check: " . $sCheck . ":" . $sName . "\n";
      if ($oField->$sCheck) {
        //echo "ok check\n";
        $aOutput = $this->renderFieldInputForm($oField, $oRow, $sType);
        $sOutput = $aOutput["output"];
        $sLabel  = $this->renderFieldInputLabel($oField, $sType, $aOutput["form"]);
        
        $this->onAfterRenderFieldInput($sOutput, $sLabel, $oField, $oRow, $sType);
        $aResult[$oField->sName] = array("label" => $sLabel, "input" => $sOutput, "name" => $oField->getName());
      }
    }
    return $aResult;
  }

  public function renderFieldInputLabel(FlexiTableFieldObject $oField, $sType, $oForm) {
    $sLabel = $this->getFieldInputLabel($oField, $sType);

    $sInputName = "input" . $sType;
    $sFormInput = $oField->$sInputName;
    $bRender = true;
    switch ($sFormInput) {
      case "edit":
      case "readonly":
      case "display":
        $bRender = true;
        break;
      case "hidden":
      case "none":
        $bRender = false;
        break;
    }

    if (is_null($sLabel)) return "";
    if (strlen($sLabel)==0) return "";
    if (! $bRender) return "";
    $sId = empty($oForm["#id"]) ? "" : " for=\"" . $oForm["#id"] . "\"";
    //echo "render: " . $oField->getName() . ", type: " . $sType;
    return "<label" . $sId . ">" . $sLabel . "</label>";
  }

  public function getFieldLabel(FlexiTableFieldObject $oField) {
    $sLabel = $oField->label;
    $this->onFieldLabel($sLabel, $oField);
    return $sLabel;
  }

  public function getFieldInputLabel(FlexiTableFieldObject $oField, $sType) {
    $sLabel = $oField->label;
    $this->onInputFieldLabel($sLabel, $oField, $sType);
    return $sLabel;
  }

  public function renderFieldInputForm(FlexiTableFieldObject $oField, $oRow, $sType) {
    $sInputName = "input" . $sType;
    $sFormInput = $oField->$sInputName;
    //var_dump($oField->getName() . ": " . $this->oView->getVar("#maximagewidth"));
    $sOutput = ""; $bAddHidden = false;
    $oForm = null;
    
    switch ($sFormInput) {
      case "edit":
        $oForm = $this->getFieldInput($oField, $oRow);
        $this->onRenderFieldInput($oForm, $oField, $oRow, $sType);
        $sOutput = $this->oView->renderMarkup($oForm, $oForm["#name"]);
        break;
      case "readonly":
      case "display":
        $sOutput = $this->getFieldDisplay($oField, $oRow) . "\n";
        $bAddHidden = true;
        //continue to output hidden field
        break;
      case "hidden":
        $bAddHidden = true;
        break;
      case "none":
        break;
    }
    //echo "name: " . $sInputName . ":[" . $sFormInput . "]";
    //var_dump($sOutput);
    //echo "<br/>\n";
    if ($bAddHidden) {
      $oFieldConfig = clone($oField);
      $oFieldConfig->type = "hidden";
      $oForm = $this->getFieldInput($oFieldConfig, $oRow);
      $this->onRenderFieldInput($oForm, $oField, $oRow, $sType);
      $sOutput .= $this->oView->renderMarkup($oForm, $oForm["#name"]);
    }

    return array("output" => $sOutput, "form" => $oForm);
  }

  /**
   * get value safe for html display
   * @param FlexiTableFieldObject $oField
   * @param array $oRow
   * @return String
   */
  public function getFieldDisplay(FlexiTableFieldObject $oField, $oRow) {
    $sName = $oField->getName();
    
    $mValue = $oRow[$sName];
    
    $bAllowHTML = $oField->allowhtml;
    switch($oField->type) {
      case "select-text":
      case "select-tinyint":
      case "select-smallint":
      case "select-bigint":
      case "select-mediumint":
      case "select-enum":
      case "select-int":
        if ($oField->existsOption($mValue)) {
          $mValue = $oField->getOptionLabel($mValue);
        }
        break;
      case "timestamp-int":
        $sFormat = FlexiDateUtil::getPHPDateTimeFormat(FlexiConfig::$sInputDateTimeFormat);
        if (!empty($mValue) && $mValue > 0) {
          $mValue = date($sFormat, $mValue);
        } else {
          $mValue = "";
        }
        break;
      case "datetime":
        $sFormat = FlexiDateUtil::getPHPDateTimeFormat(FlexiConfig::$sInputDateTimeFormat);
        if (!empty($mValue) && $mValue != "0000-00-00" && $mValue !="0000-00-00 00:00:00") {
          $mValue = date($sFormat, strtotime($mValue));
        } else {
          $mValue = "";
        }
        
        break;
      case "date":
        $sFormat = FlexiDateUtil::getPHPDateTimeFormat(FlexiConfig::$sDisplayDateFormat);
        if (!empty($mValue) && $mValue != "0000-00-00" && $mValue !="0000-00-00 00:00:00") {
          $mValue = date($sFormat, strtotime($mValue));
        } else {
          $mValue = "";
        }
        break;
      case "file-varchar":
      case "file-text":
        if (!empty($mValue)) {
          try {
            $sPath = (empty($oField->savepath)? "": $oField->savepath . "/") . $mValue;
            $sURL = FlexiFileUtil::getMediaURL($sPath);
            $mValue = "<a href='" . $sURL . "' target='_blank'>Open</a>";
          } catch (Exception $e) {
            $mValue = $e->getMessage();
          }
          $oField->allowtag = "a";
        }
        $bAllowHTML = true;
        break;
      case "image-varchar":
      case "image-text":
        if (!empty($mValue)) {
          try {
            $sPath = (empty($oField->savepath)? "": $oField->savepath . "/") . $mValue;
            //echo "path: " . $sPath;
            $sURL = FlexiFileUtil::getMediaURL($sPath);
            $sThumbURL = FlexiFileUtil::getMediaURL($sPath, null, null, array("maxwidth" => $this->iMaxImageWidth));
            $mValue = "<a href='" . $sURL . "' target='_blank'>" . 
              "<img src='" . $sThumbURL . "'/>" . 
              "</a>";
          } catch (Exception $e) {
            $mValue = $e->getMessage();
          }
          $oField->allowtag = "a,img";
        }
        $bAllowHTML = true;
        break;
      
      case "multiimage-text":
        if (!empty($mValue)) {
          $mValue = "";
          $aPath = explode($oField->uploadseparator, $oRow[$sName]);
          $sBasePath = (empty($oField->savepath)? "": $oField->savepath . "/");
          foreach($aPath as $sPath) {
            $sPath = trim($sPath);
            try {
              if (! empty($sPath)) {
                $sPath = $sBasePath . $sPath;
                $sURL = FlexiFileUtil::getMediaURL($sPath);
                $sThumbURL = FlexiFileUtil::getMediaURL($sPath, null, null, array("maxwidth" => $this->iMaxImageWidth));
                  $mValue .= "<a href='" . $sURL . "' target='_blank'>" . 
                    "<img src='" . $sThumbURL . "'/>" . 
                    "</a>";
              }
            } catch (Exception $e) {
              $mValue .= $e->getMessage();
            }
          }//foreach
          $oField->allowtag = "a,img";
        }
        $bAllowHTML = true;
        break;
      case "email":
        if (!empty($mValue)) {
          $oField->allowtag = "a";
          $sURL = "mailto:" . $mValue;
          $mValue = "<a href='" . $sURL . "'>" . $mValue . "</a>";
        }
        $bAllowHTML = true;
        break;
    }//switch type

    if (is_null($mValue)) $mValue = "";
    if ($bAllowHTML) {
      if(!empty($oField->allowtag)) {
        $aSafe = $this->getFieldSafeTags($oField);
        $sTag = implode(",", $aSafe["tag"]); $aAttribute = $aSafe["attribute"];
        $mValue = FlexiStringUtil::stripTagsAttributes($mValue, $sTag, $aAttribute);
      }
    } else {
      $mValue = strip_tags($mValue);
    }
    
    $this->onGetFieldDisplay($oField, $oRow, $mValue);
    
    return $mValue;
  }
  

  /**
   * check if value entered is safe
   * @param FlexiTableFieldObject $oField
   * @param <type> $oRow
   * @return <type>
   */
  public function isSafeFieldValue(FlexiTableFieldObject $oField, $oRow) {
    $sValue = $oRow[$oField->getName()];
    //todo
    $aSafe = $this->getFieldSafeTags($oField);
    
    return true;
  }
  
  public function getFieldSafeTags(FlexiTableFieldObject $oField) {
    $aResultTag = array();
    $aAttribute = array();
    
    return FlexiFormUtil::getSafeTagsAttributes($oField->allowtag);
  }
  
  public function getFieldInputName($sName) {
    $sPrefix = empty($this->sInputPrefix) ? "": $this->sInputPrefix;
    $sSuffix = empty($this->sInputSuffix) ? "": $this->sInputSuffix;
    return $this->sFieldPrefix . $sPrefix . $sName . $sSuffix;
  }
  
  public function getFieldInput(FlexiTableFieldObject $oField, $oRow) {
    $sName = $oField->getName();
    
    $aResult = array(
      "#name"           => $this->getFieldInputName($sName),
      "#id"             => $this->getFieldInputName($sName) . "_" . FlexiStringUtil::createRandomPassword(8),
      "#title"          => $oField->label,
      "#required"       => $oField->cannull==1? false: true,
      "#default_value"  => $oField->getPHPDefaultValue(),
      "#dbfield"        => $sName,
      "#insert"         => $oField->caninsert,
      "#update"         => $oField->canupdate,
    );
    
    switch($oField->type) {
      case "string":
      case "int":
      case "tinyint":
      case "smallint":
      case "mediumint":
      case "bigint":
      case "money":
      case "decimal":
      case "double":
      case "email":
        $aResult["#type"] = "textfield.raw";
        break;
      case "html":
        $aResult["#type"] = "html.raw";
        break;
      case "text":
        $aResult["#type"] = "textarea.raw";
        break;
      case "select-text":
      case "select-tinyint":
      case "select-smallint":
      case "select-bigint":
      case "select-mediumint":
      case "select-enum":
      case "select-int":
      case "select-char":
        $aResult["#type"] = "select.raw";
        $aResult["#options"] = $oField->getOptions();
        break;
      case "check-char":
      case "check-varchar":
      case "check-text":
        $aResult["#type"] = "checkboxes.raw";
        $aResult["#options"] = $oField->getOptions();
        $aResult["#multiple"] = true;
        break;
      case "json":
        $aResult["#type"] = "textarea.raw";
        break;
      case "date":
        $aResult["#type"] = "date.raw";
        break;
      case "datetime":
        $aResult["#type"] = "datetime.raw";
        break;
      case "timestamp":
      case "timestamp-int":
        $aResult["#type"] = "datetime.raw";
        break;
      case "monthyear":
        $aResult["#type"] = "datemonthyear.raw";
        break;
      case "file-varchar":
      case "file-text":
        $aResult["#type"] = "file.raw";
        $aResult["#savepath"] = $oField->savepath;
        break;
      case "image-varchar":
      case "image-text":
        $aResult["#type"] = "image.raw";
        $aResult["#maximagewidth"] = $this->iMaxImageWidth;
        $aResult["#savepath"] = $oField->savepath;
        break;
      case "multiimage-text":
        $aResult["#type"] = "multiimage.raw";
        $aResult["#maximagewidth"] = $this->iMaxImageWidth;
        $aResult["#savepath"] = $oField->savepath;
        $aResult["#uploadcount"] = $oField->uploadcount;
        $aResult["#uploadseparator"] = $oField->uploadseparator;
        break;
      case "hidden":
        $aResult["#type"] = "hidden.raw";
        break;
      
      case "html-tiny":
        $aResult["#type"] = "html.raw";
        break;
      default:
        throw new Exception("Unsupported type: " . $oField->type);
    }
    
    if (!empty($oField->formsize)) {
      if (substr($oField->type,0,4) == "html" ||
        substr($oField->type,0,4) == "text" ||
        substr($oField->type,0,4) == "json") {

        $aSize = explode(",",$oField->formsize);
        $aResult["#cols"] = $aSize[0];
        if (count($aSize)>=2) $aResult["#rows"] = $aSize[1];
      } else {
        //default
        $aResult["#size"] = $oField->formsize;
      }
    } else {
      
    }

    if (isset($oRow[$sName])) {
      $sValue = $oRow[$sName];
      switch($oField->type) {
        case "date":
        case "datetime":
          //dont need this as actual value is already hidden
          if ($sValue =="0000-00-00" || $sValue == "0000-00-00 00:00:00") {
            $sValue = "";
          }
          break;
        case "timestamp":
          if (empty($sValue)) {
            $sValue = "";
          } else {
            $sValue = date("Y-m-d H:i:s", $sValue);
          }
          breal;
        case "check-char":
        case "check-varchar":
        case "check-text":
          $sValue = empty($sValue) ? array(): explode($oField->uploadseparator, $sValue);
          break;
      }//switch
      $aResult["#value"] = $sValue;
    }

    return $aResult;
  }

}
