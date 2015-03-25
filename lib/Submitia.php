<?php
/*
File: Submitia.php
Date: 03/25/2015
Version 1.0
Author: Glenn Prialde
Copyright iSnare Online Technologies 2004.  All rights reserved.  http://www.isnare.com and http://www.submitia.com


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

require_once('TinyHttpClient.php');

class Submitia extends TinyHttpClient {

	private $host = 'api.submitia.com';
	private $token = null;
	private $secret = null;		
	private $port = 80;
	private $remoteFile = "/decaptcha.php";
	private $basicAuthUsernameColonPassword = "";
	private $bufferSize = 2048;
	//private $mode = "post";
	private $mode = "get";
	private $fromEmail = "admin@submitia.com";
	private $postData = "";
	private $localFile = "";
	
	public function __construct($token, $secret) {
		$this->token = $token;
		$this->secret = $secret;
	}
	
	public function balance($username) { 
		$remoteFile = $this->remoteFile . '?p=balance&username=' . $username . '&key=' . $this->token;
		$ret = $this->getRemoteFile($this->host, $this->port, $remoteFile, $this->basicAuthUsernameColonPassword, $this->bufferSize, $this->mode, $this->fromEmail, $this->postData, $this->localFile);
		return $ret;
	}

	public function decode($catpcha) { 
		$remoteFile = $this->remoteFile . '?p=decode&url=' . urlencode($catpcha) . '&key=' . $this->token . '&secret=' . $this->secret;
		$ret = $this->getRemoteFile($this->host, $this->port, $remoteFile, $this->basicAuthUsernameColonPassword, $this->bufferSize, $this->mode, $this->fromEmail, $this->postData, $this->localFile);
		return $ret;
	}	

}

?>