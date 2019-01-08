<?php /** Функция для вывода записей по произвольному полю содержащему числовое значение.
-------------------------------------
Параметры передаваемые функции (в скобках дефолтное значение):
num (10) - количество постов.
key (views) - ключ произвольного поля, по значениям которого будет проходить выборка.
order (DESC) - порядок вывода записей. Чтобы вывести сначала менее просматириваемые устанавливаем order=1
format(0) - Формат выводимых ссылок. По дефолту такой: ({a}{title}{/a}). Можно использовать, например, такой: {date:j.M.Y} - {a}{title}{/a} ({views}, {comments}).
days(0) - число последних дней, записи которых нужно вывести по количеству просмотров. Если указать год (2011,2010), то будут отбираться популярные записи за этот год.
cache (0) - использовать кэш или нет. Варианты 1 - кэширование включено, 0 - выключено (по дефолту).
echo (1) - выводить на экран или нет. Варианты 1 - выводить (по дефолту), 0 - вернуть для обработки (return).
Пример вызова: kama_get_most_viewed("num=5 &key=views &cache=1 &format={a}{title}{/a} - {date:j.M.Y} ({views}) ({comments})");
*/
function kama_get_most_viewed($args=''){
	parse_str($args, $i);
	$num    = isset($i['num']) ? $i['num']:10;
	$key    = isset($i['key']) ? $i['key']:'views';
	$order  = isset($i['order']) ? 'ASC':'DESC';
	$cache  = isset($i['cache']) ? 1:0;
	$days   = isset($i['days']) ? (int)$i['days']:0;
	$echo   = isset($i['echo']) ? 0:1;
	$format = isset($i['format']) ? stripslashes($i['format']):0;
	global $wpdb,$post;
	$cur_postID = $post->ID;

	if( $cache ){ $cache_key = (string) md5( __FUNCTION__ . serialize($args) );
		if ( $cache_out = wp_cache_get($cache_key) ){ //получаем и отдаем кеш если он есть
			if ($echo) return print($cache_out); else return $cache_out;
		}
	}

	if( $days ){
		$AND_days = "AND post_date > CURDATE() - INTERVAL $days DAY";
		if( strlen($days)==4 )
			$AND_days = "AND YEAR(post_date)=" . $days;
	}

	$sql = "SELECT p.ID, p.post_title, p.post_date, p.guid, p.comment_count, (pm.meta_value+0) AS views
	FROM $wpdb->posts p
		LEFT JOIN $wpdb->postmeta pm ON (pm.post_id = p.ID)
	WHERE pm.meta_key = '$key' $AND_days
		AND p.post_type = 'post'
		AND p.post_status = 'publish'
	ORDER BY views $order LIMIT $num";
	$results = $wpdb->get_results($sql);
	if( !$results ) return false;

	$out= '';
	preg_match( '!{date:(.*?)}!', $format, $date_m );
	foreach( $results as $pst ){
		$x == 'li1' ? $x = 'li2' : $x = 'li1';
		if ( (int)$pst->ID == (int)$cur_postID ) $x .= " current-item";
		$Title = $pst->post_title;
		$a1 = "<a href='". get_permalink($pst->ID)."' title='{$pst->views} просмотров: $Title'>";
		$a2 = "</a>";
		$comments = $pst->comment_count;
		$views = $pst->views;
		if( $format ){
			$date = apply_filters('the_time', mysql2date($date_m[1],$pst->post_date));
			$Sformat = str_replace ($date_m[0], $date, $format);
			$Sformat = str_replace(array('{a}','{title}','{/a}','{comments}','{views}'), array($a1,$Title,$a2,$comments,$views), $Sformat);
		}
		else $Sformat = $a1.$Title.$a2;
		$out .= "<li class='$x'>$Sformat</li>";
	}

	if( $cache ) wp_cache_add($cache_key, $out);

	if( $echo )
		return print $out;
	else
		return $out;
}
