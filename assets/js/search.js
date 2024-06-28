const searchBox = document.getElementById('search');

searchBox.addEventListener('input', searchMovies);

function searchMovies() {
    fetch(`search.php?query=${searchBox.value}`)
        .then(response => response.json())
        .then(data => {
            // Clear the movie list
            const movieList = document.getElementById('movie-list');
            movieList.innerHTML = '';

            // Populate the movie list with the returned movie titles
            data.forEach(movie => {
                const listItem = document.createElement('li');
                listItem.textContent = movie.title;
                movieList.appendChild(listItem);
            });
        });
}