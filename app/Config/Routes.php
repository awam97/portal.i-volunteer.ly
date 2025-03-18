<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * @var RouteCollection $routes
 */

// Default route to the login page
$routes->get('/', 'Home::Login');
$routes->get('SendNotification', 'NotificationController::sendnotification');
$routes->get('SendGroup', 'NotificationController::sendtogroup');
$routes->post('Connect', 'ApiController::connect');
$routes->get('Cities', 'ApiController::cities');
$routes->get('Statistics', 'ApiController::statistics');


// Dynamic routing for any controller and method
$routes->match(['get', 'post'], '(:segment)/(:segment)', function ($controller, $method) {
    // Build the fully qualified controller class name
    $controllerName = "App\\Controllers\\" . ucfirst($controller);

    // Check if the controller class exists
    if (class_exists($controllerName)) {
        
        $controllerInstance = new $controllerName();

        // Check if the method exists in the controller
        if (method_exists($controllerInstance, $method)) {
            // Call the method and return the result
            return $controllerInstance->$method();
        }

        // Method not found
        throw PageNotFoundException::forPageNotFound("Method '{$method}' not found in {$controllerName}.");
    }

    // Controller not found
    throw PageNotFoundException::forPageNotFound("Controller '{$controller}' not found.");
});

// Optional: Route to handle methods directly in the default controller (e.g., Home)
$routes->match(['get', 'post'], '(:segment)', function ($method) {
    $controllerName = "App\\Controllers\\Home";

    // Check if the method exists in the default controller
    if (method_exists($controllerName, $method)) {
        $controllerInstance = new $controllerName();

        // Call the method and return the result
        return $controllerInstance->$method();
    }

    // Method not found
    throw PageNotFoundException::forPageNotFound("Method '{$method}' not found in Home controller.");
});
