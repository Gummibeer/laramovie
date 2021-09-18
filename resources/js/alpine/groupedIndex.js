export default (url) => ({
    url: url,

    groupedItems: [],
    activeGroup: null,

    init() {
        this.$watch('activeGroup', () => history.pushState(null, null, '#group-'+this.activeGroup));

        this.fetchItems();
    },

    fetchItems() {
        return fetch(this.url, {
            headers: {Accept: 'application/json'}
        })
            .then(res => res.json())
            .then(json => json.data)
            .then(items => this.groupedItems = items)
            .then(() => this.activeGroup = Object.keys(this.groupedItems)[0])
            .catch(() => this.groupedItems = []);
    }

});
