<?php
class HttpRequest extends CHttpRequest
{
    public $noCsrfValidationRoutes=array();

    protected function normalizeRequest()
    {
            //attach event handlers for CSRFin the parent
        parent::normalizeRequest();
            //remove the event handler CSRF if this is a route we want skipped
        if($this->enableCsrfValidation)
        {
            $url=Yii::app()->getUrlManager()->parseUrl($this);
            foreach($this->noCsrfValidationRoutes as $route)
            {
                if(strpos($url,$route)===0)
                    Yii::app()->detachEventHandler('onBeginRequest',array($this,'validateCsrfToken'));
            }
        }
    }
}
