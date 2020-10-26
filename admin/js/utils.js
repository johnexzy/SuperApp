$(function () {
    /**
 * Submits the search query
 */
    function submitSearch() {
        if ($(".searchInput").val().trim().length > 3) {
            let query = $(".searchInput").val();
            query = encodeURI(query.trim());
            fetch(`/api/v1/search/${query}`)
                .then(res => res.json())
                .then(data => {
                    console.log(data)
                })
        }
        alert("Search Value too small")
        $(".searchInput").trigger('focus');
        return false
    }

    $(".searchInput").on("keydown", function (event) {

        if (event.key == "Enter") {
            submitSearch()
        }
    })
    // $(".searchButton").on("click", submitSearch)
})