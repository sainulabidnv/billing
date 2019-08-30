<?php
/*
Copyright (c) 2014 Pablo Tejada

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
namespace Invoice\Express;

/**
 * Class to handle the PHP session
 * The entire PHP session can be handled
 * Or a direct member of the main session array
 * which is refer as a namespace
 *
 * @package ptejada\uFlex
 * @author Pablo Tejada <pablo@ptejada.com>
 */
class Session extends LinkedCollection
{
    /**
     * @var  Log - Log errors and report
     */
    public $log;

    /**
     * @var null|string Session index to manage
     */
    protected $namespace;

    /**
     * Initialize a session handler by namespace
     *
     * @param string $namespace - Session namespace to manage
     * @param   Log $log
     */
    public function __construct($namespace = null, Log $log = null)
    {
        $this->log = $log instanceof Log ? $log : new Log('Session');
        $this->namespace = $namespace;

        // Starts the session if it has not been started yet
        if (!isset($_SESSION) && !headers_sent()) {
            session_start();
            $this->log->report('Session is been started...');
        } elseif (isset($_SESSION)) {
            $this->log->report('Session has already been started');
        } else {
            $this->log->error('Session could not be started');
        }

        if (is_null($namespace)) {
            // Manage the whole session
            parent::__construct($_SESSION);
        } else {
            if (!isset($_SESSION[$namespace])) {
                // Initialize the session namespace if does not exists yet
                $_SESSION[$namespace] = array();
            }

            // Link the SESSION namespace to the local $data variable
            parent::__construct($_SESSION[$namespace]);
        }

        $this->validate();
    }

    /**
     * Validates the session
     */
    private function validate()
    {
        /*
        * Get the correct IP
        */
        $server = new Collection($_SERVER);
        $ip = $server->HTTP_X_FORWARDED_FOR;

        if (is_null($ip) && $server->REMOTE_ADDR) {
            $ip = $server->REMOTE_ADDR;
        }

        if (!is_null($this->_ip)) {
            if ($this->_ip != $ip) {
                /*
                * Destroy the session in the IP stored in the session is different
                * then the IP of the current request
                */
                $this->destroy();
            }
        } else {
            /*
            * Save the current request IP in the session
            */
            $this->_ip = $ip;
        }
    }

    /**
     * Get current session ID identifier
     *
     * @return string
     */
    public function getID()
    {
        return session_id();
    }

    /**
     * Empty the session namespace
     */
    public function destroy()
    {
        if (is_null($this->namespace)) {
            // Destroy the whole session
            session_destroy();
        } else {
            // Just empty the current session namespace
            $_SESSION[$this->namespace] = array();
            unset($_SESSION[$this->namespace]);
        }
    }
}
