<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SupplierCreateForm extends AbstractType
{
    public const FIELD_NAME = 'name';

    public const FIELD_DESCRIPTION = 'description';

    public const FIELD_IS_ACTIVE = 'isActive';

    public const FIELD_EMAIL = 'email';

    public const FIELD_PHONE = 'phone';

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'supplier';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(static::FIELD_NAME, TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ])
            ->add(static::FIELD_DESCRIPTION, TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ])
            ->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
                'label' => 'Active',
                'mapped' => false,
                'required' => false,
                'data' => (bool)$options[static::FIELD_IS_ACTIVE],
            ])
            ->add(static::FIELD_EMAIL, TextType::class, [
                'label' => 'Email',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ])
            ->add(static::FIELD_PHONE, TextType::class, [
                'label' => 'Phone',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'Generated\Shared\Transfer\SupplierTransfer',
            static::FIELD_IS_ACTIVE => true,
        ]);
        $resolver->setAllowedTypes(static::FIELD_IS_ACTIVE, 'bool');
    }

    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }
}
