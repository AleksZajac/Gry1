<?php
/**
 * Game type.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Type;
use App\Form\DataTransformer\TagsDataTransformer;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GameType.
 */
class GameType extends AbstractType
{


    /**
     * @var TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * TaskType constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'Tytuł',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'publisher',
            TextType::class,
            [
                'label' => 'Wydawca',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'Opis',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'extensive',
            ChoiceType::class,
            [
                'choices'  => [
                    'TAK' => 1,
                    'NIE' => 0,
                    ],
            ]
        );
        $builder->add(
            'gametime',
            ChoiceType::class,
            [
                'choices'  => [
                    '15 min' => 15,
                    '30 min' => 30,
                    '45 min' => 45,
                    '1h' => 60,
                    '2h' => 120,
                ],
            ]
        );
        $builder->add(
            'releasedate',
            DateType::class,
            [
                'label' => 'Data wydania',
                'input' => 'string',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Data wydania(YYYY-MM-DD)'],
            ]
        );

        $builder->add(
            'type',
            EntityType::class,
            [
                'class' => Type::class,
                'choice_label' => function ($type) {
                    return $type->getName();
                },
                'label' => 'Typ',
                'required' => true,
            ]
        );
        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function ($type) {
                    return $type->getName();
                },
                'label' => 'Kategoria',
                'required' => true,
            ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label_tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );
        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
        $builder->add(
            'ispolish',
            ChoiceType::class,
            [
                'choices'  => [
                    'TAK' => 1,
                    'NIE' => 0,
                    'NIE WIADOMO' => 2,
                ],
            ]
        );
        $builder->add(
            'ispolish',
            ChoiceType::class,
            [
                'choices'  => [
                    'TAK' => 1,
                    'NIE' => 0,
                    'NIE WIADOMO' => 2,
                ],
            ]
        );
        $builder->add(
            'mingamers',
            ChoiceType::class,
            [
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
            ]
        );
        $builder->add(
            'maxgamers',
            ChoiceType::class,
            [
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    'więcej' => 6,
                ],
            ]
        );
    }


    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Game::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'game';
    }
}
