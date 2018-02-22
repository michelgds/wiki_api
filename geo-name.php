<?php
$cidadeNome = urlencode('Água Boa');
$estadoNome = urlencode('Minas Gerais');

$content = get_wiki_api_content($cidadeNome, $estadoNome);
$filtered_content = filter_content($content);

//var_dump($content);
//var_dump($filtered_content);

$pattern = '/\[[^\]].*/';
preg_match($pattern, implode(' ', $filtered_content), $match);

if ($match){
	$string = explode(", ",str_replace(' e ', ', ', str_replace(']]', '', str_replace('[[', '', $match)))[0]);
	//var_dump($string);
} else{
	//var_dump($filtered_content);
};

function get_wiki_api_content($cidadeNome, $estadoNome){
	
	$url = 'https://pt.wikipedia.org/w/api.php?action=query&titles=' . $cidadeNome . '&prop=revisions&rvprop=content&format=json';
	
	$wikiObject = json_decode(file_get_contents($url), true);
	$content = array_values($wikiObject['query']['pages'])[0]['revisions'][0]['*'];
	//var_dump($content);
	
	if (has_duplicated_city_name($content)){
	
		if (isset($content)){
			$url = 'https://pt.wikipedia.org/w/api.php?action=query&titles=' . $cidadeNome . '_(' . $estadoNome . ')&prop=revisions&rvprop=content&format=json';
			$wikiObject = null;
			$wikiObject = json_decode(file_get_contents($url), true);
		}
	};
	var_dump($wikiObject);
	//var_dump($content);
	
	return array_values($wikiObject['query']['pages'])[0]['revisions'][0]['*'];
};

function has_duplicated_city_name($content){
	$pattern1 = '/Categoria:Desambiguações de topônimos/';
	$pattern2 = '/#REDIRECIONAMENTO/';
	
	$match = null;
	preg_match($pattern1, $content, $match);
	
	if($match){
		return true;
	};
	
	$match = null;
	preg_match($pattern2, $content, $match);
	
	if ($match){
		return true;
	};
	
	return false;
}

function filter_content($content){
	$pattern = '/[^|]*vizinhos = [^|]*/';
	
	preg_match($pattern, $content, $match);
	
	//var_dump($content);
	//var_dump($match);
	
	return $match;
};

?>