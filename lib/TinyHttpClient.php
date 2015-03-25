<?php 
/*
File: TinyHttpClient.php
Date: 11/1/09 2236
Version 1.2
Author: Shaun Henry
Copyright Henry Ranch LLC 2009.  All rights reserved.  http://www.henryranch.net


Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* You must provide a link back to www.henryranch.net on the site on which this software is used.
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer 
in the documentation and/or other materials provided with the distribution.
* Neither the name of the HenryRanch LCC nor the names of its contributors nor authors may be used to endorse or promote products derived 
from this software without specific prior written permission.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
THE AUTHORS, OWNERS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES 
OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.  
*/

class TinyHttpClient 
{
    var $debug = false;

   /*
        Create a GET request header for the given host and filename.  If authorization is required, then it must be the standard HTTP 1.0 Basic Authentication compliant string.
        @param $host  - the host name of the remote server
        @param $filename - the filename of the resource that resides ont he remote server.  Must start with a '/'.  Append key=value pairs in GET format to the end of the resource URL
        @param $authorization - the HTTP 1.0 compliant Basic Authentication digest string
        @returns The GET request header
        */
    function generateGetRequest($host, $filename, $authorization)
    {
        $request = "GET $filename HTTP/1.0\r\n" .
        "Host: $host\r\n" .
        $authorization . 
        "User-Agent: TinyHttpClient/1.1\r\n" .
        "Connection: close\r\n" .
        "\r\n";
        return $request;
    }

   /*
        Create a POST request header for the given host and filename.  If authorization is required, then it must be the standard HTTP 1.0 Basic Authentication compliant string.
        @param $host  - the host name of the remote server
        @param $filename - the filename of the resource that resides ont he remote server.  Must start with a '/'
        @param $authorization - the HTTP 1.0 compliant Basic Authentication digest string
        @param $from - POST requests require a 'from' email address that is on your host server domain
        @param $data - the POST data (key value pairs)
        @returns The POST request header
        */
    function generatePostRequest($host, $filename, $authorization, $from, $data)
    {
        $request = "POST $filename HTTP/1.0\r\n" .
        "Host: $host\r\n" .
        $authorization .
        "Connection: close\r\n" .
        "From: $from\r\n" .
        "User-Agent: TinyHttpClient/1.1\r\n" .
        "Content-Type: application/x-www-form-urlencoded\r\n" .
        "Content-Length: " . strlen($data) . "\r\n" .
        "\r\n" .
        $data . "\r\n";
        return $request;
    }

   /*
        Create a POST request header for the given host and filename.  If authorization is required, then it must be the standard HTTP 1.0 Basic Authentication compliant string.
        @param $host  - the host name of the remote server
        @param $remoteFilename - the filename of the resource that resides ont he remote server.  Must start with a '/'
        @param $usernameColonPassword - the username and password if using Basic Authentication to access the URL (i.e. bobUsername:bobPassword).  
                                                                      If no Basic Authentication is required, simply pass an empty string.
        @param $receiveBufferSize - the size (in bytes) of the receive buffer.  This can affect performance.  A good starting place is 2048.  You should determine this value based on your server environment.
        @param $mode - either 'get' or 'post'
        @param $fromEmail - only required if using $mode=post
        @param $postData - the key value pairs of data.  only required if using $mode=post
        @param $localFilename - the filename, local to your server to store the downloaded URL contents in.
                                                    Set this value to "" (empty string) if you want the URL contents returned from this function as a string.
        @returns URL contents or an error msg.
        */
    function getRemoteFile($host, $port, $remoteFilename, $usernameColonPassword, $receiveBufferSize, $mode, $fromEmail, $postData, $localFilename) 
    {
        $fileData = "";

        if($remoteFilename == "")
            $remoteFilename = "/";

        if($port == -1)
            $port = 80;

        $timeout = 30;
        $sHandle = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$sHandle) 
        {
            return "<font color=red><SOCKET ERROR $errno: $errstr</font><br>";	
        }
            
        $authorization = "";
        if($usernameColonPassword != "")
        {
            $authorization = "Authorization: Basic " . base64_encode($usernameColonPassword) . "\r\n";
        }

        if($mode == "get")
        {
            $request = $this->generateGetRequest($host, $remoteFilename, $authorization);
        }
        else if($mode == "post")
        {
            $request = $this->generatePostRequest($host, $remoteFilename, $authorization, $fromEmail, $postData);
        }
        if($this->debug)
            print "Sending request string:<br>$request<br><br>";

        fwrite($sHandle, $request);

        $data = "";
        $buf = "";
        do
        {
            $buf = fread($sHandle, $receiveBufferSize);
            if($buf != "")
            {
                if($this->debug) print "READ: <br>$buf<br><br>";
                $data .= $buf;
            }
        } while($buf != "");
        
        fclose($sHandle);
        $dataArray = explode("\r\n\r\n", $data);
        $numElements = count($dataArray);
        $body = "";
        for($i = 1; $i <= $numElements; $i++)
        {
            $body .= $dataArray[$i];
            if($this->debug) print "body loop $i:<br>$body<br> ";
        }
        if($this->debug)
            print "<br><br>dataArray len is ".count($dataArray).".<br><br>".
                "header is:<br>".$dataArray[0]."<br><br>".
                "body is:<br>".$body."<br>";
                
        if($localFilename == "")
        {
            return $body;
        }
        else
        {
            if($this->debug) print "writing to local file: $localFilename<br>";
            $fHandle = fopen($localFilename, 'w+');
            if($fHandle) 
            {
                fwrite($fHandle, $body);
                fclose($fHandle);
                return "remote file saved to: <a href=$localFilename target=_blank>$localFilename</a><br>";
            }
            else
            {
                return "<font color=red><FILE ERROR cannot write to file: $localFilename</font><br>";	
            }
            
        }
    }
}
?>