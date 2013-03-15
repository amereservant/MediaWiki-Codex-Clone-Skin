<?php
/**
 * Mediawiki Codex Skin
 *
 * This is a clone of the {@link http://codex.wordpress.org} theme and licensed under
 * the GPL License.
 *
 * All credit is due to the designers of {@link http://codex.wordpress.org} since this
 * is a copy of their work and I just cloned their design and made it into a Mediawiki skin
 * for personal use.
 * 
 * The best I can tell is that the design is part of the content of the WordPress.org Codex
 * and falls under their GPL licensing of that site's contents, so that same licensing
 * applies to this skin as well.
 *
 * @license     http://wordpress.org/about/gpl/ GPL
 */

if( !defined('MEDIAWIKI') ) die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinCodex extends SkinTemplate
{
	/** Using codex. */
	var $skinname  = 'codex';
	var $stylename = 'codex';
	var $template  = 'CodexTemplate';
	var $useHeadElement = true;
    
    
	function setupSkinUserCss( OutputPage $out )
	{
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );

		// Append to the default screen common & print styles...
		$out->addStyle( 'codex/main.css', 'screen' );
		$out->addStyle( 'codex/wp4.css', 'screen' );
		$out->addStyle( 'codex/iphone.css', 'only screen and (max-device-width: 480px)' );
	}
}

/**
 * Codex Template Class
 *
 * This class assembles the components of the Codex template and renders the output.
 * It is written in <b>PHP5 OO Standards</b>, therefore it's not compatible with PHP4.
 *
 * @ingroup Skins 
 */
class CodexTemplate extends QuickTemplate
{
   /**
    * Sidebox Group printf Format
    *
    * @var      string
    * @access   private
    */
	private $_sideboxf = '<h3>%s</h3><ul class="submenu">%s</ul>';

   /**
    * Sidebox List Item printf Format
    *
    * @var      string
    * @access   private
    */
    private $_lif = '<li id="%s"%s>%s</li>';

   /**
    * Sidebox AnchorLink printf Format
    *
    * @var      string
    * @access   private
    */
    private $_af = '<a href="%s" %s>%s</a>';
    
   /**
    * The Skin Object
    *
    * @var      object
    * @access   public
    */
	public $skin;
	
	/**
	 * Template Filter Callback
	 *
	 * Takes an associative array of data set from a SkinTemplate-based class, and a 
	 * wrapper for MediaWiki's localization database, and outputs a formatted page.
	 *
	 * The page's HTML layout is included by calling the file {@link body.php}, which
	 * is the primary theme file.
	 *
	 * @param   void
	 * return   string  Outputs the pages generated content
	 * @access  public
	 */
	public function execute()
	{
		global $wgRequest;
        
		$this->skin = $skin = $this->data['skin'];
		$action     = $wgRequest->getText( 'action' );

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
        
        // Load the head element for the page
		$this->html( 'headelement' );
		
		// Include the main content syntax
		require realpath(dirname(__FILE__) .'/codex/body.php');
	    
	    // Restore warnings
	    wfRestoreWarnings();
	}

   /**
	* Generate Search Box
	*
	* Creates the search form for the Codex theme.
	*
	* @param    void
	* @return   string  Prints the search form.
	* @access   public
	*/
	public function searchBox() 
	{
		$format  = '<form action="%s" id="head-search">'."\n\t%s\n\t";
		$format .= '<input type="submit" name="go" class="button" id="searchGoButton" value="Go"%s />'."\n";
		$format .= "</form>\n";
		
		printf( $format,
		    htmlspecialchars($this->data['wgScript']),
            Html::input( 'text',
			    isset($this->data['search']) && strlen($this->data['search']) > 0 ? $this->data['search'] : 'Search the Codex', 
			    'text',
			    array(
				    'maxlength' => 150,
				    'class'     => 'text',
				    'title'     => $this->skin->titleAttrib( 'search' ),
				    'accesskey' => $this->skin->accesskey( 'search' ),
				    'onfocus'   => "this.value=(this.value=='Search the Codex') ? '' : this.value;",
				    'onblur'    => "this.value=(this.value=='') ? 'Search the Codex' : this.value;",
			    )
            ),
            $this->skin->tooltipAndAccesskeyAttribs( 'search-go' )
        );
	}

