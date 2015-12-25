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


// function here!

function no_space($space_name) {
	if (strpos($space_name,'(') !== false) {
		$new_name = explode(' (',$space_name);
		$new_name = str_replace(' ','-',$new_name[0]);
		$new_name = strtolower($new_name);
		return $new_name;
	} elseif (strpos($space_name,' ') !== false) {
		$new_name = str_replace(' ','-',$space_name);
		$new_name = strtolower($new_name);
		return $new_name;
	} else {
		$new_name = strtolower($space_name);
		return $new_name;
	}
}

function return_space($space_name) {
	if (strpos($space_name,'-') !== false) {
		$new_name = explode('-',$space_name);
		$new_name[0] = ucfirst(strtolower($new_name[0]));
		$new_name[1] = ucfirst(strtolower($new_name[1]));
		$new_name = implode(' ',$new_name);
		return $new_name;
	} else {
		$new_name = ucfirst(strtolower($space_name));
		return $new_name;
	}
}

function to_hm($move) {
	if (strpos($move,'cut') !== false) {
	$move = 'HM01';
	return $move;
	} elseif (strpos($move,'fly') !== false) {
	$move = 'HM02';
	return $move;
	} elseif (strpos($move,'surf') !== false) {
	$move = 'HM03';
	return $move;
	} elseif (strpos($move,'strength') !== false) {
	$move = 'HM04';
	return $move;
	} elseif (strpos($move,'waterfall') !== false) {
	$move = 'HM05';
	return $move;
	} elseif (strpos($move,'dive') !== false) {
	$move = 'HM06';
	return $move;
	}
}
	
function seo_loader_init() {
	global $wpdb;
	$urlArr = parse_url($_SERVER['REQUEST_URI']);
	$urlPath = explode('/', $urlArr['path']);

	if (isset($urlPath[1])) { $pokeaction1 = $urlPath[1]; }
	else { $pokeaction1 = ""; }
	if (isset($urlPath[2])) { $pokeaction2 = $urlPath[2]; }
	else { $pokeaction2 = ""; }
	
	if (($pokeaction2) && ($pokeaction1 == 'pokedex')) {
		
		$results = $wpdb->get_results( "SELECT * FROM pokedex;" );
		$poke = $pokeaction2;
		$checkPoke = '';
		foreach($results as $row) {
			if ($row->pokedex_id == $poke) {
				$checkPoke = true;
			}
		}
		if (!$checkPoke) {
			header('Location: /'.'pokedex'.'/');
			exit;
		}
	} elseif (($pokeaction2) && ($pokeaction1 == 'move')) {
		$results = $wpdb->get_results( "SELECT * FROM `move`;" );
		$move = $pokeaction2;
		$checkMove = '';
		foreach($results as $row) {
			if ($row->id_name == $move) {
				$checkMove = true;
				break;
			}
		}
		if (!$checkMove) {
			header('Location: /'.'move'.'/');
			exit;
		}
	} elseif (($pokeaction2) && ($pokeaction1 == 'type')){
		$checkMove = '';
		if (($pokeaction2 == 'normal') || ($pokeaction2 == 'fighting') || ($pokeaction2 == 'flying') || ($pokeaction2 == 'poison') || ($pokeaction2 == 'ground') || ($pokeaction2 == 'rock') || ($pokeaction2 == 'bug') || ($pokeaction2 == 'ghost') || ($pokeaction2 == 'steel') || ($pokeaction2 == 'fire') || ($pokeaction2 == 'water') || ($pokeaction2 == 'grass') || ($pokeaction2 == 'electric') || ($pokeaction2 == 'psychic') || ($pokeaction2 == 'ice') || ($pokeaction2 == 'dragon') || ($pokeaction2 == 'dark') || ($pokeaction2 == 'fairy')) {
			$checkMove = true;
		}
		if (!$checkMove) {
			header('Location: /'.'type'.'/');
			exit;
		}
	} elseif (($pokeaction2) && ($pokeaction1 == 'abilities')) {
		$results = $wpdb->get_results( "SELECT * FROM `abilities`;" );
		$move = $pokeaction2;
		$checkMove = '';
		foreach($results as $row) {
			$name= $row->name;
			if (no_space($name) == $move) {
				$checkMove = true;
				break;
			}
		}
		if (!$checkMove) {
			header('Location: /'.'abilities'.'/');
			exit;
		}
	} elseif (($pokeaction2) && ($pokeaction1 == 'hm-moves')) {
		$results = $wpdb->get_results( "SELECT * FROM `hm`;" );
		$move = $pokeaction2;
		$checkMove = '';
		foreach($results as $row) {
			$name= $row->name;
			if (strtolower($name) == $move) {
				$checkMove = true;
				break;
			}
		}
		if (!$checkMove) {
			header('Location: /'.'hm-moves'.'/');
			exit;
		}
	}
}

/* always define global $wpdb before query db */

//abilities here

