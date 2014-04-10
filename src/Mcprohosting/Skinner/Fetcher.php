<?php namespace Mcprohosting\Skinner;

class Fetcher
{

    /**
     * URL to get Minecraft skins from
     */
    const BASE_URL = 'http://s3.amazonaws.com/MinecraftSkins/%s.png';

    /**
     * Response code required to say "okay" on the image
     */
    const CODE_OKAY = 200;

    /**
     * Response code that results from a not found skin
     */
    const CODE_NOTFOUND = 403;

    /**
     * Downloads a skin via Curl
     * @param $username string
     * @param $timeout  int Seconds after which to give up downloading
     * @return bool|string
     */
    public function download($username, $timeout = 5)
    {
        $ch = curl_init(sprintf(self::BASE_URL, $username));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == self::CODE_NOTFOUND) {
            return file_get_contents(dirname(__FILE__) . '/steve.png');
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != self::CODE_OKAY) {
            curl_close($ch);
            throw new Exceptions\FailedToGetSkinException();
        }

        curl_close($ch);
        
        return $data;
    }
}