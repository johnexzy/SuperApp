// const jquery = require('jquery');

    /**
     * Deletes Music on request
     * @param music Object
     */
    function delMusic(music_name, id) {
        const ask = confirm(`Proceed to Delete "${music_name}" and all of its components ?`);
        if (!ask) {
            return null;
        }
        fetch(`/api/v1/music/delete/${id}`, { method: "DELETE" })
            .then((res) => res.json())
                .then((data) => {
                    data == 1 ? submitSearch() : alert("operation failed")
                })
    }
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
                    if (!data.error) {
                        /**
                         * returns data Obj for music
                         */
                        const musicResult = new Object(data.data.find((x) => x.group === 'music'))
                        /**
                         * returns data Obj for movies
                         */
                        const movieResult = new Object(data.data.find((x) => x.group === 'movies'))
                        /**
                         * returns data Obj for series
                         */
                        const seriesResult = new Object(data.data.find((x) => x.group === 'series'))
                        /**
                         * updates tab-bar states
                         */
                        $(".musicLabel").html(`<span style="font-size: 16px;">🎧</span><sup style="color: #5646ff;font-weight: bolder;">(${musicResult.data.length})</sup>`)
                        $(".movieLabel").html(`<span style="font-size: 16px;">🎬</span><sup style="color: #5646ff;font-weight: bolder;">(${movieResult.data.length})</sup>`)
                        $(".seriesLabel").html(`<span style="font-size: 16px;">📺</span><sup style="color: #5646ff;font-weight: bolder;">(${seriesResult.data.length})</sup>`)


                        $("#musicbody").html("")
                        $(".loader").hide()

                        $.each(musicResult.data, function (i, val) {
                            $("#musicbody").append(`
                                <div class="d-flex justify-content-between card-footer my-2 align-items-center" style="line-height:1;">
                                    <p>${val.music_name} </p>
                                    <div>
                                        <a href="/admin/view/music/${val.short_url}" class="text-decoration-none">
                                            <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-rounded btn-icon delete" onclick="delMusic('${val.music_name}', '${val.id}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                    
                                </div>
                            `)
                        })

                        $("#moviesbody").html("")
                        $.each(movieResult.data, function (i, val) {
                            $("#moviesbody").append(`
                            <div class="d-flex justify-content-between card-footer my-2 align-items-center" style="line-height:1;">
                                <p>${val.video_name} </p>
                                <a href="/admin/view/movie/${val.short_url}" class="text-decoration-none">
                                    <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>
                            </div>
                            `)
                        })

                        $("#seriesbody").html("")
                        $.each(seriesResult.data, function (i, val) {
                            $("#seriesbody").append(`
                            <div class="d-flex justify-content-between card-footer my-2 align-items-center" style="line-height:1;">
                                <p>${val.series_name} </p>
                                <a href="/admin/view/series/${val.short_url}" class="text-decoration-none">
                                    <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>
                            </div>
                            `)
                        })
                    }

                })
        }
        // alert("Search Value too small")
        $(".searchInput").trigger('focus');
        return false
    }
    $(".searchInput").on("input", function () {
        if ($(this).val().length < 4) {
            $("#musicbody").html("")
            $("#moviesbody").html("")
            $("#seriesbody").html("")
        }
        if ($(this).val().length !== 0) {
            $(".dashboard").hide()
            $(".search").show()
            $(".loader").show()
            submitSearch()
            return;
        }

        else {
            $(".dashboard").show()
            $(".search").hide()
            return;
        }


    })

    $(".searchInput").on("keydown", function (event) {

        if (event.key == "Enter") {
            if ($(this).val().length !== 0) {
                $(".dashboard").hide()
                $(".search").show()
                submitSearch()
            }

        }

    })