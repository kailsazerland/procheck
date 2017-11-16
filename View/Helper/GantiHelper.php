<?php

App::uses('Helper', 'View');

class GantiHelper extends Helper {
	public $helpers = array('Html','Js');
        
        public function DrawDiagramm ($name = 'Диаграмма', $data = null)
	{                        
            App::import('Vendor', 'gantti/Gantti');
            if (!class_exists('Gantti')) 
            {
		throw new CakeException('Vendor class Gantti not found!');
            }
            
            $diagramm = '';

            date_default_timezone_set('UTC');
            setlocale(LC_ALL, 'en_US');
            
            if(isset($data)&&!empty($data)) {
                $diagramm = new Gantti($data, array(
                  'title'      => $name,
                  'cellwidth'  => 25,
                  'cellheight' => 35,
                  'today'      => true
                ));

                $diagramm->convert_month_name = function ($name) {
                    return __d('cake',$name);;
                };
            }
            
            return $diagramm;
        }  
}


?>
