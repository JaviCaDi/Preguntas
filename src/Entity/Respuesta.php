<?php

namespace App\Entity;

use App\Repository\RespuestaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RespuestaRepository::class)]
class Respuesta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'respuestas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_usuario = null;

    #[ORM\ManyToOne(inversedBy: 'respuestas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pregunta $id_pregunta = null;

    #[ORM\Column]
    private ?int $respuesta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?User
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?User $id_usuario): static
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getIdPregunta(): ?Pregunta
    {
        return $this->id_pregunta;
    }

    public function setIdPregunta(?Pregunta $id_pregunta): static
    {
        $this->id_pregunta = $id_pregunta;

        return $this;
    }

    public function getRespuesta(): ?int
    {
        return $this->respuesta;
    }

    public function setRespuesta(int $respuesta): static
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
