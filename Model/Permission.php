<?php 
class Permission extends AppModel 
{
    
    public $hasAndBelongsToMany = array(
            'Group' => array('className' => 'Group',
                        'joinTable' => 'groups_permissions',
                        'foreignKey' => 'permission_id',
                        'associationForeignKey' => 'group_id',
                        'unique' => true
            )
    );
    
    /*public $hasMany = array('PermissionValues' => array('className' => 'Permission'
                                                        ,'foreignKey'    => 'parent_id'
                                                        ,'dependent' => true
                                                        ,'order' => array('created ASC')
                                    ));
    
    public $belongsTo = array('PermissionParrent' => array('className' => 'Permission'
                                                        ,'foreignKey'    => 'parent_id'
                                    ));*/         

}	 