<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $helpers = array(
	'Html',
	'Js',
	'Form' => array('className' => 'Bs3Helpers.Bs3Form'),
	'Session',
        'Menu',
	'ActionPanel',
        'Admin'
    );

    public $components = array(
        'Session',
        'Auth' => array('loginRedirect' => '/'
                        ,'logoutRedirect' => '/'
                        ,'unauthorizedRedirect' => array('controller' => 'users', 'action' => 'login')
                        ,'authorize' => array('Controller')),
    );


    public $main_menu = array();
    public $panel_menu = array();
    public $action_menu = array();
    public $search_conditions = array();
    public $filter_box_data = array();

    public function beforeFilter() {

    }

    public function beforeRender() {
        $main_menu = $this->get_main_menu();
        $action_menu = $this->get_action_menu();
        if($this->Auth->isAuthorized()) {
            $main_menu = $this->checkMenuPermission($main_menu);
            $action_menu = $this->checkMenuPermission($action_menu);
        }
        $this->set('main_menu',$main_menu);
        $this->set('action_menu',$action_menu);
        $this->set('Auth',$this->Auth->user());
    }

    public function ajaxRender($view = null, $source = null, $triger = null) {
        if($this->request->isAjax()) {
            $this->layout = false;
            $this->autoRender = false;
            $response = null;
            if($view != 'bodyNotModify') {
                $response = $this->render($view);
                $response = $response->body();
            }
            $message = $this->render('/Elements/message');
            $message = $message->body();
            echo json_encode(array('body' => $response, 'source' => $source, 'message' => $message, 'triger' => $triger ));
            die();
        }
    }

    function paginate($object = null, $scope = array(), $whitelist = array())
    {
        $sessionKey = 'paginationOptions.' . strtolower($this->name) . '-' . $this->action . '.' . strtolower($object);
        try
        {
            if(!isset($this->passedArgs['pag']))
            {
                $this->passedArgs['pag'] = true;
                $paginationOptions = (string)$this->Session->read($sessionKey);
                if (!empty($paginationOptions))
                    $paginationOptions = unserialize($paginationOptions);
                else $paginationOptions = array();
                $paginationOptions = array_merge(array('direction' => null, 'limit' => null, 'page' => null, 'sort' => null), $paginationOptions);
                $this->paginate[$object] = array_merge($paginationOptions,$this->paginate[$object]);
                return parent::paginate($object, $scope, $whitelist);
            }
            else {
                    $this->paginate[$object][0] = 'all';
                    $this->Session->write($sessionKey, serialize(array_merge($this->paginate[$object],$this->passedArgs)));
                    return parent::paginate($object, $scope, $whitelist);
            }
        }
        catch(NotFoundException $e)
        {
            extract($this->paginate[$object]);
            $count = $this->{$object}->find('count', compact('conditions'));
            $pageCount = intval(ceil($count / $limit));
            $this->redirect(array('page' => $pageCount));
        }
    }



    /**
     * isAuthorized
     *
     * Вызывается компонентом Auth для проверки доступа к элементу
     *
     * @return true if authorised/false if not authorized
     * @access public
     */
    public function isAuthorized($user) {
        return $this->__permitted($this->name,$this->action);
    }

    /**
     * __permitted
     *
     * Вспомогательная функция, которая производит проверку прав пользователя
     * описанных в форме $controllerName:$actionName
     * @return
     * @param $controllerName Object
     * @param $actionName Object
     */
    private function __permitted($controllerName,$actionName)
    {
//return true;
        //if($this->Auth->user() === null) return false;
        //Имя контроллеря указываем в нижнем регистре
        $controllerName = strtolower($controllerName);
        $actionName = strtolower($actionName);
        //Если в сессии права не были закешированны
//        if(!$this->Session->check('Permissions')){
        //...то подготовим массив для сохранения
        $permissions = array();
        //у всех есть право выйти из системы
        $permissions[] = array('action' => 'users:logout');
        //Импортируем модель пользователя, чтобы получить права
        App::import('Model', 'User');
        $User = new User;

        //Получаем текущего пользователя и его группу
        $this_user = $User->find('first',array('conditions' => array('User.id'=>$this->Auth->user('id'))));

        foreach($this_user['Group']as $this_groups){
                $this_groups = $User->Group->find('first',array('conditions' => array('Group.id' => $this_groups['id'])));
                $permissions = array_merge($permissions,$this_groups['Permission']);
        }

            //Записываем права в сессию
//            $this->Session->write('Permissions',$permissions);
//        }else{
            //...видимо права закешированны, загружаем из сессии
//            $permissions = $this->Session->read('Permissions');
//        }
//
        //Ищем среди прав соотвествующее текущему
        $return = false;
        foreach($permissions as $permission){
            $permission['action'] = trim($permission['action']);
            if(substr($permission['action'],0,1) == '^'){
                $permission['action'] = substr($permission['action'],1);
                if($permission['action'] == '*'){
                    return false;
                }
                if($permission['action'] == '*:'.$actionName){
                    return false;
                }
                if($permission['action'] == $controllerName.':*'){
                    return false;
                }
                if($permission['action'] == $controllerName.':'.$actionName){
                    return false;
                }
            }

            if($permission['action'] == '*'){
                $return = true;//Найдено право СуперАдмина
            }
            if($permission['action'] == '*:'.$actionName){
                $return = true;//Разрешаются определённое действие во всех контроллерах
            }
            if($permission['action'] == $controllerName.':*'){
                $return = true;//Разрешаются все действия в данном контроллере
            }
            if($permission['action'] == $controllerName.':'.$actionName){
                $return = true;//Найдено определённое действие
            }
        }
//var_dump($controllerName,$actionName,$permissions);die();
        return $return;
    }

    public function checkPermission($controllerName,$actionName)
    {
        return $this->__permitted($controllerName,$actionName);
    }

    public function checkMenuPermission($menu)
    {
        foreach($menu AS $k => $action)
        {
            if(isset($action['permission'])) {
                $result = explode(':', $action['permission']);
                $action['controller'] = $result[0];
                $action['action'] = $result[1];
            }
            $action['controller'] = str_replace("_", "", $action['controller']);
            if($this->__permitted($action['controller'],$action['action']) === false) {$menu[$k]['disabled'] = true;}
            if(isset($action['children'])) $menu[$k]['children'] = $this->checkMenuPermission($action['children']);
        }
        return $menu;
    }

    public function get_main_menu() {
        $this->loadModel('CashFlow');
        $this->loadModel('Aunpaid');
        $this->loadModel('Pay');
        $count_tmp = $this->CashFlow->find('count',array('conditions' => array('CashFlow.type_id' => 0)));
        $count_undefined_in = $this->CashFlow->find('count',array('conditions' => array('CashFlow.article_id' => 0, 'CashFlow.type_id <>' => 0, 'Type.alias' => 'in')));
        $count_undefined_out = $this->CashFlow->find('count',array('conditions' => array('CashFlow.article_id' => 0, 'CashFlow.type_id <>' => 0, 'Type.alias' => 'out')));
        $count_undefined = $count_undefined_in + $count_undefined_out;



        $count_au = $this->Aunpaid->find('count', array('conditions' => array('Aunpaid.paid' => 0, 'Aunpaid.send_utver' => 0,)));
        $count_au_t = $this->Aunpaid->find('count', array('conditions' => array('Aunpaid.send_utver' => 1)));
        $count_au_o = $this->Aunpaid->find('count', array('conditions' => array('Aunpaid.paid' => 1)));

        $count_pay = $this->Pay->find('count', array('conditions' => array('Pay.send_utver' => 0,'Pay.arch' => 0)));
        $count_pay_u = $this->Pay->find('count', array('conditions' => array('Pay.send_utver' => 1,'Pay.arch' => 0)));
        $count_pay_a = $this->Pay->find('count', array('conditions' => array('Pay.arch' => 1)));


        $menu = array(

            array('hiden-name' => 'Деньги' , 'icon' => 'icon-led-coins', 'permission' => 'cash_flow:menu' , 'class' => 'dropdown-toggle' ,'children' => array(
                array('name' => 'Доходы', 'icon' => 'icon-led-arrow-right' , 'controller' => 'cash_flow' , 'action' => 'cash_in', 'class' => 'ajax-link'),
                array('name' => 'Расходы', 'icon' => 'icon-led-arrow-left' , 'controller' => 'cash_flow' , 'action' => 'cash_out', 'class' => 'ajax-link'),
                array('name' => 'Невыясненные <span class="badge">' . $count_tmp . '</span>', 'icon' => 'icon-led-arrow-undo' , 'controller' => 'cash_flow' , 'action' => 'cash_tmp', 'class' => 'ajax-link tmp-count'),
                array('hiden-name' => 'Непроведенные <span class="badge">' . $count_undefined . '</span>' , 'icon' => 'icon-led-arrow-divide', 'permission' => 'cash_flow:menu' , 'class' => 'dropdown-toggle' ,'children' => array(
                    array('name' => 'Поступления <span class="badge">' . $count_undefined_in . '</span>' , 'icon' => 'icon-led-arrow-right' , 'controller' => 'cash_flow' , 'action' => 'cash_undefined/in', 'class' => 'ajax-link'),
                    array('name' => 'Платежи <span class="badge">' . $count_undefined_out . '</span>' , 'icon' => 'icon-led-arrow-left' , 'controller' => 'cash_flow' , 'action' => 'cash_undefined/out', 'class' => 'ajax-link'),
                )),
                array('name' => 'Импорт', 'icon' => 'icon-led-page-white-get' , 'controller' => 'cash_flow' , 'action' => 'import', 'class' => 'ajax-link'),
            )),
            array('hiden-name' => 'Бюджетирование' , 'icon' => 'icon-led-calculator', 'permission' => 'budgets:menu', 'class' => 'dropdown-toggle' ,'children' => array(
                array('name' => 'План/факт анализ', 'icon' => 'icon-led-doc-convert', 'permission' => 'budgets:menu', 'class' => 'dropdown-toggle' , 'children' => array(
                    array('name' => 'Бюджет доходов', 'icon' => 'icon-led-arrow-right' , 'controller' => 'budgets' , 'action' => 'view_budget_in', 'class' => 'ajax-link'),
                    array('name' => 'Бюджет расходов', 'icon' => 'icon-led-arrow-left' , 'controller' => 'budgets' , 'action' => 'view_budget_out', 'class' => 'ajax-link'),
                )),
                array('name' => 'Платежный календарь', 'icon' => 'icon-led-calendar-1' , 'controller' => 'calendars' , 'action' => 'view_calenars', 'class' => 'ajax-link'),
            )),
            array('hiden-name' => 'Заявки' , 'icon' => 'icon-led-clipboard-text' ,'permission' => 'orders:menu', 'class' => 'dropdown-toggle' , 'children' => array(
					array('name' => 'Резерв', 'icon' => 'icon-led-application-view-list' ,'controller' => 'orders' , 'action' => 'view', 'class' => 'ajax-link'),
					array('name' => 'Оплата', 'icon' => 'icon-led-doc-convert', 'permission' => 'aunpaids:menu', 'class' => 'dropdown-toggle' , 'children' => array(
						array('name' => 'Неоплаченные <span class="badge">' . $count_au . '</span>', 'icon' => 'icon-led-clipboard-text' , 'controller' => 'aunpaids' , 'action' => 'view', 'class' => 'ajax-link'),
						array('name' => 'Оплаченные <span class="badge">' . $count_au_o . '</span>', 'icon' => 'icon-led-clipboard-text' , 'controller' => 'aunpaids' , 'action' => 'paid', 'class' => 'ajax-link'),
						array('name' => 'На утверждение <span class="badge">' . $count_au_t . '</span>', 'icon' => 'icon-led-clipboard-text' , 'controller' => 'aunpaids' , 'action' => 'approval', 'class' => 'ajax-link'),
					)),
				)
			),
            array('hiden-name' => 'Платежи' , 'icon' => 'icon-led-clipboard-text' ,'permission' => 'pays:menu', 'class' => 'dropdown-toggle' , 'children' => array(
					array('name' => 'Новые <span class="badge">' . $count_pay . '</span>', 'icon' => 'icon-led-application-view-list' ,'controller' => 'pays' , 'action' => 'view', 'class' => 'ajax-link'),
					array('name' => 'Архив <span class="badge">' . $count_pay_a . '</span>', 'icon' => 'icon-led-application-view-list' ,'controller' => 'pays' , 'action' => 'arh', 'class' => 'ajax-link'),
					array('name' => 'На утверждение <span class="badge">' . $count_pay_u . '</span>', 'icon' => 'icon-led-application-view-list' ,'controller' => 'pays' , 'action' => 'aun', 'class' => 'ajax-link'),

				)
			),
            array('hiden-name' => 'Справочники' , 'icon' => 'icon-led-layout-split-vertical', 'permission' => 'reference:menu', 'class' => 'dropdown-toggle' ,'children' => array(
                array('name' => 'Контрагенты', 'icon' => 'icon-led-application-view-list' , 'controller' => 'contragents' , 'action' => 'group_view', 'class' => 'ajax-link'),
                array('name' => 'Статьи доходов и расходов', 'icon' => 'icon-led-application-view-list' , 'controller' => 'articles' , 'action' => 'type_view', 'class' => 'ajax-link'),
                array('name' => 'Номенклатура (товары и услуги)', 'icon' => 'icon-led-application-view-list' , 'controller' => 'nomenclatures' , 'action' => 'type_view', 'class' => 'ajax-link'),
                array('name' => 'Способы оплаты', 'icon' => 'icon-led-application-view-list' , 'controller' => 'pay_types' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Источники средств', 'icon' => 'icon-led-application-view-list' , 'controller' => 'sources' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Платежные поручения', 'icon' => 'icon-led-application-view-list' ,'controller' => 'p_views', 'action' => 'group_view', 'class' => 'ajax-link'),
                array('name' => 'Организации', 'icon' => 'icon-led-application-view-list' , 'controller' => 'orgs' , 'action' => 'view', 'class' => 'ajax-link'),
                //array('name' => 'Типы документов', 'icon' => 'icon-led-application-view-list' , 'controller' => 'processings' , 'action' => 'view'),
                array('name' => 'Центры финансовой ответственности (ЦФО)', 'icon' => 'icon-led-application-view-list' , 'controller' => 'otdels' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'НДС' , 'controller' => 'nalogs', 'icon' => 'icon-led-application-view-list' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Платежный календарь (шаблоны)', 'icon' => 'icon-led-application-view-list' , 'controller' => 'template_calendars' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Классификатор платежей', 'icon' => 'icon-led-application-view-list' , 'controller' => 'classificators' , 'action' => 'view', 'class' => 'ajax-link'),
            )),
            array('hiden-name' => 'Отчеты' , 'icon' => 'icon-led-report', 'permission' => 'reports:menu', 'controller' => 'reports' , 'action' => 'group_view', 'class' => 'ajax-link'),
            array('hiden-name' => 'Настройки' , 'icon' => 'icon-led-hammer-screwdriver', 'permission' => 'options:menu', 'class' => 'dropdown-toggle' ,'children' => array(
                array('name' => 'Настройки прав доступа', 'icon' => 'icon-led-key' , 'controller' => 'permissions' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Группы прав', 'icon' => 'icon-led-group' , 'controller' => 'groups' , 'action' => 'view', 'class' => 'ajax-link'),
                array('name' => 'Пользователи', 'icon' => 'icon-led-user-business' , 'controller' => 'users' , 'action' => 'view', 'class' => 'ajax-link'),
            )),
        );
        return array_merge($this->main_menu,$menu);
    }

    public function set_main_menu($menu = array()) {
        $this->main_menu = $menu;
    }

    public function get_action_menu() {
        $menu = array();
        return array_merge($this->action_menu,$menu);
    }

    public function set_action_menu($menu = array()) {
        $this->action_menu = $menu;
    }

    public function search($action = null,$agr_1 = null,$agr_2 = null) {
        if(method_exists($this,'get_search_conditions_for_filter_box')&&isset($this->data['FilterBox'])) {
            $this->Session->write('search_text',array('text' => ''
                                   ,'conditions' => (isset($this->data['reset']))?array():$this->get_search_conditions_for_filter_box($this->data['FilterBox']),
                                    'filter_box_data' => (isset($this->data['reset']))?array():$this->data['FilterBox'])); //сохраняем для возврата назад в FilterBox
        } else if(method_exists($this,'get_search_conditions'))
            $this->Session->write('search_text',array('text' => $this->data['Search']['text']
                                                    ,'conditions' => $this->get_search_conditions(str_replace('"','\"',$this->data['Search']['text']))
                                                    ,'filter_box_data' => array()));
        $this->redirect('search_view/' . $action . '/' . $agr_1 . '/' . $agr_2);
    }

    public function search_view($action = null,$agr_1 = null,$agr_2 = null) {
        $this->search_conditions = $this->Session->read('search_text');
        $this->request->params['search_text'] = $this->search_conditions['text'];
        $this->filter_box_data = $this->search_conditions['filter_box_data'];
        $this->search_conditions = $this->search_conditions['conditions'];
        $this->request->params['search_view'] = $action;
        $this->$action($agr_1,$agr_2);
        $this->render($action);
    }

    public function validate_data($model,$data = array()) {
        if(isset($model)) {
            $this->loadModel($model);
            $this->$model->set($data);
            if($this->$model->validates()) {
                return true;
            } else {
                if($this->request->isAjax()) {
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $errors = $this->$model->validationErrors;
                    $data = array();
                    foreach ($errors as $key => $error) {
                       $data[] = array(
                           'fieldname' => 'data[' . $model . ']['. $key . ']'
                          ,'message' => current($error)
                       );
                    }
                    echo json_encode(array('validation' => $data));
                    die();
                }
                return false;
            }
        }
        return false;
    }

}
