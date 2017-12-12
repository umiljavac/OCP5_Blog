<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 07/12/2017
 * Time: 16:11
 */

/**
 * This class return the page with the view and all the variables needed to allow the display of user request.
 */

namespace Main;


class ServerResponse
{
    protected $page;

    public function send()
    {
        exit($this->page->getGeneratedPage());
    }

    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($redirection)
    {
        $_SESSION['trajet'] = 'redirect';
        header('Location: '. $redirection);
    }

    public function redirect404()
    {
        $this->page = new Page();
        $this->page->setFileView(__DIR__. '/../../Errors/404.html');
        $this->addHeader('HTTP/1.O NotFound');
        $this->send();
    }

    public function redirectErrorDB()
    {
        $this->page = new Page();
        $this->page->setFileView(__DIR__. '/../../Errors/errorDB.html');
        $this->send();
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    public function setCookie($name, $value ='', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function page()
    {
        return $this->page;
    }
}
