<?php

namespace App\DataFixtures;

use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DeviceFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEVICES = [
        [
            'name' => 'Thermometer on terrasse',
            'status' => Device::STATUS_ON,
            'type' => 'Thermometer'
        ],
        [
            'name' => 'Humidity on room',
            'status' => Device::STATUS_ON,
            'type' => 'Humidity captor'
        ],
        [
            'name' => 'Beauty shop influence',
            'status' => Device::STATUS_ON,
            'type' => 'Mouvement detector'
        ],
        [
            'name' => 'Beauty shop air quality',
            'status' => Device::STATUS_ON,
            'type' => 'Air quality captor'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DEVICES as $deviceFixture) {
            $device = new Device();
            $device->setName($deviceFixture['name']);
            $device->setStatus($deviceFixture['status']);

            foreach (TypeFixtures::TYPES as $typeFixture) {
                if ($deviceFixture['type'] == $typeFixture['label']) {
                    $device->setType($this->getReference('type_' . $typeFixture['label']));
                }
            }

            $device->setState('ok');
            

            $this->addReference($deviceFixture['name'], $device);

            $manager->persist($device);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TypeFixtures::class,
        ];
    }
}

