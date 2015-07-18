<?php
class Menu extends BaseModel  {
	
	protected $table = 'menu';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return " SELECT menu.*  FROM menu  ";
	}
	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public function group(){

        return $this->belongsToMany('Group');
    }

    public function groups(){

        return $this->belongsToMany('Groups');
    }

}
