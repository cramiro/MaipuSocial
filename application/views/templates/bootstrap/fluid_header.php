<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MaipuSocial</title>
    <meta name="description" content="Aplicacion para el monitoreo de redes sociales que se integra con SugarCRM.">
    <meta name="author" content="HashTag - Consultoria OpenSource">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="<?=base_url()?>css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
      }
    </style>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/social-jquery.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/bootstrap-buttons.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/bootstrap-alerts.js"></script>
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>

    <div class="topbar">
      <div class="topbar-inner">
        <div class="container-fluid">
          <a class="brand" href="#"><?php echo $brand?></a>
			<?php 
			$list = "<ul class='nav'>";
			foreach ($navigator as $name => $option){
				if ( 'TRUE' === $option['active']){
					$list .= "<li class='active'><a href='{$option['link']}'>{$name}</a></li>";
				}else{	
					$list .= "<li class=''><a href='{$option['link']}'>{$name}</a></li>";
				}
			}
			$list .= "</ul>";
			echo $list;
			?>
          <!--<ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href=" echo site_url('social/saved_searches')">Guardadas</a></li>
            <li><a href=" echo site_url('social/admin')">Admin</a></li>
          </ul>-->
			
          <p class="pull-right">Logged in as <a href="#"><?php echo $username?></a></p>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="sidebar">
        <div class="well">
			<?php
			// Construyo el sidebar

			/*
			* $sidebar contiene las categorias como key y un array 
			* con los items que cada uno tiene item_name e item_link
			*/
			foreach($sidebar as $name => $items){
				// Muestro el titulo del sidebar
				 echo "<h5>{$name}</h5>";
				 $list = "<ul>";
				// Recorro los items y armo la lista
				foreach ($items as $key => $item ){
					$list .= "<li><a href='{$item['item_link']}'>{$item['item_name']}</a></li>";
				}
				$list .= "</ul>";
				echo $list;
			}
			?>
        </div> 	<!-- end div well-->
      </div>	<!-- end div sidebar-->
      <div class="content">
