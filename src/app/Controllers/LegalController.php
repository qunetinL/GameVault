<?php

namespace App\Controllers;

use App\Core\Controller;

class LegalController extends Controller
{
    public function privacy()
    {
        return $this->render('legal/privacy', [
            'title' => 'Politique de confidentialité — GameVault',
        ]);
    }

    public function cgu()
    {
        return $this->render('legal/cgu', [
            'title' => 'Conditions générales d\'utilisation — GameVault',
        ]);
    }

    public function mentions()
    {
        return $this->render('legal/mentions', [
            'title' => 'Mentions légales — GameVault',
        ]);
    }
}
