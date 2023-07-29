<?
namespace Cosmo\Api\V1\Controllers;

use \Cosmo\Api\V1\Pagination;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Engine\Controller;
use \Bitrix\Main\Application;
use \Bitrix\Iblock\IblockTable;
use \Bitrix\Main\Error;
use \Bitrix\Main\Context;
use \Bitrix\Main\Localization\Loc;

class BaseController extends Controller
{
  /**
   * ����� ����������� ������, ���� ������ ��� � ���������� OPTIONS, ���������� 204.
   * ��������� ������� ����� ������������ � �������������� ����������� ����� ��������� preflight (OPTIONS). ������ ������ �������� �� ���� 204 ����� �������� ������ �� ����������.
   */
  protected function processBeforeAction(\Bitrix\Main\Engine\Action $action)
  {
    $server = Context::getCurrent()->getServer();
    if($server->getRequestMethod() == 'OPTIONS') {
      Context::getCurrent()->getResponse()->setStatus(204);
      return false;
    }
    
    return true;
  }

  /**
   * ���������� id ��������� �� ��� ����
   * @param string $code - ��� ��������
   * @return int|false
  */
	protected function getIblockByCode($code)
	{
		return IblockTable::getList([
      'select' => ['ID'],
      'filter' => ['CODE' => $code]
    ])->fetch()['ID'];
	}

  /**
   * ���������� ����������� ������ ���������
   * @return \Bitrix\Main\HttpRequest
  */
	protected function getHttpRequest()
	{
		return Context::getCurrent()->getRequest();
	}

  /**
   * ���������� �������� ��������� �� url ��� ��������
   * ������, /example/{param}/
   * @param string $param �������� ���������
   * @return string �������� ���������
  */
	protected function getParameterValue($param)
	{
		return Application::getInstance()->getCurrentRoute()->getParameterValue($param);
	}

  /**
   * ����� ������� ��� getList. �������� ����� � ���� ������ ��������� ���������
   * @param array $params ��������� ������ getList
   * @param array $pagination ��������� ���������
   * @param string $pagination['pageParameterName'] �������� url ��������� ������ �������� ���������
   * @param int $pagination['limit'] ���������� ��������� �� ��������
   * @return array �������������� ��������� ����� �������
  */
  protected function _listAction($params = array(), $pagination = array())
  {
    $nav = new Pagination($pagination['pageParameterName'], $pagination['limit']);

    if($arErrors = $nav->getErrors()) return $this->addErrors($arErrors);

		$rsItems = ElementTable::getList(array_merge(
      $params,
      [
        "count_total" => true,
        "offset" => $nav->getOffset(),
        "limit" => $nav->getLimit(),
      ]
    ));

		$nav->setCount($rsItems->getCount());

		$arItems = [];
		while ($arItem = $rsItems->fetch())
		{
			$arItems[] = $arItem;
		}

    if($arErrors = $nav->getErrors()) return $this->addErrors($arErrors);

		return array_merge(
			[
				'result' => [
					'items' => $arItems
				],
			],
			$nav->getPaginationResponse()
		);
  }

  /**
   * ����� ������� ��� getById. �������� ����� � ���� �������� ���������
   * @param array $id ������������� ��������
   * @param array $params ������������ ���������
   * @return array �������������� ��������� ����� �������
  */
  protected function _idAction($id, $params = array())
  {
    if(!$arElement = ElementTable::getByPrimary($id, $params)->fetch()) {
      Context::getCurrent()->getResponse()->setStatus(400);
      $this->addError(new Error(Loc::getMessage("ERROR_ITEM_NOT_FOUND")));
      return null;
    }

    return [
      'result' => $arElement
    ];
  }
}