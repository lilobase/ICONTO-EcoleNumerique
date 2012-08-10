<?php

//load dependencies
enic::externals_load('upload');

/*
 * @TDODO : setType = jpg, gif, etc.
 */

class enicImage {

    private $uploadFile;
    private $imageClass;
    public $imageId;
    public $imageExt;
    public $imagePath;
    public $dirPath;
    public $rootPath;
    public $filePath;
    public $height;
    public $imageURI;
    public $width;
    public $resize;
    public $convert;
    public $crop = false;
    public $fill = false;
    public $allowedImageTypes = array('png', 'jpeg', 'gif', 'bmp');

    public function __construct() {
        
        $this->rootPath = COPIX_WWW_PATH.'static/enic/images/';
        
        if (!file_exists($this->rootPath)){
            mkdir($this->rootPath, 0770, true);
        }
    }

    public function upload($uploadFile) {
        
        $this->uploadFile = new externalImageUpload($uploadFile);

        $this->fire();

        return $this->imageId;
    }
    
    public function getURI($idImage, $image_x, $image_y, $options = 'auto'){
        
        $imageArray = explode('|', $idImage);
        
        //set Type
        if(isset($imageArray[1])){
            
            throw new Exception('missing type argument in image ID');
            
        }
        
        $type = strtolower($imageArray[1]);
        
        if(!in_array($type, $this->allowedImageTypes)){
            
            throw new Exception('Invalid Type');
            
        }
            
        $this->imageExt = $type;
        $this->convert = true;
        
        $this->setId($imageArray[0]);
        $this->setSize($image_x, $image_y, $options);
        
        $this->makeImagePath();
        
        if(!file_exists($this->imagePath)){
            $this->fire();
            $this->uploadFile = $this->imagePath;
        }
        
        $this->makeURI();
        
        return $this->imageURI;
    }

    public function add($imageToAdd) {
        $this->uploadFile = $imageToAdd;

        $this->fire();

        return $this->imageId;
    }

    private function setSize($size_x, $size_y, $options = 'auto') {

        //check arguments' integrity
        $check_args = function($arg) {
                    return (is_int($arg) || $args != 'auto' );
                };

        if (!$check_args($size_x) || !$check_args($size_y))
            throw new Exception('Arguments are invalids');

        if ($size_x == 'auto' && $size_y == 'auto')
            throw new Exception('Both arguments cannot be "auto"');

        //set size
        $this->resize = true;
        $this->width = $size_x;
        $this->height = $size_y;

        //options is a string
        if (!is_array($options))
            $options = array($options);

        foreach ($options as $option => $value) {

            //options have options ;-)
            if (is_int($option)) {
                $option = $value;
                $value = true;
            }
            
            if(!in_array($option, array('crop', 'fill')))
                throw new Exception('value\'s options argument is invalid, correct value is "crop" or "fill" ');
            
            //set options
            $this->$option = $value;
        }
    }

    public function fire() {

        $imageClass = $this->getImageClass($this->uploadFile);

        if(empty($this->imageId))
            $this->makeImageName();
        
        if(empty($this->imageExt))
            $this->imageExt = $imageClass->file_src_ext;
        
        $this->makeImagePath();
        
        
        $imageClass->file_new_name_body = $this->getId();
        
        //if resize
        if($this->resize){
            if (!file_exists($this->imagePath)){
                $imageClass->resize = true;
                $imageClass->image_ratio = true;
                
                if($this->width == 'auto'){
                    $imageClass->image_y = $this->height;
                    $imageClass->image_ratio_x = true;
                }elseif($this->height == 'auto'){
                    $imageClass->image_x = $this->width;
                    $imageClass->image_ratio_y = true;    
                }else{
                    $imageClass->image_x = $this->width;
                    $imageClass->image_y = $this->height;
                }
                
                if(!empty($this->crop))
                    $imageClass->image_ratio_crop = $this->crop;
                
                if(!empty($this->fill))
                    $imageClass->image_ratio_fill = $this->fill;
            }
        }
        
        if($this->convert){
            
            if (!file_exists($this->imagePath)){
                $imageClass->image_convert = $this->imageExt;
            }
            
        }
        

        $imageClass->Process($this->dirPath);


        if (!$imageClass->processed) {
            throw new Exception('Error occured in upload process');
        }

        $imageClass->clean();

        
        return $this->getId();
        
    }

    private function makeImageName() {
        
        
        $image_name = enic::get('helpers')->uniqueId();
                
        if(!empty($this->height))
            $image_name .= $this->height;
                
        if(!empty($this->width))
            $image_name .= $this->width;
        
        if(!empty($this->crop))
            $image_name .= ($this->crop === true) ? $this->crop : 'crop';
        
        if(!empty($this->fill))
            $image_name .= ($this->fill === true) ? $this->fill : 'fill';
        
        $this->imageId = $image_name;
        
        return $image_name;
    }

//return singleton
    public function getImageClass($file) {
        if (empty($this->imageClass))
            $this->imageClass = new externalImageUpload ($file);

        return $this->imageClass;
    }

    public function getId() {
        
        return $this->imageId;
    }
    
    public function getImageExt() {
        
        return $this->imageExt;
    }

    public function setId($id) {
        $this->imageId = $id;
    }

    private function makeBasePath() {
        $imageName = $this->getId();
        
        $path = $this->rootPath;
        
        $subPath = substr($imageName, 0, 2);
        
        //check : remove for production
        var_dump($subPath);
        
        $path .= $subPath.DIRECTORY_SEPARATOR;
        
        if(file_exists($path)){
            mkdir($path, 0770, true);
        }
            
        return $path;
    }
    
    private function makeImagePath(){
        
        $this->imagePath = $this->makeBasePath().$this->getId().$this->getImageExt();
        
    }
    
    private function makeImageURI(){
        
        $subPath = substr($this->getId(), 0, 2);
        
        $enicImageURI = 'static/enic/image/'.$subPath.'/';
        
        $this->imageURI = CopixUrl::get().$enicImageURI.$this->getId().$this->getImageExt();
                
        return $this->imageURI;
    }

}

?>
