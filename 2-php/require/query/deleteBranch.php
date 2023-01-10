<?
if ($_GET['action']=='deleteBranch' && $_GET['id']>=0){
        function getChilds($arr, $par)
                {
                    $childs=[];
                    $str='';
                    do{
                        if (count($childs)>0){
                            $childs=array_values($childs);
                            $par=$childs[0];
                            unset($childs[0]);
                        }
                        foreach($arr as $key => &$item) {
                            if ($item['parent']==$par){
                                $childs[]=$item['id'];
                                $str.=', '.$item['id'];
                            }
                        }
                    }while (count($childs)>0);
                    return $str;
                }
        if (is_integer((int) $_GET['id'])){
                $sql = "SELECT * FROM data";
                $sth=$dbh->prepare($sql);
                $sth->execute();
                $row = $sth->fetchAll();
                $id=$_GET['id'];
                $str_ids=''.$id;
                $str_ids.=getChilds($row, $id);
                $sql='DELETE FROM `data` WHERE id IN ('.$str_ids.')';
                
                $sth=$dbh->prepare($sql);
                $sth->execute();

                header('location: admin');
                
        }
    }

    ?>