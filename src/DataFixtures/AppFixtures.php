<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create table that contain the data we want
        $firstname = array('Salomé', 'Shana', 'Ina', 'Léna', 'Mathéo', 'Maya', 'Yanis'); 
        $lastname =  array('ACREMANN', 'AFFICHARD', 'AÏSSI', 'AKA', 'AKA', 'AKSU', 'AKSU');
        $mas = array(11, 10, 12, 10, 13, 14, 9);
        $gender = array('F', 'F', 'F', 'F', 'G', 'F', 'G');

        for ($i = 0; $i<7; $i++){ // Use table to make a list of student 
            $student = new Student;
            $student->setShortname($firstname[$i]);
            $student->setLastname($lastname[$i]);
            $student->setMas($mas[$i]);
            $student->setGender($gender[$i]);
            $manager->persist($student); // Get the object student
        }    


        $manager->flush(); // Use flush function to write manager in the database
    }
}
