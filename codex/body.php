<div id="header">
	<div class="wrapper">
	    <h1><a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>" title="<?php $this->html('title'); ?>" /><?php $this->html('title'); ?></a></h1>
	    <?php $this->searchBox(); ?>
        <ul>
			<li><a href="#" title="">Home</a></li>
			<li><a href="#" title="">Showcase</a></li>
			<li><a href="#" title="">Extend</a>
                <ul class="nav-submenu">
				    <li><a href="#" title="">Plugins</a></li>
				    <li><a href="#" title="">Themes</a></li>
			    </ul>
			</li>
			<li><a href="#" title="">About</a></li>
			<li><a class="current" href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>" title="Documentation, tutorials, best practices.">Docs</a></li>
			<li><a href="#" title="">Blog</a></li>

			<li><a href="#" title="">Forums</a></li>
			<li><a href="#" title="">Hosting</a></li>
			<li id="download"><a href="#" title="Get it. Got it? Good.">Download</a></li>
		</ul>
	</div>
</div><!-- #header -->
<div id="headline">
    <div class="wrapper">
        <h2>Codex</h2>
        <div class="portlet" id="p-personal">
		    <p class="login">Codex tools:
		    <?php foreach($this->data['personal_urls'] as $key => $item) {
		        $linkf = '<a href="%s" class="%s" %s>%s</a>';
		        $class = $item['class'] .' '. ($item['active'] ? 'active':'');
		        printf( $linkf, htmlspecialchars($item['href']), $class, $skin->tooltipAndAccesskeyAttribs('pt-'.$key), htmlspecialchars($item['text']) );
		    } ?>
		    </p>
	    </div>
	</div>
</div><!-- #headline -->

<div id="pagebody" <?php $this->html("specialpageattributes"); ?>>
	<div class="wrapper">
	<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice'); ?></div><?php } ?>
        <div id="bodyContent" class="col-10">
		    <h2 class="pagetitle"><?php $this->html('title'); ?></h2>
		    <!-- start content -->
		    <?php $this->html('bodytext'); ?>
		    <?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
		    <!-- end content -->
		    <?php if($this->data['dataAfterContent']) { $this->html('dataAfterContent'); } ?>
	    </div><!-- #bodyContent -->
	    <div class="col-2">
	        <?php $this->viewsBox(); ?>
            <?php $this->toolBox(); ?>
            <?php $this->languageBox(); ?>
	    </div>
    </div>
</div><!-- #pagebody -->
<?php
/*
		$sidebar = $this->data['sidebar'];
		if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
		if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;
		foreach ($sidebar as $boxName => $cont) {
			if ( $boxName == 'TOOLBOX' ) {
				$this->toolbox();
			} elseif ( $boxName == 'LANGUAGES' ) {
				$this->languageBox();
			} else {
				$this->customBox( $boxName, $cont );
			}
		}
*/
?>

<div id="footer"<?php $this->html('userlangattributes') ?>>
    <div class="wrapper">
<?php 
        // Generate additional footer links
		$footerlinks = array(
			'lastmod', 'viewcount', 'numberofwatchingusers', 'credits', 'copyright',
			'privacy', 'about', 'disclaimer', 'tagline',
		);
		$validFooterLinks = array();
		
		foreach( $footerlinks as $aLink ) {
			if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
				$validFooterLinks[] = $aLink;
			}
		}
		if ( count( $validFooterLinks ) > 0 ) { ?>
		<p>
        <?php
        $i = 1;
        $c = count($validFooterLinks);
		foreach( $validFooterLinks as $aLink )
		{
		    if( isset( $this->data[$aLink] ) && $this->data[$aLink] )
		    {
                $this->html($aLink);
                echo $i < $c ? ' | ':'';
            }
            $i++;
        }
?>      </p>
<?php   } ?>
        <h6>Code is Poetry</h6>
    </div>
</div><!-- #footer -->
<?php
$this->html('bottomscripts'); /* JS call to runBodyOnloadHook */
$this->html('reporttime');

if( $this->data['debug'] ): ?>
<!-- Debug output:
<?php $this->text( 'debug' ); ?>

-->
<?php endif; ?>
</body>
</html>
