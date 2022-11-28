<?php

namespace Crater\Traits;

trait GeneratesMenuTrait
{
    public function generateMenu($key, $user)
    {
        // dd($user);
        $menu = [];
        // dd(count(\Menu::get($key)->items->toArray()));
        foreach (\Menu::get($key)->items->toArray() as $data) {
            // dd($data);
            if ($user->checkAccess($data)) {
                $menu[] = [
                    'title' => $data->title,
                    'link' => $data->link->path['url'],
                    'icon' => $data->data['icon'],
                    'name' => $data->data['name'],
                    'group' => $data->data['group'],
                ];
            }
        }

        return $menu;
    }
}
