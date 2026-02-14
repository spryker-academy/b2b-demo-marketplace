<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SupplierCreateForm extends AbstractType
{
    public const string FIELD_NAME = 'name';

    public const string FIELD_DESCRIPTION = 'description';

    public const string FIELD_STATUS = 'status';

    public const string FIELD_EMAIL = 'email';

    public const string FIELD_PHONE = 'phone';

    /**
     * @return string
     */
    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'supplier';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO: Add the name field
        // TODO: Add the description field
        // TODO: Add the status field
        // TODO: Add the email field
        // TODO: Add the phone field
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }
}
