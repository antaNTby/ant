<?php

function flatAdminDepartments(
    $departments,
    $subsTables
): array
{

    $res = [];
    if (!is_array($departments))
    {
        return $res;
    }
    $menu = [];
    foreach ($departments as $dpt_key => $ad)
    {
        $dpt_id   = $ad['id'];
        $dpt_name = $ad['name'];

        $menu[$dpt_key]['dpt_id']              = $dpt_id;
        $menu[$dpt_key]['dpt_name']            = $dpt_name;
        $menu[$dpt_key]['sub_count']           = count($ad['sub_departments']);
        $menu[$dpt_key]['dpt_template_file'][] = PATH_TPL . $dpt_id . '.tpl.html';

        $ii = 0;
        foreach ($ad as $key => $value)
        {
            if ($key == 'sub_departments')
            {
                foreach ($value as $k => $v)
                {
                    $menu[$dpt_key]['sub_ids'][]   = $v['id'];
                    $menu[$dpt_key]['sub_names'][] = $v['name'];

                    if (array_key_exists($v['id'], $subsTables))
                    {
                        $menu[$dpt_key]['sub_tables'][] = $subsTables[$v['id']];
                    }
                    else
                    {
                        $menu[$dpt_key]['sub_tables'][] = null;
                    }
                    $menu[$dpt_key]['sub_hrefs'][]              = ADMIN_FILE . "?dpt={$dpt_id}&sub=" . $v['id'];
                    $menu[$dpt_key]['columns_json_files'][]     = PATH_CONFIGS . $dpt_id . '_' . $v['id'] . '__columns.json';
                    $menu[$dpt_key]['include_php_files'][]      = PATH_INCLUDES . $dpt_id . '_' . $v['id'] . '.php';
                    $menu[$dpt_key]['dpt_sub_template_files'][] = PATH_TPL . $dpt_id . '_' . $v['id'] . '.tpl.html';
                }

            }
        }
    }
    $res = $menu;
    return $res;
}