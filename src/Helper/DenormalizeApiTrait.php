<?php


namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait DenormalizeApiTrait
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getSerializer() {
        return $this->container->get('serializer');
    }

    /**
     * @param $data
     * @param $class
     * @param null $fieldNameSearchInData
     * @return null|object
     */
    public function getEntityById($data, $class, $fieldNameSearchInData = null)
    {
        $entity = null;
        if ($fieldNameSearchInData and array_key_exists($fieldNameSearchInData, $data)) {
            $entity = $this->em->getRepository($class)->find($data[$fieldNameSearchInData]);
        }
        elseif (array_key_exists('id', $data)) {
            $entity = $this->em->getRepository($class)->find($data['id']);
        }
        if (!$entity) {
            $entity = new $class();
        }
        return $entity;
    }

    /**
     * @param $data
     * @param $class
     * @param $fieldNameSearchInEntity
     * @param $fieldNameSearchInData
     * @return null|object
     */
    public function getEntityByField($data, $class, $fieldNameSearchInEntity, $fieldNameSearchInData)
    {
        $entity = null;
        if ($fieldNameSearchInData and array_key_exists($fieldNameSearchInData, $data)) {
            $entity = $this->em->getRepository($class)->findOneBy([
                $fieldNameSearchInEntity => $data[$fieldNameSearchInData]
            ]);
        }
        if (!$entity) {
            $entity = new $class();
        }
        return $entity;
    }

    /**
     * @param $data
     * @param $class
     * @param array $fields
     * @return null|object
     */
    public function getEntityByFields($class, array $fields)
    {
        $entity = null;
        $entity = $this->em->getRepository($class)->findOneBy($fields);
        if (!$entity) {
            $entity = new $class();
        }
        return $entity;
    }

    static function getDateTimeFromString($dateTimeString) {
        $datetime = new \DateTime($dateTimeString, new \DateTimeZone('UTC'));
        return $datetime;
    }

}