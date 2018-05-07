# bitrix-admin-constructor

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use Bitrix\Main;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder = new \Soft1c\Container();

//$containerBuilder->register('listPage', \Soft1c\Builder\ListPage::class);
//$containerBuilder->register('editPage', \Soft1c\Builder\EditPage::class);

/** @var \Soft1c\Builder\PageRender PageRender */
$PageRender = $containerBuilder->resolve()->buildPage();


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$dataPage = $PageRender->render();

dump($PageRender);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
