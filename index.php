<?php
session_start();

if (empty($_SESSION['state']) || $_SESSION['state'] != 'login')
{
    header('Location: ./login.php');
    exit();
}

// データベースの接続
include_once('./db-connection.php');
include_once('./util.php');
?>
<html>
<head>
	<meta http-equiv="content-language" content="ja">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width,maximum-scale=1">
    <title>生活RTAシステム - トップページ</title>

    <script type="text/javascript" src="https://npmcdn.com/chart.js@latest/dist/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="./plugin.js"></script>
    <script type="text/javascript" src="./script.js"></script>

    <script type="text/javascript">
    function ChangeTab(tabname) {
        // タブメニュー実装
        document.getElementById('area-day').style.display = 'none';
        document.getElementById('area-history').style.display = 'none';
        // タブメニュー実装
        document.getElementById(tabname).style.display = 'block';
    }
    
    window.onload = function() {
        ChangeTab('area-day');
    }
    </script>

    <link href="./style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="header">
生活RTAシステム
</div>

<div id="tab-area">
    <a onclick="ChangeTab('area-day');">日別記録</a>
    <a onclick="ChangeTab('area-history');">行動履歴</a>
</div>

<div id="area-day">
<?php
for ($backday = 0; $backday < 3; $backday++)
{
    $day_pt = time() - $backday * 86400;

    try
    {
        $sql = 'SELECT * FROM `daily-rta` WHERE time >= :start AND time < :end ORDER BY `id` DESC';
        $sth = $pdo->prepare($sql);
        $sth->bindValue(':start', date('Y-m-d', $day_pt));
        $sth->bindValue(':end',   date('Y-m-d', $day_pt + 86400));
        $sth->execute();
        $result = $sth->fetchAll();
    }
    catch(PDOException $e)
    {
        exit();
    }

    echo '<h1>' . date('Y年m月d日', $day_pt) . '</h1>';
    echo '<table>';

    // 行動データを格納
    $act = array();
    // 行動データ格納に使う一時変数
    $reclabel = '';
    $timespan = 0;

    // データの数だけループ
    for ($n = 0; $n < count($result); $n++)
    {
        $d = $result[$n];

        echo '<tr>';
        echo '<td>' . date('H:i.s', strtotime($d['time'])) . '</td>';
        echo '<td>' . state2JPN($d['state']) . '</td>';
        
        // 見ている動作が「終了」
        if ($d['state'] == 'end')
        {
            // 先頭なら（動作終了〜1日終了までが空時間）
            if ($n == 0)
            {
                // ラベル
                $reclabel = 'others';
                // 時間は、動作終了から1日の終わりまで
                $timespan = strtotime(date('Y-m-d', strtotime($d['time']))) + 86400 - strtotime($d['time']);

                // 行動データを格納
                array_push($act,
                    array(
                        'label' => $reclabel,
                        'time'  => $timespan
                    )
                );
            }
            
            // 末端なら（1日開始〜動作終了）
            if ($n == count($result) - 1)
            {
                // ラベル
                $reclabel = $d['label'];
                echo '<td class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';

                // 時間は、1日の初めから終了時点まで
                $timespan = strtotime($d['time']) - strtotime(date('Y-m-d 00:00:00', $day_pt));
                echo '<td>' . sec2time($timespan) . '</td>';
            }
            //先頭でも末端でない（動作開始〜終了）
            else
            {
                // 次のデータ(1つ前のやつ)を読み込む
                $nextd = $result[$n + 1];

                // ラベルは「開始」についたものを採用
                if($nextd['label'] == $d['label'])
                {
                    $reclabel = $d['label'];
                    echo '<td rowspan="2" class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';
                }
                else
                {
                    $reclabel = $nextd['label'];
                    echo '<td rowspan="2" class="' . $nextd['label'] . '">' . label2JPN($nextd['label']) . '</td>';
                }

                // 時間は、開始時点から終了時点まで
                $timespan = strtotime($d['time']) - strtotime($nextd['time']);
                echo '<td rowspan="2">' . sec2time($timespan) . '</td>';
            }
        }
        else
        // 見ている動作が「開始」
        if ($d['state'] == 'start')
        {
            if ($n == 0)
            {
                $reclabel = $d['label'];
                echo '<td class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';

                // 記録時間は、開始時点から1日の終わりまで
                $timespan = strtotime(date('Y-m-d', strtotime($d['time']))) + 86400 - strtotime($d['time']);

                // 今日ならば
                if (date('Y-m-d', $day_pt) == date('Y-m-d'))
                {
                    // 表示は、動作開始〜現時点
                    echo '<td>' . sec2time(time() - strtotime($d['time'])) . '</td>';
                }
                // 今日でない
                else
                {
                    // 表示も、動作開始〜1日の終わり
                    echo '<td>' . sec2time($timespan) . '</td>';
                }

                // 行動データを格納
                array_push($act,
                    array(
                        'label' => $reclabel,
                        'time'  => $timespan
                    )
                );
            }
            
            // 末端なら（1日開始〜動作開始までは空時間）
            if ($n == count($result) - 1)
            {
                // ラベル
                $reclabel = 'others';
                // 時間は、1日の初めから終了時点まで
                $timespan = strtotime($d['time']) - strtotime(date('Y-m-d 00:00:00', $day_pt));
            }
            //先頭でも末端でない（動作終了〜開始を空時間とする）
            else
            {
                // ラベル
                $reclabel = 'others';
                // 次のデータ(1つ前のやつ)を読み込む
                $nextd = $result[$n + 1];
                // 時間は、終了時点から開始時点まで
                $timespan = strtotime($d['time']) - strtotime($nextd['time']);
            }
        }

        // 行動データを格納
        array_push($act,
            array(
                'label' => $reclabel,
                'time'  => $timespan
            )
        );
        
        echo '</tr>';
    }
    echo '</table>';

    // 行動データがない時は終日othersに
    if (count($act) == 0)
    {
        array_push($act,
            array(
                'label' => 'others',
                'time'  => 86400
            )
        );
    }

    // 配列を逆順に（グラフ表示の関係で）
    $act = array_reverse($act);
?>
    <canvas id="plotarea-<?=$backday?>" width="600" height="200"></canvas>

    <script type="text/javascript">
    var action_obj = JSON.parse('<?=arr2JSON($act)?>');
    plotHorizontalBar('plotarea-<?=$backday?>', action_obj);
    </script>
<?php
}
?>
</div>

