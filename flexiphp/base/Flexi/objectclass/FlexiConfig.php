<?php

class FlexiConfig
{
  
  public static $sLoginHandlerClass = "";
  //base dir of other framework
  public static $sRootDir = "";
  //base dir of flexiphp
	public static $sBaseDir = "";
  
  public static $sBaseFullURL = "";
  //base url with an empty workspace
	public static $sBaseURL = "";
  //base url of the root dir
	public static $sBaseURLDir = "";
	
  public static $sFlexiBaseURL = "";
	public static $sAssetsURL = "";

  public static $sAssetsDir = "";

  public static $sRepositoryDir = "";

	public static $aQueryString = array();
	
	public static $aModuleURL = array();
  public static $sTinyMCEExternalLink = "";
  public static $sTinyMCEExternalMedia = "";
  public static $sTinyMCEExternalImage = "";

  public static $sTinyMCEContentCSS = "";
	
	public static $iLogLevel = 3;
	public static $sLogFile = "";
	public static $sFramework = "";
	public static $sPageTitle = "";
	public static $iStartTime = -1;
	public static $aPost = array();
	public static $aGet = array();
	public static $aCookie = array();
	public static $aSession = array();
	public static $aFiles = array();

  public static $bIsAdminPath = false;
	public static $sDefaultLanguage = "en";
  public static $sLanguagePath = "flexiphp/language";
	
	public static $sModulePath = ".";
	public static $sTemplatePath = "";
  
	public static $sDBHost 		= "";
	public static $iDBPort 		= "";
	public static $sDBType		= "";
	public static $sDBUser 		= "";
	public static $sDBPass 		= "";
	public static $sDBName 		= "";
	public static $sDBPrefix 	= "";
	
	public static $sTemplateDir = "default";
	
	public static $sTimeZone	= "";
	public static $sInputDateFormat = "";
  public static $sDisplayDateFormat = "dd-mm-yy";
  public static $sDisplayDateTimeFormat = "dd-mm-yy hh:ii:ss";
  
  public static $sInputDateTimeFormat = "dd-mm-yy hh:ii:ss";
	
	private static $oLoginHandler = null;
	//to store messages
	public static $aMessage 	= array();
	
	public static $aFormMessage = array();
	
	public static $bRenderedNotice = false;
  public static $bStandAlone = false;

  //set via mailer.*
  public static $sMailer = "mail"; // mail / sendmail / smtp
  public static $sMailerUserId = "";
  public static $sMailerPassword = "";
  public static $sMailerHost = "localhost";
  public static $sMailerPort = 25;

  public static $sSupportEmail = "";

  public static $iAdminId = 1;
  public static $sAdminUserId = ""; //admin.userid
  public static $sAdminPassword = ""; //admin.password

  public static $sLoginURL = "";

  public static $sAdminTemplate = "default";

  public static $iMaxMailPerHour = 130;
  public static $bRequireEmailVerification = true;

  public static $sEncryptionKey = "ABFGEBD2231DEDA";
	
	public static $bEnableDoctrine = false;
	
	public static $aHelperClass = array();