function poke_hm($atts, $content=null) {
	global $wpdb;
	
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );
	
	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);

	$path2 = ucfirst(strtolower($path[2]));
	
	$sql = "SELECT * FROM `hm` WHERE `name` LIKE '".$path2."' LIMIT 0, 10;";
	$results = $wpdb->get_row( $sql, ARRAY_A );
	
	if ($results) {
		
		$newhm = '"'.to_hm($path[2]).'"';
		$sql2 = "SELECT * FROM `pokedex` WHERE `hm` LIKE '%".$newhm."%' LIMIT 0, 300;";
		$results2 = $wpdb->get_results( $sql2, ARRAY_A );
		
		$resultscount = count ($results2);
		$i=0;
		$pokemon_list = '<br/><div class="row">';
		
		foreach ($results2 as $row) {
			$i++;
			if ($i == $resultscount) {
				$pokemon_list .= '<div class="small-2 medium-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" height="25" width="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5 columns end"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			} else {
				$pokemon_list .= '<div class="small-2 medium-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" height="25" width="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5 columns"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			}
		}
		
		$pokemon_list .= '</div>';
		
		echo '<div class="row">
		<div class="small-4 columns">HM</div>
		<div class="small-8 columns">'.$results['hm'].'</div>
		<div class="small-4 columns">Name</div>
		<div class="small-8 columns">'.$results['name'].'</div>
		<div class="small-4 columns">Description</div>
		<div class="small-8 columns">'.$results['description'].'</div>
		</div>';
		echo '<br/><div class="row">
		<div class="small-12 columns"><h3>Pokemon with '.to_hm($path[2]).' '.$path2.'</h3></div>
		</div>';
		echo $pokemon_list;
	} else {
		
		$sql = "SELECT * FROM `hm` ;";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		
		$list = '<div class="row">';
		
		foreach ($results as $rows) {
		
			$hmrow = '"'.$rows['hm'].'"';
			$sqlrow = "SELECT * FROM `pokedex` WHERE `hm` LIKE '%".$hmrow."%' LIMIT 0, 300;";
			$resultsrow = $wpdb->get_results( $sqlrow, ARRAY_A );
			$resultscount = count ($resultsrow);
	
			$list .= '
				<div class="small-2 small-offset-1 columns"><a href="/hm-moves/'.strtolower($rows['name']).'/" title="'.$rows['hm'].'">'.$rows['hm'].'</a></div>
				<div class="small-2 small-offset-1 columns"><a href="/hm-moves/'.strtolower($rows['name']).'/" title="'.$rows['name'].'">'.$rows['name'].'</a></div>
				<div class="small-6 columns"><div class="small-8 columns text-right">'.$resultscount.'</div></div>
			';
		}
		
		$list .='</div>';

		echo '
			<div style="background-color:#CCCCCC">
				<div class=row>
					<div class="small-2 small-offset-1 columns">HM</div>
					<div class="small-2 small-offset-1 columns">Name</div>
					<div class="small-6 columns">
						<div class="small-9 columns text-right">Pokemon</div>
					</div>
				</div>
			</div>
		';
		echo $list;
	}
}

function poke_abilities($atts, $content=null) {
	global $wpdb;
	
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );
	
	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);
	
	$path2 = $path[2];
	$path3 = return_space($path2);
	
	$sql = "SELECT * FROM `abilities` WHERE `name` LIKE '".$path3."' LIMIT 0, 300;";
	$results = $wpdb->get_row( $sql, ARRAY_A );

	if ($results) {
		
		$abi1 = '"'.$path3.'"';
		
		$sql2 = "SELECT * FROM `pokedex` WHERE `abilities` LIKE '%".$abi1."%' LIMIT 0, 300;";
		$results2 = $wpdb->get_results( $sql2, ARRAY_A );
		
		$resultscount = count ($results2);
		$i=0;
		$pokemon_list = '<br/><div class="row">';
		
		foreach ($results2 as $row) {
			$i++;
			if ($i == $resultscount) {
				$pokemon_list .= '<div class="small-2 medium-1 large-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" width="25" height="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5  large-5 columns end"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			} else {
				$pokemon_list .= '<div class="small-2 medium-1 large-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" width="25" height="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5 large-5 columns end"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			}
		}
		
		$pokemon_list .= '</div>';

		echo '<div class="row">
		<div class="small-4 columns">Name</div>
		<div class="small-8 columns">'.$results['name'].'</div>
		<div class="small-4 columns">Name(DE)</div>
		<div class="small-8 columns">'.$results['name_de'].'</div>
		<div class="small-4 columns">Name(FR)</div>
		<div class="small-8 columns">'.$results['name_fr'].'</div>
		<div class="small-4 columns">Name(JA)</div>
		<div class="small-8 columns">'.$results['name_ja'].'</div>
		<div class="small-4 columns">Description</div>
		<div class="small-8 columns">'.$results['description'].'</div>
		</div>';
		
		echo '<br/><div class="row">
		<div class="small-12 columns"><h3>Pokemon with '.$path3.'</h3></div>
		</div>';
		
		echo $pokemon_list;
		
	} else {
		
		$sql = "SELECT * FROM `abilities` LIMIT 0, 300;";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		
		$abi_list = '<div class="row">';
		
		foreach ($results as $row) {
			
			$abi2 = '"'.$row['name'].'"';
			$sql2 = "SELECT * FROM `pokedex` WHERE `abilities` LIKE '%".$abi2."%' LIMIT 0, 300;";
			$results2 = $wpdb->get_results( $sql2, ARRAY_A );
			$resultscount = count ($results2);
		
			$abi_list .= '<div class="small-7 medium-4 columns"><a href="/abilities/'.no_space($row['name']).'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			$abi_list .= '<div class="small-5 medium-2 columns"><div class="small-8 columns text-right">'.$resultscount.'</div></div>';
//			$abi_list .= '<div class="small-9 columns">'.$row['description'].'</div>';
		}
		
		$abi_list .= '</div>';

		echo '<div style="background-color:#CCCCCC"><div class=row>
			<div class="medium-4 show-for-medium-up columns"><strong>Name</strong></div>
			<div class="medium-2 show-for-medium-up columns"><strong>Pokemon</strong></div>
			<div class="medium-4 show-for-medium-up columns"><strong>Name</strong></div>
			<div class="medium-2 show-for-medium-up columns"><strong>Pokemon</strong></div>
			<div class="small-7 show-for-small-only columns"><strong>Name</strong></div>
			<div class="small-5 show-for-small-only columns"><strong>Pokemon</strong></div>
			</div></div>
			';
		echo $abi_list;
	}	
}


