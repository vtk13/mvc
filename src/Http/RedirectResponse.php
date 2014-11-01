<?php
namespace Vtk13\Mvc\Http;

class RedirectResponse extends Response
{
    public function __construct($url, $permanent = false)
    {
        parent::__construct(null, $permanent ? 301 : 302, array(
            'Location' => $url,
        ));
    }
}
