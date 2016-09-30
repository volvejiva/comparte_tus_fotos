<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
    
    public function testCrearAlbum()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // Compruebo que la página no da ningún tipo de error
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        // Compruebo que al menos hay un botón para enviar el formulario
        $numeroDeSubmit = $crawler->filter('input[type=submit]')->count();
        $this->assertGreaterThan(0, $numeroDeSubmit);
        
        // Compruebo que mi elemento h1 contiene el texto "Album creation"
        $this->assertContains('Album creation', $crawler->filter("h1")->text());
    }
    
    public function testGuardarAlbum()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/');
        
        $form = $crawler->filter('input[type=submit]')->form();
        
        $albumParameters = array(
            'name' => 'Vacaciones',
            'description' => 'Vacaciones en el mar',
            'email' => 'test@test.es'
        );
        
        $crawler = $client
            ->submit($form,
                array(
                    'album' => $albumParameters
                )
            );
            
        $this->assertContains("Redirecting to", $client->getResponse()->getContent());
    }
}