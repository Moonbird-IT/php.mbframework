<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 13.11.12
 * Time: 10:56
 * @version: $Id$
 * Purpose:
 */
uses(
    'de.moonbird.web.controller.abstract.AbstractViewController',
    'biz.sig.portal.backend.user.UserBackend');

class UserController extends AbstractViewController
{
    public function run()
    {
        if (isset($_GET['json'])) {
            $backend = new UserBackend();
            $backend->run();
        }
    }

}
