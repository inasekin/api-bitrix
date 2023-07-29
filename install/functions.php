<?
use \Bitrix\Main\Config\Configuration;
use Bitrix\Main\UserFieldTable;

class CCosmoInstall
{
	public static function SetRouteConfiguration()
	{
		$config = Configuration::getInstance();
		$routing = $config->get('routing');
		if(!isset($routing) || !isset($routing['config']) || !is_array($routing['config']))
		{
			$arRoutes = ['cosmo_api.php'];
		}
		else if(!in_array('cosmo_api.php', $routing['config']))
		{
			$arRoutes = $routing['config'];
			$arRoutes[] = ['cosmo_api.php'];
		}

		if ($arRoutes)
		{
			$config->add('routing', ['config' => $arRoutes]);
			$config->saveConfiguration();
		}

		return true;
	}

	public static function AddUfUserToken()
	{
		if (UserFieldTable::getList(['filter' => ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_COSMO_API_SECRET_KEY']])->fetch())
			return true;
		
		/*
		temp old core, wait d7 adding method

		UserFieldTable::add([
			'ENTITY_ID' => 'USER',
			'FIELD_NAME' => 'UF_COSMO_API_SECRET_KEY',
			'USER_TYPE_ID' => 'string',
		]);
		*/

		$ob = new CUserTypeEntity();
		$ob->Add([
			'ENTITY_ID' => 'USER',
			'FIELD_NAME' => 'UF_COSMO_API_SECRET_KEY',
			'USER_TYPE_ID' => 'string',
			'EDIT_FORM_LABEL' => [
				'ru' => 'COSMO API secret key',
				'en' => 'COSMO API secret key',
			]
		]);

		return true;
	}
}