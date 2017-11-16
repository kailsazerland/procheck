<?php 
class User extends AppModel 
{
    public $displayField = 'username';
    public $name = 'User';
    public $validate = array(
        'username' => array('notBlank'),
        'password' => array('alphaNumeric','notBlank'),
    );
    public $hasAndBelongsToMany = array(
        'Group' => array('className' => 'Group',
                    'joinTable' => 'groups_users',
                    'foreignKey' => 'user_id',
                    'associationForeignKey' => 'group_id',
                    'unique' => true
        )
    );
    
    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password']))
        {
	    if(!$this->find('first',array('conditions' => array('password' => $this->data[$this->alias]['password']))))
                $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

    
}	 