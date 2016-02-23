<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 18.10.12
 * Time: 11:29
 * @version: $Id$
 * Purpose:
 */
uses (
  'de.moonbird.interfaces.restricted.IRestricted',
  'biz.sig.gmdb.backend.admin.data.TableDataBackend',
  'de.moonbird.web.controller.abstract.AbstractViewController',
  'biz.sig.portal.security.Permission');

class TableDataController extends AbstractViewController implements IRestricted
{
  public function run() {
    if ($this->authenticate()) {
      $this->permit();
    } else {
      $this->forbid();
    }
  }

  public function authenticate() {
    $permission= new Permission();
    return $permission->setApplication(Configuration::get('project', 'shortname'))->setUser($_COOKIE['username'])->checkRight('admin');
  }

  public function permit() {
    if (isset($_GET['json'])) {
      $backend= new TableDataBackend();
      $backend->run();
    } else {
      $this->addHeadInclude('<link rel="stylesheet" href="doc_root/css/admin/style.css"/>');
      $this->addHeadInclude('<script type="text/javascript" src="/shared/js/jquery/plugins/jqgrid/js/i18n/grid.locale-en.js"></script>');
      $this->addHeadInclude('<script type="text/javascript" src="/shared/js/jquery/plugins/jqgrid/js/jquery.jqGrid.min.js"></script>');
      $this->addHeadInclude('<script type="text/javascript" src="/shared/js/jquery/plugins/jqgrid/plugins/jquery.contextmenu.js"></script>');
      $this->addHeadInclude('<link rel="stylesheet" href="/shared/js/jquery/plugins/jqgrid/css/ui.jqgrid.css"/>');

      $this->addHeadInclude('<script type="text/javascript" src="doc_root/js/app/admin/data/table-data.js"></script>');
      $this->addHeadInclude('<link rel="stylesheet" href="doc_root/css/admin/data/table-data.css"/>');
      $this->head();
      $this->loadView(Configuration::get('project', 'namespace') . '.frontend.admin.Admin');
      $this->loadView(Configuration::get('project', 'namespace') . '.frontend.admin.data.TableData');
      $this->foot();
    }
  }

  public function forbid() {
    $this->head();
    $this->loadView(Configuration::get('project', 'namespace') . '.frontend.access.NotAuthorized');
    $this->foot();
  }
}
