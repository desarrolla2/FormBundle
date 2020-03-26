<?php

/*
 * This file is part of the Form Bundle package
 *
 * Copyright (c) 2017 Daniel González
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Daniel González <daniel@desarrolla2.com>
 */

namespace Desarrolla2\FormBundle\Form\Type;

use AppBundle\Entity\Service\Page\Image;
use Desarrolla2\FormBundle\Form\Validator\FileExtension;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Handler\UploadHandler;

class CropperType extends AbstractType
{
    /**
     * @var UploadHandler
     */
    protected $uploadHandler;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(UploadHandler $uploadHandler, EntityManager $em)
    {
        $this->uploadHandler = $uploadHandler;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'originalImageFile',
                VichFileType::class,
                [
                    'label' => false,
                    'download_link' => false,
                    'allow_delete' => false,
                    'attr' => ['accept' => '.png, .jpeg, .jpg'],
                    'constraints' => [
                        new FileExtension(['fileExtensions' => ['jpeg', 'jpg', 'png']]),
                    ],
                ]
            )
            ->add('data', HiddenType::class)
            ->add('canvasData', HiddenType::class)
            ->add('cropBoxData', HiddenType::class)
            ->add('base64', HiddenType::class, ['mapped' => false]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();
            $image = $form->getData();

            $this->saveImage($image, $form->get('base64')->getData());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'bgCropperImage' => null,
                'bgCropperOpacity' => 1,
                'entityName' => 'image',
                'formName' => 'image'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image'] = $form->getData();
        $view->vars['bgCropperImage'] = $options['bgCropperImage'];
        $view->vars['bgCropperOpacity'] = $options['bgCropperOpacity'];
        $view->vars['entityName'] = $options['entityName'];
        $view->vars['formName'] = $options['formName'];
    }

    public function saveImage($image, ?string $base64): void
    {
        if (is_null($base64)) {
            return;
        }
        $extension = $this->getExtension($base64);
        $mimeType = $this->getMimeType($base64);
        $body64 = $this->getBodyBase64($base64);

        $fileName = sprintf('%s.%s', hash('sha256', uniqid(get_called_class(), true)), $extension);
        $dir = '/tmp/images';
        $this->cmd(sprintf('mkdir -p %s', $dir));
        $path = sprintf('%s/%s', $dir, $fileName);

        file_put_contents($path, base64_decode($body64, true));
        $realpath = realpath($path);

        if (!is_file($realpath)) {
            throw new NotFoundHttpException();
        }

        $croppedImageFile = new UploadedFile($realpath, $fileName, $mimeType, filesize($realpath), null, true);

        $image->setImageFile($croppedImageFile);
    }

    private function getExtension(string $base64): string
    {
        $data = $this->getDataExtensionByBase64($base64);

        if (!$data) {
            throw new NotFoundHttpException();
        }

        return $data['extension'];
    }

    private function getMimeType(string $base64): string
    {
        $data = $this->getDataExtensionByBase64($base64);

        if (!$data) {
            throw new NotFoundHttpException();
        }

        return $data['mime-type'];
    }

    private function cmd(string $cmd): void
    {
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function getBodyBase64(string $base64): string
    {
        $data = explode(',', $base64);

        return $data[1];
    }

    private function getDataExtensionByBase64(string $base64): ?array
    {
        foreach ($this->getFormats() as $key => $value) {
            if (!strpos($base64, $key)) {
                continue;
            }

            return ['extension' => $key, 'mime-type' => $value];
        }

        return null;
    }

    private function getFormats(): array
    {
        return ['jpeg' => 'image/jpeg', 'jpg' => 'image/jpg', 'png' => 'image/png'];
    }
}
