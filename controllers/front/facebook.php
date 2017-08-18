<?php  
	
	require_once 'social.php';

	class AskSocialLoginFacebookModuleFrontController extends SocialCustomer
	{
		public function initVars()
		{
			require_once _PS_MODULE_DIR_.'/asksociallogin/vendor/autoload.php';
			$this->ask_facebook_login      = Configuration::get('ASK_FACEBOOK_LOGIN');
			$this->ask_facebook_app_id     = Configuration::get('ASK_FACEBOOK_APP_ID');
			$this->ask_facebook_app_secret = Configuration::get('ASK_FACEBOOK_APP_SECRET');
			$this->fb = new Facebook\Facebook([
				'app_id'     => $this->ask_facebook_app_id,
				'app_secret' => $this->ask_facebook_app_secret,
			]);
		}

		public function getAccessTokenFacebook()
		{
			$helper = $this->fb->getRedirectLoginHelper();
			if (isset($_GET['state'])) {
			    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
			}
			try {
			  return $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  return false;
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  return false;
			}
		}

		public function getFacebookData($accessToken)
		{
			if(!empty($accessToken)){
				$accessToken = $accessToken->getValue();
				try {
				    if($accessToken){
						$fbApp   = new Facebook\FacebookApp($this->ask_facebook_app_id, $this->ask_facebook_app_secret);
						$request = new Facebook\FacebookRequest($fbApp, $accessToken, "GET", "/me?fields=email,id,first_name,last_name");
						$profil   = $this->fb->getClient()->sendRequest($request);
						$customer = $profil->getGraphUser();
						return [
							'password'  => $customer->getField('id'),
							'email'     => $customer->getField('email'),
							'firstname' => $customer->getField('first_name'),
							'lastname'  => $customer->getField('last_name')
						];
					}
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  return false;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  return false;
				}
			}else{
				return false;
			}
		}

		public function initContent()
		{
			parent::initContent();
			if(!$this->context->cookie->logged)
			{
				$this->initVars();
				if($this->ask_facebook_login == 1){
					$dataCustomer  = $this->getFacebookData($this->getAccessTokenFacebook());
					$this->checkCustomer($dataCustomer);
				}
			}
			Tools::redirect('my-account.php');
            exit();	
		}
	}

?>

