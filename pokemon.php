<?php
/* Print the contents of $result looping through each row returned in the result */

//test 123
//test 246
//test 789
//test push after merge 890

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

	$sql = "SELECT * FROM `pokedex` WHERE `pokedex_id` LIKE '".$path[2]."' LIMIT 0, 30";
	$results = $wpdb->get_row( $sql, ARRAY_A );

	$move = "SELECT * FROM `move`";
	$movedata = $wpdb->get_row( $move, ARRAY_A );

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

	$height = $results['height'];
	$height_decode = json_decode($height,true);

	$weight = $results['weight'];
	$weight_decode = json_decode($weight,true);

	$egggroup = $results['egg_groups'];
	$egggroup_decode = json_decode($egggroup,true);

//specify pokemon types here//

	$types = $results['types'];
	$types_decode = json_decode($types,true);

//testing function

	function get_evo($evo_name) {
		$evo_arr = explode(' (',$evo_name);
		$nice_name = $evo_arr[0];
		$nice_name = strtolower($nice_name);
		$nice_name = str_replace(' ','-',$nice_name);
		$nice_name = str_replace('[','',$nice_name);
		$nice_name = str_replace('"','',$nice_name);
		return $nice_name;
	}

	function get_mega($mega_name) {
		$mega_arr = explode(' ',$mega_name);
		$nice_name = $mega_arr[1].'-'.$mega_arr[0].'-'.$mega_arr[2];
		$nice_name = strtolower($nice_name);
//		$nice_name = str_replace(' ','-',$nice_name);
//		$nice_name = str_replace('[','',$nice_name);
//		$nice_name = str_replace(']','',$nice_name);
//		$nice_name = str_replace('"','',$nice_name);
		return $nice_name;
	}

	$types_arr = array(
	0 => "Normal",
	1 => "Fight",
	2 => "Flying",
	3 => "Poison",
	4 => "Ground",
	5 => "Rock",
	6 => "Bug",
	7 => "Ghost",
	8 => "Steel",
	9 => "Fire",
	10 => "Water",
	11 => "Grass",
	12 => "Electric",
	13 => "Psychic",
	14 => "Ice",
	15 => "Dragon",
	16 => "Dark",
	17 => "Fairy",
	'Monster' => "Monster",
	'Water 1' => "Water 1",
	'Bug'=> "Bug",
	'Flying'=> "Flying",
	'Field' => "Field",
	'Fairy' => "Fairy",
	'Grass' => "Grass",
	'Undiscovered' => "Undiscovered",
	'Human-like' => "Human-Like",
	'Water 3' => "Water 3",
	'Mineral' => "Mineral",
	'Amorphous' => "Amorphous",
	'Water 2' => "Water 2",
	'Ditto' => "Ditto",
	'Dragon' => "Dragon",
	'Gender unknown' => "Gender Unknown"
);

	$types2 = '<div class="row">';

	foreach ($types_decode as $types1){
	$types2 .= '<div class="small-6 columns">'.$types_arr[$types1].'</div>';
	}
	$types2 .= '</div>';

//specify egg groups here

	$egggroup2 = '<div class="row">';

	if ($egggroup_decode == '') {
		$egggroup2 .= '<div class="small-6 columns">-</div>';
		} else {
			foreach ($egggroup_decode as $egggroup1){
				$egggroup2 .= '<div class="small-6 columns">'.$types_arr[$egggroup1].'</div>';
				}
			}

	$egggroup2 .= '</div>';


