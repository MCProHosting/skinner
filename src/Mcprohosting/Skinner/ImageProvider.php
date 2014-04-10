<?php namespace Mcprohosting\Skinner;

use Intervention\Image;

class ImageProvider
{
    /**
     * Proxy off calls to create new Intervention images. Due to the way
     * Intervention is set up, this is necessary to mock the Image class.
     * @param  mixed $name
     * @return Image
     */
    public function make($data)
    {
        return new Image($data);
    }
}