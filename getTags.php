<?php
//проверка наличия значения id родит.папки: $parent. По дефолту - родитель текущего ресурса
if(!isset($parent)){
    $parent = $modx->resource->get('parent');
}
$parent = explode(',',$parent);

//проверка наличия значений чанка: $tpl. По дефолту - peres_ch_tex
if(!isset($tpl)){
    $tpl = 'peres_ch_tex';
}

//проверка наличия значения переменной, передающей id TV-поля, в котором хранятся теги
if(!isset($tv_id)){
    echo 'Задайте в сниппете `getTags` id TV-поля с тегами($tv_id)';
    return;
}

//проверка наличия значения переменной, передающей из вызова сниппета 
//количество выводимых в чанке совпадающих ресурсов
if(!isset($limit)){
    $limit = 0;
}

//Выборка всех тегов из TV=tags с текущего ресурса
$views = $modx->resource->getTVValue('tags');
//закидываем выборку в массив
$tk = explode(',',$views);

//выборка всех TV, id которого задано в вызове сниппета
$tvs = $modx->getCollection('modTemplateVarResource', array('tmplvarid' => $tv_id));

//Выборка коллекции ресурсов из заданного родителя
$tvss = $modx->newQuery('modResource');
$tvss->where(array('parent:IN' => $parent));
$collection = $modx->getCollection('modResource', $tvss);

//создание массива id ресурсов, которые содержат выбранный TV с тегами
foreach($collection as $coll){
            foreach($tvs as $tvcoll){
                if(($coll->get('id')) == ($tvcoll->get('contentid'))){
            $tvsn[] = $tvcoll->get('contentid');
            //print_r ($tvsn);
        }
    }
}
//перебираем всю коллекцию TV, сравнивая с массивом id нужных нам ресурсов. Лишние - выкидываем.
foreach ($tvs as $k => $tv) {
   $tvs[$k] = $tv->toArray();
    if(!in_array($tvs[$k]['contentid'], $tvsn)){
        continue;
    }
    
   // Создаем массив тегов (tags) из для каждого текущего перебираемого ресурса
    $tkk[$tvs[$k]['contentid']] = explode(',',$tvs[$k]['value']);
   // print_r($tkk);
}

    $i = array();
//перебираем массив значений тегов из TV текущего ресурса
foreach($tk as $idd => $item1){
    $news = $tk[$idd];
    //перебираем массив тегов нужных ресурсов
    foreach($tkk as $idk => $item2){
        //print_r($item2);
        //сравниваем отдельный тег нужного ресурса с отдельным тегом текущего ресурса
        foreach($item2 as $k => $val){
        //при их совпадении - закидываем в новый массив значение тега($val) 
        //и ключ с id подошедшего ресурса($idk)
        // $k - номер ключа значения тега в массиве
        if($news === $item2[$k]){
            $i[$k][$idk] = $val;
       }
     }
   }
}
//перегоняем в компактный массив со значениями id ресурсов, в которых нашлись совпадающие теги.
$arr  = array();
foreach($i as $kk => $vv){
    foreach($vv as $ki => $vvv){
        $arr[]=$ki;
    }
}
//сортируем массив по частоте встречающихся значений(при этом ключ - id ресурса,
//значение - количество повторов)
  $arr = array_count_values($arr);
  arsort($arr);
//перегоняем ключи полученного массива(то есть, набор id) в строку
$ex = implode(',',array_keys($arr));
//если в строке пусто, передаем 0 в чанк(для скрытия заголовка блока)
if(strlen($ex)<1)
{$ex='0';}
//передаем полученные значения в чанк для вывода в pdoResources
$output = $modx->getChunk($tpl,array(
   'ex' => $ex,
   'limit' => $limit
));
return $output;