<?php

App::uses('Helper', 'View');

class ActionPanelHelper extends Helper {
	public $helpers = array('Html','Js');
        
        public function DrawPanel ($actions, $id = 0)
	{
            $panel = '';
            foreach ($actions AS $action)
            {
                $options = array('escape' => false, 'class' => 'btn ajax-link');
                if(isset($action['no_ajax'])) $options['class'] = 'btn';
                if(isset($action['name'])) $options['data-tooltip'] = $action['name'];
                if(isset($action['name'])) $options['title'] = $action['name'];
                if(isset($action['class'])) $options['class'] .= ' ' . $action['class'];
                if(isset($action['target'])) $options['target'] = $action['target'];
                if(!isset($action['disabled']))
                {
                    $panel .= $this->Html->link((isset($action['icon']))?$this->Html->tag('i', '',array('class' => $action['icon'])):$action['name']
                                               ,'/' . $action['controller'] . '/' . $action['action'] . '/' . $id
                                               ,$options
                                               ,(isset($action['confrim']))?$action['confrim']:null);
                    //$panel .= '&nbsp';
                }
            }
            return $panel;
        }
        
        public function DrawJsPanel ($actions, $id = 0)
	{
            $panel = '';
            foreach ($actions AS $action)
            {
                $options = array('escape' => false, 'class' => '');
                if(isset($action['class'])) $options['class'] .= ' ' . $action['class'];
                if(isset($action['target'])) $options['target'] = $action['target'];
                if(!isset($action['disabled']))
                {
                    $panel .= $this->Js->link((isset($action['icon']))?$this->Html->tag('i', '',array('class' => $action['icon'])):$action['name']
                                               ,'/' . $action['controller'] . '/' . $action['action'] . '/' . $id
                                               ,$options);
                    //$panel .= '&nbsp';
                }
            }
            
            $panel .= $this->Js->writeBuffer();
            
            return $panel;
        }
        
}


?>
