<?php
namespace Rkryvako\Package;

class Connection
{
    protected $conn = null;
    protected $host;
    protected $port;
    protected $is_ssl = false;
    protected $ftp;

    public function __construct($host,$port=21,$isSsl = false)
	{
	    if (!extension_loaded('ftp')) {
            throw new Exception('FTP extension is not loaded!');
        }
	    
	    $this->host = $host;
	    $this->port = $port;
	    $this->is_ssl = $isSsl;
	    
	    $this->setWrapper(new FtpWrapper($this->conn));
	}
	
	/*
	* close ftp connection when destroy
	*/
	public function __destruct()
    {
        if ($this->conn) {
            $this->ftp->close();
        }
    }
	
	/*
	* connect
	*/
	public function connect($timeout = 30)
    {
        if ($this->is_ssl) {
            $this->conn = @$this->ftp->ssl_connect($this->host, $this->port, $timeout);
        } else {
            $this->conn = @$this->ftp->connect($this->host, $this->port, $timeout);
        }
        if (!$this->conn) {
            throw new Exception('Unable to connect');
        }
        //var_dump($this->conn);
        $this->setWrapper(new FtpWrapper($this->conn));
        
        return $this;
    }
	
	protected function setWrapper(FtpWrapper $wrapper)
    {
        $this->ftp = $wrapper;
        return $this;
    }
    
    public function login($username = 'anonymous', $password = '')
    {
        $result = $this->ftp->login($username, $password);
        if ($result === false) {
            throw new Exception('Login incorrect');
        }
        return $this;
    }
    
    /**
    * Returns a list of files in the given directory
    *
    * @param string $directory The directory, by default is "." the current directory
    * @param bool $recursive
    * @param callable $filter A callable to filter the result, by default is asort() PHP function.
    * The result is passed in array argument,
    * must take the argument by reference !
    * The callable should proceed with the reference array
    * because is the behavior of several PHP sorting
    * functions (by reference ensure directly the compatibility
    * with all PHP sorting functions).
    *
    * @return array
    * @throws Exception If unable to list the directory
    */
    public function nlist($directory = '.', $recursive = false, $filter = 'sort')
    {
        if (!$this->isDir($directory)) {
            throw new Exception('"'.$directory.'" is not a directory');
        }
        //return false;
        //return false;
        $this->ftp->pasv(true);
        $files = $this->ftp->nlist($directory);
        if ($files === false) {
            throw new Exception('Unable to list directory');
        }
        $result = array();
        $dir_len = strlen($directory);
        // if it's the current
        if (false !== ($kdot = array_search('.', $files))) {
            unset($files[$kdot]);
        }
        // if it's the parent
        if(false !== ($kdot = array_search('..', $files))) {
            unset($files[$kdot]);
        }
        if (!$recursive) {
            foreach ($files as $file) {
                $result[] = $directory.'/'.$file;
            }
            // working with the reference (behavior of several PHP sorting functions)
            $filter($result);
            return $result;
        }
        // utils for recursion
        $flatten = function (array $arr) use (&$flatten) {
            $flat = array();
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $flat = array_merge($flat, $flatten($v));
                } else {
                    $flat[] = $v;
                }
            }
            return $flat;
        };
        foreach ($files as $file) {
            $file = $directory.'/'.$file;
            // if contains the root path (behavior of the recursivity)
            if (0 === strpos($file, $directory, $dir_len)) {
                $file = substr($file, $dir_len);
            }
            if ($this->isDir($file)) {
                $result[] = $file;
                $items = $flatten($this->nlist($file, true, $filter));
                foreach ($items as $item) {
                    $result[] = $item;
                }
            } else {
                $result[] = $file;
            }
        }
        $result = array_unique($result);
        $filter($result);

        return $result;
    }
    
    /*
    * check if $directory is dir
    */
    public function isDir($directory)
    {
        $pwd = $this->ftp->pwd();
        if ($pwd === false) {
            throw new Exception('Unable to resolve the current directory');
        }
        if (@$this->ftp->chdir($directory)) {
            $this->ftp->chdir($pwd);
            return true;
        }
        $this->ftp->chdir($pwd);
        return false;
    }
    
    /*
    * call function
    */
    public function __call($method, array $arguments)
    {
        return $this->ftp->__call($method, $arguments);
    }
}
