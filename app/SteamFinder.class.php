<?php

class SteamFinder
{
	private static $_STEAM_KEY = STEAM_KEY;
	private static $_getOwnedGamesUrl = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/';
	private static $_Players = [];
	private static $_PlayersIntersectGames = [];

	private static function _findOneAllGames($id)
	{
		$json = file_get_contents(self::$_getOwnedGamesUrl . '?key=' . self::$_STEAM_KEY . '&steamid=' . $id . '&format=json');

		//For debug
		//$json = file_get_contents(ROOT . '/test/' . $id);
		
		$data = json_decode($json);

		$Players = [
			"name" => "name",
			"game_count" => $data->response->game_count,
			"GameList" => [],
			"PlayTime" => []
		];

		foreach ($data->response->games as $key => $game)
		{
			array_push($Players['GameList'], $game->appid);
			array_push($Players['PlayTime'], $game->playtime_forever);
		}

		array_push(self::$_Players, $Players);
	}

	private static function _getIntersectGames()
	{
		$ArrayIntersect = self::$_Players[0]['GameList'];

		for ($i = 1; $i < count(self::$_Players); $i++)
		{
			$ArrayIntersect = array_intersect($ArrayIntersect, self::$_Players[$i]['GameList']);
		}

		foreach ($ArrayIntersect as $key => $value) {
			$totalPlayTime = 0;

			foreach (self::$_Players as $keyPlayer => $Player) {
				$totalPlayTime += $Player['PlayTime'][array_search($value, $Player['GameList'])];
			}

			$oneGame = [
				"appid" => $value,
				"totalPlayTime" => $totalPlayTime
			];

			array_push(self::$_PlayersIntersectGames, $oneGame);
		}
	}

	public static function findAll($Ids)
	{
		foreach ($Ids as $key => $id)
		{
			self::_findOneAllGames($id);
		}

		self::_getIntersectGames();

		return self::$_PlayersIntersectGames;
	}

}
