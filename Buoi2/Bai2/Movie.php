<?php
require_once 'MovieExceptions.php';
class Movie
{
    private $id;
    private $title;
    private $price;
    private $totalSeats;
    private $availableSeats;

    public function __construct($id, $title, $price, $totalSeats)
    {
        if (!is_numeric($id) || !is_string($title) || !is_numeric($price) || !is_numeric($totalSeats)) {
            throw new InvalidMovieDataException("Invalid input types for Movie");
        }
        if (empty($id) || empty($title)) {
            throw new InvalidMovieDataException("ID and Title cannot be empty");
        }
        if ($price <= 0) {
            throw new InvalidMovieDataException("Price cannot be zero or negative");
        }
        if ($totalSeats <= 0) {
            throw new InvalidMovieDataException("Total seats cannot be zero or negative");
        }
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getTotalSeats()
    {
        return $this->totalSeats;
    }

    public function getAvailableSeats()
    {
        return $this->availableSeats;
    }

    public function bookTicket($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new InvalidBookingException("Quantity must be a positive number");
        }
        if ($quantity > $this->availableSeats) {
            throw new InvalidBookingException("Not enough available seats");
        }
        $this->availableSeats -= $quantity;

        echo "Booked $quantity tickets for movie: " . $this->title . ". Remaining seats: " . $this->availableSeats . "<br>";

    }

    public function cancelBooking($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new InvalidBookingException("Quantity must be a positive number");
        }
        $soldSeats = $this->getSoldSeats();
        if ($quantity > $soldSeats) {
            throw new InvalidBookingException("Cannot cancel more tickets than sold");
        }
        $this->availableSeats += $quantity;

        echo "Cancelled $quantity tickets for movie: " . $this->title . ". Remaining seats: " . $this->availableSeats . "<br>";
    }

    public function getSoldSeats()
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue()
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo()
    {
        printf(
            "| %-6s | %-15s | %-12.2f | %-10d | %-12d | %-11d | %-15.2f |\n",
            $this->id,
            $this->title,
            $this->price,
            $this->totalSeats,
            $this->availableSeats,
            $this->getSoldSeats(),
            $this->getRevenue()
        );
    }

}
?>