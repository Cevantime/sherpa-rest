<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 29/10/18
 * Time: 20:08
 */

namespace Sherpa\Rest\Controller;


use Psr\Http\Message\ServerRequestInterface;

interface RestCrudControllerInterface
{
    public function getList(ServerRequestInterface $request);
    public function getItem(ServerRequestInterface $request, $id);
    public function createItem(ServerRequestInterface $request);
    public function updateItem(ServerRequestInterface $request, $id);
    public function deleteItem(ServerRequestInterface $request, $id);
}