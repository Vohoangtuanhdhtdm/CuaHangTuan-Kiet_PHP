<?php
namespace Controllers;

use Core\Controller;
use Models\Post;

class BlogController extends Controller {
    
    // Hiển thị danh sách bài viết
    public function index() {
        $postModel = new Post();
        $posts = $postModel->getPublishedPosts();
        
        $this->render('pages/blog/index', [
            'posts' => $posts
        ]);
    }

    // Hiển thị chi tiết 1 bài viết
    public function detail($slug) {
        $postModel = new Post();
        $post = $postModel->getBySlug($slug);
        
        if (!$post) {
            header("Location: /blog"); // Trở về trang blog nếu không tìm thấy
            exit;
        }

        $this->render('pages/blog/detail', [
            'post' => $post
        ]);
    }
}