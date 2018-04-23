<?php

// 伝説のデータ構造
$label_list = array(
    'bed' => array(
        'label_jp' => 'ベッド',
        'color' => 'rgb(255, 180, 180)'
    ),
    'dish' => array(
        'label_jp' => '食事',
        'color' => 'rgba(255, 213, 158, 1)'
    ),
    'bath' => array(
        'label_jp' => '入浴',
        'color' => 'rgba(179, 226, 180, 1)'
    ),
    'out' => array(
        'label_jp' => '外出',
        'color' => 'rgba(100, 181, 246, 1)'
    ),
    'desk' => array(
        'label_jp' => '机',
        'color' => 'rgba(184, 178, 234, 1)'
    ),
    'others' => array(
        'label_jp' => 'その他',
        'color' => 'rgba(230, 230, 230, 1)'
    )
);

function label2JPN ($label)
{
    global $label_list;

    $label_jp = '';
    
    if (!empty($label_list[$label]['label_jp']))
    {
        $label_jp = $label_list[$label]['label_jp'];
    }
    else
    {
        $label_jp = $label_list['others']['label_jp'];
    }
    return $label_jp;
}

function state2JPN ($state)
{
    $state_jp = '';

    switch ($state)
    {
        case 'start':
            $state_jp = '開始';
            break;
        case 'end':
            $state_jp = '終了';
            break;
        default:
            $state_jp = '他';
    }
    return $state_jp;
}

function sec2time ($second)
{
    $time = '';
    if ($second >= 3600)
    {
        $time = ($second - ($second % 3600)) / 3600 . '時間';
        $second = $second % 3600;
    }
    if ($second >= 60)
    {
        $time .= ($second - ($second % 60)) / 60 . '分';
        $second = $second % 60;
    }
    $time .= $second . '秒';

    return $time;
}

// 配列actのデータセットから、ChartJSにおけるdatasets部分のJSONを作る
function arr2JSON ($action_list)
{
    global $label_list;

    $JSON_text = '{"datasets": [';

    foreach ($action_list as $action)
    {
        $JSON_text .= '{';
        $JSON_text .= '"label": "' . $label_list[$action['label']]['label_jp'] . '",';
        $JSON_text .= '"data": [' . $action['time'] . '],';
        $JSON_text .= '"backgroundColor": "' . $label_list[$action['label']]['color'] . '"';
        $JSON_text .= '},';
    }
    $JSON_text = rtrim($JSON_text, ','); //末尾の,を消去
    $JSON_text .= ']}';

    return $JSON_text;
}
