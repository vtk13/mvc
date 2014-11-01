<?php
namespace Vtk13\Mvc\Http;

class JsonResponse extends Response
{
    public function __construct($data, $status = 200, array $headers = array())
    {
        $headers['Content-Type'] = 'application/json';
        parent::__construct(json_encode($data), $status, $headers);
    }
}
