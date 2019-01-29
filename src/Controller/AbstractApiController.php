<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractFOSRestController
{
    /** @var PropertyAccessor */
    private $propertyAccessor;

    /** @var ValidatorInterface */
    private $validator;

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            PropertyAccessorInterface::class,
            ValidatorInterface::class,
        ]);
    }

    /**
     * @param array|object $entity
     */
    protected function handleRequest($entity, Request $request): void
    {
        $this->validateSchema($entity, $request);

        foreach ($request->request->all() as $key => $value) {
            $this->getPropertyAccessor()->setValue($entity, $key, $value);
        }

        $this->validateData($entity);
    }

    /**
     * @param array|object $entity
     */
    private function validateSchema($entity, Request $request): void
    {
        $errorFields = [];
        foreach (array_keys($request->request->all()) as $key) {
            if (!$this->getPropertyAccessor()->isWritable($entity, $key)) {
                $errorFields[] = $key;
            }
        }
        if ($errorFields) {
            throw new BadRequestHttpException('Request does not match the data schema. Fields: "' .
                implode(', ', $errorFields) . '" is not writable or not exist in ' . get_class($entity) . ' entity');
        }
    }

    /**
     * @param array|object $entity
     */
    private function validateData($entity): void
    {
        $errors = $this->getValidator()->validate($entity);
        if ($errors->count() > 0) {
            throw new ValidatorException((string) $errors);
        }
    }

    private function getPropertyAccessor(): PropertyAccessor
    {
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = $this->get(PropertyAccessorInterface::class);
        }
        return $this->propertyAccessor;
    }

    private function getValidator(): ValidatorInterface
    {
        if (null === $this->validator) {
            $this->validator = $this->get(ValidatorInterface::class);
        }
        return $this->validator;
    }
}
