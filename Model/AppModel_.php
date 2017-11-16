<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    
    public $field_list = array(
        'id' => 'Уникальный идентификатор'
       ,'created' => 'Дата создания'
       ,'modified' => 'Дата модификации'
    );
    
    public $params = array(
    );
    
    public function get_field_list()
    {
        return $this->field_list;
    }
    
    public function set_field_list($field_list)
    {
        $this->field_list = $field_list;
    }      
    
    public function get_params()
    {
        return $this->params;
    }
    
    public function set_params($params)
    {
        $this->params = $params;
    }    
   
    public function get_name()
    {
        return $this->name;
    }    
    
    public function export($filename = null,$conditions = null,$data = null)
    {              
        App::import('Vendor', 'PHPExcel/Classes/PHPExcel');
        if (!class_exists('PHPExcel')) 
        {
            throw new CakeException('Класс PHPExcel не найден!');
        }
        if(!isset($filename)) $filename = 'content';       
        $filename = substr($filename,0,56);
      
        //$zip = new ZipArchive();
        //$res = $zip->open('./files/' . $filename . '.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);        
        
        //if ($res === TRUE) 
        //{                        
            $xls = new PHPExcel();            
            $xls->removeSheetByIndex(0);
            
            $index = 0;
            $myWorkSheet = new PHPExcel_Worksheet($xls, $filename);
                        
            //self::unbindAll();
            $field_list = self::get_field_list();
            if(!isset($data))
                $data = self::find('all',array('fields' => array_keys($field_list),'conditions' => $conditions));
            
            $params = self::get_params();
            
            $myWorkSheet->setCellValueByColumnAndRow(0, 1, $filename); 
            if(isset($params['period']))
                $myWorkSheet->setCellValueByColumnAndRow(0, 2, 'За период: ' . $params['period']); 
            //$myWorkSheet->setCellValueByColumnAndRow(0, 3, 'Параметры:'); 
            
            $k_cell = 0;
            foreach ($field_list AS $field_name)
            {
                $myWorkSheet->setCellValueByColumnAndRow($k_cell++, 5, $field_name);    
            }
            
            foreach ($data AS $num_row => $row)
            {
                $k_cell = 0;
                foreach (array_keys($field_list) AS $field_id)
                {
                    $field_path = explode('.',$field_id);                    
                    if(count($field_path) === 1)
                        $value = $row[self::get_name()][$field_id];
                    else {
                        $other_model_name = current($field_path);
                        $other_field_id = end($field_path);
                        $value = $row[$other_model_name][$other_field_id];
                    }
                    $myWorkSheet->setCellValueByColumnAndRow($k_cell++, $num_row + 6, $value);
                }
            }
            
            $xls->addSheet($myWorkSheet, $index);
            $xls->setActiveSheetIndex($index++);

            $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');//new PHPExcel_Writer_Excel5($xls);
            
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $filename . '.xls"');            
            
            $objWriter->save('php://output');
            
            /*$objWriter->save('./files/' . $filename . '.xls');
            $xls->disconnectWorksheets();                
            $zip->addFile('./files/' . $filename . '.xls',mb_convert_encoding($filename . '.xls','cp866','UTF-8'));
            $zip->close();   
            
            $this->file_download('./files/' . $filename . '.zip');
            
            @unlink('./files/' . $filename . '.zip');
            @unlink('./files/' . $filename . '.xls'); */
        //}         
        die();        
    }
    
    public function unbindAll($params = array())
    {
        $unbind = array(); 
        foreach ($this->belongsTo as $model=>$info)
            $unbind['belongsTo'][] = $model;
        foreach ($this->hasOne as $model=>$info)
            $unbind['hasOne'][] = $model;
        foreach ($this->hasMany as $model=>$info)
            $unbind['hasMany'][] = $model;
        foreach ($this->hasAndBelongsToMany as $model=>$info)
            $unbind['hasAndBelongsToMany'][] = $model;
        
        parent::unbindModel($unbind);

        return true;
    }
    
    public function file_download($file, $filename = null) 
    {
        if (file_exists($file)) {
          if (ob_get_level()) {
            ob_end_clean();
          }
          // заставляем браузер показать окно сохранения файла
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          if(is_null($filename))header('Content-Disposition: attachment; filename=' . basename($file));
          else header('Content-Disposition: attachment; filename="' . $filename .'"');
          header('Content-Transfer-Encoding: binary');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          // читаем файл и отправляем его пользователю
          if ($fd = fopen($file, 'rb')) {
            while (!feof($fd)) {
              print fread($fd, 1024);
            }
            fclose($fd);
          }
          //exit;
        }
    }    
    
    public function get_list($query = array()) {
        //if(!isset($query['fields']))
            //$query = array_merge($query,array('fields' => array('id','name')));
        $result = $this->find('list', $query);
        $result[0] = 'Нет';
        ksort($result);
        return $result;
    }          
    
    public function not0($check) {
        if(current($check)==0) return false;
        else return true;
    }
    
    public function set_other_db($connection_name = null) {
        App::uses('ConnectionManager', 'Model');
        ConnectionManager::create($connection_name);
        $this->useDbConfig = $connection_name;
    }
    
    
}