function poke_type($atts, $content=null) {
	global $wpdb;
	
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );
	
	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);
	
	if (($path[2] == 'normal') || ($path[2] == 'fighting') || ($path[2] == 'flying') || ($path[2] == 'poison') || ($path[2] == 'ground') || ($path[2] == 'rock') || ($path[2] == 'bug') || ($path[2] == 'ghost') || ($path[2] == 'steel') || ($path[2] == 'fire') || ($path[2] == 'water') || ($path[2] == 'grass') || ($path[2] == 'electric') || ($path[2] == 'psychic') || ($path[2] == 'ice') || ($path[2] == 'dragon') || ($path[2] == 'dark') || ($path[2] == 'fairy')) {
		
		$path = ucfirst(strtolower($path[2]));
		
		$sql = "SELECT * FROM `type` JOIN `pokedex` ON `pokedex`.id = `type`.id WHERE `types_one` LIKE '".$path."' or `types_two` LIKE '".$path."' LIMIT 0, 300;";
		$results = $wpdb->get_results( $sql );
		
		$poke_count = count($results);
		$poke = 0;
		
		$poke_list = '<div class="row">';
		
		foreach ($results as $row) {
			
			$poke++;
			if ($poke_count == $poke) {
				$pokename= '<div class="small-10 medium-5 columns end"><a href="/pokedex/'.$row->pokedex_id.'/" title="'.$row->name.'">'.$row->name.'</a></div>';
				} else {
				$pokename= '<div class="small-10 medium-5 columns"><a href="/pokedex/'.$row->pokedex_id.'/" title="'.$row->name.'">'.$row->name.'</a></div>';
			}
			
			if ($row->types_two == 'NULL') {
				$row_types = '<div class="small-3 columns text-center"><div class="small-12 columns text-center '.$row->types_one.'">'.$row->types_one.'</div></div>';
			} else {
				$row_types = '<div class="small-3 columns text-center"><div class="small-6 columns text-center '.$row->types_one.'">'.$row->types_one.'</div><div class="small-6 columns text-center '.$row->types_two.'">'.$row->types_two.'</div></div>';
			}		
			
//			$stats = $row->base_stats;
//			$stats_decode = json_decode($stats,true);
//			$stats_total = $stats_decode[0] + $stats_decode[1] + $stats_decode[2] + $stats_decode[3] + $stats_decode[4];
			
			$poke_list .= '<div class="small-2 medium-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row->pokedex_id.'-mini.png" alt="'.$row->name.'" height="25" width="25"></div>';
			$poke_list .= ''.$pokename.'';
//			$poke_list .= '<div class="small-3 columns text-center">'.$stats_total.'</div>';
			
		}
		 
		$poke_list .= '</div>';

		echo '<h3 class="subheader">'.$path.' Type Pokemon</h3>';
		echo $poke_list;
		
//Skill here
		
		$sql = "SELECT * FROM `move` WHERE `type_spec` LIKE '".$path."' LIMIT 0, 300;";
		$results = $wpdb->get_results( $sql );
		
		$move_list = '<div class="row">';
		
		$results_count = count($results);
		$m = 0;
		
		foreach ($results as $row) {
			
			$m++;
			if ($results_count = $m++) {
				$move_list .= '<div class="small-6  medium-4 columns end"><a href="/move/'.no_space($row->name).'/" title="'.$row->name.'">'.$row->name.'</a></div>';
			} else {
				$move_list .= '<div class="small-6 medium-4 columns"><a href="/move/'.no_space($row->name).'/" title="'.$row->name.'">'.$row->name.'</a></div>';
			}
		}
		
		$move_list .= '</div>';
		
		echo '</br><h3 class="subheader">'.$path.' Type Moves</h3>';
		echo $move_list;
		
	} else {
		
		echo '<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Normal"><h4><b><a href="/type/normal/" title="Normal" class="whitetext">Normal</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Fighting"><h4><b><a href="/type/fighting/" title="Fighting" class="whitetext">Fighting</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Flying"><h4><b><a href="/type/flying/" title="Flying" class="whitetext">Flying</a></b></h4></div></div></div>
		
		<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Poison"><h4><b><a href="/type/poison/" title="Poison" class="whitetext">Poison</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Ground"><h4><b><a href="/type/ground/" title="Ground" class="whitetext">Ground</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Rock"><h4><b><a href="/type/rock/" title="Rock" class="whitetext">Rock</a></b></h4></div></div></div>
		
		<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Bug"><h4><b><a href="/type/bug/" title="Bug" class="whitetext">Bug</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Ghost"><h4><b><a href="/type/ghost/" title="Ghost" class="whitetext">Ghost</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Steel"><h4><b><a href="/type/steel/" title="Steel" class="whitetext">Steel</a></b></h4></div></div></div>
		
		<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Fire"><h4><b><a href="/type/fire/" title="Fire" class="whitetext">Fire</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Water"><h4><b><a href="/type/water/" title="Water" class="whitetext">Water</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Grass"><h4><b><a href="/type/grass/" title="Grass" class="whitetext">Grass</a></b></h4></div></div></div>
		
		<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Electric"><h4><b><a href="/type/electric/" title="Electric" class="whitetext">Electric</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Psychic"><h4><b><a href="/type/psychic/" title="Psychic" class="whitetext">Psychic</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Ice"><h4><b><a href="/type/ice/" title="Ice" class="whitetext">Ice</a></b></h4></div></div></div>
		
		<div class="row show-for-medium-up"><br />
		<div class="medium-4 columns"><div class="medium-9 columns text-center Dragon"><h4><b><a href="/type/dragon/" title="Dragon" class="whitetext">Dragon</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Dark"><h4><b><a href="/type/dark/" title="Dark" class="whitetext">Dark</a></b></h4></div></div>
		<div class="medium-4 columns"><div class="medium-9 columns text-center Fairy"><h4><b><a href="/type/fairy/" title="Fairy" class="whitetext">Fairy</a></b></h4></div></div></div>
		
		<div class="row show-for-small-only"><br />
		<div class="small-6 columns"><div class="medium-9 columns text-center Normal"><h4><b><a href="/type/normal/" title="Normal" class="whitetext">Normal</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Fighting"><h4><b><a href="/type/fighting/" title="Fighting" class="whitetext">Fighting</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Flying"><h4><b><a href="/type/flying/" title="Flying" class="whitetext">Flying</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Poison"><h4><b><a href="/type/poison/" title="Poison" class="whitetext">Poison</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Ground"><h4><b><a href="/type/ground/" title="Ground" class="whitetext">Ground</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Rock"><h4><b><a href="/type/rock/" title="Rock" class="whitetext">Rock</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Bug"><h4><b><a href="/type/bug/" title="Bug" class="whitetext">Bug</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Ghost"><h4><b><a href="/type/ghost/" title="Ghost" class="whitetext">Ghost</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Steel"><h4><b><a href="/type/steel/" title="Steel" class="whitetext">Steel</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Fire"><h4><b><a href="/type/fire/" title="Fire" class="whitetext">Fire</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Water"><h4><b><a href="/type/water/" title="Water" class="whitetext">Water</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Grass"><h4><b><a href="/type/grass/" title="Grass" class="whitetext">Grass</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Electric"><h4><b><a href="/type/electric/" title="Electric" class="whitetext">Electric</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Psychic"><h4><b><a href="/type/psychic/" title="Psychic" class="whitetext">Psychic</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Ice"><h4><b><a href="/type/ice/" title="Ice" class="whitetext">Ice</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Dragon"><h4><b><a href="/type/dragon/" title="Dragon" class="whitetext">Dragon</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Dark"><h4><b><a href="/type/dark/" title="Dark" class="whitetext">Dark</a></b></h4></div></div>
		<div class="small-6 columns"><div class="medium-9 columns text-center Fairy"><h4><b><a href="/type/fairy/" title="Fairy" class="whitetext">Fairy</a></b></h4></div></div></div>';
	}
}

