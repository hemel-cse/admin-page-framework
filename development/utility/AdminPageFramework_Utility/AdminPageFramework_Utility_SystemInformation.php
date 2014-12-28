<?php
/**
 * Admin Page Framework
 * 
 * http://en.michaeluno.jp/admin-page-framework/
 * Copyright (c) 2013-2014 Michael Uno; Licensed MIT
 * 
 */
if ( ! class_exists( 'AdminPageFramework_Utility_SystemInformation' ) ) :
/**
 * Provides utility methods to return various system information.
 *
 * @since       3.3.6
 * @extends     AdminPageFramework_Utility_Array
 * @package     AdminPageFramework
 * @subpackage  Utility
 * @internal
 */
abstract class AdminPageFramework_Utility_SystemInformation extends AdminPageFramework_Utility_URL {
    
    /**
     * Caches the result of PHP information array.
     * @since       3.3.6
     */
    static private $_aPHPInfo;
    
    /**
     * Returns the PHP information as an array.
     * 
     * @since       3.3.6
     */
    static public function getPHPInfo() {

        if ( isset( self::$_aPHPInfo ) ) {
            return self::$_aPHPInfo;
        }
    
        ob_start();
        phpinfo( -1 );

        $_sOutput = preg_replace(
            array(
                '#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
                '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
                "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
                    .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
                '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
                "# +#",
                '#<tr>#',
                '#</tr>#'
            ),
            array(
                '$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
                '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
                "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
                '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
                '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" . '<tr><td>Zend Egg</td><td>$1</td></tr>',
                ' ',
                '%S%',
                '%E%'
            ),
            ob_get_clean()
        );

        $_aSections = explode( '<h2>', strip_tags( $_sOutput, '<h2><th><td>' ) );
        unset( $_aSections[ 0 ] );

        $_aOutput = array();
        foreach( $_aSections as $_sSection ) {
            $n = substr( $_sSection, 0, strpos( $_sSection, '</h2>' ) );
            preg_match_all(
                '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
                $_sSection, 
                $_aAskApache, 
                PREG_SET_ORDER
            );
            foreach( $_aAskApache as $m ) {
                $_aOutput[ $n ][ $m[ 1 ] ] = ( ! isset( $m[3] )|| $m[2] == $m[3] )
                    ? $m[2] 
                    : array_slice( $m, 2 );
            }
        }
        self::$_aPHPInfo = $_aOutput;
        return self::$_aPHPInfo;   
    
    }  
            
    /**
     * Returns an array of constants.
     * 
     * @since       3.3.6
     * @param       array|string      $asCategory      The category key names of the returning array.
     */
    static public function getDefinedConstants( $asCategories=null, $asRemovingCategories=null ) {
        
        $_aCategories           = is_array( $asCategories ) ? $asCategories : array( $asCategories );
        $_aCategories           = array_filter( $_aCategories );
        $_aRemovingCategories   = is_array( $asRemovingCategories ) ? $asRemovingCategories : array( $asRemovingCategories );
        $_aRemovingCategories   = array_filter( $_aRemovingCategories );
        $_aConstants            = get_defined_constants( true );
        
        if ( empty( $_aCategories ) ) {
            return array_diff( $_aConstants, $_aRemovingCategories );
            return $_aConstants;
        }

        return array_diff( 
            array_intersect_key( $_aConstants, array_flip( $_aCategories ) ), 
            $_aRemovingCategories 
        );
                
    }        
        
    /**
     * Returns PHP error log path.
     * 
     * @since       3.3.6
     * @return      array|string        The error log path. It can be multiple. If so an array holding them will be returned.
     */
    static public function getPHPErrorLogPath() {
        
        $_aPHPInfo = self::getPHPInfo();
        return isset( $_aPHPInfo['PHP Core']['error_log'] ) 
            ? $_aPHPInfo['PHP Core']['error_log']
            : '';
        
    }
    
    /**
     * Returns a PHP error log.
     * @since       3.3.6
     */
    static public function getPHPErrorLog( $iLines=1 ) {
        
        $_asPHPErrorLogPath = self::getPHPErrorLogPath();
        $_aPHPErrorLogPath  = is_array( $_asPHPErrorLogPath ) ? $_asPHPErrorLogPath : array( $_asPHPErrorLogPath );
        $_aPHPErrorLogPath  = array_values( $_aPHPErrorLogPath );
        $_sPath             = array_shift( $_aPHPErrorLogPath );        
        return file_exists( $_sPath ) 
            ? trim( 
                implode( 
                    "", 
                    array_slice( 
                        file( $_sPath ), 
                        - $iLines 
                    ) 
                ) 
            )
            : '';
        
    }        
        
}
endif;