//specify tm/hm name here;

	$tm_array = array (
		TM1 => "Hone Claws",
		TM2 => "Dragon Claw",
		TM3 => "Psyshock",
		TM4 => "Calm Mind",
		TM5 => "Roar",
		TM06 => "Toxic",
		TM7 => "Hail",
		TM8 => "Bulk Up",
		TM9 => "Venoshock",
		TM10 => "Hidden Power",
		TM11 => "Sunny Day",
		TM12 => "Taunt",
		TM13 => "Ice Beam",
		TM14 => "Blizzard",
		TM15 => "Hyper Beam",
		TM16 => "Light Screen",
		TM17 => "Protect",
		TM18 => "Rain Dance",
		TM19 => "Telekinesis",
		TM20 => "Safeguard",
		TM21 => "Frustration",
		TM22 => "Solar Beam",
		TM23 => "Smack Down",
		TM24 => "Thunderbolt",
		TM25 => "Thunder",
		TM26 => "Earthquake",
		TM27 => "Return",
		TM28 => "Dig",
		TM29 => "Psychic",
		TM30 => "Shadow Ball",
		TM31 => "Brick Break",
		TM32 => "Double Team",
		TM33 => "Reflect",
		TM34 => "Sludge Wave",
		TM35 => "Flamethrower",
		TM36 => "Sludge Bomb",
		TM37 => "Sandstorm",
		TM38 => "Fire Blast",
		TM39 => "Rock Tomb",
		TM40 => "Aerial Ace",
		TM41 => "Torment",
		TM42 => "Facade",
		TM43 => "Flame Charge",
		TM44 => "Rest",
		TM45 => "Attract",
		TM46 => "Thief",
		TM47 => "Low Sweep",
		TM48 => "Round",
		TM49 => "Echoed Voice",
		TM50 => "Overheat",
		TM51 => "Ally Switch",
		TM52 => "Focus Blast",
		TM53 => "Energy Ball",
		TM54 => "False Swipe",
		TM55 => "Scald",
		TM56 => "Fling",
		TM57 => "Charge Beam",
		TM58 => "Sky Drop",
		TM59 => "Incinerate",
		TM60 => "Quash",
		TM61 => "Will-O-Wisp",
		TM62 => "Acrobatics",
		TM63 => "Embargo",
		TM64 => "Explosion",
		TM65 => "Shadow Claw",
		TM66 => "Payback",
		TM67 => "Retaliate",
		TM68 => "Giga Impact",
		TM69 => "Rock Polish",
		TM70 => "Flash",
		TM71 => "Stone Edge",
		TM72 => "Volt Switch",
		TM73 => "Thunder Wave",
		TM74 => "Gyro Ball",
		TM75 => "Swords Dance",
		TM76 => "Struggle Bug",
		TM77 => "Psych Up",
		TM78 => "Bulldoze",
		TM79 => "Frost Breath",
		TM80 => "Rock Slide",
		TM81 => "X-Scissor",
		TM82 => "Dragon Tail",
		TM83 => "Infestation",
		TM84 => "Poison Jab",
		TM85 => "Dream Eater",
		TM86 => "Grass Knot",
		TM87 => "Swagger",
		TM88 => "Sleep Talk",
		TM89 => "U-turn",
		TM90 => "Substitute",
		TM91 => "Flash Cannon",
		TM92 => "Trick Room",
		TM93 => "Wild Charge",
		TM94 => "Rock Smash",
		TM95 => "Snarl",
		TM96 => "Nature Power",
		TM97 => "Dark Pulse",
		TM98 => "Power-Up Punch",
		TM99 => "Dazzling Gleam",
		TM100 => "Confide"
		);

	$hm_array = array (
		HM01 => "Cut",
		HM02 => "Fly",
		HM04 => "Strength",
		HM03 => "Surf",
		HM05 => "Waterfall",
		HM06 => "Dive"
		);

//decoded arrange div here
	$tm_count = count($tm_decode);
	$tm_final =	'<div class="row">';
	$t = 0;
	if ($tm_count==$t){
		$tm_final .= '<div class="small-4 columns">-</div>';
		}else{
			foreach ($tm_decode as $tm1){
				$t++;
				if ($tm_count == $t) {
					$tm_final .= '<div class="small-6 columns end">'.$tm_array[$tm1].' ('.$tm1.')</div>';
				} else {
					$tm_final .= '<div class="small-6 columns">'.$tm_array[$tm1].' ('.$tm1.')</div>';
			}
		}
	}

	$tm_final .= '</div>';

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
				$hm_final .= '<div class="small-12 columns end">'.$hm_array[$hm1].' ('.$hm1.')</div>';
				} else {
				$hm_final .= '<div class="small-12 columns">'.$hm_array[$hm1].' ('.$hm1.')</div>';
				}
			}
		}

	$hm_final .= '</div>';

	$learnedmove_count = count($learnedmove_decode);
	$learnedmove_final = '<div class="row">';
	$lm = 0;
	foreach ($learnedmove_decode as $lm1){
		$lm++;
		if ($learnedmove_count == $lm) {
			$learnedmove_final .= '<div class="small-6 columns end">'.$lm1.'</div>';
		} else {
			$learnedmove_final .= '<div class="small-6 columns">'.$lm1.'</div>';
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
				$eggmoves_final .= '<div class="small-6 columns end">'.$em1.'</div>';
				} else {
				$eggmoves_final .= '<div class="small-6 columns">'.$em1.'</div>';
				}
			}
		}

	$eggmoves_final .= '</div>';

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
				$preevo_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($results['pre-evo']).'">'.$preevo1.'</a></div>';
				} else {
				$preevo_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($results['pre-evo']).'">'.$preevo1.'</a></div>';
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
				$evo_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($results['evo']).'">'.$evo1.'</a></div>';
			} else {
				$evo_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($results['evo']).'">'.$evo1.'</a></div>';
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
		} else {
		foreach ($mega_decode as $mega1){
			$mega++;
			if ($mega_count == $mega) {
				$mega_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_mega($mega1).'">'.$mega1.'</a></div>';
			} else {
				$mega_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_mega($mega1).'">'.$mega1.'</a></div>';
			}
		}
	}

	$mega_final .= '</div>';

