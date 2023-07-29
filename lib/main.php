<?
namespace Cosmo\Api;

class Main
{
	public const partnerName = "cosmo";
	public const solutionName = "api";

	public static function getModuleId()
	{
		return self::partnerName.'.'.self::solutionName;
	}
}