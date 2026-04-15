<?php

namespace App\Core;

class Controller
{
    public function render($view, $data = [], $layout = 'main')
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $viewPath = __DIR__ . "/../Views/$view.php";
        if (!file_exists($viewPath)) {
            throw new \Exception("Vue '$view' non trouvée.");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = __DIR__ . "/../Views/layouts/$layout.php";
            if (file_exists($layoutPath)) {
                ob_start();
                require $layoutPath;
                return ob_get_clean();
            }
        }

        return $content;
    }
}
