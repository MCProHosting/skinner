<?php namespace Mcprohosting\Skinner;

class Skin {

    /**
     * @var string The username of the skin
     */
    public $username;

    /**
     * @var Fetcher
     */
    protected $fetcher;

    /**
     * @var ImageProvider
     */
    protected $provider;

    /**
     * @var string The data for the string representation of this object
     */
    public $data;

    /**
     * Create a new Skin object with the given username
     * @param string        $username
     * @param Fetcher       $fetcher
     * @param ImageProvider $provider
     */
    public function __construct($username, Fetcher $fetcher, ImageProvider $provider)
    {
        $this->username = $username;
        $this->fetcher = $fetcher;
        $this->provider = $provider;
    }

    /**
     * Sets the current data to be the head of the player.
     * @return self
     */
    public function head()
    {
        $this->data = $this->skin()->data->crop(8, 8, 8, 8);

        return $this;
    }

    /**
     * Sets the current data to be the skin of the player.
     * @return self
     */
    public function skin()
    {
        if ($this->data) {
            return $this->data;
        }
        
        $contents = $this->fetcher->download($this->username);
        $this->data = $this->provider->make($contents);

        return $this;
    }

    /**
     * Get the encoded image object.
     * @param string $format 
     * @param int    $quality 
     * @return string
     */
    public function encode($format = 'png', $quality = 90)
    {
        return $this->data->encode($format, $quality);
    }

    /**
     * Proxy off unknown calls to the Intervention image, if it exists.
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->data, $method), $arguments);
    }

    /**
     * Get the string representation - the image data - of the object.
     * @return string
     */
    public function __toString()
    {
        return $this->encode();
    }
}
