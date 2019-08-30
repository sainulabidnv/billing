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
 * All in one user object use to authenticating, registering new users and other user actions
 * Note: Either start() or login() must be called at least once on your code per User instance
 *
 * @package ptejada\uFlex
 * @author  Pablo Tejada <pablo@ptejada.com>
 */
class User extends UserBase
{
    /**
     * Class Version
     *
     * @var string
     */
    const VERSION = '1.0.6';
    /**
     * @var DB_Table - The database table object
     */
    public $table;
    /**
     * @var  Session - The namespace session object
     */
    public $session;
    /**
     * Holds a unique clone number of the instance clones
     *
     * @var int
     * @ignore
     */
    protected $clone = 0;
    /**
     * @var DB - The database connection
     */
    protected $db;
    /**
     * @var  Cookie - The cookie for autologin
     */
    protected $cookie;
    /**
     * @var array Array of errors text. Could use overwritten for multilingual support
     */
    protected $errorList = array(
        //Database Error while calling register functions
        1 => 'New User Registration Failed',
        //Database Error while calling update functions
        2 => 'The Changes Could not be made',
        //Database Error while calling activate function
        3 => 'Account could not be activated',
        //When calling pass_reset and the given email doesn't exist in database
        4 => 'We don\'t have an account with this email',
        //When calling new_pass, the confirmation hash did not match the one in database
        5 => 'Password could not be changed. The request can\'t be validated',
        6 => 'Logging with cookies failed',
        7 => 'No username or Password provided',
        8 => 'Your Account has not been activated. Check your Email for instructions',
        9 => 'Your account has been deactivated. Please contact Administrator',
        10 => 'Wrong username or Password',
        //When calling check_hash with invalid hash
        11 => 'confirmation hash is invalid',
        //Calling check_hash hash failed database match test
        12 => 'Your identification could not be confirmed',
        //When saving hash to database fails
        13 => 'Failed to save confirmation request',
        14 => 'You need to reset your password to login',
        15 => 'Can not register a new user, as user is already logged in.',
        16 => 'This Email is already in use',
        17 => 'This username is not available',
    );

    /**
     * Restore the a user session or Login a with given credentials.
     *
     * @api
     *
     * @param string $identifier - username or Email
     * @param string $password - Clear text password
     * @param bool $autoLogin - Flag whether to remember the user
     *
     * @return bool
     */
    public function login($identifier = '', $password = '', $autoLogin = false)
    {
        $this->log->channel('login');

        // Start the class if is not been start yet
        $this->start(false);

        //Session Login
        if ($this->session->signed) {
            $this->log->report('User Is signed in from session');
            if ($this->session->update) {
                $this->log->report('Updating Session from database');

                //Get User From database because its info has change during current session
                $update = $this->table->getRow(array('id' => $this->id, 'activated' => 1));
                if ($update) {
                    $this->session->data = $update->toArray();

                    //Update last_login
                    $this->logLogin();

                    //Cleaning session update flag
                    unset($this->session->update);
                } else {
                    $this->logout();
                    return false;
                }
            }
            return true;
        }

        //Cookies Login
        if (($confirmation = $this->cookie->getValue()) && !$identifier && !$password) {
            $this->log->report('Attempting Login with cookies');
            list($uid, $partial) = $this->hash->examine($confirmation);

            if ($uid && $partial) {
                $autoLogin = true;
                $getBy = 'id';
                $identifier = $uid;
            } else {
                $this->log->error(6);
                $this->logout();
                return false;
            }
        } else {
            //Credentials Login
            if ($identifier && $password) {
                if (preg_match($this->_validations->email->regEx, $identifier)) {
                    //Login using email
                    $getBy = 'email';
                } else {
                    //Login using username
                    $getBy = 'username';
                }

                $this->log->report('Credentials received');
            } else {
                if ($identifier && !$password) {
                    $this->log->error(7);
                }
                return false;
            }
        }

        $this->log->report('Querying Database to authenticate user');

        //Query Database for user
        $userFile = $this->table->getRow(array($getBy => $identifier));

        if ($userFile && !$this->isSigned()) {
            if (isset($partial)) {
                // Partially match the user password to authenticate
                $this->session->signed = strpos($userFile->password, $partial) >= 0;
            } else {
                // Fully match the user password to authenticate
                $this->_updates = $userFile;

                /*
                * Determine whether to use the old or new algorithm
                */
                $aType = strlen($userFile->password) !== 40;

                /*
                * Encode the password with the hashing algorithm
                */
                $generated = $this->session->signed = $this->hash->generateUserPassword($this, $password,
                    $aType);

                /*
                * Compared the generated hash with the stored one
                * If it matches then the user will be logged in
                */
                $this->session->signed = $generated === $userFile->password;

                // Clear the updates stack
                $this->_updates = new Collection();
            }
        } else {
            if (!$this->isSigned() && $password) {
                $this->log->formError('password', $this->errorList[10]);
                return false;
            }
        }

        if ($this->isSigned()) {
            //If Account is not activated
            if ($userFile->activated == 0) {
                if ($userFile->last_login == 0) {
                    //Account has not been activated
                    $this->log->formError('password', $this->errorList[8]);
                } else {
                    if (!$userFile->confirmation) {
                        //Account has been deactivated
                        $this->log->formError('password', $this->errorList[9]);
                    } else {
                        //Account deactivated due to a password reset or reactivation request
                        $this->log->formError('password', $this->errorList[14]);
                    }
                }
                // Remove the signed flag
                $this->session->signed = 0;
                return false;
            }

            $this->session->data->update($userFile->toArray());

            //If auto Remember User
            if ($autoLogin) {
                // TODO: The way the autologin cookie works needs to be improved
                $this->cookie->setValue($this->hash->generate($this->id, $this->password));
                $this->cookie->add();
            }

            //Update last_login
            $this->logLogin();

            //Done
            $this->log->report('User Logged in Successfully');
            return true;
        } else {
            if ($password) {
                // Removes the autologin cookie
                $this->cookie->destroy();
                $this->log->formError('password', 10);
            }
            return false;
        }
    }

