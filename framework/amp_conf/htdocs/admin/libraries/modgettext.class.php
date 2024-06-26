<?php
/**
 * IssabelPBX module based gettext class
 *
 * short translates based on a modules domain self initializing
 *
 * @author Philippe Lindheimer
 */
class modgettext {

    // This hash maps a given module name to the initialized txt domain it should use.
    // If the hash is not initialized then it will be setup the first time it is attempted.
    static private $tdhash = array();

    // stack for saved textdomains
    static private $textdomain_stack = array();

    /**
     * _() translate a string given a module it comes from
     * short translates a string given the string and the owning module
     *
     * @access    static public
     * @param    string
     * @param    string
     * @return string
     *
     * Given a string and a module, this function will attempt to translate the string
     * using the module's textdomain if translations exist. It depends on the private
     * method _bindtextdomain to check if the domains have been initialized and if not
     * it will do so. It expects the proper domain to use to be returned by this method
     * so that it can simply attempt the tranlastion. If it finds the translated text
     * is the same as the original text AND the domain is NOT amp (core/framework's default)
     * then it will make a last attempt to lookup a translation in core since many old
     * translations for some modules were put in amp by the translators back when
     * this didn't work too well.
     */
    static public function _($string, $module) {
        $domain = self::_bindtextdomain($module);
        $tstring = _dgettext($domain,$string);

        // if our translation didn't change and we aren't already using 'amp' then try with amp
        if ($tstring == $string && $domain != 'amp') {
            $tstring = _dgettext('amp',$string);
        }
        return $tstring;
    }

    /**
     * textdomain
     * short sets the textdomain to the domain defined for this module
     *
     * @access static public
     * @param string
     * @return string
     */
    static public function textdomain($module) {
        // act like textdomain() would if passed null even though that is not the intended use
        if ($module === null) {
            return _textdomain(null);
        }
        _textdomain($module);
        return textdomain(self::_bindtextdomain($module));
    }

    /**
     * 
     * get_stack
     * gets the textdomain stack
     *
     * @access static public
     * @return array
     */
    static public function get_stack() {
        return self::$textdomain_stack;
    }

    /**
     *
     * push_textdomain
     * short sets the textdomain while saving the current domain on a stack
     *
     * @access static public
     * @param string
     * @return string
     */
    static public function push_textdomain($module,$fromgettext=0) {
        // if we call push_textdomain from gettextinc.php we do not want to enter a recursion loop by calling the caller fnction _textdomain
        array_push(self::$textdomain_stack, $module);
        if($fromgettext==0) {
            _textdomain($module);
        }
        return self::textdomain($module);
    }

    /**
     *
     * pop_textdomain
     * short resets the textdomain to the previous value or leaves unchanged
     *
     * @access static public
     * @return string
     */
    static public function pop_textdomain() {
        // if array is empty then null is returned to textdomain() which is the desired affect
        $module =  array_pop(self::$textdomain_stack);
        _textdomain(end(self::$textdomain_stack));
        return $module;
    }

    /**
     *
     * we use the static array $tdhash to determine if we have bound a directory
     * to a domain yet. If not, we check to see if that i18n directory exists for the given
     * module and if so we do the binding. If not, we set it to 'amp' which is core's domain.
     * We also special case core's domain since currenlty IssabelPBX does that. (something that
     * should be changed one of these days...).
     */
    static private function _bindtextdomain($module) {
        global $dirname;
        if (isset(self::$tdhash[$module])) {
            return self::$tdhash[$module];
        } else {
            // We special case core and assume it is there since that is assumed throughout
            //
            if ($module == 'core') {
                _bindtextdomain('amp',"$dirname/i18n");
                _bind_textdomain_codeset('amp', 'utf8');
                _textdomain('amp');
                self::$tdhash[$module] = 'amp';
            }
            if (php_sapi_name() == 'cli') {
                $currentLocale = T_setlocale(LC_ALL, 0);
                if (is_dir("$dirname/modules/" . $module . '/i18n/' . $currentLocale)) {
                    $fullpath = _bindtextdomain($module, "$dirname/modules/". $module . '/i18n');
                    _bind_textdomain_codeset($module, 'utf8');
                    _textdomain($module);
                    self::$tdhash[$module] = $module;
                } else {
                    self::$tdhash[$module] = 'amp';
                }

            } else {

                if (isset($_COOKIE['lang']) && is_dir("$dirname/modules/" . $module . '/i18n/' . $_COOKIE['lang'])) {
                    $fullpath = _bindtextdomain($module, "$dirname/modules/". $module . '/i18n');
                    _bind_textdomain_codeset($module, 'utf8');
                    _textdomain($module);
                    self::$tdhash[$module] = $module;
                } else {
                    self::$tdhash[$module] = 'amp';
                }
            }
            return self::$tdhash[$module];
        }
    }
}

/*
echo modgettext::_("hello world","paging") . "\n";
echo modgettext::_("how are you","paging") . "\n";
echo modgettext::_("hello world","core") . "\n";
 */
