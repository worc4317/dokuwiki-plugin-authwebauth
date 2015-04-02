<?php
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * Stanford WebAuth authentication backend
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Stephen Koo <sckoo@cs.stanford.edu>
 */
class auth_plugin_authwebauth extends DokuWiki_Auth_Plugin {

    public function __construct() {
        parent::__construct();

        // If WebAuth env variable is set, we're all good.
        if (!$_ENV['WEBAUTH_USER']) {
            $this->success = false;
        } else {
            $this->cando['external'] = true;
        }
    }

    function trustExternal($user, $pass, $sticky=false) {
        global $USERINFO;

        // Ignore $user and $pass, we will never use DokuWiki login form.
        // User is also guaranteed to be logged in if env variable is set
        // and this auth class was successfully instantiated.
        $user = $_ENV['WEBAUTH_USER'];

        // Set USERINFO
        // TODO store group data somewhere
        $USERINFO['name'] = $_ENV['WEBAUTH_LDAP_DISPLAYNAME'];
        $USERINFO['mail'] = $_ENV['WEBAUTH_LDAP_MAIL'];
        $USERINFO['grps'] = array($conf['defaultgroup']);

        // Set session
        $_SERVER['REMOTE_USER']                = $user;
        $_SESSION[DOKU_COOKIE]['auth']['user'] = $user;
        $_SESSION[DOKU_COOKIE]['auth']['info'] = $USERINFO;
    }

    // TODO Query somewhere for Stanford user data?
    /*
    public function getUserData($user) {
        return array(
            'name' => string,
            'mail' => string,
            'grps' => array()
        );
    }
    */

}
