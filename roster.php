<?php

// Simple define file for regioon, server, guild, client_id and client_secret
include_once "config.php";
include_once 'core.php';

$core = new Core;

var_dump( $core->get_character_raider_io( 'us', 'agamaggan', 'palmer' ) );

die();





$access_token = $core->get_access_token();
$guild = $core->get_guild_roster( $access_token );

$fp = fopen( 'members.json', 'w');

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
			'rank' => $member->rank,
		];

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