<?php

namespace App\Http\Controllers;

class AccountBaseController extends Controller
{
    protected string $pageTitle = '';

    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function __construct()
    {
        $this->data['pageTitle'] = &$this->pageTitle;
    }
}
