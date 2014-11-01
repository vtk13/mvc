<?php
namespace Vtk13\Mvc\Handlers;

use Vtk13\Mvc\Http\IRequest;
use Vtk13\Mvc\Http\IResponse;

interface IHandler
{
    /**
     * @param IRequest $request
     * @return IResponse|null
     */
    public function handle(IRequest $request);
}
