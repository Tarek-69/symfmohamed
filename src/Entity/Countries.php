<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountriesRepository::class)]
class Countries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $iso = null;

    // #[ORM\OneToMany(mappedBy: 'country', targetEntity: Animals::class)]
    // private Collection $animals;

    public function __construct()
    {
        // $this->animals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(string $iso): self
    {
        

        $this->iso = $iso;
        

        return $this;
    }

    /**
     * @return Collection<int, Animals>
     */
    // public function getAnimals(): Collection
    // {
    //     return $this->animals;
    // }

    // public function addAnimal(Animals $animal): self
    // {
    //     if (!$this->animals->contains($animal)) {
    //         $this->animals->add($animal);
    //         $animal->setCountry($this);
    //     }

    //     return $this;
    // }

    // public function removeAnimal(Animals $animal): self
    // {
    //     if ($this->animals->removeElement($animal)) {
    //         // set the owning side to null (unless already changed)
    //         if ($animal->getCountry() === $this) {
    //             $animal->setCountry(null);
    //         }
    //     }

    //     return $this;
    // }
}
