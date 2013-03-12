<?php

/**
 * Handles the response that is sent back to the client.
 * @package Core
 */
class Response
{

	/**
	 * Headers for the response
	 * @var array
	 * @access public
	 */
	public $headers = array(
		'Content-Type: text/html; charset=utf-8'
	);

	/**
	 * Response body
	 * @var string
	 * @access public
	 */
	public $body;

	/**
	 * Add header to the response
	 *
	 * @param string $header Header content
	 * @return void
	 * @access public
	 */
	public function add_header($header)
	{
		$this->headers[] = $header;
	}

	/**
	 * Add redirection header
	 *
	 * @param string $url URL to redirect the client to
	 * @return void
	 * @access public
	 */
	public function redirect($url)
	{
		$this->add_header("Location: $url");
	}

	/**
	 * Sends headers to the client
	 *
	 * @return Response Same Response object, for method chaining
	 * @access public
	 */
	public function send_headers()
	{
		foreach ($this->headers as $header)
			header($header);
		return $this;
	}

	/**
	 * Send response body to the client
	 *
	 * @return object Same Response object, for method chaining
	 * @access public
	 */
	public function send_body()
	{
		echo $this->body;
		return $this;
	}

}