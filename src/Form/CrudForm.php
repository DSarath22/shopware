<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Form\CrudForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cgs\AudioSite\Core\Content\MyTest\MyTestEntity;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrudForm extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', [
                'required' => true,
                'choices' => ['yes' => 'Yes', 'no' => 'No'],
                'data' => $options['select_option']
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'select_option' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'my_form';
    }

}

