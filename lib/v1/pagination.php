<?
namespace Cosmo\Api\V1;

use \Bitrix\Main\Application;
use \Bitrix\Main\Error;
use \Bitrix\Main\Context;
use \Bitrix\Main\Localization\Loc;

class Pagination
{
	protected $page = 1;
	protected $limit = 20;
	protected $count = 0;
	protected $arErrors = [];
	
	/**
	 * @param string $pageParameterName �������� get ��������� ������� ��������
	 * @param int $limit ���������� ��������� �� ��������
	 */
	public function __construct($pageParameterName = 'page', $limit = 20)
	{
		$page = Application::getInstance()->getContext()->getRequest()->get($pageParameterName ?: 'page');
		if (isset($page)) $this->setPage(intval($page));
		$this->setLimit(intval($limit));
	}

	/**
	 * ������������� ������� ��������
	 * @param int $page ������� ��������
	 */
	public function setPage($page)
	{
		if ($page <= 0)
		{
			Context::getCurrent()->getResponse()->setStatus(400);
			$this->arErrors[] = new Error(Loc::getMessage("ERROR_BAD_PAGE_VALUE"));
			return false;
		}

		$this->page = $page;
	}

	/**
	 * ���������� ������� ��������
	 * @return int ������� ��������
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * ���������� ������� ����� ��� �������
	 * @return int ������� �����
	 */
	public function getOffset()
	{
		return ($this->page - 1) * $this->limit;
	}
	
	/**
	 * ������������� ���������� ��������� �� ��������
	 * @param int $limit ���������� ��������� �� ��������
	 */
	public function setLimit($limit)
	{
		if ($limit < 1) $limit = 20;
		$this->limit = $limit;
	}

	/**
	 * ���������� ���������� ��������� �� ��������
	 * @return int ���������� ��������� �� ��������
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * ������������� ����� ���������� ���������
	 * @param int $count ����� ���������� ���������
	 */
	public function setCount($count)
	{
		if ($this->getOffset() >= $count)
		{
			Context::getCurrent()->getResponse()->setStatus(204);
			$this->arErrors[] = new Error(Loc::getMessage("ERROR_ITEMS_NOT_FOUND"));
		}

		if ($count < 0) $count = 0;
		$this->count = $count;
	}

	/**
	 * ���������� ����� ���������� ���������
	 * @return int ����� ���������� ���������
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * ���������� ������ ������ ���������
	 * @return array ������ ������
	 */
	public function getErrors()
	{
		return $this->arErrors;
	}

	/**
	 * ���������� ������ ��������� ��� ������ �������
	 * @return array ������ ���������
	 */
	public function getPaginationResponse()
	{
		return [
      'pagination' => [
        'page' => $this->page,
				'count' => $this->count,
				'limit' => $this->limit,
        'pageCount' => ceil($this->count / $this->limit),
      ]
    ];
	}
}