function poke_move($atts, $content=null) {
	global $wpdb;
	
	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );

	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);



	$sql = "SELECT * FROM `move` WHERE `id_name` LIKE '".$path[2]."' LIMIT 0, 30;";
	$results = $wpdb->get_row( $sql, ARRAY_A );

	if ($results) {
		
		$path3 = $path[2];
		$abi3 = '"'.return_space($path3).' (';
		
		$tm3 = '"'.$results['tm'].'"';
		
		$sql2 = "SELECT * FROM `pokedex` WHERE `learned_moves` LIKE '%".$abi3."%' or `tm` LIKE '%".$tm3."%' LIMIT 0, 300;";
		$results2 = $wpdb->get_results( $sql2, ARRAY_A );
		
		$resultscount = count ($results2);
		$i=0;
		$pokemon_list = '<br/><div class="row">';
		
		foreach ($results2 as $row) {
			$i++;
			if ($i == $resultscount) {
				$pokemon_list .= '<div class="small-2 medium-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" height="25" width="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5 columns end"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'" >'.$row['name'].'</a></div>';
			} else {
				$pokemon_list .= '<div class="small-2 medium-1 columns imgMiddle4"><img src="/wp-content/uploads/pokemongo/mini/'.$row['pokedex_id'].'-mini.png" alt="'.$row['name'].'" height="25" width="25"></div>';
				$pokemon_list .= '<div class="small-10 medium-5 columns"><a href="/pokedex/'.$row['pokedex_id'].'/" title="'.$row['name'].'">'.$row['name'].'</a></div>';
			}
		}
		
		$pokemon_list .= '</div>';
	

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
		
		$category_arr = array(
			'Physical' => '<div class="small-5 text-center movecategories columns">Physical</div>',
			'Special' => '<div class="small-5 text-center movecategories columns">Special</div>',
			'Status' => '<div class="small-5 text-center movecategories columns">Status</div>',
		);

		echo '
		<div class="row collapse">
		<div class="small-4 medium-5 columns">Name</div>
		<div class="small-8 medium-7 columns">'.$results['name'].'</div>
		<div class="small-4 medium-5 columns">Name(DE)</div>
		<div class="small-8 medium-7 columns">'.$results['name_de'].'</div>
		<div class="small-4 medium-5 columns">Name(FR)</div>
		<div class="small-8 medium-7 columns">'.$results['name_fr'].'</div>
		<div class="small-4 medium-5 columns">Name(JP)</div>
		<div class="small-8 medium-7 columns">'.$results['name_ja'].'</div>
		<div class="small-4 medium-5 columns">Type</div>
		<div class="small-8 medium-7 columns"><div class="small-5 text-center columns '.$types_arr[$results['type']].'"><a href="/type/'.strtolower($types_arr[$results['type']]).'/" title="'.$types_arr[$results['type']].'" class="'.$types_arr[$results['type']].'">'.$types_arr[$results['type']].'</a></div></div>
		<div class="small-4 medium-5 columns">Category</div>
		<div class="small-8 medium-7 columns">'.$category_arr[$results['category']].'</div>
		<div class="small-4 medium-5 columns">Power</div>
		<div class="small-8 medium-7 columns">'.$results['power'].'</div>
		<div class="small-4 medium-5 columns">Accuracy</div>
		<div class="small-8 medium-7 columns">'.$results['accuracy'].'</div>
		<div class="small-4 medium-5 columns">PP</div>
		<div class="small-8 medium-7 columns">'.$results['pp'].'</div>
		<div class="small-4 medium-5 columns">TM</div>
		<div class="small-8 medium-7 columns">'.$results['tm'].'</div>
		<div class="small-4 medium-5 columns">Probability</div>
		<div class="small-8 medium-7 columns">'.$results['probability'].'</div>
		<div class="small-4 medium-5 columns">Description</div>
		<div class="small-8 medium-7 columns">'.$results['description'].'</div>
	
		</div>';
		
		echo '<br/><div class="row">
		<div class="small-12 columns"><h3>Pokemon with '.return_space($path3).'</h3></div>
		</div>';
		echo $pokemon_list;
	
	
	} else {
		
		$move = "SELECT * FROM `move`";
		$movedata = $wpdb->get_results($move);
		
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
		
		$movename1 = '<div class = row>';

		foreach ($movedata as $movename) {
			$movename1 .= '<div class="small-7 medium-5 large-5 columns"><div class="small-12 medium-8 medium-offset-1 columns"><a href="/move/'.no_space($movename->name).'/" title="'.$movename->name.'">'.$movename->name.'</a></div></div>';
			$movename1 .= '<div class="small-5 medium-4 large-4 columns"><div class="small-12 medium-8 medium-offset-1 columns '.$types_arr[$movename->type].' text-center"><a href="/type/'.strtolower($types_arr[$movename->type]).'/" title="'.$types_arr[$movename->type].'" class="whitetext13">'.$types_arr[$movename->type].'</a></div></div>';
			$movename1 .= '<div class="medium-3 large-3 show-for-medium-up columns text-center">'.$movename->power.'</div>';
			
		}
		
		$movename1 .= '</div>';

		
		echo '<div style="background-color:#CCCCCC"><div class = row>
		<div class="small-7 medium-5 large-5 columns"><div class="small-12 medium-8 medium-offset-1 columns"><strong>Name</strong></div></div>
		<div class="small-5 medium-4 large-4 columns"><div class="small-12 medium-8 medium-offset-1 columns text-center"><strong>Type</strong></div></div>
		<div class="medium-3 columns large-3 show-for-medium-up text-center"><strong>Power</strong></div></div></div>';
		
		echo $movename1;
		
	}
}

