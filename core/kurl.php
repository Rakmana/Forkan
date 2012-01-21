<?PHP

/*
 * Project:		Absynthe cURL
 * File:		kurl.class.php5
 * Author:		Sylvain 'Absynthe' Rabot <sylvain@abstraction.fr>
 * Website:		http://absynthe.is.free.fr/kurl/
 * Version:		alpha 1
 * Date:		05/06/2007
 * License:		LGPL
 */
 
/**
 * Take a look at the declaration of methods.
 * All parameters initialized to false in declarations are optional if you set them with set_option().
 * If you set values with both set_option and argument, arguments not FALSE will be taken.
 * Puting arguments doesn't overwrite values set by set_option, arguments are temporary.
 * 
 * Location header is taken into account automatically, 
 * you will receive headers and content of the page pointed by the redirection
 * 
 * @example :
 * <code>
 * 
 * $kurl = new kurl();
 * 
 * $kurl->set_option('url', 'http://absynthe.is.free.fr/');
 * $kurl->set_option('port', 80);
 * $kurl->set_option('login', 'Absynthe');
 * $kurl->set_option('password', 'mypassword');
 * $kurl->set_option('headers', true);
 * $kurl->set_option('cookie', '/path/to/my/cookie.txt');
 * $kurl->set_option('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4');
 * 
 * $answer = $kurl->ping();
 * $answer = $kurl->http_request();
 * $answer = $kurl->http_auth_request();
 * $answer = $kurl->http_post_request('var=foo&var1=fooo');
 * $answer = $kurl->http_post_request(array('var' => 'foo', 'var1' => 'fooo'));
 * $answer = $kurl->http_post_auth_request('var=foo&var1=fooo');
 * $answer = $kurl->http_post_auth_request(array('var' => 'foo', 'var1' => 'fooo'));
 * 
 * $answer = $kurl->ftp_upload('/path/to/my/file.txt', 'ftp.abstraction.fr/path/to/file.txt', 21, 'Absynthe', 'mypassword');
 * $answer = $kurl->ftp_download('/path/to/my/file.txt', 'ftp.abstraction.fr/path/to/file.txt', 21, 'Absynthe', 'mypassword');
 * 
 * </code>
 */

class kurl
{
	/* Init
	---------------------------------- */
	private $handler	= false;
	private $url		= false;
	private $port		= false;
	private $login		= false;
	private $password	= false;
	private $headers	= false;
	private $cookie		= false;
	private $user_agent	= false;	

