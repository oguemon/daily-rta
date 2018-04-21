<?php
function label2JPN ($label)
{
    $label_jp = '';

    switch ($label)
    {
        case 'bed':
            $label_jp = 'ベッド';
            break;
        case 'dish':
            $label_jp = '食事';
            break;
        case 'bath':
            $label_jp = '入浴';
            break;
        case 'out':
            $label_jp = '外出';
            break;
        case 'desk':
            $label_jp = '机';
            break;
        default:
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