<?php

namespace OCA\Diary\AppInfo;

use OCA\Diary\Listener\UserDeletedListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\User\Events\UserDeletedEvent;

class Application extends App implements IBootstrap
{
    /** @var string */
    public const APP_ID = 'diary';

    public function __construct()
    {
        parent::__construct(self::APP_ID);
    }

    public function register(IRegistrationContext $context): void
    {
        include_once __DIR__.'/../../vendor/autoload.php';//TODO Check if this is needed at all
        $context->registerEventListener(UserDeletedEvent::class, UserDeletedListener::class);
    }

    public function boot(IBootContext $context): void
    {
    }
}
