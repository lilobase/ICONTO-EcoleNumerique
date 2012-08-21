<?php

//load dependencies
enic::externals_load('upload');

//alias for DIRECTORY_SEPARATOR
define('DS', DIRECTORY_SEPARATOR);
/*
 * @TDODO : setType = jpg, gif, etc.
 */

class enicImage {

    protected $imageRootPath;
    protected $imageRootURI;
    private $height = 250;
    private $width = 250;
    private $resize = false;
    private $crop = false;
    private $fill = false;

    public function __construct() {

        /*
         * CONFIG :
         */
        $this->imageRootPath = COPIX_WWW_PATH . 'static' . DS . 'images' . DS;
        $this->imageRootURI = CopixUrl::get() . '/static/images/';

        //initialization : make root directory
        if (!file_exists($this->imageRootPath)) {
            mkdir($this->imageRootPath, 0770, true);
        }
    }

    //alias for upload. Warning : argument must be a path and not an uri.
    public function add($pathToFile, $keep_original_image = true) {

        $imageClass = new externalImageUpload($pathToFile);

        $imageClass->file_new_name_body = enic::get('helpers')->uniqueId();

        $imageClass->process($this->getImageDirectory($imageClass->file_new_name_body));

        if (!$imageClass->processed) {
            throw new Exception('Error occured in upload process');
        }

        if (!$keep_original_image)
            $imageClass->clean();

        return $imageClass->file_dst_name_body . '|' . $imageClass->file_dst_name_ext;
    }

    //argument is $_FILES['input name']
    public function upload($pathToFile) {

        return $this->add($pathToFile, false);
    }

    private function getImageDirectory($imageName) {

        $subDirectory = mb_substr($imageName, 0, 2);

        $path = $this->imageRootPath . $subDirectory . DS;

        if (!file_exists($path)) {
            mkdir($path, 0770, true);
        }

        return $path;
    }

    public function getOriginal($imageOriginalName) {

        $originalFileName = str_replace('|', '.', $imageOriginalName);
        
        $imageFilePath = $this->getImageDirectory($originalFileName);
        
        if (!file_exists($imageFilePath . $originalFileName)) {
            throw new Exception('Original image not found');
        }
        
        return $this->getURI($originalFileName);
       
    }
    
    public function delete($imageOriginalName){
        
        $originalFileName = explode('|', $imageOriginalName);
        
        $imageFilePath = $this->getImageDirectory($originalFileName[0]);
        
        $images = glob($imageFilePath.$originalFileName[0].'*');
        
        foreach($images as $image){
            unlink($image);
        }
        
        return true;
        
    }

    public function get($imageOriginalName, $size_x = 0, $size_y = 0, $options = array()) {

        $this->setSize($size_x, $size_y, $options);

        $imageName = $this->getImageFileName($imageOriginalName);

        $imageFilePath = $this->getImageDirectory($imageName);

        //image already exist
        if (file_exists($imageFilePath . $imageName)) {
            return $this->getURI($imageName);
        }

        //image not exist
        $originalFileName = str_replace('|', '.', $imageOriginalName);

        if (!file_exists($imageFilePath . $originalFileName)) {
            throw new Exception('Original image not found');
        }

        $imageClass = new externalImageUpload($imageFilePath . $originalFileName);

        $imageClass->file_new_name_body = $this->getImageFileName($imageOriginalName, false);

        //if resize
        if ($this->resize) {

            $imageClass->image_resize = true;
            $imageClass->image_ratio = true;

            if ($this->width == 'auto' && $this->height == 'auto') {
                
            } elseif ($this->width == 'auto') {
                $imageClass->image_y = $this->height;
                $imageClass->image_ratio_x = true;
            } elseif ($this->height == 'auto') {
                $imageClass->image_x = $this->width;
                $imageClass->image_ratio_y = true;
            } else {
                $imageClass->image_x = $this->width;
                $imageClass->image_y = $this->height;
            }

            if (!empty($this->crop))
                $imageClass->image_ratio_crop = $this->crop;

            if (!empty($this->fill))
                $imageClass->image_ratio_fill = $this->fill;
        }

        $imageClass->Process($imageFilePath);

        if (!$imageClass->processed) {
            throw new Exception('Error occured in upload process');
        }

        return $this->getURI($imageClass->file_dst_name);
    }

    private function setSize($size_x, $size_y, $options = 'auto') {

        //check arguments' integrity
        $check_args = function($arg) {
                    return (is_int($arg) || $arg == 'auto' );
                };

        if (!$check_args($size_x) || !$check_args($size_y))
            throw new Exception('Arguments are invalids');


        //set size
        $this->resize = true;
        $this->width = (!$size_x) ? $this->width : $size_x;
        $this->height = (!$size_y) ? $this->height : $size_y;

        //options is a string
        if (!is_array($options))
            $options = array($options);

        foreach ($options as $option => $value) {

            //options have options ;-)
            if (is_int($option)) {
                $option = $value;
                $value = true;
            }

            if (!in_array($option, array('crop', 'fill')))
                throw new Exception('value\'s options argument is invalid, correct value is "crop" or "fill" ');

            //set options
            $this->$option = $value;
        }
    }

    private function getURI($imageName) {

        $subPath = substr($imageName, 0, 2);

        $enicImageURI = $this->imageRootURI . $subPath . '/';

        return $enicImageURI . $imageName;
    }

    private function getImageFileName($image_name_body, $ext = true) {

        $image_file = explode('|', $image_name_body);

        $image_name = $image_file[0];

        if (!empty($this->height))
            $image_name .= $this->height;

        if (!empty($this->width))
            $image_name .= $this->width;

        if (!empty($this->crop))
            $image_name .= ($this->crop !== true) ? $this->crop : 'crop';

        if (!empty($this->fill))
            $image_name .= ($this->fill !== true) ? $this->fill : 'fill';

        if ($ext)
            $image_name .= '.' . $image_file[1];

        return $image_name;
    }

}


?>
