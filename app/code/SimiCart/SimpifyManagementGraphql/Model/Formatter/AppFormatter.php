<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Formatter;

use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;

/**
 * Class AppFormatter to graphql response
 */
class AppFormatter
{
    public function execute(IApp $app)
    {
        $result = [
            'model' => $app,
        ];

        $result['uid'] = base64_encode($app->getId());
        $result[IApp::APP_LOGO] = $app->getAppImageUrl($app->getAppLogo());
        $result[IApp::APP_ICON] = $app->getAppImageUrl($app->getAppIcon());
        $result[IApp::SPLASH_IMAGE] = $app->getAppImageUrl($app->getSplashImage());
        $result[IApp::SPLASH_BG_COLOR] = $app->getSplashBgColor();
        $result[IApp::SPLASH_IS_FULL] = $app->isSplashFull();
        $result[IApp::CREATED_AT] = $app->getData(IApp::CREATED_AT);
        $result[IApp::UPDATED_AT] = $app->getData(IApp::UPDATED_AT);

        return $result;
    }
}
