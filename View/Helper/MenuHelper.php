<?php

App::uses('Helper', 'View');

class MenuHelper extends Helper {
	public $helpers = array('Html');	

	public function MenuLink ($menuitem, $parameters = null)
	{
            
                if(!isset($menuitem['controller'])||!isset($menuitem['action'])) {$menuitem['controller'] = ''; $menuitem['action'] = '#';}
                else {$menuitem['controller'] = '/' . $menuitem['controller'] . '/';};
		if(!isset($menuitem['attributes']))
			$menuitem['attributes'] = "";
                if(isset($menuitem['hiden-name'])) $menuitem['name'] = $menuitem['hiden-name'];
		$menuitem['name'] = __($menuitem['name'], true);
                if(isset($menuitem['count'])) {
                        $menuitem['name'] = $menuitem['name'] . '<span class="badge">' . $menuitem['count'] . '</span>';
                }
                if(isset($menuitem['hiden-name'])) {
                        $menuitem['name'] = '<span class="hidden-xs">' . $menuitem['name'] . '</span>';
                }	
		if(isset($menuitem['icon'])) {
			$link =  $this->Html->link('<i class="'.$menuitem['icon'].'"></i> ' .$menuitem['name'], $menuitem['controller'] . $menuitem['action'], $parameters);
		} else {
			$link =  $this->Html->link($menuitem['name'], $menuitem['controller'] . $menuitem['action'], $parameters);
		}
			
		return($link);
	}

	public function DrawMenu ($navigation_walk,$options)
	{
		$navigation = '';
                if(isset($options['class'])) $navigation .= '<ul class="' . $options['class'] . '">';
                else $navigation .= '<ul>';
		foreach($navigation_walk AS $k => $nav)
		{
                    if(!isset($nav['disabled']))
                    {
                        $parameters = array('escape' => false);                        
                        if(isset($nav['class']))$parameters['class'] = $nav['class'];                        
                        if(!empty($nav['children'])) {
                            
                            $navigation .= '<li class="dropdown">' . $this->MenuLink($nav, $parameters);
                            
                            $navigation .= $this->DrawMenu($nav['children'],array('class' => 'dropdown-menu'));
                            
                            $navigation .= '</li>';
                            
			} else {
                            $navigation .= '<li>' . $this->MenuLink($nav, $parameters).'</li>';
			}
                    }

		}
		$navigation .= '</ul>';
		
		return $navigation;
	}

}
