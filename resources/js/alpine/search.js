export default () => ({
    query: '',

    movies: [],
    tvShows: [],
    people: [],

    init() {
        this.$watch('query', () => Promise.all([
            this.fetchMovies(),
            this.fetchTvShows(),
            this.fetchPeople(),
        ]))
    },

    fetchMovies() {
        let url = new URL(route('api.movie.autocomplete'));
        url.searchParams.set('query', this.query);

        return fetch(url.toString(), {
            headers: {Accept: 'application/json'}
        })
            .then(res => res.json())
            .then(json => json.data)
            .then(movies => this.movies = movies)
            .catch(() => this.movies = []);
    },

    fetchTvShows() {
        let url = new URL(route('api.tvshow.autocomplete'));
        url.searchParams.set('query', this.query);

        return fetch(url.toString(), {
            headers: {Accept: 'application/json'}
        })
            .then(res => res.json())
            .then(json => json.data)
            .then(tvShows => this.tvShows = tvShows)
            .catch(() => this.tvShows = []);
    },

    fetchPeople() {
        let url = new URL(route('api.person.autocomplete'));
        url.searchParams.set('query', this.query);

        return fetch(url.toString(), {
            headers: {Accept: 'application/json'}
        })
            .then(res => res.json())
            .then(json => json.data)
            .then(people => this.people = people)
            .catch(() => this.people = []);
    },
});
