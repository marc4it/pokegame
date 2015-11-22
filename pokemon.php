<?php
/*
Plugin Name: Pokemon
Plugin URI: http://yourdomain.com/
Description: A simple hello world wordpress plugin
Version: 1.0
Author: Marcus
Author URI: http://yourdomain.com
License: GPL
*/
	
function seo_loader_init() {
		global $wpdb;
		$urlArr = parse_url($_SERVER['REQUEST_URI']);
		$urlStr = (string) $urlArr;
		$urlPath = explode('/', $urlArr['path']);
	
		if (substr($urlPath[1],0,2) != 'wp') {
			if (($urlPath[2]) && ($urlPath[2] != '')) {
				$results = $wpdb->get_results( "SELECT * FROM pokedex" );
				$poke = $urlPath[2];
				$checkPoke = '';
				foreach($results as $row) {
					if ($row->pokedex_id == $poke) {
						$checkPoke = true;
					}
				}
				if (substr($urlPath[1],2) != 'wp') {
					if (!$checkPoke) {
						header('Location: /'.'pokedex'.'/');
						exit;
					}
				}
			}
		}
}

add_shortcode( 'poke', 'poke_dex' );

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
	
	$genderRate = $results['gender_rate'];
	$genderRate_decode = json_decode($genderRate,true);

//specify pokemon types here//

	$types = $results['types'];
	$types_decode = json_decode($types,true);

