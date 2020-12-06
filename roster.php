<?php

// Simple define file for regioon, server, guild, client_id and client_secret
include_once "config.php";
include_once 'core.php';

$core = new Core;

$access_token = $core->get_access_token();
$guild = $core->get_guild_roster( $access_token );

$roster = [];

if( !empty ( $guild->members ) ) :
	foreach( $guild->members as $member ) :
		$member_file = "members/{$member->character->name}.json";

		$member_data = [
			'name' => $member->character->name,
			'uri' => $member->character->key->href,
			'id' => $member->character->id,
			'server' => $member->character->realm->slug,
			'server_id' => $member->character->realm->id,
			'level' => $member->character->level,
			'class' => $classes[$member->character->playable_class->id],
			'race' => $races[$member->character->playable_race->id],
			'guild_rank' => $member->rank,
      'guild_rank_string' => $ranks[$member->rank]
		];

    if( $member_data['guild_rank'] <= 6 && $member_data['level'] == 60 ) {
      echo "Pulling the Raider.io data for {$member_data['name']}\n";
      $raider_data = $core->get_character_raider_io( 'us', $member->character->realm->slug, $member->character->name );

      $member_data['race'] = $raider_data->race;
      $member_data['class'] = $raider_data->class;
      $member_data['active_spec_name'] = $raider_data->active_spec_name;
      $member_data['active_spec_role'] = $raider_data->active_spec_role;
      $member_data['gender'] = $raider_data->gender;
      $member_data['faction'] = $raider_data->faction;
      $member_data['achievement_points'] = $raider_data->achievement_points;
      $member_data['honorable_kills'] = $raider_data->honorable_kills;
      $member_data['thumbnail_url'] = $raider_data->thumbnail_url;
      $member_data['last_crawled_at'] = $raider_data->last_crawled_at;
      $member_data['profile_url'] = $raider_data->profile_url;
      $member_data['profile_banner'] = $raider_data->profile_banner;
    }

		$fp = fopen( $member_file, 'w' );
		fwrite( $fp, json_encode( $member_data ) );
		fclose( $fp );

		$character_id = $member_data['id'];
		unset( $member_data['id'] );
		$roster[$character_id] = $member_data;

	endforeach;
endif;

$fp = fopen( 'roster.json', 'w' );
fwrite( $fp, json_encode( $roster ) );
fclose( $fp );