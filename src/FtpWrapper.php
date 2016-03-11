<?php
namespace Rkryvako\Package;

class FtpWrapper
{
    protected $conn;
    
    public function __construct(&$connection)
    {
        $this->conn = &$connection;
    }
    
    /*
    * call ftp function
    * $function - function name, $arguments - array of arguments
    */
    public function __call($function, array $arguments)
    {
        $function = 'ftp_' . $function;
        if (function_exists($function)) {
            array_unshift($arguments, $this->conn);
            return call_user_func_array($function, $arguments);
        }
        throw new Exception("{$function} is not a valid FTP function");
    }
    
    /*
    * connect to ftp
    * $host - host, $port - port, $timeout - ftp timeout
    * returns connection
    */
    public function connect($host, $port = 21, $timeout = 90)
    {
        return ftp_connect($host, $port, $timeout);
    }

    /*
    * secure connect to ftp
    * $host - host, $port - port, $timeout - ftp timeout
    * returns connection
    */
    public function ssl_connect($host, $port = 21, $timeout = 90)
    {
        return ftp_ssl_connect($host, $port, $timeout);
    }
}
