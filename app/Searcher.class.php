<?php

class Searcher
{
	private static $_viewPath = ROOT . '/view/';
	private static $_template = 'template';

	private static function _404($_msg = 'Page not found.')
	{
		echo 'Error : '. $_msg;
		exit();
	}

	private static function _render($view, $data = array())
	{
		ob_start();
		extract($data);
		require(self::$_viewPath . $view . '.php');
		$content = ob_get_clean();

		require(self::$_viewPath . '/' . self::$_template . '.php');
	}

	public static function checkActions($action)
	{
		if (in_array($action, get_class_methods(get_class())))
		{
			return true;
		}
		else
		{
			self::_404();
			return false;
		}
	}

	public static function indexAction()
	{
		self::_render('index');
	}

	public static function searchAction()
	{
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			header('Location: index.php');
		}

		$Ids = [];
		foreach ($_POST as $k => $id)
		{
			if ($id != '')
			{
				array_push($Ids, $id);
			}
		}

		$Games = SteamFinder::findAll($Ids);

		self::_render('search', compact('Games'));
	}
}