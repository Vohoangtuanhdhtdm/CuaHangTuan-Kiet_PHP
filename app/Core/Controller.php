<?php
namespace Core;

class Controller {
    
    protected function render($view, $data = []) {
        extract($data); 
        
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            die("View không tồn tại: " . $viewFile);
        }
    }

    protected function jsonResponse($success, $data = null, $message = '') {
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => $success,
            'data'    => $data,
            'message' => $message
        ]);
        
        exit; 
    }
}