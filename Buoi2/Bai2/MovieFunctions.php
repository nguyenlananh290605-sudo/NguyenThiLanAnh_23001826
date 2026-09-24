<?php
require_once 'Movie.php';
function findMovieById($movies, $id)
{
    if (empty($movies)) {
        throw new EmptyMovieListException("Movie list is empty.");
    }

    foreach ($movies as $movie) {
        if ($movie->getId() === $id) {
            return $movie;
        }
    }
    return null;

}
function getTotalRevenue($movies)
{
    if (empty($movies)) {
        throw new EmptyMovieListException("Movie list is empty.");
    }

    $totalRevenue = 0;
    foreach ($movies as $movie) {
        $soldSeats = $movie->getSoldSeats();
        $totalRevenue += $soldSeats * $movie->getPrice();
    }
    return $totalRevenue;
}

function getBestSellingMovie($movies)
{
    if (empty($movies)) {
        throw new EmptyMovieListException("Movie list is empty.");
    }

    $bestSellingMovie = null;
    $maxSoldSeats = -1;

    foreach ($movies as $movie) {
        $soldSeats = $movie->getSoldSeats();
        if ($soldSeats > $maxSoldSeats) {
            $maxSoldSeats = $soldSeats;
            $bestSellingMovie = $movie;
        }
    }

    return $bestSellingMovie;
}
function displayAllMovies($movies)
{
    if (empty($movies)) {
        echo "Danh sách phim trống.<br>";
        return;
    }

    echo "+--------+-----------------+--------------+------------+--------------+-------------+----------------+\n";
    echo "| Mã phim| Tên phim        | Giá vé       | Tổng ghế   | Ghế còn lại  | Số vé bán   | Doanh thu      |\n";
    echo "+--------+-----------------+--------------+------------+--------------+-------------+----------------+\n";

    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            $movie->displayInfo();
        }
    }

    echo "+--------+-----------------+--------------+------------+--------------+-------------+----------------+\n";
}
?>