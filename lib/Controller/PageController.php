<?php
namespace OCA\Diary\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\Util;

class PageController extends Controller {
	private $userId;

	public function __construct($AppName, IRequest $request, $UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
        Util::addScript($this->appName, 'diary-main');
		return new TemplateResponse('diary', 'index');  // templates/index.php
	}

    /**
     * @param string $date ISO date as identifier
     * @param string $content Diary entry to save
     * @return string[]
     * @NoAdminRequired
     */
    public function updateEntry(string $date, string $content): array {
        return ['content'=>$content.'yada'.$date];
    }


}
