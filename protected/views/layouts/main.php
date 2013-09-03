<?php 
//Top Bar javascript
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScript('foundation_topbar', '$(document).foundation(\'topbar\');', CClientScript::POS_READY);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="es" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- foundation CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation.min.css" media="screen, projection, print" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body class="antialiased">

        <div class="container" id="page">
            <div class="row">
                <div class="large-12 columns hide-for-small" id="header">
                    <div id="contact">contacto@monedademos.es</div>
                    <div>
                        <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
                    </div><!-- header -->
                    <div>
                    </div>
                </div>
            </div>
            <div id="mainmenu" class="row">
                <nav class="top-bar contain-to-grid ">
                    <ul class="title-area">
                        <!-- Title Area -->
                        <li class="name">
                            <h1><a href="http://monedademos.es">Demos</a></h1>
                        </li>
                        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
                    </ul>
                    <section class="top-bar-section">
                        <?php echo $this->renderPartial('/site/_menu'); ?>
                    </section>
                </nav>

            </div><!-- mainmenu -->

            <?php if (isset($this->breadcrumbs)): ?>
                <div class="row">
                    <div class="small-12 columns">
                        <?php
                        $this->widget('zii.widgets.CBreadcrumbs', array(
                            'links' => $this->breadcrumbs,
                            'separator' => ' ',
                        ));
                        ?><!-- breadcrumbs -->
                    </div>
                </div>
            <?php endif ?>
            <?php if (Yii::app()->user->hasFlash('error')) { ?>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="alert-box alert">
                            <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (Yii::app()->user->hasFlash('notice')) { ?>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="alert-box">
                            <?php echo Yii::app()->user->getFlash('notice'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (Yii::app()->user->hasFlash('success')) { ?>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="alert-box success">
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php echo $content; ?>

            <div class="clear"></div>
            <div class="row">
                <div id="footer" class="small-12 columns">
                    <div class="row">
                        <div class="small-12 large-3 columns">
                            <ul class="inline-list">
                                <li>
                                    <?php
                                    $language = new LanguageForm;
                                    $language->language = (isset(Yii::app()->request->cookies['language']) ? Yii::app()->request->cookies['language']->value : null);
                                    $language->url = Yii::app()->request->url;
                                    echo $this->renderPartial('//site/_languageForm', array('model' => $language));
                                    ?>
                                </li>
                            </ul> 
                        </div>

                        <?php if (isset(Yii::app()->user->roles) && Yii::app()->user->getId() && count(Yii::app()->user->roles) > 1) { ?>
                            <div class="small-12 large-3 columns">
                                <ul class="inline-list">
                                    <li>
                                        <?php
                                        $roles = new RolesForm;
                                        $roles->role = Yii::app()->user->getId();
                                        $roles->url = Yii::app()->request->url;
                                        echo $this->renderPartial('//site/_rolesForm', array('model' => $roles));
                                        ?>
                                    </li>
                                </ul> 
                            </div>
                        <?php } ?>

                        <div class="small-12 large-4 columns links">
                            <ul class="inline-list">
                                <li>
                                    <?php echo CHtml::link(Yii::t('app', 'Conditions'), array('/site/page', 'view' => 'conditions')); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link(Yii::t('app', 'About'), array('/site/page', 'view' => 'about')); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="small-12 large-2 columns links">
                            <div class="social_box">
                                <ul class="inline-list">
                                    <li class="social twitter"><a href="http://twitter.com/monedademos">@monedademos</a></li>
                                    <li class="social facebook"><a href="http://facebook.com/monedaDemos">Moneda Demos</a></li>
                                    <li class="social googleplus"><a href="https://plus.google.com/113493943316049288613" rel="publisher">Moneda Demos</a></li>
                                    <li class="social blog"><a href="http://blog.monedademos.es">Blog de DEMOS</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>        
                    Copyleft <?php echo date('Y'); ?> by monedademos.es<br/>
                    <?php echo Yii::powered(); ?>
                </div><!-- footer -->
            </div>
        </div><!-- page -->

    </body>
</html>
