<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $title = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Comment::class, inversedBy: 'products')]
    private Collection $comment;

    #[ORM\Column]
    private ?\DateTimeImmutable $added_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Cart::class)]
    private Collection $carts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: GuestOrders::class)]
    private Collection $guestOrders;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?UrlImage $url_image = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $other = null;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->guestOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comment->removeElement($comment);

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->added_at;
    }

    public function setAddedAt(\DateTimeImmutable $added_at): self
    {
        $this->added_at = $added_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GuestOrders>
     */
    public function getGuestOrders(): Collection
    {
        return $this->guestOrders;
    }

    public function addGuestOrder(GuestOrders $guestOrder): self
    {
        if (!$this->guestOrders->contains($guestOrder)) {
            $this->guestOrders->add($guestOrder);
            $guestOrder->setProduct($this);
        }

        return $this;
    }

    public function removeGuestOrder(GuestOrders $guestOrder): self
    {
        if ($this->guestOrders->removeElement($guestOrder)) {
            // set the owning side to null (unless already changed)
            if ($guestOrder->getProduct() === $this) {
                $guestOrder->setProduct(null);
            }
        }

        return $this;
    }

    public function getUrlImage(): ?UrlImage
    {
        return $this->url_image;
    }

    public function setUrlImage(?UrlImage $url_image): self
    {
        $this->url_image = $url_image;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;

        return $this;
    }

}
