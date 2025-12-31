<?php

namespace Controllers;

class PageController
{
    public function about(): void { $this->render('about'); }
    public function imprint(): void { $this->render('imprint'); }
    public function privacy(): void { $this->render('privacy'); }
    public function terms(): void { $this->render('terms'); }
    public function contact(): void { $this->render('contact'); }
    public function blog(): void { $this->render('blog'); }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function render(string $slug): void
    {
        $this->view("pages/{$slug}", [
            'title' => __("pages.{$slug}.title")
        ]);
    }
}
