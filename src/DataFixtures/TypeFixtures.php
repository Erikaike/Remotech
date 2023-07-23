<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{

public const TYPES = [
    [
        'label' => 'Thermometer',
        'bounds' => [15, 18]
    ],
    [
        'label' => 'Humidity captor',
        'bounds' => [40, 70]
    ],
    [
        'label' => 'Mouvement detector',
        'bounds' => [null, 30]
    ],
    [
        'label' => 'Air quality captor',
        'bounds' => [4, 6]
    ],

];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TYPES as $typeFixture) {
            $type = new Type();
            $type->setName($typeFixture['label'])
                ->setBounds($typeFixture['bounds']);
            
            $this->addReference('type_' . $typeFixture['label'], $type);

            $manager->persist($type);
            }
            $manager->flush(); 
    }
}
