<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/18/15
 * Time: 2:27 PM
 */

Stripe::setApiKey('sk_test_1E1w55OxswP6V24fL5fqx8Z5');

class MenuController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $menus = Menu::all();
        return $menus;
    }

    public function getMenuByUser()
    {

        $id     = Authorizer::getResourceOwnerId();
        $user   = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
        $group  = Sentry::findGroupByName('Professionals');


        if ($user->inGroup($group)) {

            $customer = Stripe_Customer::retrieve($user->stripe_id);

            if ($customer->subscriptions->total_count > 0 ) {

                $menues = DB::table('users')
                            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                            ->leftJoin('group_menu', 'group_menu.group_id', '=', 'users_groups.group_id')
                            ->leftJoin('menu', 'menu.id', '=', 'group_menu.menu_id')
                            ->where('users.id', '=', $id)
                            ->orderBy('menu.order_number', 'asc')
                            ->select(array('menu.id','menu.tool_tips', 'menu.name', 'menu.icon', 'menu.view_name', 'menu.parent_id', 'menu.order_number'))
                            ->get();

            } else {

                $menues = DB::table('users')
                            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                            ->leftJoin('group_menu', 'group_menu.group_id', '=', 'users_groups.group_id')
                            ->leftJoin('menu', 'menu.id', '=', 'group_menu.menu_id')
                            ->where('users.id', '=', $id)
                            ->where('menu.view_name', '=', 'payment')
                            ->orderBy('menu.order_number', 'asc')
                            ->select(array('menu.id','menu.tool_tips', 'menu.name', 'menu.icon', 'menu.view_name', 'menu.parent_id', 'menu.order_number'))
                            ->get();
            }
        } else {

            $menues = DB::table('users')
                ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->leftJoin('group_menu', 'group_menu.group_id', '=', 'users_groups.group_id')
                ->leftJoin('menu', 'menu.id', '=', 'group_menu.menu_id')
                ->where('users.id', '=', $id)
                ->orderBy('menu.order_number', 'asc')
                ->select(array('menu.id','menu.tool_tips', 'menu.name', 'menu.icon', 'menu.view_name', 'menu.parent_id', 'menu.order_number'))
                ->get();
        }

        $arrayMenu = array();

        if (!empty($menues)) {

            foreach ($menues as $item) {

                $arraySubMenu = array();

                foreach ($menues as $subItem) {

                    if ($item->id == $subItem->parent_id) {

                        $arraySubMenu[] = array(

                            'name' => $subItem->name,
                            'icon' => $subItem->icon,
                            'view_name' => $subItem->view_name
                        );
                    }
                }



                if ($item->parent_id == 0 && count($arraySubMenu) > 0 && $item->id != 0) {

                    $arrayMenu[] = array(

                        'mainMenuName' => $item->name,
                        'subMenu' => $arraySubMenu,
                        'mainMenuIcon' => $item->icon,
//                        'mainMenuViewName' => $subItem->view_name
                    );

                } else if ($item->parent_id == 0 && count($arraySubMenu) == 0 && $item->id != 0) {

                    $arrayMenu[] = array(

                        'mainMenuName' => $item->name,
                        'subMenu' => [],
                        'mainMenuIcon' => $item->icon,
                        'mainMenuViewName' => $item->view_name,
                        'mainMenuToolTips' => $item->tool_tips
                    );

                }
            }
        }

        return $arrayMenu;

    }

    public function getMenuStatus($groupId, $menuId)
    {

        $menues = DB::table('group_menu')
            ->where('group_menu.group_id', '=', $groupId)
            ->where('group_menu.menu_id', '=', $menuId)
            ->select('group_menu.id')
            ->get();

        $available = (!empty($menues)) ? 1 : 0;

        $arrayStatus[] = array(
            'menuStatus' => $available
        );

        return $arrayStatus;

    }

    public function addGroupMenu()
    {

        $group_id = Input::get('group_id');
        $menu_id = Input::get('menu_id');

        $groups = Groups::find($group_id);
        $groups->menu()->attach($menu_id);

    }

    public function removeGroupMenu()
    {

        $group_id = Input::get('group_id');
        $menu_id = Input::get('menu_id');

        $groups = Groups::find($group_id);
        $groups->menu()->detach($menu_id);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            'name' => 'required',
            'icon' => 'required',
            'view_name' => 'required',
            'order_number' => 'required',
            'parent_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $menu = new Menu;


            $menu->name = Input::get('name');
            $menu->icon = Input::get('icon');
            $menu->view_name = Input::get('view_name');
            $menu->order_number = Input::get('order_number');
            $menu->parent_id = Input::get('parent_id');

            $menu->save();
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $symbols = Menu::find($id);
        return $symbols;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(

            'name' => 'required',
            'icon' => 'required',
            //'view_name' => 'required',
            'order_number' => 'required'
            //'parent_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $menu = Menu::find($id);


            $menu->name = Input::get('name');
            $menu->icon = Input::get('icon');
            $menu->view_name = Input::get('view_name');
            $menu->order_number = Input::get('order_number');
            $menu->parent_id = Input::get('parent_id');

            $menu->save();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
