# Movie Poster Generator
This PHP script accepts an image upload and uses ChatGPT to generate fake reviews for a movie poster.

## File Upload
See [Nike Meme Generator]([https://www.genome.gov/](https://github.com/teochewthunder/nike-meme-generator).

## Dashboard
- Uses both PHP variables and jQuery.
- jQuery portion is based off [Easter Egg Generator](https://github.com/teochewthunder/easter-egg-generator).

## Link to `ChatGPT`.
- prompt is based off `movie_title`, and/or `movie_starring`.
- prompt returns an array of 10 objects. Each object has properties...
    - `review` : A short string describing the movie.
    - `critic` : The source of the review.
 
## Layout
- middle portion contains the uploaded mage, movie title, tagline and starring.
- left and right portions contain reviews.
    - Each review has 3 to 5 stars. 
