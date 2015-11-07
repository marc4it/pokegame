<?php
/* Print the contents of $result looping through each row returned in the result */

//test 123

/*
Plugin Name: Pokemon
Plugin URI: http://yourdomain.com/
Description: A simple hello world wordpress plugin
Version: 1.0
Author: Marcus
Author URI: http://yourdomain.com
License: GPL
*/
/* This calls hello_world() function when wordpress initializes.*/
/* Note that the hello_world doesnt have brackets.*/

//* http://pokemon.game-solver.com/pokedex/charizard */


/* add action for wordpress /*
//add_action('init','hello_world');

/* create shortcode for wordpress to use in post or page*/
add_shortcode( 'poke', 'poke_dex' );

//function hello_world() {
//echo "This is plug in!";
//}

/* always define global $wpdb before query db */

header('Content-Type: text/html; charset=utf-8');

function poke_dex($atts, $content=null) {
	global $wpdb;
	
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );

	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);
	
	
	$sql = "SELECT * FROM `pokedex` WHERE `name` LIKE '".$path[2]."' LIMIT 0, 30";
//	echo "this is pokedex";
//	echo $sql;
	$results = $wpdb->get_row( $sql, ARRAY_A );

// all json_decode here*
	
	$tm = $results['tm'];
	$tm_decode = json_decode($tm,true);
	
	$learned_moves = $results['learned_moves'];
	$learnedmove_decode = json_decode($learned_moves,true);
	
	$abilities = $results['abilities'];
	$abilities_decode = json_decode($abilities,true);
	
	$eggmoves = $results['egg_moves'];
	$eggmoves_decode = json_decode($eggmoves,true);
	
	$hm = $results['hm'];
	$hm_decode = json_decode($hm,true);
	
	$preevo = $results['pre-evo'];
	$preevo_decode = json_decode($preevo,true);
	
	$evo = $results['evo'];
	$evo_decode = json_decode($evo,true);
	
	$mega = $results['mega'];
	$mega_decode = json_decode($mega,true);
	
	$basestats = $results['base_stats'];
	$basestats_decode = json_decode($basestats,true);
	
	