	public static function configure($aConfig)
	{
    //if ($aConfig["framework"] == "modx2") var_dump($aConfig["get"]);
		self::$iStartTime     = $aConfig["starttime"];
		self::$sPageTitle     = $aConfig["title"];
		self::$sBaseDir       = empty($aConfig["basedir"]) ? "": str_replace("\\", "/", $aConfig["basedir"]);
    self::$sRootDir       = empty($aConfig["rootdir"]) ? "": str_replace("\\", "/", $aConfig["rootdir"]);
    self::$sFlexiBaseURL  = @$aConfig["rooturl"];
    self::$sAssetsURL     = @$aConfig["assetsurl"];
    self::$sAssetsDir     = empty($aConfig["assetsdir"]) ? "": str_replace("\\", "/", $aConfig["assetsdir"]);
    self::$sRepositoryDir = empty($aConfig["repositorydir"]) ? "": str_replace("\\", "/", $aConfig["repositorydir"]);
    
		//if (empty($aConfig["assetsdir"])) throw new Exception("empty assets dir!");
		self::$sBaseURL			= $aConfig["baseurl"];
		self::$aModuleURL		= $aConfig["moduleurl"];
		
		self::$iLogLevel 		= $aConfig["loglevel"];
		self::$sFramework 	= $aConfig["framework"];
		self::$sTemplateDir = empty($aConfig["template"]) ? "": str_replace("\\", "/", $aConfig["template"]);
		
		self::$aPost 				= $aConfig["post"];
		self::$aGet 				= $aConfig["get"];
		self::$aCookie 			= $aConfig["cookie"];
		self::$aSession 		= $aConfig["session"];
		self::$aFiles 			= $aConfig["files"];
		
		self::$sDefaultLanguage = $aConfig["defaultlanguage"];
		
		self::$sDBHost 			= $aConfig["dbhost"];
		self::$iDBPort 			= $aConfig["dbport"];
		self::$sDBType 			= $aConfig["dbtype"];
		self::$sDBUser 			= $aConfig["dbuser"];
		self::$sDBPass 			= $aConfig["dbpass"];
		self::$sDBName 			= $aConfig["dbname"];
		self::$sDBPrefix 		= $aConfig["dbprefix"];
		
		self::$bEnableDoctrine = $aConfig["doctrine"];

    self::$sTinyMCEExternalLink = empty($aConfg["tinymce.externallink"]) ?
      "": $aConfg["tinymce.externallink"];

    self::$sTinyMCEExternalImage = empty($aConfg["tinymce.externalimage"]) ?
      "": $aConfg["tinymce.externalimage"];
    
    self::$sTinyMCEExternalMedia = empty($aConfg["tinymce.externalmedia"]) ?
      "": $aConfg["tinymce.externalmedia"];

    self::$sTinyMCEContentCSS = empty($aConfig["tinymce.contentcss"]) ?
      "": $aConfg["tinymce.contentcss"];

    self::$sMailer = isset($aConfig["mailer"]) ? $aConfig["mailer"] : self::$sMailer;
    self::$sMailerUserId = isset($aConfig["mailer.userid"]) ? $aConfig["mailer.userid"] : self::$sMailerUserId;
    self::$sMailerPassword = isset($aConfig["mailer.password"]) ? $aConfig["mailer.password"] : self::$sMailerPassword;
    self::$sMailerHost = isset($aConfig["mailer.host"]) ? $aConfig["mailer.host"] : self::$sMailerHost;
    self::$sMailerPort = isset($aConfig["mailer.port"]) ? $aConfig["mailer.port"] : self::$sMailerPort;

    self::$sSupportEmail = isset($aConfig["support.email"]) ? $aConfig["support.email"] : self::$sSupportEmail;
		self::$sTimeZone		=	$aConfig["timezone"];
		self::$sInputDateFormat = isset($aConfig["input_dateformat"]) ? $aConfig["input_dateformat"] : self::$sInputDateFormat;
    self::$sDisplayDateFormat = isset($aConfig["display_dateformat"]) ? $aConfig["display_dateformat"] : self::$sDisplayDateFormat;
    self::$sInputDateTimeFormat = isset($aConfig["input_datetimeformat"]) ? $aConfig["input_datetimeformat"] : self::$sInputDateTimeFormat;

    self::$iAdminId = isset($aConfig["admin.id"]) ? $aConfig["admin.id"] : self::$iAdminId;
    self::$sAdminUserId = isset($aConfig["admin.userid"]) ? $aConfig["admin.userid"] : self::$sAdminUserId;
    self::$sAdminPassword = isset($aConfig["admin.password"]) ? $aConfig["admin.password"] : self::$sAdminPassword;

    self::$sLoginURL = isset($aConfig["url.login"]) ? $aConfig["url.login"] : self::$sLoginURL;
    
    self::$sAdminTemplate = isset($aConfig["admin.template"]) ? $aConfig["admin.template"] : self::$sAdminTemplate;

    self::$bRequireEmailVerification = isset($aConfig["user.emailverify"]) ? $aConfig["user.emailverify"] : self::$bRequireEmailVerification;

    self::$sEncryptionKey = isset($aConfig["encryption.key"]) ? $aConfig["encryption.key"] : self::$sEncryptionKey;

    self::$bIsAdminPath = isset($aConfig["bIsAdminPath"]) ? $aConfig["bIsAdminPath"] : self::$bIsAdminPath;
    
    self::$sLoginHandlerClass = isset($aConfig["loginhandler"]) ? $aConfig["loginhandler"] : self::$sLoginHandlerClass;
    
		if (!empty(self::$sTimeZone))
		{
			date_default_timezone_set(self::$sTimeZone);
		}
		self::$sModulePath = $aConfig["modulepath"];
    //echo "Setting path : " . $aConfig["templatepath"];
    self::$sTemplatePath = $aConfig["templatepath"];
    
    /*
    echo "URL: " . self::$sBaseURL;
		$aURL = @parse_url(self::$sBaseURL);
		$sURL = $aURL["scheme"] . "://" . $aURL["host"];
		$sURL .= isset($aURL["port"]) ? ":" . $aURL["port"] : "";
    	//$sURL .="/";
		if (isset($aURL["user"]))
		{
			$sURL .= $aURL["user"] . (isset($aURL["pass"]) ? ":" . $aURL["pass"] : "");
		}
    //self::$sBaseURLDir = $sURL;
		$sURL .= empty($aURL["path"]) ? "/" : $aURL["path"];
    */
    self::$sBaseURLDir = substr(self::$sBaseURL,-1)=="/" ? self::$sBaseURL : dirname(self::$sBaseURL)."/";
    self::$sBaseURLDir = str_replace("//", "/", self::$sBaseURLDir);
		//self::$sBaseURL = $sURL;
		
    if (self::$sFramework == "modx") {
      global $modx;
      self::$sBaseURLDir = $modx->getConfig("site_url");
    } else if(self::$sFramework == "modx2") {
      global $modx;
      $aConfig = $modx->getConfig();
      //self::$sBaseURLDir = $aConfig["site_url"];
    } else if(self::$sFramework == "iscript") {
      self::$sBaseURLDir = SITE_URL;
    }
    
    self::$sFlexiBaseURL = empty(self::$sFlexiBaseURL) ?
      self::$sBaseURLDir . "flexiphp/" : self::$sFlexiBaseURL;

    self::$sAssetsURL = empty(self::$sAssetsURL) ?
      self::$sBaseURLDir . "assets/" : self::$sAssetsURL;
    
    $sBaseRootURL = dirname(self::$sFlexiBaseURL);
    self::$sBaseFullURL = (@$_SERVER['HTTPS'] == 'on' ? 'https' : 'http'). '://'. 
      (isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "localhost") . ($sBaseRootURL=="/" ? $sBaseRootURL: $sBaseRootURL . "/");
    
    self::$sAssetsDir = empty(self::$sAssetsDir) ?
      self::$sRootDir . "/assets/": self::$sAssetsDir;

    self::$sRepositoryDir = empty(self::$sRepositoryDir) ?
      self::$sAssetsDir . "models/": self::$sRepositoryDir;

    self::$aQueryString = $_GET;
    
		//fragment?
		
		self::$sLogFile			= $aConfig["logfile"];
		//setting delimiter to true for queries
		if (self::$bEnableDoctrine) {
			Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
			Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
			Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
			Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
		}
	}
  
