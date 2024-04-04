<?php

declare(strict_types=1);

namespace App\Form\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Contracts\Translation\TranslatableInterface;

final class EnumField implements FieldInterface
{
    use FieldTrait;

    private const ERROR = 'The static function setEnumClass() must be called before to initialize the class.';

    /**
     * @var string|null
     */
    public static ?string $targetEnumName = null;

    /**
     * @param string                                  $propertyName
     * @param TranslatableInterface|string|false|null $label
     *
     * @return self
     */
    public static function new(string $propertyName, $label = null): self
    {
        if (null === self::$targetEnumName) {
            throw new InvalidArgumentException(self::ERROR);
        }

        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/choice')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', self::$targetEnumName)
            ->setFormTypeOption('placeholder', 'Choisir...')
            ->formatValue(fn ($v) => $v->value)
            ->setFormTypeOption('choice_label', 'value')
            ->addCssClass('field-select');
    }

    /**
     * @param string $enumName
     *
     * @return self
     */
    public static function setEnumClass(string $enumName): self
    {
        self::$targetEnumName = $enumName;

        return new self();
    }
}