    /**
     * Starts and Configures the object
     *
     * @param bool $login Flag whether to attempt to login or not
     *
     * @return $this
     */
    public function start($login = true)
    {
        if (!($this->db instanceof DB)) {
            // Updating the predefine error logs
            $this->log->addPredefinedError($this->errorList);

            // Instantiate the Database object
            if ($this->config->database->pdo instanceof \PDO) {
                // Uses an existing PDO connection
                $this->db = new DB($this->config->database->pdo);
            } else {
                if ($this->config->database->dsn) {
                    $this->db = new DB($this->config->database->dsn);
                } else {
                    $this->db = new DB($this->config->database->host, $this->config->database->name);
                }

                // Configure the database object
                $this->db->setUser($this->config->database->user);
                $this->db->setPassword($this->config->database->password);
            }

            // Link logs
            $this->db->log = $this->log;

            //Instantiate the table DB object
            $this->table = $this->db->getTable($this->config->userTableName);

            // Instantiate and configure the cookie object
            $this->cookie = new Cookie($this->config->cookieName);
            $this->cookie->setHost($this->config->cookieHost);
            $this->cookie->setPath($this->config->cookiePath);
            $this->cookie->setLifetime($this->config->cookieTime);

            // Instantiate the session
            $this->session = new Session($this->config->userSession, $this->log);

        }

        // Link the session with the user data
        if (is_null($this->session->data)) {
            $this->session->data = $this->config->userDefaultData->toArray();
        }
        $this->_data = &$this->session->data->toArray();

        if ($login) {
            $this->login();
        }

        return $this;
    }

    /**
     * Logs user last login in database
     *
     * @ignore
     */
    protected function logLogin()
    {
        //Update last_login
        $time = time();
        $sql = "UPDATE _table_ SET last_login=:stamp WHERE id=:id";
        if ($this->table->runQuery($sql, array('stamp' => $time, 'id' => $this->id))) {
            $this->log->report('Last Login updated');
        }
    }

    /**
     * Logout the user
     * Logs out the current user and deletes any autologin cookies
     *
     * @return void
     */
    function logout()
    {
        if (!$this->cookie->destroy()) {
            $this->log->report('The Autologin cookie could not be deleted');
        }

        // Destroy the session
        $this->session->destroy();

        //Import default user object
        $this->_data = $this->config->userDefaultData->toArray();

        $this->log->report('User Logged out');
    }

    /**
     * Check if a user currently signed-in
     *
     * @return bool
     */
    public function isSigned()
    {
        return (bool)$this->session->signed;
    }

