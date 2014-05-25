<?php namespace Mcprohosting\Skinner;

class Skinner {

	/**
	 * Factory to generate a new "Skin" object for the specified user
	 * @param  string $username 
	 * @return Skin
	 * @throws InvalidUsernameException
	 */
	public static function user($username)
	{
		if (!preg_match('/^[A-z0-9_-]+$/', $username)) {
			throw new Exceptions\InvalidUsernameException();
		}
		
		$udata = self::getUserData($username);

		return new Skin($udata ? $udata['name'] : $username, new Fetcher, new ImageProvider);
	}

	/**
	 * Dynamic proxy for the user(), so that Skinner may be used in DI.
	 * @param  string $username 
	 * @return Skin
	 * @throws InvalidUsernameException
	 */
	public function make($username)
	{
		return self::user($username);
	}

	/** 
	* Function to pull the properly capitalised username and the UUID, will return {"id":"null","name":"steve"} if the username is invalid.
	* @param string $username
	* @return Json Object with UUID and properly capitalised Username
	*/
	public static function getUserData($username) {

			$data_string = '["'.$username.'"]';                                                                                   
			 
			$ch = curl_init('https://api.mojang.com/profiles/minecraft');                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);                                                                                                                   
			$result = curl_exec($ch);
			if ($result == "[]") {
				$userObject = array("id" => "null", "name" => "steve");
			} else {
				$jsonObject = json_decode($result, true);
				
				if (json_last_error() !== JSON_ERROR_NONE) {
					return array("id" => "null", "name" => "steve");
				}

				$userObject = $jsonObject[0];
			}

			return $userObject;
	}
}
