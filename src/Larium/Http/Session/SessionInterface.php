<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http\Session;

interface SessionInterface
{

    /**
     * Gets the current session id
     * 
     * Returns the session id for the current session or the empty string ("")
     * if there is no current session (no current session id exists).
     * 
     * @access public
     * @return string
     */
    public function getId();

    /**
     * Sets the current session id 
     * 
     * @param string $id
     *
     * @access public
     * @return void
     */
    public function setId($id);


    /**
     * Gets the current session name.
     * 
     * @access public
     * @return string
     */
    public function getName();

    /**
     * Sets the current session name.
     *
     * Warning:
     * The session name can't consist of digits only, at least one letter must 
     * be present. Otherwise a new session id is generated every time.
     *
     * @param string $name 
     *
     * @access public
     * @return void
     */
    public function setName($name);

    /**
     * Update the current session id with a newly generated one.
     * 
     * This method will replace the current session id with a new one, and keep 
     * the current session information.
     * 
     * @param boolean $delete_old_sesssion Whether to delete the old associated 
     *                                     session file or not.
     *
     * @access public
     * @return boolean
     */
    public function regenerateId($delete_old_sesssion=false);


    /**
     * Registers a variable to the session
     * 
     * @param string $name
     * @param mixed $value
     *
     * @access public
     * @return void
     */
    public function set($name, $value);

    /**
     * Retrieve a variable from the session
     * 
     * @param string $name
     *
     * @access public
     * @return mixed
     */
    public function get($name);


    /**
     * Frees all session variables currently registered.
     * 
     * @access public
     * @return void
     */
    public function clear();

    /**
     * Removes a session variable.
     * 
     * @param string $name
     *
     * @access public
     * @return void
     */
    public function delete($name);

    /**
     * Checks if session contains current variable. 
     * 
     * @param string $name 
     *
     * @access public
     * @return boolean
     */
    public function has($name);

    /**
     * Geta the session cookie parameters
     * 
     * @access public
     * @return array Returns an array with the current session cookie 
     *               information, the array contains the following items:
     *               "lifetime" - (int)     The lifetime of the cookie in seconds.
     *               "path"     - (string)  The url path where information is stored.
     *               "domain"   - (string)  The domain of the cookie.
     *               "secure"   - (boolean) The cookie should only be sent over secure connections.
     *               "httponly" - (boolean) The cookie can only be accessed through the HTTP protocol.
     */
    public function getCookieParams();

    /**
     * Sets the session cookie parameters.
     *
     * Available parameters are:
     *  "lifetime" - (int)     The lifetime of the cookie in seconds.
     *  "path"     - (string)  The path where information is stored.
     *  "domain"   - (string)  The domain of the cookie.
     *  "secure"   - (boolean) The cookie should only be sent over secure connections.
     *  "httponly" - (boolean) The cookie can only be accessed through the HTTP protocol.
     * 
     * @param array $params
     *
     * @access public
     * @return void
     */
    public function setCookieParams(array $params);

    /**
     * Start new or resume existing session.
     * 
     * Creates a session or resumes the current one based on a session 
     * identifier passed via a GET or POST request, or passed via a cookie.
     *
     * @access public
     * @return boolean
     */
    public function start();

    /**
     * Sets user-level session storage functions.
     * 
     * @param Larium\Http\Session\Handler\SessionHandlerInterface | 
     *        \SessionHandlerInterface $handler
     *
     * @access public
     * @return void
     */
    public function setSaveHandler($handler);

    /**
     * Writes session data and end session.
     * 
     * @access public
     * @return void
     */
    public function save();

    /**
     * Returns current cache expire in minutes
     * 
     * @access public
     * @return void
     */
    public function getExpire();
    
    /**
     * Replaces the current cache expire.
     * 
     * @param string $new_cache_expire Value in minutes.
     *
     * @access public
     * @return void
     */

    public function setExpire($new_cache_expire);

    /**
     * Sets/Change the name of the current cache limiter. 
     * 
     * @param string $name The name of the current cache limiter. 
     *                     Possible values are: 'public', 'private_no_expire', 
     *                     'private', 'nocache'
     * @link http://www.php.net/manual/en/function.session-cache-limiter.php
     *
     * @access public
     * @return void
     */
    public function setCacheLimiter($name);

    /**
     * Returns the name of the current cache limiter.
     * 
     * @access public
     * @return string
     */
    public function getCacheLimiter();

    /**
     * Decodes session data from a session encoded string. 
     * 
     * Decodes the serialized session data provided in $data, and populates 
     * the $_SESSION superglobal with the result.
     * 
     * @param string $data 
     *
     * @access public
     * @return boolean
     */
    public function decode($data);

    /**
     * Encodes the current session data as a session encoded string.
     * 
     * Returns a serialized string of the contents of the current session data 
     * stored in the $_SESSION superglobal
     * 
     * Warning: Must call Session::start() before using Session::encode().
     * 
     * @access public
     * @return string The contents of the current session encoded.
     */
    public function encode();
}
