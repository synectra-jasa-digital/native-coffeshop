<?php
namespace App\Core;

class Controller {
    
    /**
     * Render a view file within a layout
     *
     * @param string $view Path to the view file inside app/Views/
     * @param array $data Data to be extracted and passed to the view
     * @param string $layout Path to the layout file inside app/Views/layouts/
     */
    protected function view($view, $data = [], $layout = 'layout') {
        // Render the view content
        $content = $this->renderView($view, $data);
        
        // Check if layout exists
        $layoutPath = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        
        if (file_exists($layoutPath) && $layout !== false) {
            // Pass the rendered content to the layout
            extract($data);
            require $layoutPath;
        } else {
            // If no layout, just output the content directly
            echo $content;
        }
    }

    /**
     * Render a view component using output buffering
     *
     * @param string $viewPath Path to the view file
     * @param array $data Data to extract
     * @return string Rendered HTML content
     */
    protected function renderView($viewPath, $data = []) {
        $fullPath = __DIR__ . '/../Views/' . $viewPath . '.php';
        
        if (file_exists($fullPath)) {
            // Extract array keys to variables
            extract($data);
            
            // Start output buffering
            ob_start();
            
            // Include the view file
            require $fullPath;
            
            // Return buffered content and clean buffer
            return ob_get_clean();
        } else {
            die("View does not exist: " . $viewPath);
        }
    }
    
    /**
     * Helper to render a reusable component inside a view
     */
    protected function component($component, $data = []) {
        return $this->renderView('components/' . $component, $data);
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        $fullUrl = BASE_URL . (str_starts_with($url, '/') ? $url : '/' . $url);
        header("Location: " . $fullUrl);
        exit();
    }
    
    /**
     * Return JSON response (useful for AJAX)
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
