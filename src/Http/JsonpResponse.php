<?php
namespace Vtk13\Mvc\Http;

class JsonpResponse extends Response
{
    /**
     * @param string $callback
     * @param mixed $data
     * @param int $status
     * @param array $headers
     */
    public function __construct($callback, $data, $status = 200, array $headers = array())
    {
        $headers['Content-Type'] = 'application/javascript';
        parent::__construct($callback . '(' . json_encode($data) . ')', $status, $headers);
    }
}