	/**
	 * Constructor of the object
	 *
	 * @param string $url :	URL of the page
	 * @param int $port : Port used to get the page
	 * @param string $login : HTTP login
	 * @param string $password : HTTP password
	 * @param boolean $headers : set it to true if you want headers in the answer
	 * @param string $cookie : path to the cookie file if you want to use the object like a web browser
	 * @param string $user_agent : if you need to look like a web browser
	 */
	public function __construct($url = false, $port = false, $login = false, $password = false, $headers = false, $cookie = false, $user_agent = false)
	{
		if (!function_exists('curl_init'))
		{
			trigger_error('Sorry but PHP is not compiled with cURL', E_USER_ERROR);
			exit;
		}
		
		$this->handler		= curl_init();
		$this->url			= $url;
		$this->port			= $port;
		$this->login		= $login;
		$this->password		= $password;
		$this->headers		= $headers;
		$this->cookie		= $cookie;
		$this->user_agent	= $user_agent;
	}
	
	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if (is_resource($this->handler))
			curl_close($this->handler);
	}
	
	/**
	 * Wake up method to enable unserialization
	 */
	public function __wakeup()
	{
		if (!is_resource($this->handler))
			$this->handler = curl_init();
	}
	
	/**
	 * Set object options
	 *
	 * @param string $var
	 * @param string $value
	 */
	public function set_option($var, $value)
	{
		$var		= strtolower($var);
		$this->$var	= $value;
	}
	
	/**
	 * Ping an URL
	 *
	 * @param string $url :	URL to ping
	 * @param int $port : Port used to ping
	 * @param int $post : Ping with post request
	 * @return boolean
	 */
	public function ping($url = false, $port = false, $post = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		
		/* cURL Setup
		---------------------------------- */ 
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, false); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($this->handler, CURLOPT_NOBODY, true);
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $port);
		
		/* Preparation of post request
		---------------------------------- */
		if ($post !== false)
		{
			curl_setopt($this->handler, CURLOPT_POST, true);
			
			if (is_array($post))
			{
				$string = '';
				
				foreach ($post as $key => $value)
					$string .= "$key=$value&";
				
				curl_setopt($this->handler, CURLOPT_POSTFIELDS, $string);
			}
			else
			{
				curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);
			}
		}
			
		/* Execution
		---------------------------------- */
		return curl_exec($this->handler);
	}
	
	/**
	 * Retrieve content through HTTP
	 * 
	 * @param string $url :	URL of the page
	 * @param int $port : Port used to get the page
	 * @param boolean $headers : set it to true if you want headers in the answer
	 * @param string $cookie : path to the cookie file if you want to use the object like a web browser
	 * @param string $user_agent : if you need to look like a web browser
	 * @return string / array
	 */
	public function http_request($url = false, $port = false, $headers = false, $cookie = false, $user_agent = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($headers, $this->headers);
		$this->test_var($cookie, $this->cookie);
		$this->test_var($user_agent, $this->user_agent);
		
		/* cURL Setup
		---------------------------------- */ 
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, $headers); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $port);
			
		if (is_file($cookie))
		{
			curl_setopt($this->handler, CURLOPT_COOKIE, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEFILE, $cookie);
		}
		
		if ($user_agent !== false)
			curl_setopt($this->handler, CURLOPT_USERAGENT, $this->user_agent); 
		
		/* Execution
		---------------------------------- */ 
		if ($headers)
			return array("headers" => curl_exec($this->handler), "content" => curl_exec($this->handler));
		else
			return curl_exec($this->handler);
	}
	
	/**
	 * Retrieve content through HTTP with authentication
	 *
	 * @param string $url :	URL of the page
	 * @param int $port : Port used to get the page
	 * @param string $login : HTTP login
	 * @param string $password : HTTP password
	 * @param boolean $headers : set it to true if you want headers in the answer
	 * @param string $cookie : path to the cookie file if you want to use the object like a web browser
	 * @param string $user_agent : if you need to look like a web browser
	 * @return string / array
	 */
	public function http_auth_request($url = false, $port = false, $login = false, $password = false, $headers = false, $cookie = false, $user_agent = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($login, $this->login);
		$this->test_var($password, $this->password);
		$this->test_var($headers, $this->headers);
		$this->test_var($cookie, $this->cookie);
		$this->test_var($user_agent, $this->user_agent);
		
		/* cURL Setup
		---------------------------------- */ 
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, $headers); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($this->handler, CURLOPT_USERPWD, "$login:$password");		
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $this->port);
			
		if (is_file($cookie))
		{
			curl_setopt($this->handler, CURLOPT_COOKIE, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEFILE, $cookie);
		}
		
		/* Execution
		---------------------------------- */ 
		if ($headers)
			return array("headers" => curl_exec($this->handler), "content" => curl_exec($this->handler));
		else
			return curl_exec($this->handler);
	}
	
	/**
	 * Send a post request and retrieve the content
	 *
	 * @param string/array $post : Post request
	 * @param string $url :	URL of the page
	 * @param int $port : Port used to get the page
	 * @param boolean $headers : set it to true if you want headers in the answer
	 * @param string $cookie : path to the cookie file if you want to use the object like a web browser
	 * @param string $user_agent : if you need to look like a web browser
	 * @return string/array
	 */
	public function http_post_request($post, $url = false, $port = false, $headers = false, $cookie = false, $user_agent = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($headers, $this->headers);
		$this->test_var($cookie, $this->cookie);
		$this->test_var($user_agent, $this->user_agent);
		
		/* cURL Setup
		---------------------------------- */ 
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, $headers); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->handler, CURLOPT_POST, true);
		
		if (is_string($post))
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $port);
		
		if ($user_agent !== false)
			curl_setopt($this->handler, CURLOPT_USERAGENT, $this->user_agent);
		
		if (is_file($cookie))
		{
			curl_setopt($this->handler, CURLOPT_COOKIE, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEFILE, $cookie);
		}
		
		/* Preparation of post request
		---------------------------------- */ 
		if (is_array($post))
		{
			$string = '';
			
			foreach ($post as $key => $value)
				$string .= "$key=$value&";
			
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $string);
		}
		else
		{
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);
		}
		
		/* Execution
		---------------------------------- */ 
		if ($headers)
			return array("headers" => curl_exec($this->handler), "content" => curl_exec($this->handler));
		else
			return curl_exec($this->handler);
	}
	
	/**
	 * Send a post request and retrieve the content with HTTP authentication
	 *
	 * @param string/array $post : Post request
	 * @param string $url :	URL of the page
	 * @param int $port : Port used to get the page
	 * @param string $login : HTTP login
	 * @param string $password : HTTP password
	 * @param boolean $headers : set it to true if you want headers in the answer
	 * @param string $cookie : path to the cookie file if you want to use the object like a web browser
	 * @param string $user_agent : if you need to look like a web browser
	 * @return string/array
	 */
	public function http_auth_post_request($post, $url = false, $port = false, $login = false, $password = false, $headers = false, $cookie = false, $user_agent = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($login, $this->login);
		$this->test_var($password, $this->password);
		$this->test_var($headers, $this->headers);
		$this->test_var($cookie, $this->cookie);
		$this->test_var($user_agent, $this->user_agent);
		
		/* cURL Setup
		---------------------------------- */  		
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, $headers); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($this->handler, CURLOPT_USERPWD, "$login:$password");
		curl_setopt($this->handler, CURLOPT_POST, true);			
		
		if (is_string($post))
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);
			
		if ($user_agent !== false)
			curl_setopt($this->handler, CURLOPT_USERAGENT, $this->user_agent);
		
		if (is_file($cookie))
		{
			curl_setopt($this->handler, CURLOPT_COOKIE, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($this->handler, CURLOPT_COOKIEFILE, $cookie);
		}
		
		/* Preparation of post request
		---------------------------------- */ 
		if (is_array($post))
		{
			$string = '';
			
			foreach ($post as $key => $value)
				$string .= "$key=$value&";
			
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $string);
		}
		else
		{
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);
		}
		
		/* Execution
		---------------------------------- */ 
		if ($headers)
			return array("headers" => curl_exec($this->handler), "content" => curl_exec($this->handler));
		else
			return curl_exec($this->handler);
	}
	
	
	/**
	 * Upload a file to a ftp
	 *
	 * @param string $path : path to the file to upload
	 * @param string $url : url of the ftp with the path where to upload
	 * @param int $port : Port used to get the page
	 * @param string $login : FTP login
	 * @param string $password : FTP password
	 * @return boolean
	 */
	public function ftp_upload($path, $url = false, $port = false, $login = false, $password = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($login, $this->login);
		$this->test_var($password, $this->password);
		
		/* Checkpoint
		---------------------------------- */
		if (is_file($path))
		{
			if (!$fhandler = fopen($path, 'r'));
				return false;
		}
		else
		{
			return false;
		}
		
		/* Var updates if needed
		---------------------------------- */
		$file = pathinfo($path);
		
		if (!preg_match("#^(ftp:\/\/)#i", $url))
				$url = "ftp://".$url;
		
		if ($login !== false && $password !== false)
			if (!preg_match("#ftp:\/\/([[:alnum:]-\._])+:([[:alnum:]-\._])+@(.*)#i", $url))
				$url = preg_replace("#(ftp:\/\/)(.*)#i", "\\1$login:$password@\\2", $url);
			
		if (!preg_match("#(\/)^#i", $url))
			$url .= "/".$file['basename'];
		else
			$url .= $file['basename'];
		
		/* cURL Setup
		---------------------------------- */ 
		curl_setopt($this->handler, CURLOPT_URL, $url);
		curl_setopt($this->handler, CURLOPT_UPLOAD, true);
		curl_setopt($this->handler, CURLOPT_INFILE, $fhandler);
		curl_setopt($this->handler, CURLOPT_INFILESIZE, filesize($path));
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $port);
		
		/* Execution
		---------------------------------- */ 
		return curl_exec($this->handler);
	}
	
	/**
	 * Download a file from a FTP
	 *
	 * @param string $path : path where to ulpoad
	 * @param string $url : url of the ftp with the path of the file to download
	 * @param int $port : Port used to get the page
	 * @param string $login : FTP login
	 * @param string $password : FTP password
	 * @return boolean
	 */
	public function ftp_download($path, $url = false, $port = false, $login = false, $password = false)
	{
		/* Init
		---------------------------------- */
		$this->test_var($url, $this->url);
		$this->test_var($port, $this->port);
		$this->test_var($login, $this->login);
		$this->test_var($password, $this->password);
		
		/* Var updates if needed
		---------------------------------- */
		if (!preg_match("#^(ftp:\/\/)#i", $url))
				$url = "ftp://".$url;
		
		if ($login !== false && $password !== false)
			if (!preg_match("#ftp:\/\/([[:alnum:]-\._])+:([[:alnum:]-\._])+@(.*)#i", $url))
				$url = preg_replace("#(ftp:\/\/)(.*)#i", "\\1$login:$password@\\2", $url);
		
		/* cURL Setup
		---------------------------------- */  
		curl_setopt($this->handler, CURLOPT_URL, $url); 
		curl_setopt($this->handler, CURLOPT_HEADER, false); 
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_AUTOREFERER, true);
		
		if ($port !== false)
			curl_setopt($this->handler, CURLOPT_PORT, $port);
		
		/* Execution
		---------------------------------- */ 
		$content = curl_exec($this->handler);
		
		/* Writing
		---------------------------------- */
		if($fhandler = fopen($path, 'w+'))
			if (fwrite($fhandler, $content))
				return true;
			else
				return false;
		else
			return false;
	}

	/**
	 * Set a variable to $default parameter if it's false
	 * @param anything $var
	 * @param anything $default
	 */
	private function test_var(&$var, $default)
	{
		if ($var === false)
			$var = $default;
	}
}

?>