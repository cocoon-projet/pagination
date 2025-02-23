<?php

/*
 * LICENSE
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous license MIT.
 * Consulter le fichier LICENSE du project. LICENSE.txt.
 *
 */
namespace Cocoon\Pager;

class PaginatorConfig
{
    private $data;
    private int $perPage = 10;
    private $forPage;
    private $styling = 'all';
    private $total;

    public function __construct($data, $count, $forPage = 'page')
    {
        $this->setForPage($forPage);
        $this->setTotal($count);
        $this->setData($data);
    }
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }
    public function getPerPage()
    {
        return $this->perPage;
    }
    public function setForPage($forPage)
    {
        $this->forPage = $forPage;
    }
    public function getForPage()
    {
        return $this->forPage;
    }
    public function setstyling($styling)
    {
        $this->styling = $styling;
    }
    public function getstyling()
    {
        return $this->styling;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getTotal()
    {
        return $this->total;
    }
}
