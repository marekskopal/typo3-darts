<?php

declare(strict_types=1);

namespace MarekSkopal\MsDarts\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Player extends AbstractEntity
{
    protected string $firstName = '';

    protected string $lastName = '';

    protected string $phone = '';

    protected string $email = '';

    /** @var ObjectStorage<FileReference> */
    #[Lazy]
    protected ObjectStorage $images;

    public function __construct()
    {
        $this->images = new ObjectStorage();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /** @return ObjectStorage<FileReference> */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    public function getImageMain(): ?FileReference
    {
        foreach ($this->images as $image) {
            return $image;
        }
        return null;
    }
}
