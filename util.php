<?php

// 伝説のデータ構造
$label_list = array(
    'bed' => array(
        'label_jp' => 'ベッド',
        'color' => 'rgba(244, 143, 177, 0.6)'
    ),
    'dish' => array(
        'label_jp' => '食事',
        'color' => 'rgba(255, 235, 59, 1)'
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
    'other' => array(
        'label_jp' => 'その他',
        'color' => 'rgba(255, 255, 255, 1)'
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
        $label_jp = 'その他';
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