    /**
     * Register A New User
     * Takes two parameters, the first being required
     *
     * @access public
     * @api
     *
     * @param array|Collection $info An associative array, the index being the field name(column in database)and the value
     *                                     its content(value)
     * @param bool $activation Default is false, if true the user will need required further steps to activate account
     *                                     Otherwise the account will be activated if registration succeeds
     *
     * @return string|bool Returns activation hash if second parameter $activation is true
     *                        Returns true if second parameter $activation is false
     *                        Returns false on Error
     */
    public function register($info, $activation = false)
    {
        $this->log->channel('registration'); //Index for Errors and Reports

        /*
        * Prevent a signed user from registering a new user
        * NOTE: If a signed user needs to register a new user
        * use the User::manageUser() function to create a new user
        * object which then can then be use to register a new user
        */
        if ($this->isSigned()) {
            $this->log->error(15);
            return false;
        }

        //Saves Registration Data in Class
        $this->_updates = $info = $this->toCollection($info);

        //Validate All Fields
        if (!$this->validateAll(true)) {
            return false;
        } //There are validations error

        //Set Registration Date
        $info->reg_date = time();

        /*
        * Built in actions for special fields
        */

        //Hash password
        if ($info->password) {
            $info->password = $this->hash->generateUserPassword($this, $info->password);
        }

        //Check for email in database
        if ($info->email) {
            if ($this->table->isUnique('email', $info->email, 16)) {
                return false;
            }
        }

        //Check for username in database
        if ($info->username) {
            if ($this->table->isUnique('username', $info->username, 17)) {
                return false;
            }
        }

        //Check for errors
        if ($this->log->hasError()) {
            return false;
        }

        //User Activation
        if (!$activation) {
            //Activates user upon registration
            $info->activated = 1;
        }

        //Prepare Info for SQL Insertion
        $data = array();
        $into = array();
        foreach ($info->toArray() as $index => $val) {
            if (!preg_match("/2$/", $index)) { //Skips double fields
                $into[] = $index;
                //For the statement
                $data[$index] = $val;
            }
        }

        // Construct the fields
        $intoStr = implode(', ', $into);
        $values = ':' . implode(', :', $into);

        //Prepare New User Query
        $sql = "INSERT INTO _table_ ({$intoStr})
                VALUES({$values})";

        //Enter New user to Database
        if ($this->table->runQuery($sql, $data)) {
            $this->log->report('New User has been registered');
            // Update the new id internally
            $this->_data['id'] = $info->id = $this->table->getLastInsertedID();
            if ($activation) {
                // Generate a user specific hash
                $info->confirmation = $this->hash->generate($info->id);
                // Update the newly created user with the confirmation hash
                $this->update(array('confirmation' => $info->confirmation));
                // Return the confirmation hash
                return $info->confirmation;
            } else {
                return true;
            }
        } else {
            $this->log->error(1);
            return false;
        }
    }

    /**
     * Validates and updates any field in the database for the current user
     * Similar to the register method function in structure,
     * this Method validates and updates any field in the database
     *
     * @api
     *
     * @param array|Collection $updates An associative array,
     *                                  the index being the field name(column in database)
     *                                  and the value its content(value)
     *
     * @return bool Returns true on success anf false on error
     */
    public function update($updates = null)
    {
        $this->log->channel('update');

        if (!is_null($updates)) {
            //Saves Updates Data in Class
            $this->_updates = $updates = $this->toCollection($updates);
        } else {
            if ($this->_updates instanceof Collection && !$this->_updates->isEmpty()) {
                // Use the updates from the queue
                $updates = $this->_updates;
            } else {
                // No updates
                return false;
            }
        }

        //Validate All Fields
        if (!$this->validateAll()) {
            //There are validations error
            return false;
        }

        /*
        * Built in actions for special fields
        */

        //Hash password
        if ($updates->password) {
            $updates->password = $this->hash->generateUserPassword($this, $updates->
            password);
        }

        //Check for email in database
        if ($updates->email) {
            if ($updates->email != $this->email) {
                if ($this->table->isUnique('email', $updates->email,
                    'This email is Already in Use')
                ) {
                    return false;
                }
            }
        }

        //Check for errors
        if ($this->log->hasError()) {
            return false;
        }

        //Prepare Info for SQL Insertion
        $data = array();
        $set = array();
        foreach ($updates->toArray() as $index => $val) {
            if (!preg_match('/2$/', $index)) { //Skips double fields
                $set[] = "{$index}=:{$index}";
                //For the statement
                $data[$index] = $val;
            }
        }

        $set = implode(', ', $set);

        //Prepare User Update Query
        $sql = "UPDATE _table_ SET {$set}  WHERE id=:id";
        $data['id'] = $this->id;

        //Check for Changes
        if ($this->table->runQuery($sql, $data)) {
            $this->log->report('Information Updated');

            if ($this->clone === 0) {
                $this->session->update = true;
            }

            // Update the current object with the updated information
            $this->_data = array_merge($this->_data, $updates->toArray());

            // Clear the updates stack
            $this->_updates = new Collection();

            return true;
        } else {
            $this->log->error(2);
            return false;
        }
    }

