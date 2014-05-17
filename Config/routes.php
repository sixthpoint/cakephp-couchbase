<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
//Router::connect('/', array('controller' => 'pages', 'action' => 'index')); 
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
// Include custom subdomain routes class
App::uses('SubdomainRoute', 'Routes');

Router::mapResources('api');
//Router::mapResources('churches');
Router::mapResources('artists');
Router::mapResources('albums');
Router::parseExtensions('json');

// Use subdomain routing to determine what kind of subdomain route we have (church or station). Only executes if we have a subdomain
if (Configure::read('SubdomainHTTP.subdomain')) {

    $subdomainRouting = new SubdomainRoute();
}

// Set basic pages for IRDB.FM
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::connect('/streaming', array('controller' => 'pages', 'action' => 'streaming'));
Router::connect('/webdesign', array('controller' => 'pages', 'action' => 'webdesign'));
Router::connect('/networking', array('controller' => 'pages', 'action' => 'networking'));
Router::connect('/techsupport', array('controller' => 'pages', 'action' => 'techsupport'));
Router::connect('/freequote', array('controller' => 'pages', 'action' => 'freequote'));
Router::connect('/help', array('controller' => 'pages', 'action' => 'help'));
Router::connect('/services', array('controller' => 'pages', 'action' => 'services'));
Router::connect('/contactus', array('controller' => 'pages', 'action' => 'contactus'));
Router::connect('/aboutus', array('controller' => 'pages', 'action' => 'aboutus'));
Router::connect('/computerwarranty', array('controller' => 'pages', 'action' => 'computerwarranty'));
Router::connect('/churchoffer', array('controller' => 'pages', 'action' => 'churchoffer'));


if (Configure::read('Subdomain.sub_bus_id') != NULL) {

    // Setup routes for Church Pages
    Router::connect('/', array('controller' => 'businesses', 'action' => 'web_player'));
    
    Router::connect('/data_widget/:size', array('controller' => 'businesses', 'action' => 'data_widget'), array('pass' => array('size'), 'size' => '[0-9]+'));
    Router::connect('/audio_widget/*', array('controller' => 'businesses', 'action' => 'audio_widget'));

    Router::connect('/recordings/*', array('controller' => 'businesses', 'action' => 'recordings'));
    Router::connect('/stream/*', array('controller' => 'streams', 'action' => 'status'));

    // both need moved to a better location
    Router::connect('/schedule/*', array('controller' => 'businesses', 'action' => 'schedule'));
} else {
    // Default route for IRDB.FM with no subdomain set
    Router::connect('/', array('controller' => 'pages', 'action' => 'index'));
}


// Music Recognition System Routing
Router::connect('/albums/:artist/:album', array('controller' => 'albums'), array('pass' => array('artist', 'album')));
Router::connect('/artists/:artist', array('controller' => 'artists'), array('pass' => array('artist')));



// Marked for deletion! 7/7/13
//Router::connect('/stations/:action/', array('controller' => 'stations'));
//Router::connect('/stations/artists/:action/:id/:artist', array('controller' => 'artists'), array('pass' => array('id', 'artist'), 'id' => '[0-9]+'));
//Router::connect('/mobile/:action/:id-:slug', array('controller' => 'mobile'), array('pass' => array('id', 'slug'), 'id' => '[0-9]+'));





/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
