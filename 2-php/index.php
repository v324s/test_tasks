
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Пользовательская страница</title>
	<link rel="stylesheet" href="css/index.css">
</head>
<body>
<?php

try {
	$dbh = new PDO('mysql:dbname=php_test;host=127.0.0.1', 'root', 'root');
} catch (PDOException $e) {
	die($e->getMessage());
}
$sql = "SELECT * FROM data";
$sth=$dbh->prepare($sql);
$sth->execute();

$row = $sth->fetchAll();
				$tempRow=$row;
                $goodArr = array();
                
                foreach($tempRow as $key => &$item) {
                   $goodArr[$item['id']] = &$item;
                   $goodArr[$item['id']]['children'] = array();
                }
                
                foreach($tempRow as $key => &$item)
                   if($item['parent'] && isset($goodArr[$item['parent']]))
                      $goodArr [$item['parent']]['children'][] = &$item;
                
                foreach($tempRow as $key => &$item) {
                   if($item['parent'] && isset($goodArr[$item['parent']]))
                      unset($tempRow[$key]);
                }
				function child($item,$brd){
					
				
                    $brd+=30;
                    if (!$item['parent'])
                        $item['parent']='-1';
                    ?>
                    <div id="item-row_<? echo $item['id']; ?>" class="flex item-row" style="padding-left: <? echo $brd; ?>px; display: none;">
					
								<div class="branch-control">
                               		<button class="mg-auto button-ico but-add cPointer" onclick="toggleChilds(this.getAttribute('row'))">↑↓</button>
								</div>
                                <div class="branch-info" style="width: calc(100% - 100px -  <? echo $brd; ?>px);">
                                    <div class="cPointer" onclick="toggleDesc(<? echo $item['id']; ?>)"><? echo $item['name']; ?></div>
                                    <div id="description-name_<? echo $item['id']; ?>" class="branch-info_decription"><? echo $item['description']; ?></div>
                                </div>
                            </div>
                    <?
                    if (count($item['children']>0)){
                        foreach($item['children'] as $ch){
                            child($ch,$brd);
                        }
                    }
                }
                

                foreach ($tempRow as $key) {
                    if (!$key['parent'])
                        $key['parent']='-1';
                    ?>
                        <div id="item-row_<? echo $key['id']; ?>" class="flex item-row">
                            <div class="branch-control">
                                <button class="mg-auto button-ico but-add cPointer" onclick="toggleChilds(this.getAttribute('row'))">↑↓</button>
                            </div>
                            <div class="branch-info">
                                <div class="cPointer" onclick="toggleDesc(<? echo $key['id']; ?>)"><? echo $key['name']; ?></div>
                                <div id="description-name_<? echo $key['id']; ?>" class="branch-info_decription"><? echo $key['description']; ?></div>
                            </div>
                        </div>
                    <?
                $brd=0;
                
                    if (count($key['children']>0)){
                        foreach($key['children'] as $ch){
                            child($ch,$brd);
                        }
                    }
                }
?>
<script src="js/funcs.js"></script>
<script src="js/indexfuncs.js"></script>
</body>
</html>