<div id="area-history">
<h1>行動時間履歴</h1>
<?php
    try
    {
        $sql = 'SELECT * FROM `daily-rta` ORDER BY `id` DESC';
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
    }
    catch(PDOException $e)
    {
        exit();
    }

    echo '<table>';

    for ($n = 0; $n < count($result); $n++)
    {
        $d = $result[$n];
        
        // 今見ている動作が「end」ならば
        if ($d['state'] == 'end')
        {
            echo '<tr>';

            // 末端なら
            if ($n == count($result) - 1)
            {
                echo '<td></td>';
                echo '<td class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';
                echo '<td></td>';
            }
            // 末端でない
            else
            {
                $nextd = $result[$n + 1];
                echo '<td>' . date('m-d H:i.s', strtotime($nextd['time'])) . '</td>';
                if($nextd['label'] == $d['label'])
                {
                    echo '<td class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';
                }
                else
                {
                    echo '<td class="' . $d['label'] . '">' . label2JPN($nextd['label']) . '</td>';
                }
                echo '<td>' . sec2time(strtotime($d['time']) - strtotime($nextd['time'])) . '</td>';
            }

            echo '</tr>';
        }
        else
        // 今見ている動作が「start」ならば
        if ($d['state'] == 'start' && $n == 0)
        {
            echo '<tr>';

            echo '<td>' . date('m-d H:i.s', strtotime($d['time'])) . '</td>';
            echo '<td class="' . $d['label'] . '">' . label2JPN($d['label']) . '</td>';
            echo '<td>' . sec2time(time() - strtotime($d['time'])) . '</td>';
            
            echo '</tr>';
        }
    }

    echo '</table>';
?>
</div>
</body>
</html>
