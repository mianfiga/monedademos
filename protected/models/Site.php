<?php

/**
 * This is the model class for table "{{rule}}".
 *
 * The followings are the available columns in table '{{rule}}':
 * @property string $id
 * @property string $added
 * @property string $salary
 * @property integer $multiplier
 */
class Site {

    const TITLE = "MonedaDemos.es | ";

    public static function mobileCheck() {
        /* 		if(!isset(Yii::app()->session['mobile']) && isset(Yii::app()->request->cookies['mobile']))
          {
          Yii::app()->session['mobile'] = Yii::app()->request->cookies['mobile']->value;
          } */

        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if (!isset(Yii::app()->request->cookies['mobile']) && preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
//			Yii::app()->session['mobile'] = 1;
            $cookie = new CHttpCookie('mobile', 1);
            $cookie->expire = time() + 60 * 60 * 24 * 180;
            Yii::app()->request->cookies['mobile'] = $cookie;
//			Yii::app()->session['mobile'] = Yii::app()->request->cookies['mobile']->value;
        }
    }

    public static function languageCheck() {
        //Poner los idiomas disponibles en la configuración, o en la clase, poner en la cookie el idioma por si el usuario elije otro idioma
        if (isset(Yii::app()->request->cookies['language'])) {
            Yii::app()->setLanguage(Yii::app()->request->cookies['language']->value);
            return;
        } else {
            Yii::app()->setLanguage('es_es');
            $cookie = new CHttpCookie('language', Yii::app()->getLanguage());
            $cookie->expire = time() + 60 * 60 * 24 * 180;
            Yii::app()->request->cookies['language'] = $cookie;
        }


        /* 		$available_languages = array('en_us' => true,'es_es' => true, 'en' => 'en_us', 'es' => 'es_es');

          $arr = explode('_',Yii::app()->request->preferredLanguage);

          if($arr == null)
          {
          Yii::app()->setLanguage('es_es');
          }
          else
          {
          $locale = $arr[0].'_'. (isset($arr[1])?strtolower($arr[1]):$arr[0]);
          if(isset($available_languages[$locale]))
          Yii::app()->setLanguage($locale);
          elseif(isset($available_languages[$arr[0]]))
          Yii::app()->setLanguage($available_languages[ $arr[0] ]);
          else
          Yii::app()->setLanguage('es_es');
          }

          $cookie = new CHttpCookie('language', Yii::app()->getLanguage());
          $cookie->expire = time()+60*60*24*180;
          Yii::app()->request->cookies['language'] = $cookie; */
    }

    public static function languageList() {
        return array(
            'en_us' => 'english',
            'es_es' => 'español',
        );
    }
    public static function userChartData() {
        $data= array();
        $data['cols']= array();
        $data['cols'][]= array('id'=>'','label'=> Yii::t('app','Date'), 'type'=> 'date');
        $data['cols'][]= array('id'=>'','label'=> Yii::t('app','Active users'), 'type'=> 'number');
        $data['cols'][]= array('id'=>'','label'=> Yii::t('app','Total users'), 'type'=> 'number');
        
        $data['rows'] = array();
        $data['rows'][] = array('c' => array());
        
        
        
        
        return '{
  "cols": [
        {"id":"","label":"Topping","pattern":"","type":"string"},
        {"id":"","label":"Slices","pattern":"","type":"number"},
        {"id":"","label":"Slices2","pattern":"","type":"number"}
      ],
  "rows": [
        {"c":[{"v":"Mushrooms","f":null},{"v":3,"f":null},{"v":30,"f":null}]},
        {"c":[{"v":"Onions","f":null},{"v":1,"f":null},{"v":11,"f":null}]},
        {"c":[{"v":"Olives","f":null},{"v":1,"f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Zucchini","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null},{"v":2,"f":null}]}
      ]
}';
    }
}