   /**
    * ToolBox Sidebox
    *
    * Formats and prints the HTML syntax for the ToolBox links.
    *
    * @param    void
    * @return   string  Prints the HTML syntax that makes up the ToolBox links and section.
    * @access   public
	*/
	public function toolBox()
	{
	    $title = $this->translator->translate('Toolbox');
	    $li    = '';
	    
        if( $this->data['notspecialpage'] )
            $li .= sprintf($this->_lif, 
			    't-whatlinkshere', 
			    '', 
			    sprintf( $this->_af, htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href']), 
			             $this->skin->tooltipAndAccesskeyAttribs('t-whatlinkshere'), 
			             htmlspecialchars( $this->translator->translate('whatlinkshere') ) )
			);
		
		if( $this->data['feeds'] )
	    {
		    $alinks .= '';
		    foreach($this->data['feeds'] as $key => $feed)
		    {
			    $alinks .= sprintf( $this->_af, 
			        htmlspecialchars($feed['href']),
			        'id="'. Sanitizer::escapeId( "feed-$key" ) .'" rel="alternate" type="application/'. $key .'+xml" class="feedlink" '.
			        $this->skin->tooltipAndAccesskeyAttribs('feed-'.$key),
			        htmlspecialchars($feed['text']) .'&nbsp' );
		    }
		    
	        $li .= sprintf($this->_li_lif, $this->msg('feedlinks', TRUE), '', $alinks);
	    }

	    foreach( array('recentchangeslinked', 'trackbacklink', 'contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special )
	    {
		    if( is_array($this->data['nav_urls'][$special]) )
		        $li .= sprintf( $this->_lif,
		            't-'. $special,
		            '',
		            sprintf( $this->_af,
		                htmlspecialchars($this->data['nav_urls'][$special]['href']),
		                $this->skin->tooltipAndAccesskeyAttribs('t-'. $special),
		                htmlspecialchars( $this->translator->translate($special) ) )
		        );
	    }

	    if( strlen($this->data['nav_urls']['print']['href']) > 0 )
		    $li .= sprintf( $this->_lif,
		        't-print',
		        '',
		        sprintf( $this->_af,
		            htmlspecialchars($this->data['nav_urls']['print']['href']),
			        'rel="alternate" '. $this->skin->tooltipAndAccesskeyAttribs('t-print'),
			        htmlspecialchars( $this->translator->translate('printableversion') ) )
		    );
	    
	    if( strlen($this->data['nav_urls']['permalink']['href']) > 0 )
	        $li .= sprintf( $this->_lif,
			    't-permalink',
			    '',
			    sprintf( $this->_af,
			        htmlspecialchars($this->data['nav_urls']['permalink']['href']),
			        $this->skin->tooltipAndAccesskeyAttribs('t-permalink'),
			        htmlspecialchars( $this->translator->translate('permalink') ) )
			);
	    
	    else
			$li .= sprintf( $this->_lif,
			    't-ispermalink',
			    $this->skin->tooltip('t-ispermalink'),
			    htmlspecialchars( $this->translator->translate('permalink') ) 
			);
	    
	    printf($this->_sideboxf, $title, $li);
        wfRunHooks( 'CodexTemplateToolboxEnd', array( &$this ) );
	    wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
    }
   
   /**
    * Views Sidebox
    *
    * This method formats and prints the <i>Views</i> Sidebox menu items.
    *
    * @param    void
    * @return   string  Prints the formatted HTML syntax for the Views sidebox section.
    * @access   public
    */
    public function viewsBox()
    {
        $title = $this->translator->translate('Views');
    	$li    = '';
    	
    	foreach($this->data['content_actions'] as $key => $tab)
		{
			$id     = Sanitizer::escapeId("ca-{$key}");
			$class  = $tab['class'] ? ' class="'. htmlspecialchars($tab['class']) .'"' : '';
			$href   = htmlspecialchars($tab['href']);
			$tool   = in_array( $action, array('edit', 'submit') ) && 
			          in_array( $key, array('edit', 'watch', 'unwatch') ) ? 
			          $this->skin->tooltip( "ca-$key" ) : $this->skin->tooltipAndAccesskeyAttribs( "ca-$key" );
			$text   = htmlspecialchars($tab['text']);
			
			$alink  = sprintf($this->_af, $href, $tool, $text);
			$li     .= sprintf($this->_lif, $id, $class, $alink);
	    }
		
		printf($this->_sideboxf, $title, $li);
	}

	/*************************************************************************************************/
	public function languageBox()
	{
	    if( !$this->data['language_urls'] ) return;
        
        $links = '';
        foreach($this->data['language_urls'] as $langlink)
        { 
				$links .= sprintf( $this->_lif,
				    'lang-'. htmlspecialchars($langlink['text']),
				    ' class="'. htmlspecialchars($langlink['class']) .'"',
				    sprintf( $this->_af,
				        htmlspecialchars($langlink['href']),
				        '',
				        htmlspecialchars( $this->translator->translate($langlink['text']) )
				    )
				);
        }
        
        printf( $this->_sideboxf, $this->html('userlangattributes'), htmlspecialchars( $this->translator->translate('otherlanguages') ), $links );
	}

   /**
    * Create Custom Sidebox
    *
    * This is used to add a custom sidebox section.
    * 
    * @param    string          $bar    Unsure
    * @param    array|string    $cont   The content to add to the Sidebox.  It can be
    *                                   an array of items to itterate over or an already
    *                                   processed string of data to add directly.
    * @return   string                  Prints out the formatted Sidebox syntax.
    * @access   public
    * @todo     Try making this method serve the other Sidebox methods in this class
    *           by processing the data for them and minimizing the code in them if possible.
	*/
	public function customBox( $bar, $cont )
	{
        $links = '';
		$out   = wfMsg( $bar );
		$title = wfEmptyMsg($bar, $out) ? htmlspecialchars( $this->translator->translate($bar) ) : 
		    htmlspecialchars( $this->translator->translate($out) );
		
		if ( !is_array($cont) ) 
		{
		    printf( $this->_sideboxf, $title, $cont );
		    return;
		}
		
		foreach($cont as $key => $val)
		{ 
				$links .= sprintf( $this->_lif,
				    Sanitizer::escapeId($val['id']),
				    ($val['active'] ? ' class="active"':''),
				    sprintf( $this->_af,
				        htmlspecialchars($val['href']),
				        $this->skin->tooltipAndAccesskeyAttribs($val['id']),
				        htmlspecialchars( $this->translator->translate($val['text']) )
				    )
				);
		}
        
        printf($this->_sideboxf, $title, $links);
	}
} // end of class


