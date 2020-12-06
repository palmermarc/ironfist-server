<?php

Class core
{

	private $region;
	private $server;
	private $guild_name;
	private $client_id;
	private $client_secret;
	private $api_base;

	public function __construct(
		$region = 'NA',
		$server = 'burning-legion',
		$guild_name = 'ironfist',
		$client_id = '96adc66832024326b62b6cf276723411',
		$client_secret = 'P3l9YKt6hhQr5HAcTXlWmBfL6DlHQdeI',
		$api_base = 'https://us.api.blizzard.com/'
	)
	{
		$this->region = $region;
		$this->server = $server;
		$this->guild_name = $guild_name;
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->api_base = $api_base;
	}

	/**
	 * Get the access token from the Blizzard API and return it
	 * @return mixed
	 */
	function get_access_token()
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://us.battle.net/oauth/token');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_USERPWD, "$this->client_id:$this->client_secret");

		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$result = json_decode($result);
		return $result->access_token;
	}

	function get_guild_roster($access_token) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "{$this->api_base}data/wow/guild/{$this->server}/{$this->guild_name}/roster?namespace=profile-us" );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		$headers = array();
		$headers[] = "Authorization: Bearer {$access_token}";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);

		return json_decode( $result );
	}

	function get_character_raider_io( $region, $server, $character_name ) {
		$ch = curl_init();

    $raider_io_url = "https://raider.io/api/v1/characters/profile?region={$region}&realm={$server}&name=" . urlencode( $character_name );

		curl_setopt($ch, CURLOPT_URL, $raider_io_url );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		$headers = array();
		$headers[] = 'Accept: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);
		return json_decode( $result );
	}
}