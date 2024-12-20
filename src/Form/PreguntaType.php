<?php
// src/Form/PreguntaType.php
namespace App\Form;

use App\Entity\Pregunta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Asegúrate de que esto esté correcto
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enunciado', TextType::class)
            ->add('fechaInicio', DateTime::class, [
                'widget' => 'single_text',
            ])
            ->add('fechaFin', DateTime::class, [
                'widget' => 'single_text',
            ])
            ->add('respuestas', CollectionType::class, [
                'entry_type' => RespuestaType::class,  // Formulario para cada respuesta
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,  // Esto es importante para que Symfony trate correctamente las respuestas
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pregunta::class,
        ]);
    }
}
