Sin uso        
<?php
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
        $cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
        $cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
        $cs->registerScript('foundation_section', '$(document).foundation(\'section\');', CClientScript::POS_READY);
        ?>
        <div class="section-container auto" data-section>
            <div class="section">
                <p class="title"><a href="#panel1"><?php echo Yii::t('app', 'What\'s new') ?></a></p>
                <div class="content">
                    <?php echo $this->renderPartial('_socialRow', array('dataProviderMarketAd' => $dataProviderMarketAd)); ?>
                </div>
            </div>
            <div class="section">
                <p class="title"><a href="#panel2"><?php echo Yii::t('app', 'User statistics') ?></a></p>
                <div class="content">
                    <?php echo $this->renderPartial('_statisticsUser', array('dataProviderMarketAd' => $dataProviderMarketAd)); ?>
                </div>
            </div>
            <div class="section">
                <p class="title"><a href="#panel3"><?php echo Yii::t('app', 'Money statistics') ?></a></p>
                <div class="content">
                    <p>Content of section 3.</p>
                </div>
            </div>
        </div>
