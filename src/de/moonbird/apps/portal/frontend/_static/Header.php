<!DOCTYPE html>
<html>
<head>
  <title><?= Configuration::get('project', 'shortname') ?> :: <?= Configuration::get('project', 'name') ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Shared resources -->
  <script type="text/javascript" src="/shared/js/jquery/base/js/jquery-1.7.2.min.js"></script>
  <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
  <script type="text/javascript" src="/shared/js/jquery/base/js/jquery-ui-1.8.22.custom.min.js"></script>
  <link rel="stylesheet" href="/shared/js/jquery/base/css/custom-theme/jquery-ui-1.8.18.custom.css"/>
  <!-- load JS translation -->
  <script type="text/javascript" src="/shared/js/i18n.js"></script>
  <script type="text/javascript">
    new I18nLoader('<?= @$_COOKIE['lang']?>', 'sidb');
  </script>

  <!-- controller-specific resources -->
  <?php print $headDirectives; ?>
</head>
<body class="metrouicss">
<div class="portal-body">
