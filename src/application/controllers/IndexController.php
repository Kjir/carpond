<?php
// application/controllers/IndexController.php

/**
 * IndexController is the default controller for this application
 * 
 * Notice that we do not have to require 'Zend/Controller/Action.php', this
 * is because our application is using "autoloading" in the bootstrap.
 *
 * @see http://framework.zend.com/manual/en/zend.loader.html#zend.loader.load.autoload
 */
class IndexController extends Zend_Controller_Action 
{
    /**
     * The "index" action is the default action for all controllers. This 
     * will be the landing page of your application.
     *
     * Assuming the default route and default router, this action is dispatched 
     * via the following urls:
     *   /
     *   /index/
     *   /index/index
     *
     * @return void
     */
    public function indexAction() 
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $this->view->singleRides = $db->fetchAll("SELECT Ride.*, dep.name AS dep_town_name, arr.name AS arr_town_name  FROM Ride, Town dep, Town arr WHERE dep_town = dep.id AND arr_town = arr.id AND repeatable = FALSE AND date >= CURRENT_DATE() ORDER BY Date LIMIT 0, 10");
        $this->view->periodicRides = $db->fetchAll("SELECT Ride.*, dep.name AS dep_town_name, arr.name AS arr_town_name FROM Ride, Town dep, Town arr WHERE dep.id = Ride.dep_town AND arr.id = arr_town AND repeatable = TRUE AND (num_spots > 0 OR num_spots IS NULL) LIMIT 0, 10");
    }
}
