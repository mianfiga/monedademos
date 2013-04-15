<div class="row">
    <div class="small-12 large-4 columns">
        <div class="list_top"><?php echo CHtml::link('Market', array('/market/index')); ?></div>
        <div class="list_body">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'id' => 'ads-grid',
                'dataProvider' => $dataProviderMarketAd,
                'itemView' => '//market/_viewList',
                'summaryText' => '',
            ));
            ?>
        </div><br/>
    </div>
    <div class="small-12 large-4 columns hide-for-small">
        <div class="list_top"><a href="http://blog.monedademos.es">Blog</a></div>
        <div id="hlrpsa" class="list_body">
            <script src="http://helplogger.googlecode.com/svn/trunk/recent-posts-with-snippets.js">
            </script>
            <script>
                var numposts = 10;var showpostdate = true;var showpostsummary = true;var numchars = 100;var standardstyling = true;
            </script>
            <script src="http://monedademos.blogspot.com/feeds/posts/default?orderby=published&amp;alt=json-in-script&amp;callback=showrecentposts">
            </script></div>
        <div id="rpdr" style="font-family: arial, sans-serif; font-size: 9px;">
            <a href="http://helplogger.blogspot.com/2012/04/recent-posts-widget-for-bloggerblogspot.html" target="_blank" title="Grab this Recent Posts Widget">Recent Posts Widget</a> by <a href="http://helplogger.blogspot.com" title="Recent Posts Widget">Helplogger</a></div>
        <noscript>Your browser does not support JavaScript!</noscript>
        <style type="text/css">
            #hlrpsa a {color: #0B3861; font-size: 13px;} #rpdr {background: url(http://3.bp.blogspot.com/-WM-QlPmHc6Y/T5wJV58qj9I/AAAAAAAACAk/1kULxdNyEyg/s1600/blogger.png) 0px 0px no-repeat; padding: 1px 0px 0px 19px; height:14px; margin: 5px 0px 0px 0px;line-height:14px;}
            #rpdr, #rpdr a {color:#808080;}
            #hlrpsa { color: #999999; font-size: 11px; border-bottom:1px #cccccc dotted; padding-bottom:10px;}
            .hlrps a {font-weight:bold; }
            .hlrpssumm {}
        </style><br/>
    </div>
    <div class="small-12 large-4 columns hide-for-small">
        <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/monedademos" data-widget-id="319760225722826753">Tweets by @monedademos</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <br/>
    </div>
</div>