<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FlexiRemoteClient
 *
 * @author james
 */
abstract class FlexiRemoteClient {
  protected $mData = array();
  public $mResult = null;
  protected $sRemoteClientName = "FlexiRemoteClient v1.1";
  protected $sRemoteKey = "";
  protected $sToken = "";
  
  public function __construct($sRemoteKey) {
    $this->sRemoteKey = $sRemoteKey;
    $this->init($sRemoteKey);
  }

  function init($sRemoteKey) {}

  public function setContent($mData) {
    $this->mData = $mData;
  }

  public function getHeaders() {
    return array_merge(array(
			"User-Agent: " . $this->sRemoteClientName,
			"Accept-Language:	en-us,en;",
			"Accept-Charset:	utf-8,ISO-8859-1;q=0.7,*;q=0.7",
			"Keep-Alive:	115",
			"Connection: keep-alive",
			"Cache-Control:	max-age=0"
		), $this->_getHeaders());
  }

  /**
   * Do call to remote url
   * @param String $asURL
   * @param String $asModule
   * @param String $asMethod
   * @return boolean: true/false
   */
  public function callRemote($asURL = "http://localhost", $asModule = "", $asMethod = "") {
    FlexiLogger::debug(__METHOD__, "Calling URL: " . $asURL);
    $sURL = $asURL;
    $sModule = empty($asModule) ? "default" : $asModule;
    $sMethod = empty($asMethod) ? "default" : $asMethod;
    
    //$sURL = "temp/bloomberg-stocks.html";
		$aHeader = $this->getHeaders();

		$opts = array(
			'http'=>array(
				'method'=>"POST",
				'header'=> implode("\r\n", $aHeader),
        //'content' => http_build_query(array('status' => $message)),
        'content' => $this->getRequestContent($sModule, $sMethod)
			)
		);
    
    FlexiLogger::debug(__METHOD__, "Content: " . $opts["http"]["content"]);

		$context = stream_context_create($opts);
    FlexiLogger::debug(__METHOD__, "Processing URL: " . $asURL);

    $sResult = file_get_contents($sURL, false, $context);
    //var_dump($sResult);
		$this->mResult = FlexiCryptUtil::b64Decrypt($sResult, $this->sRemoteKey);
    //echo "<hr/>";
    //var_dump($this->mResult);
    
    FlexiLogger::debug(__METHOD__, "Result raw: " . $this->mResult);
    $aResult = $this->getResult();
    return $aResult->status;
  }

  public function getResultReturned() {
    $aResult = $this->getResult();
    return $aResult->return;
  }
  
  public function getResult() {
    return $this->getContent($this->mResult);
  }

  /**
   * get data prepared by remote call type, example: json
   * @return String
   */
  public function getRequestContent($sModule, $sMethod) {
    return $this->_prepareContent(
            array(
            "module" => $sModule,
            "method" => $sMethod,
            "token" => $this->sToken,
            "data" => $this->mData
            )
    );
  }

  abstract public function _getHeaders();
  /**
   * convert data to remote call data
   * @param Mixed $mData
   * @return String
   */

  public function _prepareContent($amData) {
    $mData = $this->prepareContent($amData);
    //echo "sending: " . $mData;
    //encrypt data before sending
    return FlexiCryptUtil::b64Encrypt($mData, $this->sRemoteKey);
  }
  
  abstract public function prepareContent($mData);
  /**
   * convert result from remote call to object
   * @param String $sData
   * @return Mixed
   */
  abstract public function getContent($sData);
  
  
  public function doGetLoginToken($sUserName, $sPassword, $asURL = "http://localhost/remote.php") {
    $this->setContent(array("username" => $sUserName, "password" => $sPassword));
    $bResult = $this->callRemote($asURL, "FlexiRemoteServer", "login");

    if ($bResult) {
      $mResult = $this->getResultReturned();
      //var_dump($mResult);
      if ($mResult->login_status) {
        $this->sToken = $mResult->token;
        return $this->sToken;
      }
    }
    return null;
  }
  
}