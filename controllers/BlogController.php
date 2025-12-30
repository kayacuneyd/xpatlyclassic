<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Models\BlogPost;
use Models\BlogComment;

class BlogController
{
    /**
     * Display blog listing page
     */
    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $posts = BlogPost::getPublished($perPage, $offset);
        $total = BlogPost::countPublished();
        $totalPages = ceil($total / $perPage);

        $this->view('blog/index', [
            'title' => __('blog.title') ?? 'Blog',
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Display single blog post
     */
    public function show(string $slug): void
    {
        $post = BlogPost::findBySlug($slug);

        if (!$post || ($post['status'] !== 'published' && !Auth::isAdmin())) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            exit;
        }

        // Increment views
        BlogPost::incrementViews($post['id']);

        // Get recent posts for sidebar
        $recentPosts = BlogPost::getRecent(5);

        // Get comments
        $comments = BlogComment::getByPost($post['id']);
        $commentCount = BlogComment::countByPost($post['id']);

        $this->view('blog/show', [
            'title' => $post['meta_title'] ?? $post['title'],
            'metaDescription' => $post['meta_description'] ?? '',
            'post' => $post,
            'recentPosts' => $recentPosts,
            'comments' => $comments,
            'commentCount' => $commentCount,
        ]);
    }

    /**
     * Add comment to blog post
     */
    public function addComment(string $slug): void
    {
        if (!Auth::check()) {
            Flash::error(__('auth.login') ?? 'Please login to comment');
            header("Location: " . url('blog/' . $slug));
            exit;
        }

        $post = BlogPost::findBySlug($slug);
        if (!$post || $post['status'] !== 'published') {
            http_response_code(404);
            exit;
        }

        $validator = new Validator($_POST, [
            'content' => 'required|min:10|max:1000',
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: " . url('blog/' . $slug));
            exit;
        }

        BlogComment::create([
            'post_id' => $post['id'],
            'user_id' => Auth::id(),
            'content' => strip_tags($_POST['content']),
        ]);

        Flash::success(__('blog.comment_submitted') ?? 'Your comment has been submitted for approval');
        header("Location: " . url('blog/' . $slug) . '#comments');
        exit;
    }

    /**
     * Admin: List all posts
     */
    public function adminIndex(): void
    {
        Auth::requireRole(['super_admin']);

        $posts = BlogPost::getAll();

        $this->view('admin/blog/index', [
            'title' => 'Manage Blog Posts',
            'posts' => $posts,
        ]);
    }

    /**
     * Admin: Create post form
     */
    public function create(): void
    {
        Auth::requireRole(['super_admin']);

        $this->view('admin/blog/edit', [
            'title' => 'Create Blog Post',
            'post' => null,
        ]);
    }

    /**
     * Admin: Store new post
     */
    public function store(): void
    {
        Auth::requireRole(['super_admin']);

        $validator = new Validator($_POST, [
            'title_en' => 'required|min:5|max:255',
            'content_en' => 'required|min:50',
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /admin/blog/create');
            exit;
        }

        $slug = BlogPost::generateSlug($_POST['title_en']);

        $id = BlogPost::create([
            'slug' => $slug,
            'status' => $_POST['status'] ?? 'draft',
            'author_id' => Auth::id(),
            'title_en' => $_POST['title_en'],
            'content_en' => $_POST['content_en'],
            'meta_title_en' => $_POST['meta_title_en'] ?? null,
            'meta_description_en' => $_POST['meta_description_en'] ?? null,
            'title_et' => $_POST['title_et'] ?? null,
            'content_et' => $_POST['content_et'] ?? null,
            'meta_title_et' => $_POST['meta_title_et'] ?? null,
            'meta_description_et' => $_POST['meta_description_et'] ?? null,
            'title_ru' => $_POST['title_ru'] ?? null,
            'content_ru' => $_POST['content_ru'] ?? null,
            'meta_title_ru' => $_POST['meta_title_ru'] ?? null,
            'meta_description_ru' => $_POST['meta_description_ru'] ?? null,
            'published_at' => $_POST['status'] === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        // Handle featured image
        if (!empty($_FILES['featured_image']['name'])) {
            $this->handleImageUpload($id);
        }

        Flash::success(__('blog.created') ?? 'Blog post created successfully');
        header('Location: /admin/blog');
        exit;
    }

    /**
     * Admin: Edit post form
     */
    public function edit(string $id): void
    {
        Auth::requireRole(['super_admin']);

        $post = BlogPost::find((int) $id);

        if (!$post) {
            Flash::error('Post not found');
            header('Location: /admin/blog');
            exit;
        }

        $this->view('admin/blog/edit', [
            'title' => 'Edit Blog Post',
            'post' => $post,
        ]);
    }

    /**
     * Admin: Update post
     */
    public function update(string $id): void
    {
        Auth::requireRole(['super_admin']);

        $id = (int) $id;
        $post = BlogPost::find($id);

        if (!$post) {
            Flash::error('Post not found');
            header('Location: /admin/blog');
            exit;
        }

        $validator = new Validator($_POST, [
            'title_en' => 'required|min:5|max:255',
            'content_en' => 'required|min:50',
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /admin/blog/{$id}/edit");
            exit;
        }

        // Regenerate slug if title changed
        $slug = $post['slug'];
        if ($_POST['title_en'] !== $post['title_en']) {
            $slug = BlogPost::generateSlug($_POST['title_en'], $id);
        }

        $wasPublished = $post['status'] === 'published';
        $isNowPublished = $_POST['status'] === 'published';

        BlogPost::update($id, [
            'slug' => $slug,
            'status' => $_POST['status'] ?? 'draft',
            'title_en' => $_POST['title_en'],
            'content_en' => $_POST['content_en'],
            'meta_title_en' => $_POST['meta_title_en'] ?? null,
            'meta_description_en' => $_POST['meta_description_en'] ?? null,
            'title_et' => $_POST['title_et'] ?? null,
            'content_et' => $_POST['content_et'] ?? null,
            'meta_title_et' => $_POST['meta_title_et'] ?? null,
            'meta_description_et' => $_POST['meta_description_et'] ?? null,
            'title_ru' => $_POST['title_ru'] ?? null,
            'content_ru' => $_POST['content_ru'] ?? null,
            'meta_title_ru' => $_POST['meta_title_ru'] ?? null,
            'meta_description_ru' => $_POST['meta_description_ru'] ?? null,
            'published_at' => (!$wasPublished && $isNowPublished) ? date('Y-m-d H:i:s') : $post['published_at'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Handle featured image
        if (!empty($_FILES['featured_image']['name'])) {
            $this->handleImageUpload($id);
        }

        Flash::success(__('blog.updated') ?? 'Blog post updated successfully');
        header('Location: /admin/blog');
        exit;
    }

    /**
     * Admin: Delete post
     */
    public function delete(string $id): void
    {
        Auth::requireRole(['super_admin']);

        BlogPost::delete((int) $id);

        Flash::success(__('blog.deleted') ?? 'Blog post deleted successfully');
        header('Location: /admin/blog');
        exit;
    }

    /**
     * Handle featured image upload
     */
    private function handleImageUpload(int $postId): void
    {
        $uploader = new \Core\Uploader();
        $result = $uploader->upload(
            $_FILES['featured_image'],
            "public/uploads/blog/{$postId}",
            'featured_'
        );

        if ($result) {
            BlogPost::update($postId, [
                'featured_image' => '/uploads/blog/' . $postId . '/' . $result['filename']
            ]);
        }
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