//*decoded arrange div here*
	$tm_count = count($tm_decode);	
	$tm_final =	'<div class="row">';
	$t = 0;
	foreach ($tm_decode as $tm1){
//		$tm_final .= '<div class="small-3 columns">'.$tm1.'</div>';
//		$tm_final = $tm_final.'<div class="small-2 columns">'.$tm1.'</div>';
		$t++;
		if ($tm_count == $t) {
			$tm_final .= '<div class="small-3 columns end">'.$tm1.'</div>';
		} else {
			$tm_final .= '<div class="small-3 columns">'.$tm1.'</div>';
		}
	}
	$tm_final .= '</div>';
	
	$learnedmove_count = count($learnedmove_decode);	
	$learnedmove_final = '<div class="row">';
	$lm = 0;
	foreach ($learnedmove_decode as $lm1){
		$lm++;
		if ($learnedmove_count == $lm) {
			$learnedmove_final .= '<div class="small-12 columns end">'.$lm1.'</div>';
		} else {
			$learnedmove_final .= '<div class="small-12 columns">'.$lm1.'</div>';
		}
	}
	$learnedmove_final .= '</div>';
	
	$abilities_count = count($abilities_decode);	
	$abilities_final = '<div class="row">';
	$ab = 0;
	foreach ($abilities_decode as $ab1){
		$ab++;
		if ($abilities_count == $ab) {
			$abilities_final .= '<div class="small-12 columns end">'.$ab1.'</div>';
		} else {
			$abilities_final .= '<div class="small-12 columns">'.$ab1.'</div>';
		}
	}
	$abilities_final .= '</div>';
	
	$eggmoves_count = count($eggmoves_decode);
	$eggmoves_final = '<div class="row">';
	$em = '0';
	$em2 = '-';
	if ($eggmoves_count==$em){
		$eggmoves_final .= '<div class="small-4 columns">'.$em2.'</div>';
		}else{
		foreach ($eggmoves_decode as $em1){
			$em++;
			if ($eggmoves_count == $em) {
				$eggmoves_final .= '<div class="small-12 columns end">'.$em1.'</div>';
				} else {
				$eggmoves_final .= '<div class="small-12 columns">'.$em1.'</div>';
				}
			}
		}
	
	$eggmoves_final .= '</div>';
	
	$hm_count = count($hm_decode);
	$hm_final = '<div class="row">';
	$hm = '0';
	$hm2 = '-';
	if ($hm_count==$hm){
		$hm_final .= '<div class="small-4 columns">'.$hm2.'</div>';
		}else{
		foreach ($hm_decode as $hm1){
			$hm++;
			if ($hm_count == $hm) {
				$hm_final .= '<div class="small-12 columns end">'.$hm1.'</div>';
				} else {
				$hm_final .= '<div class="small-12 columns">'.$hm1.'</div>';
				}
			}
		}
	
	$hm_final .= '</div>';
	
	$preevo_count = count($preevo_decode);
	$preevo_final = '<div class="row">';
	$preevo = '0';
	$preevo2 = '-';
	if ($preevo_count==$preevo){
		$preevo_final .= '<div class="small-4 columns">'.$preevo2.'</div>';
		}else{
		foreach ($preevo_decode as $preevo1){
			$preevo++;
			if ($preevo_count == $preevo) {
				$preevo_final .= '<div class="small-12 columns end">'.$preevo1.'</div>';
				} else {
				$preevo_final .= '<div class="small-12 columns">'.$preevo1.'</div>';
				}
			}
		}
	
	$preevo_final .= '</div>';
	
	$evo_count = count($evo_decode);
	$evo_final = '<div class="row">';
	$evo = '0';
	$evo2 = '-';
	if ($evo_count==$evo){
		$evo_final .= '<div class="small-4 columns">'.$evo2.'</div>';
		}else{
		foreach ($evo_decode as $evo1){
			$evo++;
			if ($evo_count == $evo) {
				$evo_final .= '<div class="small-12 columns end">'.$evo1.'</div>';
				} else {
				$evo_final .= '<div class="small-12 columns">'.$evo1.'</div>';
				}
			}
		}
	
	$evo_final .= '</div>';
	
	$mega_count = count($mega_decode);
	$mega_final = '<div class="row">';
	$mega = '0';
	$mega2 = '-';
	if ($mega_count==$mega){
		$mega_final .= '<div class="small-4 columns">'.$mega2.'</div>';
		}else{
		foreach ($mega_decode as $mega1){
			$mega++;
			if ($mega_count == $mega) {
				$mega_final .= '<div class="small-12 columns end">'.$mega1.'</div>';
				} else {
				$mega_final .= '<div class="small-12 columns">'.$mega1.'</div>';
				}
			}
		}
	
	$mega_final .= '</div>';
	
