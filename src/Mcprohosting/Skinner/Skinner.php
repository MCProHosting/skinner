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

		return new Skin($username, new Fetcher, new ImageProvider);
	}
}
