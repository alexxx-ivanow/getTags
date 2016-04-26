Сниппет getTags выводит похожие ресурсы, совпадающие по максимальному количеству пересекающихся тегов. Подразумевается, что у этих ресурсов есть дополнительное поле типа "метка", в которые менеджер заносит необходимые метки.

По этим полям ведется сравнительный анализ и вывод найденных ресурсов в порядке убывания общих тегов.

Для работы сниппета необходим установленный сниппет pdoResources.

Пример вызова сниппета:
[[!getTags3? 
&tpl=`peres_ch_tex` &parent=`9` 
&tv_id = `4` &limit = `5`
]]

Передаваемые параметры:

&tpl - имя чанка, в котором вызывается сниппет pdoResources, которому из сниппета обязательно передается переменная "ex", содержащая список id ресурсов, совпадающих по тегам. А в этом чанке, через параметр сниппета pdoResources, указывается чанк для вывода списка, со всеми стандартными плейсхолдерами.

&parent - id родительского ресурса, дочерние ресурсы которого перебираются сниппетом. Возможно указать несколько родительских ресурсов через запятую. Выборка происходит только у прямых потомков, без рекурсии.

&limit - количество выводимых похожих ресурсов.

&tv_id - id дополнительного поля, в которое заносятся метки.