//*Stats here* HP, Attack, Defense, Sp.Attack, Sp.Def, Speed, Total: *//

	$basestats_all = array($basestats_decode[0], $basestats_decode[1], $basestats_decode[2], $basestats_decode[3], $basestats_decode[4],$basestats_decode[5]);
	$stats_total = array_sum($basestats_all);

	$stats ='<div class="small-6 columns">HP</div>'.'<div class="small-6 columns">'.$basestats_decode[0].'</div>';
	$stats .='<div class="small-6 columns">Attack</div>'.'<div class="small-6 columns">'.$basestats_decode[1].'</div>';
	$stats .='<div class="small-6 columns">Defense</div>'.'<div class="small-6 columns">'.$basestats_decode[2].'</div>';
	$stats .='<div class="small-6 columns">Sp.Attack</div>'.'<div class="small-6 columns">'.$basestats_decode[3].'</div>';
	$stats .='<div class="small-6 columns">Sp.Def</div>'.'<div class="small-6 columns">'.$basestats_decode[4].'</div>';
	$stats .='<div class="small-6 columns">Speed</div>'.'<div class="small-6 columns">'.$basestats_decode[5].'</div>';
	$stats .='<div class="small-6 columns">Total</div>'.'<div class="small-6 columns">'.$stats_total.'</div>';


	$allfield = array(
	'tier',
	'types',
	'base_stats',
	'abilities',
	'learned_moves',
	'tm',
	'hm',
	'pre-evo',
	'evo',
	'mega',
	'egg_moves',
	'genus',
	'color',
	'gender_rate',
	'capture_rate',
	'base_happiness',
	'is_baby',
	'hatch_counter',
	'has_gender_differences',
	'growth_rate',
	'height',
	'weight',
	'base_experience',
	'egg_groups'
	) ;
	
	for($i=0;$i <count($allfield); $i++){
	
		$j=$allfield[$i];
		if (!isset($results[$j]) || $results[$j] =='' || $results[$j] ==' ') {
			$results[$j] = '-';
		}
	}
	
	
	echo '<div class="row">
  
  <div class="small-4 columns">Pokedex ID</div>
  <div class="small-8 columns">'.$results['pokedex_number'].'</div>
  <div class="small-4 columns">Name</div>
  <div class="small-8 columns">'.$results['name'].', '.$results['name_de'].'(DE), </br>'.$results['name_fr'].'(FR), '.$results['name_ja'].'(JP)</div>
  <div class="small-4 columns">Tier</div>
  <div class="small-8 columns">'.$results['tier'].'</div>
  <div class="small-4 columns">Types</div>
  <div class="small-8 columns">'.$results['types'].'</div>
  <div class="small-4 columns">Stats</div>
  <div class="small-6 columns">'.$stats.'</div>
  <div class="small-4 columns"></br>Abilities</div>
  <div class="small-8 columns"></br>'.$abilities_final.'</div>
  <div class="small-4 columns"></br>Learned Moves (Level)</div>
  <div class="small-8 columns"></br>'.$learnedmove_final.'</div>
  <div class="small-4 columns"></br>TM</div>
  <div class="small-8 columns"></br>'.$tm_final.'</div>
  <div class="small-4 columns"></br>HM</div>
  <div class="small-8 columns"></br>'.$hm_final.'</div>
  <div class="small-4 columns">Pre-EVO</div>
  <div class="small-8 columns">'.$preevo_final.'</div>
  <div class="small-4 columns">EVO</div>
  <div class="small-8 columns">'.$evo_final.'</div>
  <div class="small-4 columns">Mega-Evo</div>
  <div class="small-8 columns">'.$mega_final.'</div>
  <div class="small-4 columns">Egg Moves</div>
  <div class="small-8 columns">'.$eggmoves_final.'</div>
  <div class="small-4 columns"></br>Genus</div>
  <div class="small-8 columns"></br>'.$results['genus'].'</div>
  <div class="small-4 columns">Color</div>
  <div class="small-8 columns">'.$results['color'].'</div>
  <div class="small-4 columns">Gender Rate</div>
  <div class="small-8 columns">'.$results['gender_rate'].'</div>
  <div class="small-4 columns">Capture Rate</div>
  <div class="small-8 columns">'.$results['capture_rate'].'</div>
  <div class="small-4 columns">Base Happiness</div>
  <div class="small-8 columns">'.$results['base_happiness'].'</div>
  <div class="small-4 columns">Baby</div>
  <div class="small-8 columns">'.$results['is_baby'].'</div>
  <div class="small-4 columns">Hatch Counter</div>
  <div class="small-8 columns">'.$results['hatch_counter'].'</div>
  <div class="small-4 columns">Gender Differences</div>
  <div class="small-8 columns">'.$results['has_gender_differences'].'</div>
  <div class="small-4 columns">Growth Rate</div>
  <div class="small-8 columns">'.$results['growth_rate'].'</div>
  <div class="small-4 columns">Height</div>
  <div class="small-8 columns">'.$results['height'].'</div>
  <div class="small-4 columns">Weight</div>
  <div class="small-8 columns">'.$results['weight'].'</div>
  <div class="small-4 columns">Base Experience</div>
  <div class="small-8 columns">'.$results['base_experience'].'</div>
  <div class="small-4 columns">Egg Groups</div>
  <div class="small-8 columns">'.$results['egg_groups'].'</div>  
  
</div>';

//print_r( $results );
//print_r($path);
}


function ingress_shortcode($atts, $content=null){
    global $wpdb;
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );

	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);
	
	
	
	
}

// flush_rules() if our rules are not yet included
function my_flush_rules(){
    $rules = get_option( 'rewrite_rules' );

	if ( !isset($rules['(pokedex)/(.+)$']) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function my_insert_rewrite_rules( $rules ) {
	$newrules = array();
	$newrules['pokedex/(.+)$'] = 'index.php?pagename=pokedex';
	return $newrules + $rules;
}

add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_action( 'wp_loaded','my_flush_rules' );

?>
