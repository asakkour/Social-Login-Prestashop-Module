<?php  
	if (!defined('_PS_VERSION_'))
	{
	  exit;
	}
	class AskSocialLogin extends Module
	{
		
		function __construct()
		{
			$this->name = 'asksociallogin';
		    $this->tab = 'front_office_features';
		    $this->version = '0.0.1';
		    $this->author = 'askreative.ma Team';
		    $this->bootstrap = true;
		    $this->displayName = $this->l('Askreative module for login and sign in by social acoount');
		    $this->description = $this->l("With this module, your customers will be able to login and sign in with facebook or google plus account.");	
		    parent::__construct(); // oblegatoir

		}

		public function install()
		{
			if(!parent::install()){ return false; }
			if(!$this->registerHook('displayCustomerAccountFormTop')) return false;
			//if(!$this->registerHook('createAccountForm')) return false;
			Configuration::updateValue("ASK_FACEBOOK_LOGIN", 0);
			Configuration::updateValue('ASK_FACEBOOK_APP_ID', 0);
			Configuration::updateValue('ASK_FACEBOOK_APP_SECRET', 0);
			Configuration::updateValue("ASK_GOOGLE_LOGIN", 0);
			Configuration::updateValue('ASK_GOOGLE_APP_ID', 0);
			Configuration::updateValue('ASK_GOOGLE_APP_SECRET', 0);
			Configuration::updateValue('ASK_SOCIAL_DEFAULT_GROUPEID', 1);
			return true;
		}
		public function unistall()
		{
			if(!parent::uninstall()) { return false; }
			Configuration::deleteByName('ASK_FACEBOOK_LOGIN');
			Configuration::deleteByName('ASK_FACEBOOK_APP_ID');
			Configuration::deleteByName('ASK_FACEBOOK_APP_SECRET');
			Configuration::deleteByName('ASK_GOOGLE_LOGIN');
			Configuration::deleteByName('ASK_GOOGLE_APP_ID');
			Configuration::deleteByName('ASK_GOOGLE_APP_SECRET');
			Configuration::deleteByName('ASK_SOCIAL_DEFAULT_GROUPEID');
			return true;
		}

		// config module
		public function getContent()
		{
			$this->processConfigation();
			$this->assignConfiguration();
			return $this->display(__FILE__, 'getContent.tpl');
		}
		public function processConfigation()
		{
			if(Tools::isSubmit('submit_my_new_mod_social_setting'))
			{
				$ask_facebook_login       = Tools::getValue('ask_facebook_login');
				$ask_facebook_app_id      = Tools::getValue('ask_facebook_app_id');
				$ask_facebook_app_secret  = Tools::getValue('ask_facebook_app_secret');
				$ask_google_login         = Tools::getValue('ask_google_login');
				$ask_google_app_id        = Tools::getValue('ask_google_app_id');
				$ask_google_app_secret    = Tools::getValue('ask_google_app_secret');
				$ask_social_default_group = Tools::getValue('ask_social_default_group');
				Configuration::updateValue("ASK_FACEBOOK_LOGIN"     , $ask_facebook_login);
				Configuration::updateValue("ASK_FACEBOOK_APP_ID"    , $ask_facebook_app_id);
				Configuration::updateValue("ASK_FACEBOOK_APP_SECRET", $ask_facebook_app_secret);
				Configuration::updateValue("ASK_GOOGLE_LOGIN"       , $ask_google_login);
				Configuration::updateValue("ASK_GOOGLE_APP_ID"      , $ask_google_app_id);
				Configuration::updateValue("ASK_GOOGLE_APP_SECRET"  , $ask_google_app_secret);
				Configuration::updateValue('ASK_SOCIAL_DEFAULT_GROUPEID', $ask_social_default_group);
				$this->context->smarty->assign('confirmation', true);
			}
		}
		public function assignConfiguration()
		{
			$ask_facebook_login       = Configuration::get('ASK_FACEBOOK_LOGIN');
			$ask_facebook_app_id      = Configuration::get('ASK_FACEBOOK_APP_ID');
			$ask_facebook_app_secret  = Configuration::get('ASK_FACEBOOK_APP_SECRET');
			$ask_google_login         = Configuration::get('ASK_GOOGLE_LOGIN');
			$ask_google_app_id        = Configuration::get('ASK_GOOGLE_APP_ID');
			$ask_google_app_secret    = Configuration::get('ASK_GOOGLE_APP_SECRET');
			$ask_social_default_group = Configuration::get('ASK_SOCIAL_DEFAULT_GROUPEID');
			$this->context->smarty->assign('ask_facebook_login'     , $ask_facebook_login);
			$this->context->smarty->assign('ask_facebook_app_id'    , $ask_facebook_app_id);
			$this->context->smarty->assign('ask_facebook_app_secret', $ask_facebook_app_secret);
			$this->context->smarty->assign('ask_google_login'       , $ask_google_login);
			$this->context->smarty->assign('ask_google_app_id'      , $ask_google_app_id);
			$this->context->smarty->assign('ask_google_app_secret'  , $ask_google_app_secret);
			$this->context->smarty->assign('ask_social_default_group', $ask_social_default_group);
		}
		// en config module
		// $back = $this->context->smarty->getTemplateVars('back');
		// hook displayCustomerAccountFormTop
		public function hookdisplayCustomerAccountFormTop()
		{
			$ask_facebook_login      = Configuration::get('ASK_FACEBOOK_LOGIN');
			$ask_facebook_app_id     = Configuration::get('ASK_FACEBOOK_APP_ID');
			$ask_facebook_app_secret = Configuration::get('ASK_FACEBOOK_APP_SECRET');
			$ask_google_login        = Configuration::get('ASK_GOOGLE_LOGIN');
			$ask_google_app_id       = Configuration::get('ASK_GOOGLE_APP_ID');
			$ask_google_app_secret   = Configuration::get('ASK_GOOGLE_APP_SECRET');
			$this->setMedia();
			$this->getFacebookLink($ask_facebook_login, $ask_facebook_app_secret, $ask_facebook_app_id);
			$this->getGoogleLink($ask_google_login, $ask_google_app_secret, $ask_google_app_id);
			return $this->display(__FILE__, 'displayCustomerAccountFormTop.tpl');
		}

		public function getGoogleLink($ask_google_login, $ask_google_app_secret, $ask_google_app_id)
		{
			if($ask_google_login)
			{
				require_once ('libraries/Google/autoload.php');
				$client_id      = Configuration::get('ASK_GOOGLE_APP_ID'); 
				$client_secret  = Configuration::get('ASK_GOOGLE_APP_SECRET');
				if(!empty($client_id) && !empty($client_secret))
				{
					$controller_uri = "/index.php?fc=module&module=asksociallogin&controller=google";
					$redirect_uri   = _PS_BASE_URL_.__PS_BASE_URI__.$controller_uri;

					$client = new Google_Client();
					$client->setClientId($client_id);
					$client->setClientSecret($client_secret);
					$client->setRedirectUri($redirect_uri);
					$client->addScope("email");
					$client->addScope("profile");

					$service = new Google_Service_Oauth2($client);

					$this->context->smarty->assign('google_link', $client->createAuthUrl());
				}
			}
		}

		public function getFacebookLink($ask_facebook_login, $ask_facebook_app_secret, $ask_facebook_app_id)
		{
			if($ask_facebook_login  == 1)
			{
				if(isset($ask_facebook_app_id) && isset($ask_facebook_app_secret))
				{
					require_once 'vendor/autoload.php';
					try {
						$fb = new Facebook\Facebook([
							'app_id' => $ask_facebook_app_id,
							'app_secret' => $ask_facebook_app_secret ,
						]);
						$helper        = $fb->getRedirectLoginHelper();
						$permissions   = ['email']; // Optional permissions getLoginUrl getReRequestUrl
						$link_backFb   = $this->context->link->getModuleLink('asksociallogin','facebook',[]);
						$facebook_link = $helper->getLoginUrl($link_backFb , $permissions);
						$this->context->smarty->assign('facebook_link', $facebook_link);
						return true;
					} catch (Exception $e) {
						echo $this->l('Your facebook app id or facebook App secret is not valide');
						return false;
					}
				}
			}
		}
		// end hook displayCustomerAccountFormTop

		public function setMedia()
		{
			$this->context->controller->addCss($this->_path."views/css/asksociallogin.css");
		}
	}

?>