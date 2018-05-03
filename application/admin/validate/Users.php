<?php
namespace app\admin\validate;
use think\Validate;

class Users extends Validate{
	protected $rule = [
       'username'=>'length:1,30|unique:users|checkName:',
    ];
    
    protected $message = [
        'name.require'  =>  '用户名必须',
    ];
    
    protected $scene = [
        'add'   =>  ['username'=>'length:1,30'],
        'edit'  =>  ['username'=>'length:1,30'],
    ];  

    protected function checkName($value){
    	
    	return true;
    }



}
