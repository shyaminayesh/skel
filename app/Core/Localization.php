<?php
namespace Avanciro\Skel\Core;

class Localization {

    // PROPERTIES
    private $config = null;
    private $store = array();


    public function __construct() {
        $this->config = Config::get('localization');
    }



    /**
     * This method will use to query language strings according
     * to the language cookie and the default language configuration
     * on the localization config file.
     */
    public function query($file, $string) {
        if ( $this->config->enable ):
            if ( isset($_COOKIE['lang']) AND file_exists(dirname(dirname(__DIR__)).'/'.$this->config->directory.'/'.$_COOKIE['lang'].'/'.$file.'.php') ):

                /**
                 * Seems like we have localization file that defined in
                 * language cookie.
                 */
                if ( array_key_exists($string, $this->file($_COOKIE['lang'], $file)) ):
                    return $this->file($_COOKIE['lang'], $file)[$string];
                else:
                    return false;
                endif;

            elseif ( file_exists(dirname(dirname(__DIR__)).'/'.$this->config->directory.'/'.$this->config->language['default'].'/'.$file.'.php') ):

                /**
                 * Seems like cookie defined language file is not found
                 * on the language store. We need to return default language
                 * file string in that case if exists.
                 */
                if ( array_key_exists($string, $this->file($this->config->language['default'], $file)) ):
                    return $this->file($this->config->language['default'], $file)[$string];
                else:
                    return false;
                endif;

            else:

                /**
                 * We can't locate default language file or the cookie
                 * specified language file. Returning false to the parent
                 * caller to handle execution.
                 */
                return false;

            endif;
        endif;
    }



    /**
     * This method will use to load the language configuration file
     * and keep it in the memory until we take all the strings
     * out of it when needed.
     */
    public function file($lang, $file) {
        if ( array_key_exists($file, $this->store) ):
            return $this->store[$file];
        else:
            $this->store[$file] = include dirname(dirname(__DIR__)).'/'.$this->config->directory.'/'.$lang.'/'.$file.'.php';
            return $this->store[$file];
        endif;
    }



    /**
     * Set the default locale configuration if the language cookie
     * is not present in the user's cookies.
     */
    public function is_cookie() {
		if ( $this->config->enable AND !isset($_COOKIE['lang']) ):
			setcookie('lang', $this->config->language['default'], time()+(86400*10000), "/", $_SERVER['HTTP_HOST'], 0);
		endif;
    }

}

?>