    /**
     * Method to reset password, Returns confirmation code to reset password
     *
     * @access public
     * @api
     *
     * @param string $email User email to reset password
     *
     * @return Collection|bool On Success it returns a Collection with the user's (email,username,id,confirmation)
     *                        which could then be use to construct the confirmation URL and email.
     *                        On Failure it returns false
     */
    public function resetPassword($email)
    {
        $this->log->channel('resetPassword');

        $user = $this->table->getRow(array('email' => $email));

        if ($user) {
            if (!$user->activated && !$user->confirmation) {
                //The Account has been manually disabled and can't reset password
                $this->log->error(9);
                return false;
            }

            $data = array(
                'id' => $user->id,
                'confirmation' => $this->hash->generate($user->id),
            );

            $this->table->runQuery('UPDATE _table_ SET confirmation=:confirmation WHERE id=:id',
                $data);

            return new Collection(array(
                'email' => $email,
                'username' => $user->username,
                'id' => $user->id,
                'confirmation' => $data['confirmation']));
        } else {
            $this->log->formError('email', $this->errorList[4]);
            return false;
        }
    }

    /**
     * Changes a password with a confirmation hash from the pass_reset method
     * This is for users that forget their passwords to change the signed in user password use ->update()
     *
     * @access public
     * @api
     *
     * @param string $hash hash returned by the pass_reset() method
     * @param array $newPass An array with indexes 'password' and 'password2' Example:
     *                          array(
     *                          [password] => pass123
     *                          [password2] => pass123
     *                          )
     *
     * @return bool Returns true on a successful password change. Returns false on error
     */
    public function newPassword($hash, $newPass)
    {
        $this->log->channel('newPassword');

        list($uid, $partial) = $this->hash->examine($hash);

        if ($uid && $user = $this->table->getRow(array('id' => $uid, 'confirmation' => $hash))) {
            $this->_updates = new Collection($newPass);
            if (!$this->validateAll()) {
                return false;
            } //There are validations error

            $this->_updates = $user;

            // Generate the password hash
            $pass = $this->hash->generateUserPassword($this, $newPass['password']);

            $sql = "UPDATE _table_ SET `password`=:pass, confirmation='', activated=1 WHERE confirmation=:confirmation AND id=:id";
            $data = array(
                'id' => $uid,
                'pass' => $pass,
                'confirmation' => $hash);

            if ($this->table->runQuery($sql, $data)) {
                $this->log->report('password has been changed');
                return true;
            }
        }

        //Error
        $this->log->error(5);
        return false;
    }

    /**
     * Destroys the session if the instance is a clone
     */
    public function __destruct()
    {
        if ($this->clone > 0) {
            $this->session->destroy();
        }
    }

    /**
     * Activates Account with a hash
     * Takes Only and Only the URL parameter of a confirmation page
     * which would be the hash returned by the register() method
     *
     * @access public
     * @api
     *
     * @param string $hash Hash returned in the register method
     *
     * @return bool Returns true account activation and false on failure
     */
    public function activate($hash)
    {
        $this->log->channel('activation');

        $info = $this->hash->examine($hash);

        if ($info && is_array($info)) {
            list($uid, $partial) = $info;

            $user = $this->manageUser($uid);

            if ($user->id) {
                if ($user->confirmation === $hash) {

                    $user->activated = 1;
                    $user->confirmation = '';

                    // Updates the flag on the database
                    if ($user->update()) {
                        $this->log->report('Account has been activated');
                        return true;
                    }
                } else {
                    $this->log->report('The activation hash does not match the DB record');
                }
            } else {
                $this->log->report("Unable to find user with ID $uid to activate");
            }
        }

        /*
        * Execution will end up here if something goes wrong
        */
        $this->log->error(3);
        return false;
    }

    /**
     * User factory
     * Returns a clone of the User instance which allows simple user managing
     * capabilities such as updating a user field, resetting its password and so on.
     *
     * @api
     *
     * @param int $id
     *
     * @return bool|User Returns false if user does not exists in database
     */
    public function manageUser($id = null)
    {
        $user = clone $this;
        $user->log->channel('Cloning');

        if (is_numeric($id) && $id) {
            $user->log->report('Fetching user from database');
            $data = $user->table->getRow(array('id' => $id));
            if ($data) {
                $user->_data = $data->toArray();

                $user->log->report('User imported to object');
            }
        }

        return $user;
    }

    /**
     * Magic method to handle object cloning
     *
     * @ignore
     */
    protected function __clone()
    {
        $this->clone++;

        // Copy the configuration
        $this->config = new Collection($this->config->toArray());

        $this->config->cookieName .= '_c' . $this->clone;
        $this->config->userSession .= '_c' . $this->clone;

        $this->session = new Session($this->config->userSession);
        $this->cookie = new Cookie($this->config->cookieName);

        $this->_updates = new Collection();
        $this->log = $this->log->newChildLog('UserClone' . $this->clone);

        //Import default user object to session
        $this->session->data = $this->config->userDefaultData->toArray();
        //Link the new session namespace to the internal data array
        $this->_data = &$this->session->data->toArray();
    }
}
