<?php


namespace App\Repositories;

use App\Helper\ConstantHelper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;

class MenuItemRepository extends Repository
{
    protected  $preferredLanguage;

    public function __construct(MenuItems $menuItems)
    {
        $this->model =  $menuItems;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
    }

    public function items($id)
    {
        $items = $this->model->where('menu_id', $id)->orderBy('display_order', 'asc')->where('language_id', $this->preferredLanguage)->get();
        $response = [];
        if ($items) {
            foreach ($items as $data) {
                $multiContent = json_decode(MenuItems::multicontent($data->id));
                if ($data->parent_id == null) {
                    $response['parent'][$data->id]['id'] = $data->id;
                    $response['parent'][$data->id]['title'] = $data->title;
                    $response['parent'][$data->id]['slug'] = $data->slug;
                    $response['parent'][$data->id]['url'] = url($data->link_url);
                    $response['parent'][$data->id]['relative_url'] = $data->link_url;
                    $response['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                    $response['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                    $response['parent'][$data->id]['multiContent'] = $multiContent->multiContent;
                    $response['parent'][$data->id]['multiId'] = $multiContent->multiId;
                    $response['parent'][$data->id]['multiTitle'] = $multiContent->multiTitle;
                    $response['parent'][$data->id]['is_new'] = $multiContent->is_new;
                    $response['parent'][$data->id]['type'] = $data->type;
                    $response['parent'][$data->id]['module'] = $data->type == ConstantHelper::MENU_TYPE_CONTENT ? $data->module->name : '';
                } else {
                    $response['child'][$data->parent_id][$data->id]['id'] = $data->id;
                    $response['child'][$data->parent_id][$data->id]['title'] =  $data->title;
                    $response['child'][$data->parent_id][$data->id]['slug'] = $data->slug;
                    $response['child'][$data->parent_id][$data->id]['url'] = url($data->link_url);
                    $response['child'][$data->parent_id][$data->id]['relative_url'] = $data->link_url;
                    $response['child'][$data->parent_id][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                    $response['child'][$data->parent_id][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                    $response['child'][$data->parent_id][$data->id]['multiContent'] = $multiContent->multiContent;
                    $response['child'][$data->parent_id][$data->id]['multiId'] = $multiContent->multiId;
                    $response['child'][$data->parent_id][$data->id]['multiTitle'] = $multiContent->multiTitle;
                    $response['child'][$data->parent_id][$data->id]['is_new'] = $multiContent->is_new;
                    $response['child'][$data->parent_id][$data->id]['type'] = $data->type;
                    $response['child'][$data->parent_id][$data->id]['module'] = $data->type == ConstantHelper::MENU_TYPE_CONTENT ? $data->module->name : '';
                }
            }
        }
        return $response;
    }
}
