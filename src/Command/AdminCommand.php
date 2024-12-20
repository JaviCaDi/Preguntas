<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:cambiar-contraseña-admin')]
class AdminCommand extends Command
{
    private $repositorioUsuarios;
    private $encriptadorContraseñas;
    private $gestorEntidades;

    public function __construct(
        UserRepository $repositorioUsuarios,
        UserPasswordHasherInterface $encriptadorContraseñas,
        EntityManagerInterface $gestorEntidades
    ) {
        parent::__construct();
        $this->repositorioUsuarios = $repositorioUsuarios;
        $this->encriptadorContraseñas = $encriptadorContraseñas;
        $this->gestorEntidades = $gestorEntidades;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Cambia la contraseña del administrador.')
            ->setHelp('Este comando te ayuda a cambiar la contraseña del administrador.');
    }

    protected function execute(InputInterface $entrada, OutputInterface $salida): int
    {
        $ayudante = $this->getHelper('question');

        // Preguntar por el correo electrónico
        $preguntaCorreo = new Question('Por favor, introduce el correo electrónico del administrador: ');
        $correo = $ayudante->ask($entrada, $salida, $preguntaCorreo);

        // Verificar que el administrador existe
        $administrador = $this->repositorioUsuarios->findOneBy(['email' => $correo]);

        if (!$administrador) {
            $salida->writeln('<error>No se encontró un administrador con el correo proporcionado.</error>');
            return Command::FAILURE;
        }

        // Verificar si el administrador tiene el rol "ROLE_ADMIN"
        if (!in_array('ROLE_ADMIN', $administrador->getRoles())) {
            $salida->writeln('<error>El usuario no tiene el rol adecuado para cambiar la contraseña.</error>');
            return Command::FAILURE;
        }

        // Preguntar por la nueva contraseña
        $preguntaContraseña = new Question('Por favor, introduce la nueva contraseña: ');
        $preguntaContraseña->setHidden(true);
        $preguntaContraseña->setHiddenFallback(false); // Evita mostrar la contraseña en texto plano si no se puede ocultar
        $nuevaContraseña = $ayudante->ask($entrada, $salida, $preguntaContraseña);

        // Actualizar la contraseña
        $contraseñaEncriptada = $this->encriptadorContraseñas->hashPassword($administrador, $nuevaContraseña);
        $administrador->setPassword($contraseñaEncriptada);

        $this->gestorEntidades->persist($administrador);
        $this->gestorEntidades->flush();

        $salida->writeln('<info>Contraseña actualizada correctamente.</info>');
        return Command::SUCCESS;
    }
}