function poke_dex($atts, $content=null) {
	global $wpdb;

	$output = '';
	extract( shortcode_atts( array(
		'name' => 'None',
	), $atts ) );

	$urlArr = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED));
	$path = explode('/', $urlArr['path']);



	$sql = "SELECT * FROM `pokedex` WHERE `pokedex_id` LIKE '".$path[2]."' LIMIT 0, 30;";
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
		"Monster" => "Monster",
		"Water 1" => "Water 1",
		"Bug"=> "Bug",
		"Flying"=> "Flying",
		"Field" => "Field",
		"Fairy" => "Fairy",
		"Grass" => "Grass",
		"Undiscovered" => "Undiscovered",
		"Human-Like" => "Human-Like",
		"Water 3" => "Water 3",
		"Mineral" => "Mineral",
		"Amorphous" => "Amorphous",
		"Water 2" => "Water 2",
		"Ditto" => "Ditto",
		"Dragon" => "Dragon",
		"Gender unknown" => "Gender Unknown"
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
			$genderRate2 .= '<div class="small-7 columns">Genderless</div>';
			} else {
				$genderRate2 .= '<div class="small-8 columns">'.$genderRate_arr[$results['gender_rate']].'</div>';
			}
			
		$genderRate2 .= '</div>';

		$types2 = '<div class="row collapse">';

		foreach ($types_decode as $types1) {
		$types2 .= '<div class="small-5 medium-3 columns end '.$types_arr[$types1].' text-center"><a href="/type/'.strtolower($types_arr[$types1]).'/" title="'.$types_arr[$types1].'" class="whitetext13">'.$types_arr[$types1].'</a></div>';
		}
		$types2 .= '</div>';
		

	//specify egg groups here