//height and weight here//

	$height_10 = $results['height']/10;
	$weight_10 = $results['weight']/10;

	$height_final = '<div class="row">';
	$height0 = '0';
	$height2 = '-';
	if ($height_decode==$height0){
		$height_final .= '<div class="small-4 columns">'.$height2.'</div>';
		} else {
		$height_final .= '<div class="small-4 columns">'.$height_10.'m</div>';
	}

	$height_final .= '</div>';

	$weight_final = '<div class="row">';
	$weight0 = '0';
	$weight2 = '-';
	if ($weight_decode==$weight0){
		$weight_final .= '<div class="small-4 columns">'.$weight2.'</div>';
		} else {
		$weight_final .= '<div class="small-4 columns">'.$weight_10.'kg</div>';
	}

	$weight_final .= '</div>';

//*Stats here* HP, Attack, Defense, Sp.Attack, Sp.Def, Speed, Total: *//

	$basestats_all = array($basestats_decode[0], $basestats_decode[1], $basestats_decode[2], $basestats_decode[3], $basestats_decode[4],$basestats_decode[5]);
	$stats_total = array_sum($basestats_all);

	$stats ='<div class="row">';

	$stats .='<div class="small-6 columns">HP</div>'.'<div class="small-6 columns">'.$basestats_decode[0].'</div>';
	$stats .='<div class="small-6 columns">Attack</div>'.'<div class="small-6 columns">'.$basestats_decode[1].'</div>';
	$stats .='<div class="small-6 columns">Defense</div>'.'<div class="small-6 columns">'.$basestats_decode[2].'</div>';
	$stats .='<div class="small-6 columns">Sp.Attack</div>'.'<div class="small-6 columns">'.$basestats_decode[3].'</div>';
	$stats .='<div class="small-6 columns">Sp.Def</div>'.'<div class="small-6 columns">'.$basestats_decode[4].'</div>';
	$stats .='<div class="small-6 columns">Speed</div>'.'<div class="small-6 columns">'.$basestats_decode[5].'</div>';
	$stats .='<div class="small-6 columns">Total</div>'.'<div class="small-6 columns">'.$stats_total.'</div>';

	$stats .= '</div>';


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
		if (!isset($results[$j]) || $results[$j] =='' || $results[$j] ==' ' || $results[$j] =='0') {
			$results[$j] = '-';
		}
	}

	$image_name = $results['pokedex_id'];

	echo '<div class="img">
	<img src="http://pokemon.game-solver.com/wp-content/uploads/pokemongo/'.$image_name.'.png">
	</div>';

	echo '<div class="row">

	<div class="small-4 columns">Pokedex ID</div>
	<div class="small-8 columns">'.$results['pokedex_number'].'</div>
	<div class="small-4 columns">Name</div>
	<div class="small-8 columns">'.$results['name'].', '.$results['name_de'].'(DE), </br>'.$results['name_fr'].'(FR), '.$results['name_ja'].'(JP)</div>
	<div class="small-4 columns">Tier</div>
	<div class="small-8 columns">'.$results['tier'].'</div>
	<div class="small-4 columns">Types</div>
	<div class="small-8 columns">'.$types2.'</div>
	<div class="small-4 columns">Stats</div>
	<div class="small-8 columns">'.$stats.'</div>
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
	<div class="small-8 columns">'.$height_final.'</div>
	<div class="small-4 columns">Weight</div>
	<div class="small-8 columns">'.$weight_final.'</div>
	<div class="small-4 columns">Base Experience</div>
	<div class="small-8 columns">'.$results['base_experience'].'</div>
	<div class="small-4 columns">Egg Groups</div>
	<div class="small-8 columns">'.$egggroup2.'</div>

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
