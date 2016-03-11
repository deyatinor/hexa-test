<?php
namespace Rkryvako\Package;

class Saver
{
    protected $conn;
    
    /*
    * connect to host $host, using credentials $user and $pass
    */
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
    
    /*
    * search for pictures in directory $directory
    */
    public function searchPictures($directory)
    {
        $regex = array();
        $regex[] = '/(.*)\.png$/i';
        $regex[] = '/(.*)\.jpeg$/i';
        $regex[] = '/(.*)\.jpg$/i';
        $regex[] = '/(.*)\.gif$/i';
        
        //non-recursive search
        $files = $this->conn->nlist($directory,/*true*/false);
        $pictures = array();
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
    
    /*
    * save pictures from $sourceDir on remote server to $destinationDir
    */
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

