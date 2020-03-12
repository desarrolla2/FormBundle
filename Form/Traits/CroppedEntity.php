<?php

/*
 * This file is part of the she crm package.
 *
 * Copyright (c) 2016-2019 Devtia Soluciones.
 * All rights reserved.
 *
 * @author Daniel González <daniel@devtia.com>
 */

namespace Desarrolla2\FormBundle\Form\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

trait CroppedEntity
{
    /**
     * @ORM\Column(type="string")
     */
    protected $originalImage = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $image = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $data = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $canvasData = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $cropBoxData = '';

    /**
     * @Assert\Image(maxWidth=8000, maxHeight=8000, minWidth=100, minHeight=100, mimeTypes={"image/png", "image/jpg", "image/jpeg"})
     * @Vich\UploadableField(mapping="image", fileNameProperty="originalImage")
     */
    protected $originalImageFile;

    /**
     * @Assert\Image(maxWidth=8000, maxHeight=8000, minWidth=100, minHeight=100, mimeTypes={"image/png", "image/jpg", "image/jpeg"})
     * @Vich\UploadableField(mapping="image", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="integer")
     */
    private $width = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $height = 0;

    public function getImage()
    {
        return $this->image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getOriginalImage()
    {
        return $this->originalImage;
    }

    public function getOriginalImageFile()
    {
        return $this->originalImageFile;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getCanvasData()
    {
        return $this->canvasData;
    }

    public function getCropBoxData()
    {
        return $this->cropBoxData;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function hasOriginalImage(): bool
    {
        return $this->getOriginalImage() ? true : false;
    }

    public function hasImage(): bool
    {
        return $this->getImage() ? true : false;
    }

    public function setImage($image)
    {
        $this->image = (string) $image;
    }

    /**
     * @param File|UploadedFile $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    public function setOriginalImage($originalImage)
    {
        $this->originalImage = (string) $originalImage;
    }

    /**
     * @param UploadedFile||File $originalImageFile
     */
    public function setOriginalImageFile($originalImageFile)
    {
        $this->originalImageFile = $originalImageFile;
    }

    public function setData($data)
    {
        $this->data = (string) $data;
    }

    public function setCanvasData($canvasData)
    {
        $this->canvasData = (string) $canvasData;
    }

    public function setCropBoxData($cropBoxData)
    {
        $this->cropBoxData = (string) $cropBoxData;
    }

    public function setWidth($width)
    {
        $this->width = (int) $width;
    }

    public function setHeight($height)
    {
        $this->height = (int) $height;
    }
}
