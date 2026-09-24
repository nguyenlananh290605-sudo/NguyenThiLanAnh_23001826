<?php
require_once 'MovieFunctions.php';
echo "<pre>";
echo "<h2>Film management program:</h2>";

$movies = [];
try {
    $movie1 = new Movie(1, "Avengers", 100000, 100);
    $movie2 = new Movie(2, "Avatar", 120000, 80);
    $movie3 = new Movie(3, "Batman", 90000, 120);
    $movies[] = $movie1;
    $movies[] = $movie2;
    $movies[] = $movie3;
    echo "Movies created successfully.<br>";
} catch (InvalidMovieDataException $e) {
    echo "Error creating movie: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}

echo "----------------------------<br>";
echo "List of movies:<br>";
displayAllMovies($movies);

echo "----------------------------<br>";
echo "Booking tickets for movie ID 1:<br>";
try {
    $avengers = findMovieById($movies, 1);
    if ($avengers) {
        $avengers->bookTicket(10);
        $avengers->bookTicket(5);
    } else {
        echo "Movie with ID 1 not found.<br>";
    }
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}

echo "----------------------------<br>";
echo "Booking tickets for movie ID 2:<br>";
try {
    $avatar = findMovieById($movies, 2);
    if ($avatar) {
        $avatar->bookTicket(20);
        $avatar->bookTicket(10);
    } else {
        echo "Movie with ID 2 not found.<br>";
    }
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}

echo "----------------------------<br>";

try {
    $avengers->bookTicket(0);
} catch (InvalidBookingException $e) {
    echo "Error booking tickets: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}

try {
    $avatar->bookTicket(100);
} catch (InvalidBookingException $e) {
    echo "Error booking tickets: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}

try {
    $avengers->cancelBooking(-5);
} catch (InvalidCancellationException $e) {
    echo "Error canceling tickets: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "<br>";
}


echo "----------------------------<br>";
echo "List of movies after booking:<br>";
displayAllMovies($movies);
echo "</pre>";
?>