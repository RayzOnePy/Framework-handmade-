<?php

namespace Src;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class View
{
    private string $view = '';
    private array $data = [];
    private string $root = '';
    private string $layout = '/layouts/main.php';

    public function __construct(string $view = '', array $data = [])
    {
        $this->root = $this->getRoot();
        $this->view = $view;
        $this->data = $data;
    }

    private function getRoot(): string
    {
        global $app;
        $root = $app->settings->getRootPath();
        $path = $app->settings->getViewsPath();

        return $_SERVER['DOCUMENT_ROOT'] . $root . $path;
    }

    private function getPathToMain(): string
    {
        return $this->root . $this->layout;
    }

    //Путь до текущего шаблона
    private function getPathToView(string $view = ''): string
    {
        $view = str_replace('.', '/', $view);
        return $this->getRoot() . "/$view.php";
    }

    /**
     * @throws Exception
     */
    public function render(string $view = '', array $data = []): string
    {
        $path = $this->getPathToView($view);

        if (file_exists($this->getPathToMain()) && file_exists($path)) {

            extract($data, EXTR_PREFIX_SAME, '');

            ob_start();
            require $path;
            $content = ob_get_clean();

            return require($this->getPathToMain());
        }
        throw new Exception('Error render');
    }

    #[NoReturn] public function toJSON(array $data = [], int $code = 200): void
    {
        header_remove();
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }


    /**
     * @throws Exception
     */
    public function __toString(): string
    {
        return $this->render($this->view, $this->data);
    }

}
