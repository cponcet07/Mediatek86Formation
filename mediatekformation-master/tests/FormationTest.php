<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Test de la méthode pour retourner la date en string
 *
 * @author quens
 */
class FormationTest extends TestCase{
    public function TestGetDateToString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2022-03-09"));
        $this->assertEquals("09/03/2022", $formation->getPublishedAtString());
    }
}