//		print_r ($egggroup_decode);

		
		$egggroup2 = '<div class="row">';

		if ($egggroup_decode == '') {
			$egggroup2 .= '<div class="small-6 columns">-</div>';
			} else {
				foreach ($egggroup_decode as $egggroup1){
					$egggroup2 .= '<div class="small-6 columns">'.$types_arr[''.$egggroup1.''].'</div>';
					}
				}

		$egggroup2 .= '</div>';


	//specify tm/hm name here;

		$tm_array = array (
			'TM1' => "Hone Claws",
			'TM2' => "Dragon Claw",
			'TM3' => "Psyshock",
			'TM4' => "Calm Mind",
			'TM5' => "Roar",
			'TM06' => "Toxic",
			'TM7' => "Hail",
			'TM8' => "Bulk Up",
			'TM09' => "Venoshock",
			'TM10' => "Hidden Power",
			'TM11' => "Sunny Day",
			'TM12' => "Taunt",
			'TM13' => "Ice Beam",
			'TM14' => "Blizzard",
			'TM15' => "Hyper Beam",
			'TM16' => "Light Screen",
			'TM17' => "Protect",
			'TM18' => "Rain Dance",
			'TM19' => "Telekinesis",
			'TM20' => "Safeguard",
			'TM21' => "Frustration",
			'TM22' => "Solar Beam",
			'TM23' => "Smack Down",
			'TM24' => "Thunderbolt",
			'TM25' => "Thunder",
			'TM26' => "Earthquake",
			'TM27' => "Return",
			'TM28' => "Dig",
			'TM29' => "Psychic",
			'TM30' => "Shadow Ball",
			'TM31' => "Brick Break",
			'TM32' => "Double Team",
			'TM33' => "Reflect",
			'TM34' => "Sludge Wave",
			'TM35' => "Flamethrower",
			'TM36' => "Sludge Bomb",
			'TM37' => "Sandstorm",
			'TM38' => "Fire Blast",
			'TM39' => "Rock Tomb",
			'TM40' => "Aerial Ace",
			'TM41' => "Torment",
			'TM42' => "Facade",
			'TM43' => "Flame Charge",
			'TM44' => "Rest",
			'TM45' => "Attract",
			'TM46' => "Thief",
			'TM47' => "Low Sweep",
			'TM48' => "Round",
			'TM49' => "Echoed Voice",
			'TM50' => "Overheat",
			'TM51' => "Ally Switch",
			'TM52' => "Focus Blast",
			'TM53' => "Energy Ball",
			'TM54' => "False Swipe",
			'TM55' => "Scald",
			'TM56' => "Fling",
			'TM57' => "Charge Beam",
			'TM58' => "Sky Drop",
			'TM59' => "Incinerate",
			'TM60' => "Quash",
			'TM61' => "Will-O-Wisp",
			'TM62' => "Acrobatics",
			'TM63' => "Embargo",
			'TM64' => "Explosion",
			'TM65' => "Shadow Claw",
			'TM66' => "Payback",
			'TM67' => "Retaliate",
			'TM68' => "Giga Impact",
			'TM69' => "Rock Polish",
			'TM70' => "Flash",
			'TM71' => "Stone Edge",
			'TM72' => "Volt Switch",
			'TM73' => "Thunder Wave",
			'TM74' => "Gyro Ball",
			'TM75' => "Swords Dance",
			'TM76' => "Struggle Bug",
			'TM77' => "Psych Up",
			'TM78' => "Bulldoze",
			'TM79' => "Frost Breath",
			'TM80' => "Rock Slide",
			'TM81' => "X-Scissor",
			'TM82' => "Dragon Tail",
			'TM83' => "Infestation",
			'TM84' => "Poison Jab",
			'TM85' => "Dream Eater",
			'TM86' => "Grass Knot",
			'TM87' => "Swagger",
			'TM88' => "Sleep Talk",
			'TM89' => "U-turn",
			'TM90' => "Substitute",
			'TM91' => "Flash Cannon",
			'TM92' => "Trick Room",
			'TM93' => "Wild Charge",
			'TM94' => "Rock Smash",
			'TM95' => "Snarl",
			'TM96' => "Nature Power",
			'TM97' => "Dark Pulse",
			'TM98' => "Power-Up Punch",
			'TM99' => "Dazzling Gleam",
			'TM100' => "Confide"
			);

		$hm_array = array (
			'HM01' => "Cut",
			'HM02' => "Fly",
			'HM03' => "Surf",
			'HM04' => "Strength",
			'HM05' => "Waterfall",
			'HM06' => "Dive"
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
						$tm_final .= '<div class="small-12 medium-6 columns end"><a href="/move/'.no_space($tm_array[$tm1]).'/" title="'.$tm_array[$tm1].' ('.$tm1.')">'.$tm_array[$tm1].' ('.$tm1.')</a></div>';
					} else {
						$tm_final .= '<div class="small-12 medium-6 columns"><a href="/move/'.no_space($tm_array[$tm1]).'/" title="'.$tm_array[$tm1].' ('.$tm1.')">'.$tm_array[$tm1].' ('.$tm1.')</div>';
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
					$hm_final .= '<div class="small-12 medium-6 columns end"><a href="/hm-moves/'.strtolower($hm_array[$hm1]).'/" title="'.$hm_array[$hm1].' ('.$hm1.')">'.$hm_array[$hm1].' ('.$hm1.')</a></div>';
					} else {
					$hm_final .= '<div class="small-12 medium-6 columns"><a href="/hm-moves/'.strtolower($hm_array[$hm1]).'/" title="'.$hm_array[$hm1].' ('.$hm1.')">'.$hm_array[$hm1].' ('.$hm1.')</a></div>';
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
				$learnedmove_final .= '<div class="small-12 medium-6 columns end"><a href="/move/'.no_space($lm1).'/" title="'.$lm1.'">'.$lm1.'</a></div>';
			}
		}
	
		$learnedmove_final .= '</div>';

		$abilities_count = count($abilities_decode);
		$abilities_final = '<div class="row">';
		$ab = 0;
		foreach ($abilities_decode as $ab1){
			$ab++;
			if ($abilities_count == $ab) {
				$abilities_final .= '<div class="small-6 medium-6 columns end"><a href="/abilities/'.no_space($ab1).'/" title="'.$ab1.'">'.$ab1.'</a></div>';
			} else {
				$abilities_final .= '<div class="small-6 medium-6 columns"><a href="/abilities/'.no_space($ab1).'/" title="'.$ab1.'">'.$ab1.'</a></div>';
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
					$eggmoves_final .= '<div class="small-6 columns end"><a href="/move/'.no_space($em1).'/" title="'.$em1.'">'.$em1.'</div>';
					} else {
					$eggmoves_final .= '<div class="small-6 columns"><a href="/move/'.no_space($em1).'/" title="'.$em1.'">'.$em1.'</div>';
					}
				}
			}

		$eggmoves_final .= '</div>';
	
	//testing function for evo

		function get_evo($evo_name) {
			if (strpos($evo_name,'Plant Cloak') !== false) {
				$new_name = 'burmy';
				return $new_name;
			} else {
				if (strpos($evo_name,'Burmy ') !== false) {
					$replace = str_replace('(','',$evo_name);
					$replace = str_replace(')','',$replace);
					$replace_arr = explode(' ',$replace);
					$new_name = $replace_arr[0].'-'.$replace_arr[1];
					$new_name = strtolower($new_name);
					return $new_name;
				} else {
					if (strpos($evo_name,'♀') !== false) {
						$evo_arr = explode(' (',$evo_name);
						$replace = str_replace('♀','-f ',$evo_arr[0]);
						$new_name = strtolower($replace);
						return $new_name;
					} else {
						if (strpos($evo_name,'♂') !== false) {
							$evo_arr = explode(' (',$evo_name);
							$replace = str_replace('♂','-m ',$evo_arr[0]);
							$new_name = strtolower($replace);
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
					$preevo_final .= '<div class="small-12 columns end"><a href="/pokedex/'.get_evo($preevo1).'/" title="'.$preevo1.'">'.$preevo1.'</a></div>';
					} else {
					$preevo_final .= '<div class="small-12 columns"><a href="/pokedex/'.get_evo($preevo1).'/" title="'.$preevo1.'">'.$preevo1.'</a></div>';
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
					$evo_final .= '<div class="small-12 columns end"><a href="/pokedex/'.get_evo($evo1).'/" title="'.$evo1.'">'.$evo1.'</a></div>';
				} else {
					$evo_final .= '<div class="small-12 columns"><a href="/pokedex/'.get_evo($evo1).'/" title="'.$evo1.'">'.$evo1.'</a></div>';
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
					$mega_final .= '<div class="small-12 columns end"><a href="/pokedex/'.get_evo($mega1).'/" title="'.$mega1.'">'.$mega1.'</a></div>';
				} else {
					$mega_final .= '<div class="small-12 columns"><a href="/pokedex/'.get_evo($mega1).'/" title="'.$mega1.'">'.$mega1.'</a></div>';
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

		$stats .='<div class="small-6 medium-6 columns">HP</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[0].'</div>';
		$stats .='<div class="small-6 medium-6 columns">Attack</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[1].'</div>';
		$stats .='<div class="small-6 medium-6 columns">Defense</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[2].'</div>';
		$stats .='<div class="small-6 medium-6 columns">Sp.Attack</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[3].'</div>';
		$stats .='<div class="small-6 medium-6 columns">Sp.Def</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[4].'</div>';
		$stats .='<div class="small-6 medium-6 columns">Speed</div>'.'<div class="small-3 medium-2 small-text-right columns">'.$basestats_decode[5].'</div>';
		$stats .='<div class="small-6 medium-6 medium-6 columns">Total</div>'.'<div class="small-3 medium-2 small-text-right columns end">'.$stats_total.'</div>';

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
	
	/*
			$country = $data[0];
			$count = $wpdb->num_rows;
			if ($currentstation == 0) {
				$prevstation = $count - 1;
				$nextstation = $currentstation + 1;
			}
			elseif ($currentstation == $count - 1) {
				$prevstation = $currentstation - 1;
				$nextstation = 0;
			}
			else {
				$prevstation = $currentstation - 1;
				$nextstation = $currentstation + 1;
			}
			$prev = "/".$country.'/'.$stations[$prevstation]->tag.'/';
			$next = "/".$country.'/'.$stations[$nextstation]->tag.'/';
			
			$tag = $result[0]->tag;
			$name = $result[0]->name;
			if ($result[0]->image) {
				$img = "/wp-content/uploads/logo/".$result[0]->image.".jpg";
			} else {
				$img = "/wp-content/uploads/logo/radio.jpg";
			}
	*/
		$poke_id = $wpdb->get_results( "SELECT `id` FROM `pokedex` ", ARRAY_A);
		$count = $wpdb->num_rows;
		$currentpokeid = $results['id'];

		
		if ($currentpokeid == 1) {
				$prevpokeid = 794;
				$nextpokeid = $currentpokeid + 1;
			}
			elseif ($currentpokeid == $count) {
				$prevpokeid = $currentpokeid - 1;
				$nextpokeid = 1;
			}
			else {
				$prevpokeid = $currentpokeid - 1;
				$nextpokeid = $currentpokeid + 1;
			}
	
		$previous = $wpdb->get_results( "SELECT `pokedex_id` FROM `pokedex` WHERE `id` = ".$prevpokeid."", ARRAY_A);
		$next = $wpdb->get_results( "SELECT `pokedex_id` FROM `pokedex` WHERE `id` = ".$nextpokeid."", ARRAY_A);

		echo '
		<a href="/pokedex/'.$previous['0']['pokedex_id'].'/" title="Previous Pokemon" class="button tiny round left">Previous</br>Pokemon</a>
		<a href="/pokedex/'.$next['0']['pokedex_id'].'/" title="Next Pokemon" class="button tiny round right">Next</br>Pokemon</a>
		';

		echo '<div class="img">
		<img src="/wp-content/uploads/pokemongo/'.$image_name.'.png" alt="'.$results['name'].'">
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
		</br><a href="/pokedex/'.$previous['0']['pokedex_id'].'/" title="Previous Pokemon" class="button tiny round left">Previous</br>Pokemon</a>
		<a href="/pokedex/'.$next['0']['pokedex_id'].'/" title="Next Pokemon" class="button tiny round right">Next</br>Pokemon</a>
		';

	
		} else {
			
			global $wpdb;
			$sql = "SELECT * FROM `pokedex`";
			$data = $wpdb->get_results( $sql, ARRAY_A );

			echo '<div style="background-color:#CCCCCC"><div class=row>
			<div class="small-5 medium-5 columns small-offset-2 medium-offset-1 text-left"><strong>Pokemon</strong></div>
			<div class="small-7 medium-3 columns show-for-medium-up text-center"><strong>Types</strong></div>
			<div class=" medium-3 columns show-for-medium-up text-center"><strong>Stats Total</strong></div>
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
						$types2 .= '<div class="small-6 columns text-center '.$types_arr[$types1].'"><a href="/type/'.strtolower($types_arr[$types1]).'/" title="'.$types_arr[$types1].'" class="whitetext13">'.$types_arr[$types1].'</a></div>';
						} else {
							$types2 .= '<div class="small-12 columns text-center '.$types_arr[$types1].'"><a href="/type/'.strtolower($types_arr[$types1]).'/" class="whitetext13" title="'.$types_arr[$types1].'" >'.$types_arr[$types1].'</a></div>';
						}
					}

				$types2 .= '</div>';
			
				echo '
				
				<div class=row>
				<div class="small-2 medium-1 columns imgMiddle"><img src="/wp-content/uploads/pokemongo/mini/'.$link.'-mini.png" alt="'.$name.'" width="30" height="30"></div>
				<div class="small-10 medium-5 columns text-left"><a href="/pokedex/'.$link.'/" title="'.$name.'">'.$name.'</a></div>
				<div class="medium-3 columns show-for-medium-up text-center">'.$types2.'</div>
				<div class="medium-3 show-for-medium-up columns text-center">'.$stats_total.'</div>
			
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

	if ( !isset($rules['(pokedex)/(.+)$']) || !isset($rules['(move)/(.+)$']) || !isset($rules['(type)/(.+)$']) || !isset($rules['(abilities)/(.+)$']) || !isset($rules['(hm-moves)/(.+)$']) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function my_insert_rewrite_rules( $rules ) {
	$newrules = array();
	$newrules['pokedex/(.+)$'] = 'index.php?pagename=pokedex';
	$newrules['move/(.+)$'] = 'index.php?pagename=move';
	$newrules['type/(.+)$'] = 'index.php?pagename=type';
	$newrules['abilities/(.+)$'] = 'index.php?pagename=abilities';
	$newrules['hm-moves/(.+)$'] = 'index.php?pagename=hm-moves';

	return $newrules + $rules;
}


add_shortcode( 'poke', 'poke_dex' );
add_shortcode( 'move', 'poke_move' );
add_shortcode( 'type', 'poke_type' );
add_shortcode( 'abilities', 'poke_abilities' );
add_shortcode( 'hm', 'poke_hm' );

add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_action( 'wp_loaded','my_flush_rules' );
add_action( 'init', 'seo_loader_init', 0 );
?>