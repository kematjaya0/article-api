<?php

namespace App\DataPersister;

use App\Entity\PtiUser;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $decorator, $encoderFactory;
    
    public function __construct(
        ContextAwareDataPersisterInterface $decorator,
        EncoderFactoryInterface $encoderFactory) 
    {
        $this->decorator = $decorator;
        $this->encoderFactory = $encoderFactory;
    }
    
    public function persist($data, array $context = array()) 
    {
        $encoder = $this->encoderFactory->getEncoder($data);
        if($encoder->needsRehash($data->getPassword())) 
        {
            $password = $encoder->encodePassword( $data->getPassword(), $data->getUsername());
            $data->setPassword($password);
            
            $this->decorator->persist($data, $context);
        }
    }

    public function remove($data, array $context = array()) 
    {
        $this->decorator->remove($data, $context);
    }

    public function supports($data, array $context = array()): bool 
    {
        return $data instanceof PtiUser and $this->decorator->supports($data, $context);
    }

}