/*	function get_mega($mega_name) {
		$mega_arr = explode(' ',$mega_name);
		$nice_name = $mega_arr[1].'-'.$mega_arr[0].'-'.$mega_arr[2];
		$nice_name = strtolower($nice_name);
//		$nice_name = str_replace(' ','-',$nice_name);
//		$nice_name = str_replace('[','',$nice_name);
//		$nice_name = str_replace(']','',$nice_name);
//		$nice_name = str_replace('"','',$nice_name);
		return $nice_name;
	}
*/


	if ($results) {
	
		$types_arr = array(
		0 => "Normal",
		1 => "Fighting",
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
		
		$genderRate_arr = array (
		null => 'Genderless',
		-1 => 'Genderless',
		0 => 'Male only',
		1 => '1 ♀ : 7 ♂',
		2 => '1 ♀ : 3 ♂',
		4 => '1 ♀ : 1 ♂',
		6 => '3 ♀ : 1 ♂',
		8 => 'Female only',
		);
		
		$genderRate2 = '<div class="row">';

		if ($genderRate_decode == '') {
			$genderRate2 .= '<div class="small-6 columns">Genderless</div>';
			} else {
				$genderRate2 .= '<div class="small-6 columns">'.$genderRate_arr[$results['gender_rate']].'</div>';
			}
			
		$genderRate2 .= '</div>';

		$types2 = '<div class="row collapse">';

		foreach ($types_decode as $types1) {
		$types2 .= '<div class="small-5 medium-3 columns end '.$types_arr[$types1].' text-center">'.$types_arr[$types1].'</div>';
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
			TM09 => "Venoshock",
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
						$tm_final .= '<div class="small-12 medium-6 columns end">'.$tm_array[$tm1].' ('.$tm1.')</div>';
					} else {
						$tm_final .= '<div class="small-12 medium-6 columns">'.$tm_array[$tm1].' ('.$tm1.')</div>';
				}
			}
		}

		$tm_final .= '</div>';

		$hm_count = count($hm_decode);
		$hm_final = '<div class="row">';
		$hm = '0';
		$hm2 = '-';
		if ($hm_count==$hm){
			$hm_final .= '<div class="small-4 medium-6 columns">'.$hm2.'</div>';
			}else{
			foreach ($hm_decode as $hm1){
				$hm++;
				if ($hm_count == $hm) {
					$hm_final .= '<div class="small-12 medium-6 columns end">'.$hm_array[$hm1].' ('.$hm1.')</div>';
					} else {
					$hm_final .= '<div class="small-12 medium-6 columns">'.$hm_array[$hm1].' ('.$hm1.')</div>';
					}
				}
			}

		$hm_final .= '</div>';

		$learnedmove_count = count($learnedmove_decode);
		$learnedmove_final = '<div class="row">';
		$lm = 0;
		if ($learnedmove_count == $lm) {
				$learnedmove_final .= '<div class="small-12 columns">-</div>';
			} else {
				foreach ($learnedmove_decode as $lm1) {
				$learnedmove_final .= '<div class="small-12 medium-6 columns">'.$lm1.'</div>';
			}
		}
	
		$learnedmove_final .= '</div>';

		$abilities_count = count($abilities_decode);
		$abilities_final = '<div class="row">';
		$ab = 0;
		foreach ($abilities_decode as $ab1){
			$ab++;
			if ($abilities_count == $ab) {
				$abilities_final .= '<div class="small-6 medium-6 columns end">'.$ab1.'</div>';
			} else {
				$abilities_final .= '<div class="small-6 medium-6 columns">'.$ab1.'</div>';
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
	
	//testing function for evo

		function get_evo($evo_name) {
			if (strpos($evo_name,'Burmy ') !== false) {
				$replace = str_replace('(','',$evo_name);
				$replace = str_replace(')','',$replace);
				$replace_arr = explode(' ',$replace);
				$new_name = $replace_arr[0].'-'.$replace_arr[1];
				$new_name = strtolower($new_name);
				return $new_name;
			} else {
			if (strpos($evo_name,'Wormadam') !== false) {
				$replace = str_replace('(','',$evo_name);
				$replace = str_replace(')','',$replace);
				$replace_arr = explode(' ',$replace);
				$new_name = $replace_arr[0].'-'.$replace_arr[1];
				$new_name = strtolower($new_name);
				return $new_name;
			} else {
			if (strpos($evo_name,'Mega') !== false) {
				$evo_arr = explode(' ',$evo_name);
				$tmp1 = $evo_arr[0];
				$evo_arr[0] = $evo_arr[1];
				$evo_arr[1] = $tmp1;
				$evo_imp = implode(' ',$evo_arr);
				$evo_imp = strtolower($evo_imp);
				$evo_imp = str_replace(' ','-',$evo_imp);
				return $evo_imp;
			} else {
				$evo_arr = explode(' (',$evo_name);
				$nice_name = $evo_arr[0];
				$nice_name = strtolower($nice_name);
				$nice_name = str_replace(' ','-',$nice_name);
				$nice_name = str_replace('[','',$nice_name);
				$nice_name = str_replace('"','',$nice_name);
				return $nice_name;
				}
			}
		}
	}

	

	/*	echo $results['evo'];
		echo '</br>';
		print_r ($evo_decode);
		echo '</br>';
		echo $evo_decode;
	*/

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
					$preevo_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($preevo1).'">'.$preevo1.'</a></div>';
					} else {
					$preevo_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($preevo1).'">'.$preevo1.'</a></div>';
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
					$evo_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($evo1).'">'.$evo1.'</a></div>';
				} else {
					$evo_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($evo1).'">'.$evo1.'</a></div>';
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
					$mega_final .= '<div class="small-12 columns end"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($mega1).'">'.$mega1.'</a></div>';
				} else {
					$mega_final .= '<div class="small-12 columns"><a href="http://pokemon.game-solver.com/pokedex/'.get_evo($mega1).'">'.$mega1.'</a></div>';
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

		$stats .='<div class="small-6 columns">HP</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[0].'</div>';
		$stats .='<div class="small-6 columns">Attack</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[1].'</div>';
		$stats .='<div class="small-6 columns">Defense</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[2].'</div>';
		$stats .='<div class="small-6 columns">Sp.Attack</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[3].'</div>';
		$stats .='<div class="small-6 columns">Sp.Def</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[4].'</div>';
		$stats .='<div class="small-6 columns">Speed</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[5].'</div>';
		$stats .='<div class="small-6 columns">Total</div>'.'<div class="small-3 medium-2 small-text-right columns end">'.$stats_total.'</div>';

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

	//button next/previous here
	
		$previous = $wpdb->get_results( "SELECT `pokedex_id` FROM `pokedex` WHERE `id` = ".($results['id']-1)."", ARRAY_A);
		$next = $wpdb->get_results( "SELECT `pokedex_id` FROM `pokedex` WHERE `id` = ".($results['id']+1)."", ARRAY_A);


		echo '
		<a href="http://pokemon.game-solver.com/pokedex/'.$previous['0']['pokedex_id'].'" class="button tiny round left">Previous</br>Pokemon</a>
		<a href="http://pokemon.game-solver.com/pokedex/'.$next['0']['pokedex_id'].'" class="button tiny round right">Next</br>Pokemon</a>
		';

		echo '<div class="img">
		<img src="http://pokemon.game-solver.com/wp-content/uploads/pokemongo/'.$image_name.'.png">
		<div class="small-12 columns"><br /></div>
		</div>';

		echo '<div class="row">

		<div class="small-4 columns">PokedexID</div>
		<div class="small-8 columns">'.$results['pokedex_number'].'</div>
		<div class="small-4 columns">Name</div>
		<div class="small-8 columns">'.$results['name'].'</div>
		<div class="small-4 columns">Name (DE)</div>
		<div class="small-8 columns">'.$results['name_de'].'</div>
		<div class="small-4 columns">Name (FR)</div>
		<div class="small-8 columns">'.$results['name_fr'].'</div>
		<div class="small-4 columns">Name (JP)</div>
		<div class="small-8 columns">'.$results['name_ja'].'</div>
		<div class="small-4 columns">Tier</div>
		<div class="small-8 columns">'.$results['tier'].'</div>
		<div class="small-4 columns">Types</div>
		<div class="small-8 columns">'.$types2.'</div>
		<div class="small-4 columns">Pre-EVO</div>
		<div class="small-8 columns">'.$preevo_final.'</div>
		<div class="small-4 columns">EVO</div>
		<div class="small-8 columns">'.$evo_final.'</div>
		<div class="small-4 columns">Mega-Evo</div>
		<div class="small-8 columns">'.$mega_final.'</div>
		<div class="small-4 columns">Stats</div>
		<div class="small-8 columns">'.$stats.'</div>
		<div class="small-6 medium-4 columns"></br>Genus</div>
		<div class="small-6 medium-8 columns"></br>'.$results['genus'].'</div>
		<div class="small-6 medium-4 columns">Color</div>
		<div class="small-6 medium-8 columns">'.$results['color'].'</div>
		<div class="small-6 medium-4 columns">Gender Rate</div>
		<div class="small-6 medium-8 columns">'.$genderRate2.'</div>
		<div class="small-6 medium-4 columns">Capture Rate</div>
		<div class="small-6 medium-8 columns">'.$results['capture_rate'].'</div>
		<div class="small-6 medium-4 columns">Base Happiness</div>
		<div class="small-6 medium-8 columns">'.$results['base_happiness'].'</div>
		<div class="small-6 medium-4 columns">Baby</div>
		<div class="small-6 medium-8 columns">'.$results['is_baby'].'</div>
		<div class="small-6 medium-4 columns">Hatch Counter</div>
		<div class="small-6 medium-8 columns">'.$results['hatch_counter'].'</div>
		<div class="small-7 medium-4 columns">Gender Differences</div>
		<div class="small-5 medium-8 columns">'.$results['has_gender_differences'].'</div>
		<div class="small-6 medium-4 columns">Growth Rate</div>
		<div class="small-6 medium-8 columns">'.$results['growth_rate'].'</div>
		<div class="small-6 medium-4 columns">Height</div>
		<div class="small-6 medium-8 columns">'.$height_final.'</div>
		<div class="small-6 medium-4 columns">Weight</div>
		<div class="small-6 medium-8 columns">'.$weight_final.'</div>
		<div class="small-6 medium-4 columns">Base Experience</div>
		<div class="small-6 medium-8 columns">'.$results['base_experience'].'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">Egg Groups</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>Egg Groups</div>
		<div class="small-12 medium-8 columns"></br>'.$egggroup2.'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">Abilities</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>Abilities</div>
		<div class="small-12 medium-8 columns"></br>'.$abilities_final.'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">Learned Moves (Level)</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>Learned Moves (Level)</div>
		<div class="small-12 medium-8 columns"></br>'.$learnedmove_final.'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">HM</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>HM</div>
		<div class="small-12 medium-8 columns"></br>'.$hm_final.'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">TM</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>TM</div>
		<div class="small-12 medium-8 columns"></br>'.$tm_final.'</div>
		<div class="show-for-small-only small-12 columns"></br><span class="round alert label">Egg Moves</span></div>
		<div class="show-for-medium-up small-12 medium-4 columns"></br>Egg Moves</div>
		<div class="small-12 medium-8 columns"></br>'.$eggmoves_final.'</div>

		</div>';
	
		echo '
		</br><a href="http://pokemon.game-solver.com/pokedex/'.$previous['0']['pokedex_id'].'" class="button tiny round left">Previous</br>Pokemon</a>
		<a href="http://pokemon.game-solver.com/pokedex/'.$next['0']['pokedex_id'].'" class="button tiny round right">Next</br>Pokemon</a>
		';

	
		} else {
			
			global $wpdb;
			$sql = "SELECT * FROM `pokedex`";
			$data = $wpdb->get_results( $sql, ARRAY_A );
			
			echo '<div style="background-color:#CCCCCC"><div class=row>
			<div class="small-5 columns small-offset-2 medium-offset-1 text-left"><strong>Pokemon</strong></div>
			<div class="small-3 columns show-for-medium-up text-center"><strong>Types</strong></div>
			<div class=" medium-3 columns show-for-medium-up text-center"><strong>Stats Total</strong></div>
			<div class=" small-3 show-for-small columns text-center"><strong>Stats</strong></div>
			</div></div>';
			
			for($i=0;$i<794;$i++){
			
				$types_arr = array(
				0 => "Normal",
				1 => "Fighting",
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
				17 => "Fairy"
				);
				
			
				$name = $data[$i]['name'];
				$stats = $data[$i]['base_stats'];
				$stats_decode = json_decode ($stats,true);
				$types = $data[$i]['types'];
				$types_decode = json_decode ($types,true);
				$link = $data[$i]['pokedex_id'];
			
				$stats_all = array($stats_decode[0], $stats_decode[1], $stats_decode[2], $stats_decode[3], $stats_decode[4],$stats_decode[5]);
				$stats_total = array_sum($stats_all);
				
				$types_count = count($types_decode);
				$types2 = '<div class="row medium-uncollapse large-uncollapse">';
				
				foreach ($types_decode as $types1) {
					if ($types_count == 2) {
						$types2 .= '<div class="small-6 columns text-center '.$types_arr[$types1].'">'.$types_arr[$types1].'</div>';
						} else {
							$types2 .= '<div class="small-12 columns text-center '.$types_arr[$types1].'">'.$types_arr[$types1].'</div>';
						}
					}

				$types2 .= '</div>';
			
				echo '
				
				<div class=row>
				<div class="small-2 medium-1 columns imgMiddle"><img src="http://pokemon.game-solver.com/wp-content/uploads/pokemongo/mini/'.$link.'-mini.png"></div>
				<div class="small-7 medium-5 columns text-left"><a href="http://pokemon.game-solver.com/pokedex/'.$link.'">'.$name.'</a></div>
				<div class="medium-3 columns show-for-medium-up text-center">'.$types2.'</div>
				<div class="small-3 medium-3 columns text-center">'.$stats_total.'</div>
			
				</div>';
			
			}
		}
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
add_action( 'init', 'seo_loader_init', 0 );
?>
