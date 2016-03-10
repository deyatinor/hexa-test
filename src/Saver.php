<?php
namespace Rkryvako\Package;

class Saver
{
    protected $conn;
    
    public function __construct($host,$user = "", $pass = "")
    {
        $this->conn = new Connection($host);
        $this->conn->connect();
        if ($user != "" && $pass != ""){
            $this->conn->login($user, $pass);
        } else {
            $this->conn->login();
        }
    }
    
    public function searchPictures($directory)
    {
        $regex = array();
        $regex[] = '/(.*)\.png$/i';
        $regex[] = '/(.*)\.jpeg$/i';
        $regex[] = '/(.*)\.jpg$/i';
        $regex[] = '/(.*)\.gif$/i';
        
        $files = $this->conn->nlist($directory,/*true*/false);
        $pictures = array();
        //var_dump($files);
        foreach($files as $file){
            $prepared_file = strtolower($file);
            foreach ($regex as $pattern){
                if (preg_match($pattern, $prepared_file) == 1){
                    $pictures[] = $file;
                }
            }
        }
        
        return $pictures;
    }
    
    public function savePictures($sourceDir, $destinationDir)
    {
        if (!is_dir($destinationDir)){
            throw new Exception('Destination "'.$destinationDir.'" is not a directory');
        }
        if (!is_writable($destinationDir)){
            throw new Exception('Destination "'.$destinationDir.'" is not writable');
        }
        
        $pictures = $this->searchPictures($sourceDir);
        if (!$pictures){
            throw new Exception('No pictures found');
        }
        foreach ($pictures as $picture){
            $name = basename($picture);
            $fp = fopen($destinationDir.$name,"wb");
            //var_dump($this->conn);
            if (!($success = $this->conn->fget($fp, $picture, FTP_BINARY))){
                throw new Exception('File '.$picture.' could not be loaded');
            }
            fclose($fp);
            
        }
        return true;
    }
}
