<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset($_GET['fundraiser']) && get_post_status ( $_GET['fundraiser'] ))
{
    require_once($this->plugin_path.'/inc/admin-page-content-single-fundraiser.php');
}
else
{
    require_once($this->plugin_path.'/inc/admin-page-content-fundraisers.php');
}