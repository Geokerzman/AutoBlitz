<?php 

class Router
{
    private $routes = [];

    public function addRoute($url, $template)
    {
        $this->routes['/' . PROJECT_NAME_FOLDER  . $url] = $template;
    }

    public function dispatch($url)
    {
        // print_r($url);
        // print_r($this ->routes);
        // echo $twig->render('index.twig', ['name' => 'Fabien']);
      
        if (array_key_exists($url, $this->routes)) {
            $template = $this->routes[$url];
            $this->renderTemplate($template);
       
        } else {
            // Handle 404 Not Found
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function renderTemplate($template)
    {
        global $twig; // Assuming $twig is available globally

        echo $twig->render($template);
    }
}