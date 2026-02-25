<?php

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\ProfileForm as SprykerProfileForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class ProfileForm extends SprykerProfileForm
{
    public const FIELD_MESSAGE = 'fk_message';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $this->addMessageField($builder);
    }

    public function addMessageField(FormBuilderInterface $builder): static
    {
        $builder->add(self::FIELD_MESSAGE, TextType::class, [
            'label' => 'customer.profile.message',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        $builder->get(self::FIELD_MESSAGE)->addModelTransformer(
            // TODO: Use the factory to pass the MessageTransformer as parameter
        );

        return $this;
    }
}
