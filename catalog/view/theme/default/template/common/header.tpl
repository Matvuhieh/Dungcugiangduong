<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:url" content="<?php echo $uri; ?>" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image" content="<?php echo $image; ?>" />
	
<?php $css = ''; ?>
<?php foreach ($styles as $style) { ?>
  <?php $css .= $style['href'] . '|'; ?>
<?php } ?>
<link href="index.php?route=common/css&css=<?php echo $css; ?>" rel="stylesheet" type="text/css" />

<?php $javascript = ''; ?>
<?php foreach ($scripts as $script) { ?>
  <?php $javascript .= $script . '|'; ?>
<?php } ?>
<script src="index.php?route=common/javascript&javascript=<?php echo $javascript; ?>" type="text/javascript"></script>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">
<div id="top"></div>
<?php if($header_top){ ?>
<div class="container"> <?php echo $header_top; ?> </div>
<?php } ?>
<!--header-->
<?php if (in_array($layout_id,array(6,10,3,7,12,8,4,1,11,5,2,13,9,14))) { ?>
<div class="ui-sortable-handle page-frame"><div class="row"><div class="block"><?php
    if ($logged) {
        $user_name = $_this->user->getFirstName() . ' ' . $_this->user->getLastName();
    }
?>

<nav class="navigation-38" id="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-4">
               <div id="top-links" class="pull-left nav-telephone">
                    <?php echo $_this->language->get('text_telephone'); ?><?php echo $_this->config->get('config_telephone'); ?>
                </div> 
            </div>
            <div class="col-md-9 col-sm-9 col-xs-8 nav-content">
                <div id="top-links" class="nav pull-right">
                    <ul class="list-inline">
                    <?php foreach ($navigations as $navigation){ ?>
                      <?php if($navigation['children']){ ?>
                        <li id="navigation-item-<?php echo $navigation['navigation_id']; ?>" class="dropdown">
                        <a href="<?php echo $navigation['url']; ?>" title="<?php echo $navigation['title']; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa <?php echo $navigation['icon']; ?>"></i> <span class="hidden-xs"><?php echo $navigation['title']; ?></span> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                            <?php foreach($navigation['children'] as $children) { ?>
                                <li id="navigation-item-<?php echo $children['navigation_id']; ?>"><a href="<?php echo $children['url']; ?>"><?php echo $children['title']; ?></a>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>
                      <?php }else { ?>
                        <li id="navigation-item-<?php echo $navigation['navigation_id']; ?>">
                          <a href="<?php echo $navigation['url']; ?>" title="<?php echo $navigation['title']; ?>"><i class="fa <?php echo $navigation['icon']; ?>"></i> <span class="hidden-xs"><?php echo $navigation['title']; ?></span></a>
                        </li>
                      <?php } ?>
                    <?php } ?>
                        <li id="account-navigation" class="dropdown"><a href="<?php echo $user_name; ?>" title="<?php echo ($logged) ? $user_name : $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-staff"></i> <span class="hidden-xs"><?php echo ($logged) ? $user_name : $text_account; ?></span> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php if ($logged) { ?>
                                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                                <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                                <?php } else { ?>
                                <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>  
    </div>
</nav></div></div></div><div class="page-frame"><div class="row"><div class="block"><header class="header" id="header">
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</header>
<?php echo $megamenu; ?></div></div></div>
<?php } ?>
<!--end-header-->
<div id="page-body">