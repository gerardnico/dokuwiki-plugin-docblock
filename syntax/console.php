<?php
/**
 * Plugin Webcode: Show webcode (Css, HTML) in a iframe
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Nicolas GERARD
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 * 
 * Format
 * 
 * syntax_plugin_PluginName_PluginComponent
 */
class syntax_plugin_docblock_console extends DokuWiki_Syntax_Plugin
{

    const PLUGIN_NAME = 'docblock';
    const NODE_NAME = 'console'; // sames as $this->getPluginComponent
    const PLUGIN_COMPONENT_NAME = self::NODE_NAME;

    /*
     * What is the type of this plugin ?
     * This a plugin categorization
     * This is only important for other plugin
     * See @getAllowedTypes
     */
    public function getType()
    {
        return 'formatting';
    }


    // Sort order in which the plugin are applied
    public function getSort()
    {
        return 168;
    }
    
    /**
     * 
     * @return type
     * The plugin type that are allowed inside
     * this node (ie nested)
     * Otherwise the node that are in the matched content are not processed
     */
    function getAllowedTypes() { 
        return array(); 
        
    }
    
    /**
     * Handle the node 
     * @return string
     * See
     * https://www.dokuwiki.org/devel:syntax_plugins#ptype
     */
    function getPType(){ return 'block';}

    // This where the addEntryPattern must bed defined
    public function connectTo($mode)
    {
        // This define the DOKU_LEXER_ENTER state
        $this->Lexer->addEntryPattern('<'.self::NODE_NAME.'.*?>(?=.*?</'.self::NODE_NAME.'>)', $mode, 'plugin_'.self::PLUGIN_NAME.'_'.self::PLUGIN_COMPONENT_NAME);
    }

    public function postConnect()
    {
        // We define the DOKU_LEXER_EXIT state
        $this->Lexer->addExitPattern('</'.self::NODE_NAME.'>', 'plugin_'.self::PLUGIN_NAME.'_'.self::PLUGIN_COMPONENT_NAME);
    }


    /**
     * Handle the match
     * You get the match for each pattern in the $match variable
     * $state says if it's an entry, exit or match pattern
     *
     * This is an instruction block and is cached apart from the rendering output
     * There is two caches levels
     * This cache may be suppressed with the url parameters ?purge=true
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {

            case DOKU_LEXER_ENTER :

                break;

            case DOKU_LEXER_UNMATCHED:
                return array($state,$match);
                break;

            case DOKU_LEXER_EXIT:

                break;

        }

    }

    /**
     * Create output
     * The rendering process
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        // The $data variable comes from the handle() function
        //
        // $mode = 'xhtml' means that we output html
        // There is other mode such as metadata, odt 
        if ($mode == 'xhtml') {

            $state = $data[0];
            // No Unmatched because it's handled in the handle function
            switch ($state) {

                case DOKU_LEXER_UNMATCHED:
                    $text=$data[1];
                    /**
                     * @var Doku_Renderer_xhtml
                     * See code in Doku_Renderer_xhtml
                     * with lang, filename, highlight,... parameters
                     */
                    $renderer->code($text);
                    break;
                
            }
            return true;
        }
        return false;
    }

}
