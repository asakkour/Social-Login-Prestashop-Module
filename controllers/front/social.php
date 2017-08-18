<?php  
	
	class SocialCustomer extends ModuleFrontController
	{
		public function checkCustomer($dataCustomer)
		{			
			$sql = "SELECT * FROM "._DB_PREFIX_."customer WHERE email = '".$dataCustomer['email']."'";
			$customer = Db::getInstance()->executeS($sql);
			if(!$customer){
				$this->addCustomer($dataCustomer);
			}
			$this->authCustomer($dataCustomer['email']);
		}

		public function authCustomer($email)
		{
			$customer = new Customer();
	        $authentication = $customer->getByemail(trim($email));
	        /* Handle brute force attacks */
	        sleep(1);
            $this->context->cookie->id_customer = intval($customer->id);
            $this->context->cookie->customer_lastname = $customer->lastname;
            $this->context->cookie->customer_firstname = $customer->firstname;
            $this->context->cookie->logged = 1;
            $this->context->cookie->passwd = $customer->passwd;
            $this->context->cookie->email = $customer->email;
            Tools::redirect('order&step=1');
            exit();
		}

		public function addCustomer($dataCustomer)
		{
			$now = Date('Y-m-d H:i:s');
			if(!empty($dataCustomer['firstname']) && !empty($dataCustomer['lastname']) && 
				!empty($dataCustomer['email']) && !empty($dataCustomer['password']))
			{
				$customer = [
					"lastname"      => pSQL($dataCustomer['lastname']),
					"firstname"     => pSQL($dataCustomer['firstname']),
					"email"         => pSQL($dataCustomer['email']),
					"passwd"        => pSQL(md5(_COOKIE_KEY_."".$dataCustomer['password'])),
					"date_add"      => pSQL($now),
					"date_upd"      => pSQL($now),
					"secure_key"    => pSQL(md5(uniqid(rand(), true))),
					"optin"         => "1",
					"active"        => "1",
					"newsletter"    => "1",
					"id_shop_group" => "1",
					"id_risk"       => '0',
				];
				Db::getInstance()->insert('customer', $customer);
				$id_customer = (int)Db::getInstance()->Insert_ID();
				$id_group    = (int)Configuration::get('ASK_SOCIAL_DEFAULT_GROUPEID');
				if($id_group)
				{
					$group = [
						"id_customer" => (int)$id_customer,
						"id_group"    => (int)$id_group
					];
					Db::getInstance()->insert('customer_group', $group);
				}
			}
		}
	}

?>