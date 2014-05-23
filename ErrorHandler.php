<?php

namespace prgTW\ErrorHandlerBundle;

use prgTW\ErrorHandler\Error\ErrorException;
use prgTW\ErrorHandler\Handler\HandlerInterface;
use prgTW\ErrorHandler\Metadata\Metadata;
use prgTW\ErrorHandler\Processor\ProcessorInterface;

class ErrorHandler implements HandlerInterface
{
	/** @var \prgTW\ErrorHandler\ErrorHandler */
	protected $errorHandler;

	/**
	 * @param \prgTW\ErrorHandler\ErrorHandler $errorHandler
	 */
	public function __construct(\prgTW\ErrorHandler\ErrorHandler $errorHandler)
	{
		$this->errorHandler = $errorHandler;
		$this->errorHandler->register(false, false, true);
	}

	/** {@inheritdoc} */
	public function handleError(ErrorException $error, Metadata $metadata = null)
	{
		if (array() === $metadata->getCategories())
		{
			$metadata->addCategory('default');
		}

		$this->errorHandler->handleError(
			$error->getCode(),
			$error->getMessage(),
			$error->getFile(),
			$error->getLine(),
			$error->getContext(),
			$metadata
		);
	}

	/** {@inheritdoc} */
	public function handleException(\Exception $exception, Metadata $metadata = null)
	{
		if (array() === $metadata->getCategories())
		{
			$metadata->addCategory('default');
		}
		$this->errorHandler->handleException($exception, $metadata);
	}

	/** {@inheritdoc} */
	public function handleEvent($event, Metadata $metadata = null)
	{
		if (array() === $metadata->getCategories())
		{
			$metadata->addCategory('default');
		}
		$this->errorHandler->handleEvent($event, $metadata);
	}

	/**
	 * @param HandlerInterface $handler
	 * @param array            $categories
	 */
	public function addHandler(HandlerInterface $handler, array $categories = array())
	{
		$categories = array() !== $categories ? $categories : array('default');
		$this->errorHandler->getHandlerManager()->attach($handler, $categories);
	}

	/**
	 * @param ProcessorInterface $processor
	 */
	public function addProcessor(ProcessorInterface $processor)
	{
		$this->errorHandler->getProcessorManager()->attach($processor);
	}

}
