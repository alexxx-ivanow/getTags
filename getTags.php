<?php
$query = $modx->newQuery('modTemplateVar');
$query->where( array('name' => 'tags') );
$res = $modx->getObject('modTemplateVar', $query);
$tvid = '';
if ($res) {
  $tvid = $res->get('id');
}else{
    $tvid = 0;
}

$parent = (isset($parent)) ? $parent : $modx->resource->parent;
$depth = (isset($depth)) ? $depth : '5';
$limit = (isset($limit)) ? $limit : '6';
$exclude = (isset($exclude)) ? $exclude : '-1';
$tplTags = (isset($tplTags)) ? $tplTags : '';
$titleTags = (isset($titleTags)) ? $titleTags : 'Похожие позиции';
$container = ($container == 1) ? '1' : '0';

$views = $modx->resource->getTVValue($tvid);
if(strlen($views) > 0){
    $tk = explode(',',$views);
    $ids = $modx->getChildIds($parent,$depth,array('context' => 'web'));

    if($exclude > 0){
        if(stristr($exclude, ',') === FALSE) {
            $excludeChild = $modx->getChildIds($exclude,$depth,array('context' => 'web'));
            $ids = array_diff($ids, $excludeChild);
        }else{
            $excludeChild = '';
            $exclude = explode(',',$exclude);
            foreach ($exclude as $exCh){
                $arr = $modx->getChildIds($exCh, $depth, array('context' => 'web'));
                $excludeChild .= implode(',',$arr).',';
            }
            $res = explode(',',$excludeChild);
            array_pop($res);
            $ids = array_diff($ids, $res);
        }
    }
    
    $tvs = $modx->getCollection('modResource', array('id:IN' => $ids));
    
    if($container == 0){
            foreach ($tvs as $k => $tv) {    
              if(($tv->getTVValue($tvid) != '') AND ($tv->isfolder == 0)){  
                $tkk[ $tv->id] = explode(',',$tv->getTVValue($tvid));
                $tkk[$k] +=  [contentid => $tv->id];
                //echo $tkk[$k]['contentid'].',';
                }
            }        
    }elseif($container == 1){
        foreach ($tvs as $k => $tv) {
            if(($tv->getTVValue($tvid) != '')){  
            $tkk[ $tv->id] = explode(',',$tv->getTVValue($tvid));
            $tkk[$k] +=  [contentid => $tv->id];
            }
        }    
    }    
  
    $mass = [];
    foreach($tkk as $to){
        $result = array_intersect($to, $tk);
        $result += [id => $to['contentid']]; 
        $mass[] = $result;
        $mass['id'] += $to['contentid']; 
    }
    sort($mass);
    $mass = array_reverse($mass);
    $ex = [];
    foreach($mass as $mas){
            if(count($mas) > 1){
                if($mas['id'] != $modx->resource->get('id')){ 
                $ex[] = $mas['id'];
                }
            }
        }
         
       if(count($ex) > 0){
        $ex = implode($ex, ',');
       }else{
            $ex='0';
        }
    //echo $ex;
    $output = $modx->getChunk($tplTags, array(
       'ex' => $ex, 
       'limit' => $limit, 
       'titleTags' => $titleTags, 
       'exclude' => $exclude,
    ));
    return $output;    
        
}