  public static function getBaseURLDir() {
    static $sBaseDir = null;
    if ($sBaseDir != null) { return $sBasePath; }
    
    $sBaseDir = self::$sBaseURL;
    $iPos = strrpos($sBaseDir, "/");
    if ($iPos !== false) {
      $sBaseDir = substr($sBaseDir, 0, $iPos);
    }

    return $sBaseDir;
  }

  public static function getRequestModuleVarName() {
    return FlexiConfig::$aModuleURL["module"];
  }

  public static function getRequestMethodVarName() {
    return FlexiConfig::$aModuleURL["method"];
  }

	public static function isMySQL()
	{
		return self::$sDBType == "mysql" || self::$sDBType == "mysqli";
	}
	
	/**
	 * finalize setting for particular framework
	 */
	public static function finalize()
	{
		//initialise db
		FlexiModelUtil::getInstance()->setDBSetting(self::$sDBType, self::$sDBHost, self::$iDBPort, self::$sDBUser, self::$sDBPass, self::$sDBName);
		
		if (self::$bEnableDoctrine && !empty(self::$sDBType)) { FlexiModelUtil::getDBInstance(); }
    
    if (is_null(self::$oLoginHandler)) {
      if (! empty(self::$sLoginHandlerClass)) {
        self::setLoginHandler(new self::$sLoginHandlerClass());
      } else {
        switch(self::$sFramework)
        {
          case "drupal":
            //TODO
            break;
          case "modx":
            self::setLoginHandler(new FlexiModXLoginHandler());
            break;
          case "modx2":
            self::setLoginHandler(new FlexiModX2LoginHandler());
            break;
          case "iscript":
            self::setLoginHandler(new FlexiIScriptLoginHandler());
            break;
          case "joomla":
            //TODO
            break;
          case "":
            self::setLoginHandler(new FlexiLoginHandler());
            break;
          default:
            $sClass = "Flexi" . ucfirst(self::$sFramework) . "LoginHandler";
            self::setLoginHandler(new $sClass());
        }//switch
      }
    }//end if
		
	}
	
	/**
	 * Get Login handler
	 * @return FlexiLoginBaseHandler
	 */
	public static function getLoginHandler()
	{
		return self::$oLoginHandler;
	}
	
	/**
	 * Set login handler
	 * @param FlexiLoginBaseHandler
	 */
	public static function setLoginHandler(FlexiLoginBaseHandler $oLogin)
	{
		self::$oLoginHandler = $oLogin;
	}
	
	/**
	 * autoload function for flexiphp
	 * @param mixed
	 */
	public static function autoLoad($sClass)
	{
		flexiInclude($sClass);
	}
}
