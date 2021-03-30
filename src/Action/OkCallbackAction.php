<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Action;

use DOMDocument;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OkCallbackAction implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();

        return $this->responseFactory->createResponse()
            ->withBody($this->streamFactory->createStream($this->getSuccessXml()))
            ->withHeader('Content-Type', 'application/xml');
    }

    private function getSuccessXml(): string
    {
        $rootElement = 'callbacks_payment_response';

        $dom = $this->createXMLWithRoot($rootElement);
        $root = $dom->getElementsByTagName($rootElement)->item(0);

        // добавление текста "true" в тег <callbacks_payment_response>
        $root->appendChild($dom->createTextNode('true'));

        // генерация xml
        $dom->formatOutput = true;
        return $dom->saveXML();
    }

    private function createXMLWithRoot($root): DomDocument
    {
        // создание xml документа
        $dom = new DomDocument('1.0');
        // добавление корневого тега
        $root = $dom->appendChild($dom->createElement($root));
        $attr = $dom->createAttribute("xmlns:ns2");
        $attr->value = "http://api.forticom.com/1.0/";
        $root->appendChild($attr);
        return $dom;
    }
}
