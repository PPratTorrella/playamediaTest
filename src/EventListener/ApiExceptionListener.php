<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Psr\Log\LoggerInterface;

class ApiExceptionListener
{

	public function __construct(private readonly LoggerInterface $logger)
	{
	}

	public function onKernelException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();
		$message = sprintf(
			'Error: %s with code: %s',
			$exception->getMessage(),
			$exception->getCode()
		);

		$this->logger->error($message);

		$response = new JsonResponse([
			'success' => false,
			'error' => 'An error occurred while processing your request.'
		]);

		// you can do more here, but also more specific error management in the specific api calls
		$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

		$event->setResponse($response);
	}
}
