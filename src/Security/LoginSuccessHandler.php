<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        // Obtiene el usuario autenticado
        $user = $token->getUser();

        // Verifica si el usuario tiene el rol de administrador
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse('/admin/preguntas'); // Redirige al administrador a la lista de preguntas
        }

        // Si el usuario no es administrador, redirígelo a su perfil o a otra página
        return new RedirectResponse('/profile');
    }
}
