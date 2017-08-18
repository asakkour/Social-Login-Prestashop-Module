<?php  

	require_once 'social.php';

	class AskSocialLoginGoogleModuleFrontController extends SocialCustomer
	{
		public function initVars()
		{
			require_once _PS_MODULE_DIR_.'/asksociallogin/libraries/Google/autoload.php';
			$this->ask_google_login      = Configuration::get('ASK_GOOGLE_LOGIN');
			$this->ask_google_app_id     = Configuration::get('ASK_GOOGLE_APP_ID');
			$this->ask_google_app_secret = Configuration::get('ASK_GOOGLE_APP_SECRET');
		}

		public function getCustomerData($code)
		{
			$client_id      = Configuration::get('ASK_GOOGLE_APP_ID'); 
			$client_secret  = Configuration::get('ASK_GOOGLE_APP_SECRET');
			$controller_uri = "/index.php?fc=module&module=asksociallogin&controller=google";
			$redirect_uri   = _PS_BASE_URL_.__PS_BASE_URI__.$controller_uri;

			if(!empty($client_id) && !empty($client_secret))
			{
				$client = new Google_Client();
				$client->setClientId($client_id);
				$client->setClientSecret($client_secret);
				$client->setRedirectUri($redirect_uri);
				$client->addScope("email");
				$client->addScope("profile");
				$service = new Google_Service_Oauth2($client);

				$client->authenticate($code);
	  			$access_token  = $client->getAccessToken();
	  			if($access_token)
	  			{
	  				$client->setAccessToken($access_token);
	  			}

	  			$customer = $service->userinfo->get(); 
	  			return [
					'password'  => $customer->id,
					'email'     => $customer->email,
					'firstname' => $customer->givenName,
					'lastname'  => $customer->familyName,
				];
			}
		}	

		public function initContent()
		{
			parent::initContent();
			if(!empty(Tools::getValue('code'))){
				$this->initVars();
				if($this->ask_google_login == 1)
				{
					$dataCustomer = $this->getCustomerData(Tools::getValue('code'));
					$this->checkCustomer($dataCustomer);
				}
			}
		}

	}

?>

