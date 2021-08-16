<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class menu extends Model {

	protected $table = "menu";
	protected $fillable = ['nombre', 'url', 'orden', 'icono', 'menu_id'];
	protected $guarded = ['id'];
	public $timestamps = false;


    public function roles() {
    	return $this->belongsToMany(Role::class, 'menu_rol', 'menu_id', 'rol_id' );
    }

    public static function getMenus() {
    	return Menu::orderBy('id')->get();
    }

    public static function getMenu($band=false) {
    	$menus = new Menu();
    	$items = $menus->getItemsMenu($band);
    	$menuAll = [];

    	return $items;
    }

    public function getItemsMenu($band) {
    	if ($band) {
    		return $this->whereHas('roles', function($query) {
    			$query->where('rol_id', Auth::User()->activeRole())->orderby('menu_id', 'asc');
    		})->orderby('menu_id', 'asc')
    			->get()
    			->toArray();
    	}
    }	

}
